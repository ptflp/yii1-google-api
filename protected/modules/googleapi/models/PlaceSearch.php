<?php

class PlaceSearch
{
   protected $placesApiObj;

   protected $results = [];

   protected $apiKeyPath;

   protected $cityModel;

   protected $placesData = [];

   protected $addressData = [];

   /*
   // надо бы найти и сопоставить google types
   // и найти руссифицированные типы
   // нашел в гугл мапс русские аналоги типов.
   // Multiple types queries depricated прийдется по каждому типу выводить поочередно.
   */
   protected $placeTypes;

   public function __construct(GooglePlacesApi $placesApiObj, City $cityModel)
   {
      $this->placesApi = $placesApiObj;
      $this->apiKeyPath = Yii::app()->params['g_api_key'];
      $this->setApiKey();
      $this->city = $cityModel;
      $this->placeTypes = [
         [
            'en' => 'movie_theater',
            'ru' => 'кинотеатр'
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

   public function setApiKey()
   {
      $jsonKey = file_get_contents($this->apiKeyPath);
      $keyObj = json_decode($jsonKey);
      $key = $keyObj->key;
      $this->placesApi->setApiKey($key);
   }

   public function getData($cityId,string $place)
   {
      $place = trim($place);
      $this->placesApi
         ->requestCitiesByName($cityId)
         ->findOne()
         ->requestDetails('geometry,address_components')
         ->getResults();

      $addressDetails = $this->placesApi
								->requestAdressByCity($cityId,$place)
								->requestDetails('geometry')
                        ->getResults();

      array_slice($addressDetails,0,2);
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

      foreach ($this->placeTypes as $type) {
         $addType = '';
         if(strpos($place,$type['ru']) == false) {
            $addType = $type['ru'];
         }
         $temp = $this->placesApi
         ->nearbySearch($addType.' '.$place,$type['en'])
         ->getResults();
         foreach ($temp as $item) {
            $itemName = mb_strtolower($item->name);
            if(strpos($itemName,$place) !== false){
               $name = $item->name . ', ' . $addType;
               $lat = $item->geometry->location->lat;
               $lng = $item->geometry->location->lng;
               $address = substr($item->vicinity, 0, strrpos($item->vicinity, ","));
               // $address = $item->vicinity;
               $this->placesData[] = [
                  "name" => $name,
                  "longitude" => $lng,
                  "latitude" => $lat,
                  "address" => $address
               ];
            }
         }
      }

      $this->results = array_merge($this->addressData,$this->placesData);

      return $this;
   }

   public function getResults()
   {
      return $this->results;
   }
}
