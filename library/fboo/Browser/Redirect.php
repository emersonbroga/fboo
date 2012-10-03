<?php

namespace fboo\Browser;

class Redirect
{

    protected $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function go()
    {
        header('Location: ' . $this->url);
    }

}