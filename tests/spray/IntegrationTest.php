<?php
class IntegrationTest extends PHPUnit_Framework_TestCase
{
    public function testSpray()
    {
        Spray\Wrapper::init();

        $response = new Spray\Response();
        $response->setStatusCode(Spray\Response::STATUS_302);
        $response->setHeader('Location', 'http://www.bing.com/');
        $response->setBody('thisisabody');
        Spray\Wrapper::setResponse($response);

        $expected = "HTTP/1.0 302 Moved\r\nLocation: http://www.bing.com/\r\n\r\nthisisabody";
        $response = file_get_contents('http://www.google.com/');
        $this->assertEquals($expected, $response);
    }
}