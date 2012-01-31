<?php
class WrapperTest extends PHPUnit_Framework_TestCase
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
        $this->markTestIncomplete('');
    }

    public function testStream_write()
    {
        $this->markTestIncomplete('');
    }

    public function testStream_eof()
    {
        $this->markTestIncomplete('');
    }

    public function testStream_read()
    {
        $this->markTestIncomplete('');
    }

    public function testStream_stat()
    {
        $this->assertEquals(array(), $this->spray->stream_stat());
    }

}