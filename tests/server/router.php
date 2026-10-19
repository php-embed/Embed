<?php
declare(strict_types = 1);

/**
 * Router for the built-in web server used by CurlClientTest.
 * Every request is appended to EMBED_TEST_LOG as one JSON object.
 */

$log = getenv('EMBED_TEST_LOG');
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH);
$queryString = parse_url($requestUri, PHP_URL_QUERY);
$path = is_string($path) && $path !== '' ? $path : '/';
$queryString = is_string($queryString) ? $queryString : '';

$headers = [];
if (function_exists('getallheaders')) {
    foreach (getallheaders() as $name => $value) {
        $headers[strtolower((string) $name)] = (string) $value;
    }
}

$entry = [
    'path' => $path,
    'query' => $queryString,
    'method' => (string) ($_SERVER['REQUEST_METHOD'] ?? ''),
    'authorization' => $headers['authorization'] ?? '',
    'cookie' => $headers['cookie'] ?? '',
    'host' => $headers['host'] ?? '',
    'referer' => $headers['referer'] ?? '',
    'body' => (string) file_get_contents('php://input'),
];

if (is_string($log) && $log !== '') {
    $encoded = json_encode($entry);
    if (is_string($encoded)) {
        file_put_contents($log, $encoded."\n", FILE_APPEND | LOCK_EX);
    }
}

$params = [];
parse_str($queryString, $params);

if ($path === '/go') {
    $code = isset($params['code']) ? (int) $params['code'] : 302;
    if (!in_array($code, [301, 302, 303, 307, 308], true)) {
        $code = 302;
    }
    $to = isset($params['to']) && is_string($params['to']) ? $params['to'] : '/secret';
    header('Location: '.$to, true, $code);
    echo 'redirect';

    return;
}

if ($path === '/chain') {
    $i = isset($params['i']) ? (int) $params['i'] : 0;
    $n = isset($params['n']) ? (int) $params['n'] : 0;
    if ($i >= $n) {
        http_response_code(200);
        echo 'done';

        return;
    }
    header('Location: /chain?i='.($i + 1).'&n='.$n, true, 302);
    echo 'redirect';

    return;
}

if ($path === '/set-cookie') {
    header('Set-Cookie: consent=yes; Path=/');
    header('Location: /read-cookie', true, 302);
    echo 'redirect';

    return;
}

if ($path === '/to-other') {
    $port = isset($params['port']) ? (string) $params['port'] : '';
    header('Location: http://other.test:'.$port.'/landed', true, 302);
    echo 'redirect';

    return;
}

if ($path === '/switch') {
    $code = isset($params['code']) ? (int) $params['code'] : 302;
    if (!in_array($code, [301, 302, 303, 307, 308], true)) {
        $code = 302;
    }
    header('Location: /landed', true, $code);
    echo 'redirect';

    return;
}

if ($path === '/pause') {
    $ms = isset($params['ms']) ? (int) $params['ms'] : 0;
    if ($ms > 0) {
        usleep(min($ms, 5000) * 1000);
    }
    $to = isset($params['to']) && is_string($params['to']) ? $params['to'] : '';
    if ($to !== '') {
        header('Location: '.$to, true, 302);
        echo 'redirect';

        return;
    }
    http_response_code(200);
    echo 'ok';

    return;
}

if ($path === '/secret') {
    http_response_code(200);
    echo 'LEAKED';

    return;
}

http_response_code(200);
echo 'ok';
