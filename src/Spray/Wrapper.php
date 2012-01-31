<?php
namespace Spray;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Request.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Response.php';

class Wrapper
{
    protected $output = '';
    protected $currentPosition = 0;

    protected static $overrideWrappers = array('http', 'https');
    protected static $response;
    protected static $request;

    public static function init()
    {
        $existingWrappers = stream_get_wrappers();
        foreach (self::$overrideWrappers as $wrapper) {
            if (in_array($wrapper, $existingWrappers)) {
                stream_wrapper_unregister($wrapper);
                stream_wrapper_register($wrapper, "Spray\\Wrapper", true);
            }
        }
    }

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        self::$request = new Request();
        $this->output = (string) self::$response;
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
        return $val;
    }

    public static function setResponse(Response $response)
    {
        self::$response = $response;
    }

    public function stream_stat()
    {
        return array();
    }

    public static function reset()
    {
        foreach (self::$overrideWrappers as $wrapper) {
            stream_wrapper_restore($wrapper);
        }
    }
}
