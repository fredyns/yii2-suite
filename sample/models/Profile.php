<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use fredyns\suite\models\Profile as BaseProfile;

/**
 * This is the model class for table "profile".
 *
 * @property integer $picture
 * @property integer $picture_id
 */
class Profile extends BaseProfile
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
                parent::behaviors(), [
                # custom behaviors
                ]
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(
                parent::rules(), [
                # custom validation rules
                ]
        );
    }

}