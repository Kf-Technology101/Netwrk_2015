<?php 

namespace frontend\modules\netwrk\controllers;
use frontend\components\BaseController;

class MeetController extends BaseController
{
    public function actionIndex() 
    {
        return $this->render('index');
    }
}

?>