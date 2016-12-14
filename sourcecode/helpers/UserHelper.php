<?php

namespace fredyns\suite\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use fredyns\suite\models\User;
use fredyns\suite\models\Profile;

/**
 * common function about user
 * used together with dektrium/user
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class UserHelper
{

    /**
     * get user model class name
     *
     * @return string
     */
    public static function userClass()
    {
        return ArrayHelper::getValue(Yii::$app->modules, 'user.modelMap.User', User::className());
    }

    /**
     * get profile model class name
     *
     * @return string
     */
    public static function profileClass()
    {
        return ArrayHelper::getValue(Yii::$app->modules, 'user.modelMap.Profile', Profile::className());
    }

    /**
     * get user model
     *
     * @param integer $user_id
     * @return \fredyns\suite\models\User
     */
    public static function user($user_id)
    {
        $classname = static::userClass();

        return $classname::findOne($user_id);
    }

    /**
     * get profile model
     *
     * @param integer $user_id
     * @return \fredyns\suite\models\Profile
     */
    public static function profile($user_id)
    {
        $classname = static::profileClass();

        return $classname::findOne($user_id);
    }

    /**
     * get spesified attribute from user account model
     *
     * @param integer $user_id
     * @param string $attribute
     * @param mixed $default
     * @return mixed
     */
    public static function userAttr($user_id, $attribute, $default = null)
    {
        if (($model = static::user($user_id)) !== null)
        {
            return ArrayHelper::getValue($model, $attribute, $default);
        }

        return null;
    }

    /**
     * get spesified attribute from user profile model
     *
     * @param integer $user_id
     * @param string $attribute
     * @param mixed $default
     * @return mixed
     */
    public static function profileAttr($user_id, $attribute, $default = null)
    {
        if (($model = static::profile($user_id)) !== null)
        {
            return ArrayHelper::getValue($model, $attribute, $default);
        }

        return null;
    }

}