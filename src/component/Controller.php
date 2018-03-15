<?php

namespace bizley\podium\component;

use bizley\podium\controllers\BaseController;
use Yii;
use app\components\CheckAccessClient;


class Controller extends BaseController
{
    public function beforeAction($action)
    {

        if (Yii::$app->session->get('lang') != null) {
            Yii::$app->language = Yii::$app->session->get('lang');
        }

        if(Yii::$app->user->isGuest){
            $accessSite = new CheckAccessClient($_SERVER['HTTP_HOST']);
            $accessSite->checkAccess();
        }
        return parent::beforeAction($action);
    }


}