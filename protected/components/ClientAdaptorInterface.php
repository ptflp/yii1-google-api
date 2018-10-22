<?php

interface ClientAdaptorInterface
{
    public function setUrl(string $url);

    public function setParamsUrl(array $paramsUrl);

    public function setRequestType(string $requestType);

    public function generateUrl();

    public function fetch();

    public function getStatusCode();

    public function getResponse();
}