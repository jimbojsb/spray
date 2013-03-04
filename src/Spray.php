<?php
class Spray
{
    const STATUS_200 = "200 OK";
    const STATUS_404 = "404 Not Found";
    const STATUS_301 = "301 Moved Permanently";
    const STATUS_302 = "302 Moved";

    protected $output = '';
    protected $currentPosition = 0;

    protected static $init = false;
    protected static $existingWrappers = array();
    protected static $originalWrappers = array();
    protected static $responses = array();

    public static function stub($url, array $response)
    {
        self::$responses[$url] = $response;
        $urlParts = parse_url($url);
        if ($urlParts['scheme']) {
            self::wrap($urlParts['scheme']);
        }
    }

    private static function wrap($scheme)
    {
        if (!in_array($scheme, self::$existingWrappers)) {
            $registeredWrappers = stream_get_wrappers();
            if (in_array($scheme, $registeredWrappers)) {
                self::$originalWrappers[] = $scheme;
                stream_wrapper_unregister($scheme);
            }
            stream_wrapper_register($scheme, "Spray", true);
            self::$existingWrappers[] = $scheme;
        }
    }

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $this->output = $this->renderResponse(self::$responses[$path], $this->context);
        return true;
    }

    public function stream_write($data)
    {
        echo $data;
    }

    public function stream_eof()
    {
        return $this->currentPosition >= strlen($this->output);
    }

    public function stream_read($count)
    {
        $val = substr($this->output, $this->currentPosition, $count);
        $this->currentPosition += $count;
        if ($this->currentPosition > strlen($this->output)) {
            $this->currentPosition = strlen($this->output);
        }
        return $val;
    }

    public function stream_stat()
    {
        return array();
    }

    public static function reset()
    {
        foreach (self::$existingWrappers as $wrapper) {
            stream_wrapper_unregister($wrapper);
            if (in_array($wrapper, self::$originalWrappers)) {
                stream_wrapper_restore($wrapper);
            }
        }
        self::$originalWrappers = array();
        self::$existingWrappers = array();
    }

    private static function renderResponse(array $response, $context)
    {
        if ($response['raw']) {
            return $response['raw'];
        }
        if ($response['echo_back']) {
            $contextOpts = stream_context_get_options($context);
            $options = array_shift($contextOpts);
            return $options[$response['echo_back']];
        }
        $status = $response['status'] ? $response['status'] : self::STATUS_200;
        if (is_int($status)) {
            $status = constant("Spray::STATUS_$status");
        }
        $headers = $response['headers'] ? $response['headers'] : array();
        $output = "HTTP/1.0 $status\r\n";
        foreach ($headers as $header => $value) {
            $output .= "$header: $value\r\n";
        }
        $output .= "\r\n{$response['body']}";
        return $output;
    }
}
