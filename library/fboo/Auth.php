<?php

namespace fboo;

class Auth
{

    const URL_AUTH = 'https://graph.facebook.com/oauth/authorize';
    const URL_TOKEN = 'https://graph.facebook.com/oauth/access_token';
    const URL_PERMISSIONS = 'https://graph.facebook.com/me/permissions';

    private $appId, $appSecret, $callbackUrl;

    public function __construct($appId, $appSecret)
    {
        if($appId == '')
            throw new \InvalidArgumentException('You must provide an ID');

        if($appSecret == '')
            throw new \InvalidArgumentException('You must provide a Secret');

        if(!ctype_digit($appId))
            throw new \InvalidArgumentException('You must provide a valid Id');

        if(!ctype_xdigit($appSecret))
            throw new \InvalidArgumentException('You must provide a valid Secret');

        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    public function setCallbackUrl($url)
    {
        if(empty($url) || !is_scalar($url) || strlen($url) < 3)
            throw new \InvalidArgumentException('You must provide a valid Callback URL');

        $this->callbackUrl = $url;
        return $this;
    }

    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }

    public function authorize()
    {
        if(!$this->callbackUrl)
            throw new \InvalidArgumentException('You must provide a valid Callback URL');

        $args = array(
            'client_id' => $this->appId,
            'redirect_uri' => $this->callbackUrl,
            'scope' => implode(',', func_get_args()),
        );

        return (object) array(
            'method' => 'get',
            'url' => sprintf('%s?%s', self::URL_AUTH, http_build_query($args))
        );
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

        return (object) array(
            'method' => 'get',
            'url' => sprintf('%s?%s', self::URL_TOKEN, http_build_query($args))
        );
    }

    public function getPermissions($token)
    {
        if(!$token)
            throw new \InvalidArgumentException('You must provide a token');

        $args = array(
            'access_token' => $token,
        );

        return (object) array(
            'method' => 'get',
            'url' => sprintf('%s?%s', self::URL_PERMISSIONS, http_build_query($args))
        );
    }

}
