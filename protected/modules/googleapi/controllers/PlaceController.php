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

        $cityId = $_GET['city_id'];
        $keyword = $_GET['keyword'];

        $dataWrapper = $this->container
                            ->get('DataWrapper');
        $data = $dataWrapper->requestData($cityId, $keyword)
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
