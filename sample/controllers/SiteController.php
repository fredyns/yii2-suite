<?php

namespace app\controllers;

use yii\web\Controller;
use fredyns\suite\filters\AdminLTELayout;

class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'layout' => [
                'class' => AdminLTELayout::className(),
            ],
        ];
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        return $this->redirect(['/user/security/login']);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        return $this->redirect(['/user/security/logout']);
    }

}