<?php

namespace fredyns\suites\models;

use Yii;
use yii\helpers\ArrayHelper;
use dektrium\user\models\User as BaseUser;
use fredyns\suites\traits\ModelTool;
use fredyns\suites\traits\ModelBlame;

/**
 * This is the model class for table "user".
 */
class User extends BaseUser
{

    use ModelTool,
        ModelBlame;

    public function behaviors()
    {
        return ArrayHelper::merge(
                parent::behaviors(), [
                # custom behaviors
                ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
                parent::rules(), [
                # custom validation rules
                ]
        );
    }

}