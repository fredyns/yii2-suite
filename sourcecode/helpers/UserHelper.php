<?php

namespace fredyns\suites\helpers;

use Yii;
use dektrium\user\models\User as DektriumUser;
use fredyns\suites\models\User;
use fredyns\suites\models\Profile;

/**
 * common function about user
 * used together with dektrium/user
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class UserHelper
{

    /**
     * check whether current user is admin
     *
     * @return boolean
     */
    public static function isAdmin()
    {
        $user = Yii::$app->user;

        if ($user->isGuest == FALSE)
        {
            if ($user->identity instanceof DektriumUser)
            {
                return $user->identity->isAdmin;
            }
        }

        return FALSE;
    }

    /**
     * get spesified attribute from user model
     *
     * @param string $classname
     * @param integer $user_id
     * @param string $attribute
     * @param mixed $default
     * @return mixed
     */
    public static function modelAttribute($classname, $user_id, $attribute = null, $default = null)
    {
        if (($model = $classname::findOne($user_id)) !== null)
        {
            if (empty($attribute))
            {
                return $model;
            }

            if ($model->hasAttribute($attribute))
            {
                $value = $model->getAttribute($attribute);

                if (empty($value) == FALSE)
                {
                    return $value;
                }
            }

            return $default;
        }

        return null;
    }

    /**
     * get spesified attribute from user account model
     *
     * @param integer $user_id
     * @param string $attribute
     * @param mixed $default
     * @return mixed
     */
    public static function account($user_id, $attribute = null, $default = null)
    {
        return static::modelAttribute(User::className(), $user_id, $attribute, $default);
    }

    /**
     * get spesified attribute from user profile model
     *
     * @param integer $user_id
     * @param string $attribute
     * @param mixed $default
     * @return mixed
     */
    public static function profile($user_id, $attribute = null, $default = null)
    {
        return static::modelAttribute(Profile::className(), $user_id, $attribute, $default);
    }

}