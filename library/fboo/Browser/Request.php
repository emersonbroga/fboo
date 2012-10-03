<?php

namespace fboo\Browser;

class Request
{

    protected $method, $url, $data;

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = strtoupper($method);
        return $this;
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

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function go()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if($this->method == 'GET') {
            curl_setopt($curl, CURLOPT_URL, $this->url);
        }
        elseif($this->method == 'POST') {
            curl_setopt($curl, CURLOPT_URL, $this->url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
        }

        $content = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return (object) array(
            'status' => $status,
            'content' => $content
        );
    }

}