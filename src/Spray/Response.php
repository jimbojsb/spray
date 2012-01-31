<?php
namespace Spray;
class Response
{
    const STATUS_200 = "200 OK";
    const STATUS_404 = "404 Not Found";
    const STATUS_301 = "301 Moved Permanently";
    const STATUS_302 = "302 Moved";

    protected $statusCode = self::STATUS_200;
    protected $headers = array();
    protected $body;

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    public function setHeader($headerName, $headerValue)
    {
        $this->headers[$headerName] = $headerValue;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function __toString()
    {
        $output = "HTTP/1.0 $this->statusCode\r\n";
        foreach ($this->headers as $header => $value) {
            $output .= "$header: $value\r\n";
        }
        $output .= "\r\n";
        $output .= $this->body;
        return $output;
    }
}
?>