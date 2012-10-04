<?php

namespace fboo;

class User
{

    private $id, $token;

    public function __construct($token, $id = null)
    {

        if($token == '')
            throw new \InvalidArgumentException('You must provide a Token');

        if(!ctype_alnum($token))
            throw new \InvalidArgumentException('You must provide a valid Token');

        if(!is_null($id) && !ctype_digit($id))
            throw new \InvalidArgumentException('You must provide a valid Id');

        $this->token = $token;
        $this->id = $id ? : 'me';
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

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

}
