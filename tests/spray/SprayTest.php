<?php
class SprayTest extends PHPUnit_Extensions_OutputTestCase
{
    public function setUp()
    {
        $this->spray = new Spray();
    }

    public function testStream_open()
    {
        $_ = null;
        $expected = "HTTP/1.0 200 OK\r\n\r\nfoo";
        $this->setPrivate('responses', array('http://url' => array('body' => 'foo')));
        $this->assertTrue($this->spray->stream_open('http://url', null, null, $_));
        $this->assertEquals($expected, $this->getPrivate('output'));
    }

    public function testStream_write()
    {
        $this->expectOutputString('test');
        $this->spray->stream_write('test');
    }

    public function testStream_eof()
    {
        $this->assertTrue($this->spray->stream_eof());

        $this->setPrivate('output', 'test');
        $this->assertFalse($this->spray->stream_eof());

        $this->setPrivate('currentPosition', 4);
        $this->assertTrue($this->spray->stream_eof());
    }

    public function testStream_read()
    {
        $this->assertEmpty($this->spray->stream_read(1));

        $this->setPrivate('output','hello world');
        $this->assertEquals($this->spray->stream_read(5), 'hello');

        $this->assertEquals($this->spray->stream_read(6), ' world');

        $this->assertEmpty($this->spray->stream_read(10));
    }

    public function testStream_stat()
    {
        $this->assertEquals(array(), $this->spray->stream_stat());
    }

    private function setPrivate($prop, $value)
    {
        $property = new ReflectionProperty($this->spray, $prop);
        $property->setAccessible(true);
        $property->setValue($this->spray, $value);
    }

    private function getPrivate($prop)
    {
        $property = new ReflectionProperty($this->spray, $prop);
        $property->setAccessible(true);
        return $property->getValue($this->spray);
    }
}