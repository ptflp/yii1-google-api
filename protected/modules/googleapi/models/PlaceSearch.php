<?php

use Modules\GoogleApi\GooglePlacesApi;

class PlaceSearch
{
  protected $placesApiObj;

  protected $apiKeyPath;

  protected $cityModel;

  protected $results = [];

  protected $placesData = [];

  protected $placesDataRaw = [];

  protected $addressData = [];

  protected $addressDataRaw = [];

  protected $citiesData = [];

  protected $cityName = NULL;

  protected $cityAttributes = [];

  protected $placeTypes = [];

  protected $detectedTypes = [];

  protected $addressLimit = 3;

  protected $placesLimit = 5;

  protected $matchPercent = 89;

  public function __construct(GooglePlacesApi $placesApiObj, City $cityModel)
  {
    $this->placesApi = $placesApiObj;
    $this->apiKeyPath = Yii::app()->params['g_api_key'];
    $this->setApiKey();
    $this->cityModel = $cityModel;
    /* Hardcoded
    // надо бы найти и сопоставить google types
    // то есть найти руссифицированные типы
    // нашел в гугл мапс русские аналоги типов.
    // Multiple types queries depricated прийдется по каждому типу выводить по очередно.
    */
    $this->placeTypes = [
      [
        'en' => 'movie_theater',
        'ru' => 'кинотеатр'
      ],
      [
        'en' => 'electronics_store',
        'ru' => 'Магазин электроники'
      ],
      [
        'en' => 'lodging', // сюда входят мини гостиницы и прочие
        'ru' => 'гостиница'
      ],
      [
        'en' => 'cafe',
        'ru' => 'кафе'
      ],
      [
        'en' => 'bar',
        'ru' => 'бар'
      ],
      [
        'en' => 'liquor_store',
        'ru' => 'Магазин спиртных напитков'
      ],
      [
        'en' => 'supermarket',
        'ru' => 'супермаркет'
      ]
    ];
  }

  public function setApiKey(string $keyInput = NULL)
  {
    $key = $keyInput;
    if ($keyInput==NULL) {
          $jsonKey = file_get_contents($this->apiKeyPath);
          $keyObj = json_decode($jsonKey);
          $key = $keyObj->key;
    }
    $this->placesApi->setApiKey($key);

    return $this;
  }

  public function setMatchPercent(int $matchPercent)
  {
    $this->matchPercent = $matchPercent;

    return $this;
  }

  public function setAddressLimit(int $addressLimit)
  {
    $this->addressLimit = $addressLimit;

    return $this;
  }

  public function setPlacesLimit(int $placesLimit)
  {
    $this->placesLimit = $placesLimit;

    return $this;
  }

  public function setPlacesApiCity()
  {
    $this->placesApi
        ->requestCitiesByName($this->cityName)
        ->findOne()
        ->requestDetails('geometry');
  }


  public function requestData(int $cityId,string $input)
  {
    $input = trim($input);

    $this->requestCityById($cityId);
    if(is_array($this->cityAttributes)) {

        $this->setPlacesApiCity();

        $this->requestAddresses($input)
            ->prepareAddressData();

        $this->requestPlaces($input)
             ->preparePlacesData();
    }

    $this->results = array_merge($this->addressData,$this->placesData);

    return $this;
  }

  protected function requestCityById(int $cityId)
  {
    $cityModel = $this->cityModel::model()->findByPk($cityId);
    if($cityModel !== NULL) {
        $this->cityName = $cityModel->name;
        $this->cityAttributes = $cityModel->attributes;
    }

  }

  public function requestCitiesByName(string $cityName = NULL)
  {
    if ($cityName !== NULL) {
          $this->cityName = $cityName;
    }
    $fields = 'geometry,name';
    $rawCities = $this->placesApi
        ->requestCitiesByName($this->cityName)
        ->requestDetails($fields)
        ->getResults();

    foreach ($rawCities as $city) {
          $this->citiesData[] = [
            "name" => $city->details->name,
            "place_id" => $city->place_id,
            "longitude" => $city->details->geometry->location->lng,
            "latitude" => $city->details->geometry->location->lat,
            "description" => $city->description
          ];
    }


    return $this;
  }

  public function getCities()
  {
    return $this->citiesData;
  }

  protected function requestAddresses(string $address)
  {
    $city = $this->cityName;
    $addressDetails = $this->placesApi
                      ->requestAdressByCity($city,$address)
                      ->requestDetails('geometry')
                      ->getResults();
    $this->addressDataRaw = $addressDetails;
    return $this;
  }

  public function prepareAddressData()
  {
    $addressDetails = $this->addressDataRaw;
    array_slice($addressDetails,0,$this->addressLimit);
    foreach ($addressDetails as $addressObj) {
        $name = $addressObj->structured_formatting->main_text;
        $lng = $addressObj->details->geometry->location->lng;
        $lat = $addressObj->details->geometry->location->lat;
        $this->addressData[] = [
          "name" => $name,
          "longitude" => $lng,
          "latitude" => $lat,
          "address" => $name
        ];
    }

    return $this;
  }

  public function detectTypes($input = NULL)
  {
    if ($input !==NULL ) {
      $this->input = $input;
    }

    $temp = [];

    foreach ($this->placeTypes as $key => $type) {
      $words = explode(' ',$input);
      foreach ($words as $word) {
        similar_text($type['ru'], $word, $percent);

        if($percent > $this->matchPercent) {
          $temp[] = $this->placeTypes[$key];
        }
      }
    }

    if (!empty($temp)) {
      $this->detectedTypes = $temp;
      return true;
    } else {
      return false;
    }
  }

  public function requestPlaces(string $place)
  {
    $detect = $this->detectTypes($place);
    if ($detect) {
      $types = $this->detectedTypes;
    } else {
      $types = $this->placeTypes;
    }
    $temp = [];
    foreach ($types as $key => $type) {
      $input = $place;
      if(!$detect) {
        $input = $type['ru'] . ' ' . $place;
      }
      $result = $this->placesApi
                    ->nearbySearch($input,$type['en'])
                    ->getResults();
      foreach ($result as $item) {
        $temp[]=$item;
      }
    }

    $this->placesDataRaw = $temp;

    return $this;
  }

  public function preparePlacesData()
  {
    $temp = $this->placesDataRaw;
    foreach ($temp as $item) {
      $onePlace = $this->prepareOnePlace($item);
      if (!empty($onePlace)) {
        $this->placesData[] = $onePlace;
      }
    }
  }

  public function prepareOnePlace($place)
  {
    $item = $place;
    $itemName = mb_strtolower($item->name);
    similar_text($itemName, $this->input, $percent);
    $data = [];
    if($percent > $this->matchPercent) {
      $name = $item->name . ', ' . $item->types[0];
      $lat = $item->geometry->location->lat;
      $lng = $item->geometry->location->lng;
      $address = substr($item->vicinity, 0, strrpos($item->vicinity, ","));

      $data = [
        "name" => $name,
        "longitude" => $lng,
        "latitude" => $lat,
        "address" => $address
      ];
    }

    return $data;
  }

  public function getPlacesRaw()
  {
    return $this->placesDataRaw;
  }

  public function getAddressRaw()
  {
    return $this->addressDataRaw;
  }

  public function getRawData()
  {
    $raw = array_merge($this->addressDataRaw,$this->placesDataRaw);
    return $raw;
  }

  public function getResults()
  {
    return $this->results;
  }
}
