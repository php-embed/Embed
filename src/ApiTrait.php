<?php
declare(strict_types = 1);

namespace Embed;

use DateTime;
use Psr\Http\Message\UriInterface;
use Throwable;

trait ApiTrait
{
    protected Extractor $extractor;
    /** @var array<string, mixed> */
    private array $data = [];

    public function __construct(Extractor $extractor)
    {
        $this->extractor = $extractor;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        if ($this->data === []) {
            $this->data = $this->fetchData();
        }

        return $this->data;
    }

    /**
     * @return mixed
     */
    public function get(string ...$keys)
    {
        $data = $this->all();

        foreach ($keys as $key) {
            if (!is_array($data) || !isset($data[$key])) {
                return null;
            }

            $data = $data[$key];
        }

        return $data;
    }

    public function str(string ...$keys): ?string
    {
        $value = $this->get(...$keys);

        if (is_array($value)) {
            $value = array_shift($value);
        }

        if (is_string($value)) {
            return clean($value);
        } elseif (is_scalar($value)) {
            return clean((string) $value);
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function strAll(string ...$keys): array
    {
        $all = (array) $this->get(...$keys);
        return array_filter(array_map(fn ($value) => is_string($value) ? clean($value) : null, $all), fn ($value) => $value !== null);
    }

    public function html(string ...$keys): ?string
    {
        $value = $this->get(...$keys);

        if (is_array($value)) {
            $value = array_shift($value);
        }

        if (is_string($value)) {
            return clean($value, true);
        } elseif (is_scalar($value)) {
            return clean((string) $value, true);
        }

        return null;
    }

    public function int(string ...$keys): ?int
    {
        $value = $this->get(...$keys);

        if (is_array($value)) {
            $value = array_shift($value);
        }

        return is_numeric($value) ? (int) $value : null;
    }

    public function url(string ...$keys): ?UriInterface
    {
        $url = $this->rawUrl(...$keys);

        try {
            return $url !== null ? $this->extractor->resolveUri($url) : null;
        } catch (Throwable $error) {
            return null;
        }
    }

    /**
     * Inner spaces are part of the URL, so they are kept (and later encoded)
     * instead of being collapsed like in str(). Tabs and line breaks are
     * removed, as browsers do.
     */
    private function rawUrl(string ...$keys): ?string
    {
        $value = $this->get(...$keys);

        if (is_array($value)) {
            $value = array_shift($value);
        }

        if (!is_string($value)) {
            return null;
        }

        $value = trim(str_replace(["\t", "\r", "\n"], '', $value));

        return $value === '' ? null : $value;
    }

    public function time(string ...$keys): ?DateTime
    {
        $time = $this->str(...$keys);
        $datetime = $time !== null ? date_create($time) : false;

        if ($datetime === false && $time !== null && ctype_digit($time)) {
            $datetime = date_create_from_format('U', $time);
        }

        return ($datetime !== false && $datetime->getTimestamp() > 0) ? $datetime : null;
    }

    abstract protected function fetchData(): array;
}
