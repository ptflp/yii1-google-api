<?php

class SiteController extends Controller
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'postOnly + flush', // we only allow deletion via POST request
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $params = [];
        if (!Yii::app()->user->isGuest) {
            $cityList = City::model()->findAll(array('order'=>'id'));
            $params = [
                'cityList' => $cityList
            ];
        }
        $this->render('index', $params);
    }

    public function actionRedis()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);

        if (!Yii::app()->user->isGuest && isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'flushall':
                    $url = 'http://webhook:9000/hooks/redis-flush';
                    break;
                case 'stop':
                    $url = 'http://webhook:9000/hooks/redis-stop';
                    break;
                case 'start':
                    $url = 'http://webhook:9000/hooks/redis-start';
                    break;
                case 'webhook-restart':
                    $url = 'http://webhook:9000/hooks/webhook-restart';
                    break;
                default:
                    $url = 'http://webhook:9000/hooks/';
                    break;
            }
            try{
                $client = $this->container
                                ->get('Modules\GoogleApi\ClientAdaptor')
                                ->setUrl($url)
                                ->fetch();

                $this->renderJSON(["success, Redis ".$_POST['action']]);
            } catch (\Exception $e) {
                $this->renderJSON(['error']);
            }
        } else {
            $this->renderJSON($_POST);
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error=Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
}
