<?php
declare(strict_types = 1);

namespace Embed\Tests;

use RuntimeException;

/**
 * php -S bound to 127.0.0.1 on a free port, with a request log.
 */
final class LocalHttpServer
{
    /** @var resource */
    private $process;
    private string $logFile;
    private int $port;
    private string $stderr;

    public static function start(): self
    {
        $log = tempnam(sys_get_temp_dir(), 'embed-ssrf-');
        if ($log === false) {
            throw new RuntimeException('Unable to create the request log');
        }

        $port = self::allocatePort();
        $stderr = $log.'.stderr';
        $command = [PHP_BINARY, '-S', '127.0.0.1:'.$port, __DIR__.'/server/router.php'];
        $spec = [
            0 => ['pipe', 'r'],
            1 => ['file', $log.'.stdout', 'w'],
            2 => ['file', $stderr, 'w'],
        ];

        $env = [];
        foreach (array_merge($_SERVER, $_ENV) as $name => $value) {
            if (is_string($name) && is_string($value)) {
                $env[$name] = $value;
            }
        }
        $env['EMBED_TEST_LOG'] = $log;

        $process = proc_open($command, $spec, $pipes, null, $env);
        if (!is_resource($process)) {
            throw new RuntimeException('Unable to start the test server');
        }
        fclose($pipes[0]);

        $server = new self($process, $log, $port, $stderr);
        $server->waitUntilListening();

        return $server;
    }

    /**
     * @param resource $process
     */
    private function __construct($process, string $logFile, int $port, string $stderr)
    {
        $this->process = $process;
        $this->logFile = $logFile;
        $this->port = $port;
        $this->stderr = $stderr;
    }

    public function port(): int
    {
        return $this->port;
    }

    public function clearLog(): void
    {
        file_put_contents($this->logFile, '');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function requests(): array
    {
        $raw = is_file($this->logFile) ? (string) file_get_contents($this->logFile) : '';
        $rows = [];
        foreach (preg_split('/\r?\n/', trim($raw)) ?: [] as $line) {
            if ($line === '') {
                continue;
            }
            $decoded = json_decode($line, true);
            if (!is_array($decoded)) {
                continue;
            }
            $row = [];
            foreach ($decoded as $key => $value) {
                if (is_string($key)) {
                    $row[$key] = is_string($value) ? $value : '';
                }
            }
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * @return array<int, string>
     */
    public function paths(): array
    {
        $paths = [];
        foreach ($this->requests() as $request) {
            $paths[] = $request['path'] ?? '';
        }

        return $paths;
    }

    public function stop(): void
    {
        proc_terminate($this->process);
        proc_close($this->process);
    }

    private function waitUntilListening(): void
    {
        $deadline = microtime(true) + 5;
        while (microtime(true) < $deadline) {
            $socket = @fsockopen('127.0.0.1', $this->port, $errno, $error, 0.2);
            if (is_resource($socket)) {
                fclose($socket);

                return;
            }
            usleep(50000);
        }

        $details = is_file($this->stderr) ? (string) file_get_contents($this->stderr) : '';
        $this->stop();

        throw new RuntimeException('Test server did not start: '.$details);
    }

    private static function allocatePort(): int
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0', $errno, $error);
        if ($socket === false) {
            throw new RuntimeException('Unable to allocate a port: '.$error);
        }

        $name = stream_socket_get_name($socket, false);
        fclose($socket);
        if (!is_string($name)) {
            throw new RuntimeException('Unable to read the allocated port');
        }

        $colon = strrpos($name, ':');
        if ($colon === false) {
            throw new RuntimeException('Unable to read the allocated port');
        }

        return (int) substr($name, $colon + 1);
    }
}
