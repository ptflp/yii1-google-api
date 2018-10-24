<?php

class PlaceSearch
{
   public $placesApi;

   public $results;

   protected $apiKeyPath;

   /*
   // надо бы найти и сопоставить google types
   // и найти руссифицированные типы
   // нашел в гугл мапс русские аналоги типов.
   // Multiple types queries depricated прийдется по каждому типу выводить поочередно.
   */
   protected $placeTypes = [
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

   public function __construct(GooglePlacesApi $placesApi)
   {
      $this->placesApi = $placesApi;
      $this->apiKeyPath = Yii::app()->params['g_api_key'];
   }
}
