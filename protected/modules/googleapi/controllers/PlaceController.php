<?php

class PlaceController extends Controller
{
    public function actionSearch()
    {
        if (!isset($_GET['city_id']) || !isset($_GET['keyword'])) {
            $this->renderJSON([]);
            return;
        }
        if (!is_numeric($_GET['city_id']) || mb_strlen($_GET['keyword'])<1) {
            $this->renderJSON([]);
            return;
        }

        $matchPercent = 61.8;
        if (isset($_GET['match_percent'])) {
            if (is_numeric($_GET['match_percent'])) {
                $matchPercent = $_GET['match_percent'];
            }
        }

        $addressLimit = 8;
        if (isset($_GET['addresses_limit'])) {
            if (is_numeric($_GET['addresses_limit'])) {
                $addressLimit = $_GET['addresses_limit'];
            }
        }

        $placesLimit = 8;
        if (isset($_GET['places_limit'])) {
            if (is_numeric($_GET['places_limit'])) {
                $placesLimit = $_GET['places_limit'];
            }
        }

        $cityId = $_GET['city_id'];
        $keyword = $_GET['keyword'];

        $data = $this   ->container
                        ->get('DataWrapper')
                        ->setPlacesMatch($matchPercent)
                        ->setAddressesLimit($addressLimit)
                        ->setPlacesLimit($placesLimit)
                        ->requestData($cityId, $keyword)
                        ->getData();

        $this->renderJSON($data);
    }

    public function actionFindcity()
    {
        if (($_GET['city_name'])) {
            $cityName = mb_strtolower($_GET['city_name']);
            $cityName = trim($cityName);

            $cities = $this->container
                            ->get('PlaceSearch')
                            ->requestCitiesByName($cityName)
                            ->getCities();

            $this->renderJSON($cities);
        } else {
            $this->renderJSON([]);
        }
    }

    public function actionTest()
    {
        $jsonKey = file_get_contents(Yii::app()->params['g_api_key']);
        $keyObj = json_decode($jsonKey);
        $key = $keyObj->key;

        $client = $this->container->get('Modules\GoogleApi\ClientAdaptor');

        $client->setUrl('https://maps.googleapis.com/maps/api/place/nearbysearch/json');

        $types = [
            "administrative_area_level_1",
            "administrative_area_level_2",
            "administrative_area_level_3",
            "administrative_area_level_4",
            "administrative_area_level_5",
            "colloquial_area",
            "country",
            "establishment",
            "finance",
            "floor",
            "food",
            "general_contractor",
            "geocode",
            "health",
            "intersection",
            "locality",
            "natural_feature",
            "neighborhood",
            "place_of_worship",
            "political",
            "point_of_interest",
            "post_box",
            "postal_code",
            "postal_code_prefix",
            "postal_code_suffix",
            "postal_town",
            "premise",
            "room",
            "route",
            "street_address",
            "street_number",
            "sublocality",
            "sublocality_level_4",
            "sublocality_level_5",
            "sublocality_level_3",
            "sublocality_level_2",
            "sublocality_level_1",
            "subpremise"
        ];

        foreach ($types as $type) {
            echo $type;
            $paramsUrl = [
                'location'=>"62.0354523,129.6754745",
                'radius'=>17000,
                'type'=>$type,
                'language'=>"ru",
                'keyword'=>"синет",
                'key'=> $key,
            ];

            $client->setParamsUrl($paramsUrl);
            $client->fetch();
            $json = $client->getResponse();
            $data = json_decode($json, true);
            $this->debug($data);
        }
    }
}
