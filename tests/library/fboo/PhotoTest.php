<?php

namespace fboo;

class PhotoTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $photo = new Photo(new Album(new User('aBcDeFgHiJ', '1234567890')));
        $this->assertInstanceOf('fboo\Photo', $photo);
    }

    public function testCreate()
    {
        $data = array(
            'image' => __FILE__,
            'caption' => 'photo caption',
        );

        $expectedData = array(
            'image' => '@' . __FILE__,
            'caption' => 'photo caption',
        );

        $album = new Album(new User('aBcDeFgHiJ'));
        $album->setId('1234');

        $photo = new Photo($album);

        $toRequest = $photo->create($data['image'], $data['caption']);
        $url = sprintf(Photo::URL_PHOTOS, '1234') . '?access_token=aBcDeFgHiJ';
        $this->assertInstanceOf('\stdClass', $toRequest);
        $this->assertObjectHasAttribute('method', $toRequest);
        $this->assertObjectHasAttribute('url', $toRequest);
        $this->assertObjectHasAttribute('data', $toRequest);
        $this->assertEquals('post', $toRequest->method);
        $this->assertEquals($url, $toRequest->url);
        $this->assertEquals($expectedData, $toRequest->data);
    }

    /**
     * @dataProvider providerForTestInvalidCreate
     * @expectedException InvalidArgumentException
     */
    public function testInvalidCreate($filePath, $caption)
    {
        $photo = new Photo(new Album(new User('aBcDeFgHiJ')));
        $photo->create($filePath, $caption);
    }

    public function providerForTestInvalidCreate()
    {
        return array(
            array('', ''),
            array(true, ''),
            array(false, ''),
            array(null, ''),
            array(array(), ''),
            array('', 'caption'),
            array(__FILE__, null),
            array(__FILE__, ''),
            array(__FILE__, array()),
        );
    }

}
