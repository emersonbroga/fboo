<?php

namespace fboo;

class Photo
{

    const URL_PHOTOS = 'https://graph.facebook.com/%s/photos';

    private $album;

    public function __construct(Album $album)
    {
        $this->album = $album;
    }

    public function create($filePath, $caption)
    {
        if(empty($filePath))
            throw new \InvalidArgumentException('You must provide a File Path');

        if(!file_exists($filePath) || !is_readable($filePath))
            throw new \InvalidArgumentException('You must provide a valid and readable File Path');

        if(empty($caption) || !is_scalar($caption))
            throw new \InvalidArgumentException('You must provide a Caption');

        $args = array(
            'caption' => $caption,
            'image' => '@' . $filePath,
        );

        return (object) array(
            'method' => 'post',
            'url' => sprintf('%s?access_token=%s', sprintf(self::URL_PHOTOS, $this->album->getId()), $this->album->getUser()->getToken()),
            'data' => $args
        );
    }

}
