<?php

namespace fboo;

class AlbumTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $album = new Album(new User('aBcDeFgHiJ', '1234567890'));
        $this->assertInstanceOf('fboo\Album', $album);
    }

    public function testCreate()
    {
        $data = array(
            'name' => 'album name',
            'description' => 'album description',
        );

        $album = new Album(new User('aBcDeFgHiJ', '1234567890'));

        $toRequest = $album->create($data['name'], $data['description']);
        $url = sprintf(Album::URL_ALBUMS, '1234567890') . '?access_token=aBcDeFgHiJ';
        $this->assertInstanceOf('\stdClass', $toRequest);
        $this->assertObjectHasAttribute('method', $toRequest);
        $this->assertObjectHasAttribute('url', $toRequest);
        $this->assertObjectHasAttribute('data', $toRequest);
        $this->assertEquals('post', $toRequest->method);
        $this->assertEquals($url, $toRequest->url);
        $this->assertEquals($data, $toRequest->data);

        $album = new Album(new User('aBcDeFgHiJ'));

        $toRequest = $album->create($data['name'], $data['description']);
        $url = sprintf(Album::URL_ALBUMS, 'me') . '?access_token=aBcDeFgHiJ';
        $this->assertInstanceOf('\stdClass', $toRequest);
        $this->assertObjectHasAttribute('method', $toRequest);
        $this->assertObjectHasAttribute('url', $toRequest);
        $this->assertObjectHasAttribute('data', $toRequest);
        $this->assertEquals('post', $toRequest->method);
        $this->assertEquals($url, $toRequest->url);
        $this->assertEquals($data, $toRequest->data);
    }

    /**
     * @dataProvider providerForTestInvalidCreate
     * @expectedException InvalidArgumentException
     */
    public function testInvalidCreate($name, $description)
    {
        $album = new Album(new User('aBcDeFgHiJ'));
        $album->create($name, $description);
    }

    public function providerForTestInvalidCreate()
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
        );
    }

}
