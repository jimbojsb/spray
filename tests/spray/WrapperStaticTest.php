<?php
class WrapperStaticTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->orig_wrappers = Spray\Wrapper::$overrideWrappers;
        Spray\Wrapper::$overrideWrappers = array('test');
    }

    public function tearDown()
    {
        Spray\Wrapper::reset();
        Spray\Wrapper::$overrideWrappers = $this->orig_wrappers;
    }

    public function testInit()
    {
        $old_wrappers = stream_get_wrappers();
        Spray\Wrapper::init();
        $new_wrappers = stream_get_wrappers();

        $this->assertNotEquals($old_wrappers, $new_wrappers);
    }

    /*
     * @depends testInit
     */
    public function testReset()
    {
        $old_wrappers = stream_get_wrappers();
        Spray\Wrapper::init();
        Spray\Wrapper::reset();
        $new_wrappers = stream_get_wrappers();

        $this->assertEquals($old_wrappers, $new_wrappers);
    }

}