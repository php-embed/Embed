<?php
declare(strict_types = 1);

namespace Embed\Http;

/**
 * Classifies IP addresses as public or not.
 *
 * Ranges are matched with inet_pton and an explicit mask so the result is the
 * same on PHP 7.4 through 8.5. FILTER_FLAG_GLOBAL_RANGE is intentionally not
 * used: it does not exist before PHP 8.2, and an undefined constant is a fatal
 * error on PHP 8.0 and 8.1.
 *
 * @internal
 */
final class IpClassifier
{
    /**
     * IPv4 networks that must not be contacted.
     *
     * @var array<int, array{0: string, 1: int}>
     */
    private const IPV4_NETWORKS = [
        ['0.0.0.0', 8],
        ['10.0.0.0', 8],
        ['100.64.0.0', 10],
        ['127.0.0.0', 8],
        ['169.254.0.0', 16],
        ['172.16.0.0', 12],
        ['192.0.0.0', 24],
        ['192.0.2.0', 24],
        ['192.88.99.0', 24],
        ['192.168.0.0', 16],
        ['198.18.0.0', 15],
        ['198.51.100.0', 24],
        ['203.0.113.0', 24],
        ['224.0.0.0', 4],
        ['240.0.0.0', 4],
        ['255.255.255.255', 32],
    ];

    /**
     * IPv6 networks that must not be contacted.
     *
     * @var array<int, array{0: string, 1: int}>
     */
    private const IPV6_NETWORKS = [
        ['::', 128],
        ['::1', 128],
        ['fc00::', 7],
        ['fe80::', 10],
        ['fec0::', 10],
        ['ff00::', 8],
        ['2001:db8::', 32],
        ['100::', 64],
        ['2001::', 32], // Teredo: the whole prefix, not the embedded IPv4
        ['64:ff9b:1::', 48],
    ];

    public static function isPublic(string $ip): bool
    {
        $packed = self::pack($ip);
        if ($packed === null) {
            return false;
        }

        if (strlen($packed) === 4) {
            return !self::inNetworks($packed, self::IPV4_NETWORKS);
        }

        $embedded = self::embeddedIpv4($packed);
        if ($embedded !== null) {
            return !self::inNetworks($embedded, self::IPV4_NETWORKS);
        }

        return !self::inNetworks($packed, self::IPV6_NETWORKS);
    }

    /**
     * Canonical textual form of an IP literal, including inet_aton spellings
     * (hex, octal, and shortened IPv4). Returns null when $value is a hostname.
     */
    public static function canonical(string $value): ?string
    {
        $value = self::unwrap($value);
        if ($value === '') {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_IP) !== false) {
            return self::format($value);
        }

