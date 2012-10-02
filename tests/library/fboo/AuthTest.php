<?php

namespace fboo;

class AuthTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider providerForTestConstructor
     */
    public function testConstructor($id, $secret)
    {
        $auth = new Auth($id, $secret);
        $this->assertInstanceOf('fboo\Auth', $auth);
    }

    /**
     * @dataProvider providerForTestInvalidConstructor
     * @expectedException InvalidArgumentException
     */
    public function testInvalidConstructor($id, $secret)
    {
        new Auth($id, $secret);
    }

    public function providerForTestConstructor()
    {
        return array(
            array('1', '1'),
            array('1', 'a'),
        );
    }

    public function providerForTestInvalidConstructor()
    {
        return array(
            array('', ''),
            array('-1', ''),
            array('a', ''),
            array('1', ''),
            array('', '-1'),
            array('', 'g'),
            array('', 'a'),
            array(null, null),
            array(array(), array()),
            array(true, true),
            array(false, false),
        );
    }
}
