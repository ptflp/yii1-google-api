<?php

class PlaceController extends Controller
{

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
                array('allow',  // allow all users to perform 'index' and 'view' actions
                    'actions'=>array('search','findcity','test'),
                    'users'=>array('@'),
                ),
                array('deny',  // deny all users
                    'users'=>array('*'),
                    'deniedCallback' => function () {
                        Yii::app()->controller->redirect(array ('/site/index'));
                    }
                ),
        );
    }

    public function actionSearch()
    {
        if (!isset($_GET['city_id']) || !isset($_GET['keyword'])) {
            $this->renderJSON([]);
            return;
        }
        if (!is_numeric($_GET['city_id']) || mb_strlen($_GET['keyword'])<1 || mb_strlen($_GET['keyword'])>20) {
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
                        ->get('Modules\GoogleApi\Models\DataWrapper')
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
                            ->get('Modules\GoogleApi\Models\PlaceSearch')
                            ->requestCitiesByName($cityName)
                            ->getCities();

            $this->renderJSON($cities);
        } else {
            $this->renderJSON([]);
        }
    }

    public function actionTest()
    {
        $arr = [
            [
                1, 37, 8, 9, 140, 217
            ],
            [
                21, 75, 38, 97, 10, 17
            ],
            [
                31, 76, 8, 49, 10, 147
            ],
            [
                1, 76, 83, 9, 180, 137
            ],
        ];
        dump_r($arr);

        $temp = [];
        foreach ($arr as $subArray) {
            $max1 = max($subArray);
            $key=array_search($max1, $subArray);
            unset($subArray[$key]);
            $max2 = max($subArray);
            $temp[] = $max1 + $max2;
        }
        $maxSumm = max($temp);

        $key = array_search($maxSumm, $temp);

        dump_r($arr[$key]);
    }
}
