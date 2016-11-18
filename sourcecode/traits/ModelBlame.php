<?php

namespace fredyns\suites\traits;

/**
 * Add blaming for model
 *
 * @property fredyns\suites\models\User $createdByUser
 * @property fredyns\suites\models\User $updatedByUser
 * @property fredyns\suites\models\User $deletedByUser
 * @property fredyns\suites\models\Profile $createdByProfile
 * @property fredyns\suites\models\Profile $updatedByProfile
 * @property fredyns\suites\models\Profile $deletedByProfile
 *
 * @author fredy
 */
trait ModelBlame
{

    public function yiiModelUser()
    {
        $alternatives = [
            'app'      => 'app\models\User',
            'frontend' => 'frontend\models\User',
            'backend'  => 'backend\models\User',
            'common'   => 'common\models\User',
        ];

        foreach ($alternatives as $value)
        {
            if (class_exists($value))
            {
                return $value;
            }
        }

        return null;
    }

    public function modelUser()
    {
        $dektriumUserClass = 'dektrium\user\models\User';
        $yiiUserClass      = $this->yiiModelUser();

        if (class_exists($dektriumUserClass))
        {
            if ($yiiUserClass && is_subclass_of($yiiUserClass, $dektriumUserClass))
            {
                return $yiiUserClass;
            }

            return $dektriumUserClass;
        }

        return $yiiUserClass;
    }

    public function yiiModelProfile()
    {
        $alternatives = [
            'app'      => 'app\models\Profile',
            'frontend' => 'frontend\models\Profile',
            'backend'  => 'backend\models\Profile',
            'common'   => 'common\models\Profile',
        ];

        foreach ($alternatives as $value)
        {
            if (class_exists($value))
            {
                return $value;
            }
        }

        return null;
    }

    public function modelProfile()
    {
        $dektriumProfileClass = 'dektrium\user\models\Profile';
        $yiiProfileClass      = $this->yiiModelProfile();

        if (class_exists($dektriumProfileClass))
        {
            if ($yiiProfileClass && is_subclass_of($yiiProfileClass, $dektriumProfileClass))
            {
                return $yiiProfileClass;
            }

            return $dektriumProfileClass;
        }

        return $yiiProfileClass;
    }
    /* ======================== global blaming ======================== */

    /**
     * Getting blamable user model based on particular attribute
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBlamedUser($attribute)
    {
        $modelName = $this->modelUser();

        if ($this->hasAttribute($attribute) && $modelName)
        {
            return $this->hasOne($modelName, ['id' => $attribute]);
        }

        return NULL;
    }

    /**
     * Getting blamable profile model based on particular attribute
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBlamedProfile($attribute)
    {
        $modelName = $this->modelProfile();

        if ($this->hasAttribute($attribute) && $modelName)
        {
            return $this->hasOne($modelName, ['user_id' => $attribute]);
        }

        return NULL;
    }
    /* ======================== model blaming ======================== */

    /**
     * Getting blamable Profile model based for creating model
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->getBlamedProfile('created_by');
    }

    /**
     * Getting blamable Profile model based for updating model
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->getBlamedProfile('updated_by');
    }

    /**
     * Getting blamable Profile model based for deleting model
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->getBlamedProfile('deleted_by');
    }

    /**
     * Getting blamable Profile model based for creating model
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedByProfile()
    {
        return $this->getBlamedProfile('created_by');
    }

    /**
     * Getting blamable Profile model based for updating model
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedByProfile()
    {
        return $this->getBlamedProfile('updated_by');
    }

    /**
     * Getting blamable Profile model based for deleting model
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedByProfile()
    {
        return $this->getBlamedProfile('deleted_by');
    }

    /**
     * Getting blamable User model based for creating model
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedByUser()
    {
        return $this->getBlamedUser('created_by');
    }

    /**
     * Getting blamable User model based for updating model
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedByUser()
    {
        return $this->getBlamedUser('updated_by');
    }

    /**
     * Getting blamable User model based for deleting model
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedByUser()
    {
        return $this->getBlamedUser('deleted_by');
    }

}