<?php
class WrapperTest extends PHPUnit_Extensions_OutputTestCase
{

    public function setUp()
    {
        Spray\Wrapper::init();
        $this->spray = new Spray\Wrapper();
    }

    public function tearDown()
    {
        Spray\Wrapper::reset();
    }

    public function testStream_open()
    {
        $testResponse = 'test';
        $_ = null;
        $this->changePrivateProperty('response', $testResponse);
        $this->assertTrue($this->spray->stream_open(null, null, null, $_));
        $this->assertEquals($this->getPrivateProperty('output'), $testResponse);
        $this->assertInstanceOf('Spray\Request', $this->getPrivateProperty('request'));
    }

    public function testStream_write()
    {
        $testString = 'test';
        $this->expectOutputString($testString);
        $this->spray->stream_write($testString);
    }

    public function testStream_eof()
    {
        $this->assertTrue($this->spray->stream_eof());

        $this->changePrivateProperty('output', 'test');
        $this->assertFalse($this->spray->stream_eof());

        $this->changePrivateProperty('currentPosition', 4);
        $this->assertTrue($this->spray->stream_eof());
    }

    public function testStream_read()
    {
        $this->assertEmpty($this->spray->stream_read(1));

        $this->changePrivateProperty('output','hello world');
        $this->assertEquals($this->spray->stream_read(5), 'hello');

        $this->assertEquals($this->spray->stream_read(6), ' world');

        $this->assertEmpty($this->spray->stream_read(10));
    }

    public function testStream_stat()
    {
        $this->assertEquals(array(), $this->spray->stream_stat());
    }

    private function changePrivateProperty($prop, $value)
    {
        $property = new ReflectionProperty($this->spray, $prop);
        $property->setAccessible(true);
        $property->setValue($this->spray, $value);
    }

    private function getPrivateProperty($prop)
    {
        $property = new ReflectionProperty($this->spray, $prop);
        $property->setAccessible(true);
        return $property->getValue($this->spray);
    }
}
?>