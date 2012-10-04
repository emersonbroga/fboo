<?php

namespace fboo;

class UserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider providerForTestConstructor
     */
    public function testConstructor($token, $id, $expectedId)
    {
        $user = new User($token, $id);
        $this->assertInstanceOf('fboo\User', $user);
        $this->assertEquals($token, $user->getToken());
        $this->assertEquals($expectedId, $user->getId());
    }

    /**
     * @dataProvider providerForTestInvalidConstructor
     * @expectedException InvalidArgumentException
     */
    public function testInvalidConstructor($token, $id)
    {
        new User($token, $id);
    }

    public function testSettersAngGetters()
    {
        $user = new User('abCdEfGhij', '0123456789');

        $this->assertEquals('abCdEfGhij', $user->getToken());
        $this->assertEquals('0123456789', $user->getId());

        $user = $user->setToken('kLm4NoPq5');
        $this->assertInstanceOf('fboo\User', $user);
        $this->assertEquals('kLm4NoPq5', $user->getToken());

        $user = $user->setId('12345');
        $this->assertInstanceOf('fboo\User', $user);
        $this->assertEquals('12345', $user->getId());
    }

    public function providerForTestConstructor()
    {
        return array(
            array('1', '1', '1'),
            array('1', null, 'me'),
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
            array('@', ''),
            array(' ', null),
            array('=*34$@#', null),
            array(null, null),
            array(array(), array()),
            array(true, true),
            array(false, false),
        );
    }
}
