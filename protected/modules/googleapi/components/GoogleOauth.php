<?php

class GoogleOauth implements GoogleOauthInterface
{
    const OAUTH_URL = "https://accounts.google.com/o/oauth2/auth";

    const OAUTH_TOKEN_URL = "https://accounts.google.com/o/oauth2/token";

    const USERINFO_URL = "https://www.googleapis.com/oauth2/v1/userinfo";

    const SCOPE_URL = "https://www.googleapis.com/auth/";

    const PLUS_LOGIN = "plus.login";

    const PLUS_ME = "plus.me";

    const USERINFO_EMAIL = "userinfo.email";

    const USERINFO_PROFILE = "userinfo.profile";

    protected $clientId;

    protected $clientSecret;

    protected $scope;

    protected $redirectUri;

    protected $configPath;

    protected $oauthLink;

    protected $oauthToken;

    protected $userInfo;

    protected $httpClient;

    public function __construct(\Modules\GoogleApi\ClientAdaptor $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function setClientId(string $clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function setClientSecret(string $clientSecret)
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    public function setRedirectUri(string $redirectUri)
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    public function setConfigFile(string $configPath)
    {
        $this->configPath = $configPath;
    }


    public function loadFileConfig(string $configPath = NULL)
    {
        if (!$configPath == NULL) {
            $this->setConfigFile($configPath);
        }
        $json = file_get_contents($this->configPath);

        $dataSecrets = json_decode($json);
        $this->setClientId($dataSecrets->web->client_id)
             ->setClientSecret($dataSecrets->web->client_secret);

        return $this;
    }

    public function addScope(string $scope)
    {
        $this->scope .= self::SCOPE_URL.$scope.' ';

        return $this;
    }

    public function createOauthLink()
    {
        $params = [
            'redirect_uri'  => $this->redirectUri,
            'response_type' => 'code',
            'client_id'     => $this->clientId,
            'scope'         => $this->scope
        ];
        $url = self::OAUTH_URL;
        $this->oauthLink = $this->httpClient
                                    ->setParamsUrl($params)
                                    ->setUrl($url)
                                    ->generateUrl();

        return $this;
    }

    public function getOauthLink()
    {
        return $this->oauthLink;
    }

    public function requestOauthToken(string $code)
    {
        $url = self::OAUTH_TOKEN_URL;
        $params = [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'grant_type'    => 'authorization_code',
            'code'          => $code
        ];

        $this->oauthToken = $this->fetch($url,$params,'POST');

        return $this;
    }

    public function getOauthToken()
    {
        return $this->oauthToken;
    }

    public function requestUserInfo()
    {
        $url = self::USERINFO_URL;
        $params = [
            'access_token'     => $this->oauthToken->access_token
        ];

        $this->userInfo = $this->fetch($url,$params);

        return $this;

    }

    public function getUserInfo()
    {
        return $this->userInfo;
    }

    protected function fetch(string $url, array $paramsUrl, string $requestType = 'GET')
    {
        $this->httpClient->setUrl($url)
                     ->setParamsUrl($paramsUrl)
                     ->setRequestType($requestType)
                     ->fetch();

		$json = $this->httpClient->getResponse();
        $data = json_decode($json);

        return $data;
    }
}