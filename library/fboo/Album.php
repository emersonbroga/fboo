<?php

namespace fboo;

class Album
{

    const URL_ALBUMS = 'https://graph.facebook.com/%s/albums';

    private $user;
    private $id;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function create($name, $description)
    {
        if(empty($name))
            throw new \InvalidArgumentException('You must provide a Name');

        if(empty($description))
            throw new \InvalidArgumentException('You must provide a Description');

        if(!is_scalar($name))
            throw new \InvalidArgumentException('You must provide a valid Id');

        if(!is_scalar($description))
            throw new \InvalidArgumentException('You must provide a valid Description');

        $args = array(
            'name' => $name,
            'description' => $description,
        );

        return (object) array(
            'method' => 'post',
            'url' => sprintf('%s?access_token=%s', sprintf(self::URL_ALBUMS, $this->user->getId()), $this->user->getToken()),
            'data' => $args
        );
    }

}
