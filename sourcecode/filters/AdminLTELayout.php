<?php

namespace fredyns\suites\filters;

use Yii;

/**
 * change layout using AdminLTE when user is logged in
 *
 * @author fredy
 */
class AdminLTELayout extends \yii\base\ActionFilter
{

    public function beforeAction($action)
    {
        $main     = '@app/views/layouts/main';
        $adminlte = '@app/views/layouts/adminlte';

        Yii::$app->controller->layout = (Yii::$app->user->isGuest) ? $main : $adminlte;

        return parent::beforeAction($action);
    }

}