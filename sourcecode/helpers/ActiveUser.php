<?php

namespace fredyns\suite\helpers;

use Datetime;
use DateTimeZone;
use Yii;
use dektrium\user\models\User as DektriumUser;

/**
 * common function about currently active user
 * used together with dektrium/user
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class ActiveUser extends UserHelper
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
     * get user model
     *
     * @return \fredyns\suite\models\User
     */
    public static function user()
    {
        if (Yii::$app->user->isGuest)
        {
            return null;
        }

        return static::user(Yii::$app->user->id);
    }

    /**
     * get profile model
     *
     * @return \fredyns\suite\models\Profile
     */
    public static function profile()
    {
        if (Yii::$app->user->isGuest)
        {
            return null;
        }

        return static::profile(Yii::$app->user->id);
    }

    /**
     * @inheritdoc
     */
    public static function userAttr($attribute, $default = null)
    {
        if (Yii::$app->user->isGuest)
        {
            return $default;
        }

        return parent::userAttr(Yii::$app->user->id, $attribute, $default);
    }

    /**
     * @inheritdoc
     */
    public static function profileAttr($attribute, $default = null)
    {
        if (Yii::$app->user->isGuest)
        {
            return $default;
        }

        return parent::profileAttr(Yii::$app->user->id, $attribute, $default);
    }

    /**
     * get user timeZone
     * if not set use application default
     *
     * @return string
     */
    public static function timeZone()
    {
        $timeZone = static::userAttr('timezone');

        return ($timeZone) ? $timeZone : Yii::$app->timeZone;
    }

    /**
     * get date time zone instance based on user timezone
     *
     * @return DateTimeZone
     */
    public static function dateTimeZone()
    {
        return new DateTimeZone(static::timeZone());
    }

    /**
     * get datetime object based on user timezone
     *
     * @return Datetime
     */
    public static function datetime($time = 'now')
    {
        return new Datetime($time, static::dateTimeZone());
    }

}