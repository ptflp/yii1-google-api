<?php

class CityController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='/layouts/material';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
            'postOnly + clear', // we only allow deletion via POST request
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
                'actions'=>array('index','view','create','update','admin','delete','add','clear','list'),
                'roles'=>array(User::ROLE_ADMIN),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
                'deniedCallback' => function () {
                    Yii::app()->controller->redirect(array ('/site/index'));
                }
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model'=>$this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new City;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['City'])) {
            $model->attributes=$_POST['City'];
            if ($model->save()) {
                $this->renderJSON(['success']);
            } else {
                $this->renderJSON(['error']);
            }
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['City'])) {
            $model->attributes=$_POST['City'];
            if ($model->save()) {
                $this->redirect(array('view','id'=>$model->id));
            }
        }

        $this->render('update', array(
            'model'=>$model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
    }

    /**
     * Lists all models.
     * */
    // public function actionIndex()
    // {
    //     $dataProvider=new CActiveDataProvider('City');
    //     $this->render('index', array(
    //         'dataProvider'=>$dataProvider,
    //     ));
    // }
    public function actionClear()
    {
        $sql = Yii::app()->db->createCommand()->truncateTable('tbl_city');
        $this->redirect(array('index'));
    }

    public function actionList()
    {
        $model=new City;

        $dataProvider = $model->search();
        $dataProvider->setPagination(false);
        $data = $dataProvider->getData();

        $cityList = [];
        foreach ($data as $item) {
            $cityList[]=$item->attributes;
        }

        $this->renderJSON($cityList);
    }

    public function actionIndex()
    {
        // $jsonKey = file_get_contents(Yii::app()->params['g_api_key']);
        // $keyObj = json_decode($jsonKey);
        // $key = $keyObj->key;

        // $city = $this->container->get('Modules\GoogleApi\GooglePlacesApi')
        //                 ->setApiKey($key)
        //                 ->getPlaceDetailsById("ChIJ3X3hWOnMl0YRmnXkblVM1n0");
        // $this->debug($city);

        $dataProvider=new CActiveDataProvider('City');
        $this->render('index', array(
            'dataProvider'=>$dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdd()
    {
        $model=new City('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['City'])) {
            $model->attributes=$_GET['City'];
        }

        $this->render('admin', array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return City the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=City::model()->findByPk($id);
        if ($model===null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param City $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax']==='city-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
