<?php

interface GoogleOauthInterface
{
    public function setClientId(string $clientId);

    public function setClientSecret(string $clientSecret);

    public function setRedirectUri(string $redirectUri);

    public function addScope(string $scope);

    public function createOauthLink();

    public function getOauthLink();

    public function requestOauthToken(string $code);

    public function getOauthToken();

    public function requestUserInfo();

    public function getUserInfo();
}