<?php
class SprayStaticTest extends PHPUnit_Framework_TestCase
{
    public function testStubAndReset()
    {
        Spray::stub('test://path', array('body' => 'foo'));
        Spray::stub('http://url', array('body' => 'bar'));

        $this->assertContains('test', stream_get_wrappers());
        $this->assertContains('http', stream_get_wrappers());

        Spray::reset();

        $this->assertNotContains('test', stream_get_wrappers());
        $this->assertContains('http', stream_get_wrappers());
    }

    public function testRegexStub()
    {
        Spray::regexStub('test', '`foo/.*`', array('body' => 'bar'));
        Spray::regexStub('http', '/(bar|baz)/', array('body' => 'wtf'));

        $this->assertContains('test', stream_get_wrappers());
        $this->assertContains('http', stream_get_wrappers());

        Spray::reset();

        $this->assertNotContains('test', stream_get_wrappers());
        $this->assertContains('http', stream_get_wrappers());
    }
}