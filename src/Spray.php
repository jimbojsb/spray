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
    protected static $responses = array();

    public static function stub($url, array $response)
    {
        self::$responses[$url] = $response;
        $urlParts = @parse_url($url);
        if ($urlParts['scheme']) {
            self::wrap($urlParts['scheme']);
        }
    }

    private static function wrap($scheme)
    {
        if (!in_array($scheme, self::$existingWrappers)) {
            $registeredWrappers = stream_get_wrappers();
            if (in_array($scheme, $registeredWrappers)) {
                stream_wrapper_unregister($scheme);
            }
            stream_wrapper_register($scheme, "Spray", true);
            self::$existingWrappers[] = $scheme;
        }
    }

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $this->output = $this->renderResponse(self::$responses[$path]);
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
            stream_wrapper_restore($wrapper);
        }
    }

    private static function renderResponse(array $response)
    {
        extract($response);
        if (is_int($status)) {
            $status = constant("Spray::STATUS_$status");
        }

        $output = "HTTP/1.0 $status\r\n";
        foreach ($headers as $header => $value) {
            $output .= "$header: $value\r\n";
        }
        $output .= "\r\n";
        $output .= $body;
        return $output;
    }
}
