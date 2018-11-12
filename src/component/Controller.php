<?php

namespace bizley\podium\component;

use bizley\podium\controllers\BaseController;
use Yii;
use app\components\CheckAccessClient;


class Controller extends BaseController
{
    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest) {
            if ($action->id == 'forum' || $action->id == 'inbox') {
                Yii::$app->session->setFlash('loginMessage', 'Please Login or Signup to Access These Features');
            }
        }
        if (Yii::$app->session->get('lang') != null) {
            Yii::$app->language = Yii::$app->session->get('lang');
        }

        if (Yii::$app->user->isGuest) {
            $accessSite = new CheckAccessClient($_SERVER['HTTP_HOST']);
            $accessSite->checkAccess();
        }
		if(!Yii::$app->site->useCommunity){
            Yii::$app->getResponse()->redirect('/');
            return false;
        }
        return parent::beforeAction($action);
    }


}