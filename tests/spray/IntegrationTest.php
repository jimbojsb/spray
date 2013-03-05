<?php

use Spray\Spray;

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

    public function testRawSpray()
    {
        $raw = "this is some raw text";
        $response = array('raw' => $raw);
        Spray::stub('foo://bar', $response);

        $this->assertEquals($raw, file_get_contents('foo://bar'));
    }

    public function testEchoBackSpray()
    {
        $response = array('echo_back' => 'content');
        Spray::stub('foo://bar', $response);

        $content = 'this is some content to be echod back';
        $context = stream_context_create(array('foo' => array('content' => $content)));

        $this->assertEquals($content, file_get_contents('foo://bar', false, $context));
    }

    public function testRegexSpray()
    {
        $response = array('status' => Spray::STATUS_200,
                          'headers' => array("connection" => "close"),
                          'body' => "{foo: bar}");
        Spray::regexStub('http', '`test/.*`', $response);

        $expected = "HTTP/1.0 200 OK\r\nconnection: close\r\n\r\n{foo: bar}";

        $this->assertEquals($expected, file_get_contents('http://test/some_url?foo=bar#frag'));
    }
}