        return self::inetAton($value);
    }

    /**
     * True when both values are the same address. IPv4 and its IPv4-mapped
     * IPv6 form compare equal.
     */
    public static function equals(string $left, string $right): bool
    {
        $a = self::pack($left);
        $b = self::pack($right);
        if ($a === null || $b === null) {
            return false;
        }

        if ($a === $b) {
            return true;
        }

        $a4 = self::asIpv4($a);
        $b4 = self::asIpv4($b);

        return $a4 !== null && $a4 === $b4;
    }

    private static function pack(string $ip): ?string
    {
        $canonical = self::canonical($ip);
        if ($canonical === null) {
            return null;
        }

        $packed = inet_pton($canonical);
        if ($packed === false) {
            return null;
        }

        return $packed;
    }

    private static function format(string $ip): ?string
    {
        $packed = inet_pton($ip);
        if ($packed === false) {
            return null;
        }

        $text = inet_ntop($packed);

        return $text === false ? null : $text;
    }

    private static function unwrap(string $value): string
    {
        if (strlen($value) >= 2 && $value[0] === '[' && substr($value, -1) === ']') {
            return substr($value, 1, -1);
        }

        return $value;
    }

    /**
     * glibc inet_aton / libcurl accept these spellings and connect to the
     * resulting address without consulting CURLOPT_RESOLVE.
     */
    private static function inetAton(string $host): ?string
    {
        if (preg_match('/^(?:0x[0-9a-f]+|\d+)(?:\.(?:0x[0-9a-f]+|\d+)){0,3}$/i', $host) !== 1) {
            return null;
        }

        $parts = explode('.', $host);
        $count = count($parts);
        $numbers = [];

        foreach ($parts as $part) {
            $number = self::parseComponent($part);
            if ($number === null) {
                return null;
            }
            $numbers[] = $number;
        }

        $bytes = self::atonBytes($numbers, $count);
        if ($bytes === null) {
            return null;
        }

        return $bytes[0].'.'.$bytes[1].'.'.$bytes[2].'.'.$bytes[3];
    }

    private static function parseComponent(string $part): ?float
    {
        if ($part === '') {
            return null;
        }

        if (stripos($part, '0x') === 0) {
            $hex = substr($part, 2);
            if ($hex === '' || strlen($hex) > 8 || !ctype_xdigit($hex)) {
                return null;
            }

            return (float) hexdec($hex);
        }

        if (strlen($part) > 1 && $part[0] === '0') {
            if (strlen($part) > 11 || strspn($part, '01234567') !== strlen($part)) {
                return null;
            }

            return (float) octdec($part);
        }

        if (!ctype_digit($part) || strlen($part) > 10) {
            return null;
        }

        return (float) $part;
    }

    /**
     * @param array<int, float> $numbers
     *
     * @return array{0: int, 1: int, 2: int, 3: int}|null
     */
    private static function atonBytes(array $numbers, int $count): ?array
    {
        if ($count === 1) {
            return self::split($numbers[0], 4);
        }

        if ($count === 2) {
            if ($numbers[0] > 255) {
                return null;
            }
            $rest = self::split($numbers[1], 3);
            if ($rest === null) {
                return null;
            }

            return [(int) $numbers[0], $rest[1], $rest[2], $rest[3]];
        }

        if ($count === 3) {
            if ($numbers[0] > 255 || $numbers[1] > 255) {
                return null;
            }
            $rest = self::split($numbers[2], 2);
            if ($rest === null) {
                return null;
            }

            return [(int) $numbers[0], (int) $numbers[1], $rest[2], $rest[3]];
        }

        if ($count !== 4 || $numbers[0] > 255 || $numbers[1] > 255 || $numbers[2] > 255 || $numbers[3] > 255) {
            return null;
        }

        return [(int) $numbers[0], (int) $numbers[1], (int) $numbers[2], (int) $numbers[3]];
    }

    /**
     * @return array{0: int, 1: int, 2: int, 3: int}|null
     */
    private static function split(float $value, int $width): ?array
    {
        $limit = [1 => 255.0, 2 => 65535.0, 3 => 16777215.0, 4 => 4294967295.0];
        if ($value < 0 || $value > $limit[$width]) {
            return null;
        }

        $bytes = [0, 0, 0, 0];
        $remaining = $value;
        for ($i = 3; $i >= 4 - $width; --$i) {
            $bytes[$i] = (int) fmod($remaining, 256.0);
            $remaining = floor($remaining / 256.0);
        }

        return [$bytes[0], $bytes[1], $bytes[2], $bytes[3]];
    }

    private static function embeddedIpv4(string $packed): ?string
    {
        if (strlen($packed) !== 16) {
            return null;
        }

        // IPv4-mapped (::ffff:0:0/96), including the hexadecimal form.
        if (substr($packed, 0, 12) === "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xff\xff") {
            return substr($packed, 12, 4);
        }

        // NAT64 well-known prefix 64:ff9b::/96, dotted or hex.
        $nat64 = inet_pton('64:ff9b::');
        if ($nat64 !== false && substr($packed, 0, 12) === substr($nat64, 0, 12)) {
            return substr($packed, 12, 4);
        }

        // 6to4, 2002::/16. The IPv4 address occupies bits 16-47.
        if (substr($packed, 0, 2) === "\x20\x02") {
            return substr($packed, 2, 4);
        }

        // Deprecated IPv4-compatible addresses, ::/96.
        if (substr($packed, 0, 12) === str_repeat("\x00", 12)) {
            return substr($packed, 12, 4);
        }

        return null;
    }

    private static function asIpv4(string $packed): ?string
    {
        if (strlen($packed) === 4) {
            return $packed;
        }

        if (strlen($packed) === 16 && substr($packed, 0, 12) === "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xff\xff") {
            return substr($packed, 12, 4);
        }

        return null;
    }

    /**
     * @param array<int, array{0: string, 1: int}> $networks
     */
    private static function inNetworks(string $packed, array $networks): bool
    {
        foreach ($networks as $network) {
            if (self::inCidr($packed, $network[0], $network[1])) {
                return true;
            }
        }

        return false;
    }

    private static function inCidr(string $packed, string $network, int $bits): bool
    {
        $networkPacked = inet_pton($network);
        if ($networkPacked === false || strlen($networkPacked) !== strlen($packed)) {
            return false;
        }

        $fullBytes = intdiv($bits, 8);
        if ($fullBytes > 0 && substr($packed, 0, $fullBytes) !== substr($networkPacked, 0, $fullBytes)) {
            return false;
        }

        $remainder = $bits % 8;
        if ($remainder === 0) {
            return true;
        }

        $mask = (0xFF << (8 - $remainder)) & 0xFF;

        return (ord($packed[$fullBytes]) & $mask) === (ord($networkPacked[$fullBytes]) & $mask);
    }
}
