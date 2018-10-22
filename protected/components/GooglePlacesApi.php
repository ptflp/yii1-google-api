<?php

class GooglePlacesApi implements GooglePlacesApiInterface
{
    protected $placeId;

    protected $radius=17000;

    protected $client;

    protected $location = [];

    protected $autocompleteUrl = 'https://maps.googleapis.com/maps/api/place/autocomplete/json';

    protected $detailsUrl = 'https://maps.googleapis.com/maps/api/place/details/json';

    protected $paramsUrl = [];

    protected $results;

    protected $key;

    protected $lang = 'ru';

    public function __construct(ClientAdaptor $client, string $key=NULL)
    {
        if(!$key==NULL) {
            $this->key = $key;
        }
        $this->client = $client;
    }

    public function requestCitiesByName(string $name)
    {
        $paramsUrl = [
            'input'=>$name,
            'types'=>'(cities)',
            'language'=>$this->lang,
            'key'=>$this->key,
        ];
        $this->client->setParamsUrl($paramsUrl);
        $data = $this->fetch($this->autocompleteUrl);

        $this->results = $data->predictions;

        return $this;

    }

    public function requestPlacesByName(string $name, int $radius=NULL, array $location=NULL)
    {
        if(!$location==NULL) {
            $this->location = $location;
        }

        if(!empty($this->location)) {
            $lat = $this->location[0];
            $lng = $this->location[1];
        } else {
            //Exception
            die('Location not set');
        }

        if(!$radius==NULL) {
            $this->radius = $radius;
        }

        $paramsUrl = [
            'input'=>$name,
            'location'=>"$lat,$lng",
            'types'=>'establishment',
            'strictbounds' =>'',
            'radius'=>$this->radius,
            'language'=>$this->lang,
            'key'=> $this->key,
        ];
        $this->client->setParamsUrl($paramsUrl);


        $data = $this->fetch($this->autocompleteUrl);

        $this->results = $data->predictions;

        return $this;
    }

    public function requestPlacesByCity(string $city, string $place)
    {
        $paramsUrl = [
            'input'=>$city.' '.$place,
            'types'=>'establishment',
            'language'=>$this->lang,
            'key'=> $this->key,
        ];
        $this->client->setParamsUrl($paramsUrl);

        $data = $this->fetch($this->autocompleteUrl);

        $this->results = $data->predictions;

        return $this;
    }

    public function requestAdressByCity(string $city, string $address)
    {
        $paramsUrl = [
            'input'=>$city.' '.$address,
            'types'=>'address',
            'language'=>$this->lang,
            'key'=> $this->key,
        ];

        $this->client->setParamsUrl($paramsUrl);

        $data = $this->fetch($this->autocompleteUrl);

        $this->results = $data->predictions;

        return $this;
    }

    public function requestDetails(string $fields = NULL)
    {
        if (!is_array($this->results)) {
            $this->results->details = $this->getPlaceDetailsById($this->placeId,$fields);
            if(isset($this->results->geometry)) {
                $this->location[] = $this->results->geometry->location->lat;
                $this->location[] = $this->results->geometry->location->lng;
            }
        } else {
            foreach ($this->results as $key => $value) {
                $this->results[$key]->details=$this->getPlaceDetailsById($value->place_id,$fields);
            }
        }
        return $this;
    }

    public function getPlaceDetailsById(string $placeId, string $fields=NULL)
    {
        $paramsUrl = [
            'placeid'=>$placeId,
            'types'=>'establishment',
            'language'=>$this->lang,
            'key'=> $this->key,
        ];

        if(!$fields==NULL) {
            $paramsUrl['fields'] = $fields;
        }
        $this->client->setParamsUrl($paramsUrl);

        $data = $this->fetch($this->detailsUrl);

        return $data->result;
    }

    public function findOne()
    {
        $this->results = $this->results[0];
        $this->placeId = $this->results->place_id;

        return $this;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function setApiKey(string $key)
    {
        $this->key = $key;
        return $this;
    }

    public function setLanguage(string $lang)
    {
        $this->lang = $lang;
        return $this;
    }

    public function setRadius(int $radius)
    {
        $this->radius = $radius;
        return $this;
    }

    protected function fetch(string $url)
    {
        $this->client->setUrl($url);
        $this->client->fetch();

		$json = $this->client->getResponse();
        $data = json_decode($json);
        return $data;
    }
}