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
        if (isset($_GET['address_limit'])) {
            if (is_numeric($_GET['address_limit'])) {
                $addressLimit = $_GET['address_limit'];
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
}
