<?php

namespace fredyns\suite\traits;

use DateTime;
use DateTimeZone;
use Yii;

/**
 * additional function for model with timezone field
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 *
 * @property string $timezone model timezone field
 * @property DateTimeZone $dateTimeZone model timezone object
 * @property DateTime $dateTime current datetime as object
 */
trait ModelTimezone
{

    /**
     * get date time zone instance based on timezone field
     *
     * @return DateTimeZone
     */
    public function getDateTimeZone()
    {
        try {
            return new DateTimeZone($this->timezone);
        } catch (\Exception $ex) {
            return new DateTimeZone(Yii::$app->timeZone);
        }
    }

    /**
     * get current datetime as object based on timezone field
     *
     * @return Datetime
     */
    public function getDatetime($time = 'now')
    {
        return new Datetime($time, $this->dateTimeZone);
    }
}