<?php
class IntegrationTest extends PHPUnit_Framework_TestCase
{
    public function testSpray()
    {
        $response = array('status' => Spray::STATUS_200,
                          'headers' => array("connection" => "close"),
                          'body' => "{foo: bar}");
        Spray::stub('http://test', $response);

        $expected = "HTTP/1.0 200 OK\r\nconnection: close\r\n\r\n{foo: bar}";

        $this->assertEquals($expected, file_get_contents('http://test'));
    }
}