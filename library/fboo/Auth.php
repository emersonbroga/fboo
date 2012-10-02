<?php

namespace fboo;

class Auth
{

    const AUTH_URL = 'https://graph.facebook.com/oauth/authorize';
    const TOKEN_URL = 'https://graph.facebook.com/oauth/access_token';

    private $appId, $appSecret, $callbackUrl;

    public function __construct($appId, $appSecret)
    {
        if($appId == '')
            throw new \InvalidArgumentException('You must provide an ID');

        if($appSecret == '')
            throw new \InvalidArgumentException('You must provide a Secret');

        if(!ctype_digit($appId))
            throw new \InvalidArgumentException('You must provide a valid ID');

        if(!ctype_xdigit($appSecret))
            throw new \InvalidArgumentException('You must provide a valid Secret');

        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    public function setCallbackUrl($url)
    {
        $this->callbackUrl = $url;
    }

    public function authorize($scope)
    {
        if(!$this->callbackUrl)
            throw new BadMethodCallException('You must provide callbackUrl');

        $args = array(
            'client_id' => $this->appId,
            'redirect_uri' => $this->callbackUrl,
            'scope' => $scope,
        );

        header('Location: ' . sprintf('%s?%s', self::AUTH_URL, http_build_query($args)));
    }

    public function authenticate($code)
    {
        if(!$code)
            throw new \InvalidArgumentException('You must provide a code');

        $args = array(
            'client_id' => $this->appId,
            'redirect_uri' => $this->callbackUrl,
            'client_secret' => $this->appSecret,
            'code' => $code,
        );

        $url = sprintf('%s?%s', self::TOKEN_URL, http_build_query($args));

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        $error = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if(!$result)
            throw new Exception($error);

        $elements = array();
        parse_str($result, $elements);

        if(!isset($elements['access_token']))
            throw new Exception('Access Token not Found');

        return $elements['access_token'];
    }

}
