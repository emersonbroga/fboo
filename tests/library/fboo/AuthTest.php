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

    /**
     * @dataProvider providerForTestSetCallbackUrl
     * @expectedException InvalidArgumentException
     */
    public function testSetCallbackUrl($url)
    {
        $auth = new Auth('0123456789', '0123456789abcdef');
        $auth->setCallbackUrl($url);
    }

    public function testSetGetCallbackUrl()
    {
        $auth = new Auth('0123456789', '0123456789abcdef');

        $this->assertEquals($auth, $auth->setCallbackUrl('http://localhost/callback'));
        $this->assertEquals('http://localhost/callback', $auth->getCallbackUrl());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAuthorizeWithoutSetCallbackUrl()
    {
        $auth = new Auth('0123456789', '0123456789abcdef');
        $auth->authorize();
    }

    public function testAuthorize()
    {
        $auth = new Auth('0123456789', '0123456789abcdef');
        $auth->setCallbackUrl('http://localhost/callback');

        $toRequest = $auth->authorize();
        $url = Auth::URL_AUTH . '?client_id=0123456789&redirect_uri=http%3A%2F%2Flocalhost%2Fcallback&scope=';
        $this->assertInstanceOf('\stdClass', $toRequest);
        $this->assertObjectHasAttribute('method', $toRequest);
        $this->assertObjectHasAttribute('url', $toRequest);
        $this->assertEquals('get', $toRequest->method);
        $this->assertEquals($url, $toRequest->url);

        $toRequest = $auth->authorize('email', 'user_about_me');
        $url = Auth::URL_AUTH . '?client_id=0123456789&redirect_uri=http%3A%2F%2Flocalhost%2Fcallback&scope=email%2Cuser_about_me';
        $this->assertInstanceOf('\stdClass', $toRequest);
        $this->assertObjectHasAttribute('method', $toRequest);
        $this->assertObjectHasAttribute('url', $toRequest);
        $this->assertEquals('get', $toRequest->method);
        $this->assertEquals($url, $toRequest->url);

    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAuthenticateWithoutSetCode()
    {
        $auth = new Auth('0123456789', '0123456789abcdef');
        $auth->authenticate('');
    }

    public function testAuthenticate()
    {
        $auth = new Auth('0123456789', '0123456789abcdef');
        $toRequest = $auth->authenticate('abcdefghijklmnopqrstuvwxyz');
        $url = Auth::URL_TOKEN . '?client_id=0123456789&client_secret=0123456789abcdef&code=abcdefghijklmnopqrstuvwxyz';
        $this->assertInstanceOf('\stdClass', $toRequest);
        $this->assertObjectHasAttribute('method', $toRequest);
        $this->assertObjectHasAttribute('url', $toRequest);
        $this->assertEquals('get', $toRequest->method);
        $this->assertEquals($url, $toRequest->url);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetPermissionsWithoutSetToken()
    {
        $auth = new Auth('0123456789', '0123456789abcdef');
        $auth->getPermissions('');
    }

    public function testGetPermissions()
    {
        $auth = new Auth('0123456789', '0123456789abcdef');
        $toRequest = $auth->getPermissions('a1b2c3d4e5f6g7h8i9j1k2l3m4n5o6p7q8r9s1t2u3v4w6x7y8z');
        $url = Auth::URL_PERMISSIONS . '?access_token=a1b2c3d4e5f6g7h8i9j1k2l3m4n5o6p7q8r9s1t2u3v4w6x7y8z';
        $this->assertInstanceOf('\stdClass', $toRequest);
        $this->assertObjectHasAttribute('method', $toRequest);
        $this->assertObjectHasAttribute('url', $toRequest);
        $this->assertEquals('get', $toRequest->method);
        $this->assertEquals($url, $toRequest->url);
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

    public function providerForTestSetCallbackUrl()
    {
        return array(
            array(''),
            array(null),
            array(array()),
            array(true),
            array(false),
        );
    }

}
