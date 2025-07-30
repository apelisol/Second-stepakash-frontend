<?php

/**
 * Simple WebSocket Client for Deriv API
 * Place this file in application/libraries/WebSocket_client.php
 */

namespace WebSocket;

class Client
{
    private $socket;
    private $url;
    private $options;
    private $headers;
    private $timeout;

    public function __construct($url, $options = [], $context_options = [])
    {
        $this->url = $url;
        $this->options = $options;
        $this->timeout = isset($options['timeout']) ? $options['timeout'] : 10;
        $this->headers = isset($options['headers']) ? $options['headers'] : [];

        $this->connect($context_options);
    }

    private function connect($context_options = [])
    {
        $parsed_url = parse_url($this->url);

        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] : 'ws';
        $host = $parsed_url['host'];
        $port = isset($parsed_url['port']) ? $parsed_url['port'] : ($scheme === 'wss' ? 443 : 80);
        $path = isset($parsed_url['path']) ? $parsed_url['path'] : '/';
        if (isset($parsed_url['query'])) {
            $path .= '?' . $parsed_url['query'];
        }

        // Create socket context
        $context = stream_context_create($context_options);

        // Create socket connection
        $socket_url = ($scheme === 'wss' ? 'ssl://' : '') . $host . ':' . $port;
        $this->socket = stream_socket_client(
            $socket_url,
            $errno,
            $errstr,
            $this->timeout,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$this->socket) {
            throw new ConnectionException("Failed to connect to WebSocket: $errstr ($errno)");
        }

        // Set timeout
        stream_set_timeout($this->socket, $this->timeout);

        // Perform WebSocket handshake
        $this->performHandshake($host, $path);
    }

    private function performHandshake($host, $path)
    {
        $key = base64_encode(random_bytes(16));

        $headers = [
            "GET $path HTTP/1.1",
            "Host: $host",
            "Upgrade: websocket",
            "Connection: Upgrade",
            "Sec-WebSocket-Key: $key",
            "Sec-WebSocket-Version: 13"
        ];

        // Add custom headers
        foreach ($this->headers as $name => $value) {
            $headers[] = "$name: $value";
        }

        $headers[] = "";
        $headers[] = "";

        $handshake = implode("\r\n", $headers);

        if (fwrite($this->socket, $handshake) === false) {
            throw new ConnectionException("Failed to send handshake");
        }

        // Read response
        $response = '';
        while (strpos($response, "\r\n\r\n") === false) {
            $response .= fread($this->socket, 1024);
        }

        // Verify handshake response
        $expected_accept = base64_encode(sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));

        if (strpos($response, "HTTP/1.1 101") === false) {
            throw new ConnectionException("WebSocket handshake failed: " . trim($response));
        }

        if (strpos($response, "Sec-WebSocket-Accept: $expected_accept") === false) {
            throw new ConnectionException("WebSocket handshake validation failed");
        }
    }

    public function send($data)
    {
        if (!is_string($data)) {
            $data = json_encode($data);
        }

        $frame = $this->encodeFrame($data);

        if (fwrite($this->socket, $frame) === false) {
            throw new ConnectionException("Failed to send data");
        }
    }

    public function receive()
    {
        $frame = $this->readFrame();
        return $frame['payload'];
    }

    private function encodeFrame($data, $opcode = 0x1)
    {
        $length = strlen($data);
        $frame = '';

        // First byte: FIN (1) + RSV (000) + OPCODE (0001 for text)
        $frame .= chr(0x80 | $opcode);

        // Second byte: MASK (1) + payload length
        if ($length < 126) {
            $frame .= chr(0x80 | $length);
        } elseif ($length < 65536) {
            $frame .= chr(0x80 | 126) . pack('n', $length);
        } else {
            $frame .= chr(0x80 | 127) . pack('NN', 0, $length);
        }

        // Masking key (4 bytes)
        $mask = random_bytes(4);
        $frame .= $mask;

        // Masked payload
        for ($i = 0; $i < $length; $i++) {
            $frame .= chr(ord($data[$i]) ^ ord($mask[$i % 4]));
        }

        return $frame;
    }

    private function readFrame()
    {
        // Read first two bytes
        $header = fread($this->socket, 2);
        if (strlen($header) < 2) {
            throw new ConnectionException("Failed to read frame header");
        }

        $firstByte = ord($header[0]);
        $secondByte = ord($header[1]);

        $fin = ($firstByte & 0x80) === 0x80;
        $opcode = $firstByte & 0x0F;
        $masked = ($secondByte & 0x80) === 0x80;
        $payloadLength = $secondByte & 0x7F;

        // Extended payload length
        if ($payloadLength === 126) {
            $extendedLength = fread($this->socket, 2);
            $payloadLength = unpack('n', $extendedLength)[1];
        } elseif ($payloadLength === 127) {
            $extendedLength = fread($this->socket, 8);
            $payloadLength = unpack('J', $extendedLength)[1];
        }

        // Masking key
        $maskingKey = '';
        if ($masked) {
            $maskingKey = fread($this->socket, 4);
        }

        // Payload
        $payload = '';
        if ($payloadLength > 0) {
            $payload = fread($this->socket, $payloadLength);

            // Unmask payload if masked
            if ($masked) {
                for ($i = 0; $i < $payloadLength; $i++) {
                    $payload[$i] = chr(ord($payload[$i]) ^ ord($maskingKey[$i % 4]));
                }
            }
        }

        return [
            'fin' => $fin,
            'opcode' => $opcode,
            'masked' => $masked,
            'payload' => $payload
        ];
    }

    public function close()
    {
        if ($this->socket) {
            // Send close frame
            $closeFrame = $this->encodeFrame('', 0x8);
            fwrite($this->socket, $closeFrame);

            fclose($this->socket);
            $this->socket = null;
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}

class ConnectionException extends \Exception
{
    //
}

// For backward compatibility, create the class in global namespace as well
if (!class_exists('\WebSocket\Client', false)) {
    class_alias('\WebSocket\Client', 'WebSocket\Client');
}
if (!class_exists('\WebSocket\ConnectionException', false)) {
    class_alias('\WebSocket\ConnectionException', 'WebSocket\ConnectionException');
}
