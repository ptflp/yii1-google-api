<?php

class ClientAdaptor implements ClientAdaptorInterface
{
    protected $url;

    protected $paramsUrl=[];

    protected $response;

    protected $statusCode;

    protected $client;

    protected $requestType = 'GET';

    public function __construct(GuzzleHttp\Client $client)
    {
        $this->client = $client;
    }

    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    public function setParamsUrl(array $paramsUrl)
    {
        $this->paramsUrl = $paramsUrl;

        return $this;
    }

    public function setRequestType(string $requestType)
    {
        $this->requestType = $requestType;

        return $this;
    }

    public function generateUrl()
    {
        $url = $this->url . '?' . urldecode(http_build_query($this->paramsUrl));

        return $url;
    }

    public function fetch()
    {
        if($this->requestType == 'POST') {
            $params = [
                'form_params' => $this->paramsUrl,
            ];
        } else {
            $params = [
                'query' => $this->paramsUrl,
            ];
        }

        $res = $this->client->request($this->requestType, $this->url, $params);

        $this->statusCode = $res->getStatusCode();
        $this->response = $res->getBody();

        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getResponse()
    {
        return $this->response;
    }
}