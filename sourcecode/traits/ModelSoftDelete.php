<?php

namespace fredyns\components\traits;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * adding soft delete functionality
 *
 * @property boolean $isSoftDeleteEnabled
 *
 * @author fredy
 */
trait ModelSoftDelete
{

    public function recordStatus_active()
    {
        return 'active';
    }

    public function recordStatus_deleted()
    {
        return 'deleted';
    }

    /**
     * check soft-delete functionality
     *
     * @return boolean
     */
    public function getIsSoftDeleteEnabled()
    {
        return $this->hasAttribute('deleted_at');
    }

    /**
     * @inheritdoc
     */
    public function delete()
    {
        if ($this->isSoftDeleteEnabled)
        {
            return $this->softDelete();
        }

        return $this->hardDelete();
    }

    /**
     * prefering soft-delete instead of deleting permanently
     * adding delete time & blamable user
     *
     * @return boolean
     */
    public function softDelete()
    {
        $this->setAttribute('recordStatus', $this->recordStatus_deleted());
        $this->setAttribute('deleted_at', time());
        $this->setAttribute('deleted_by', Yii::$app->user->getId());
        $this->detachBehavior('timestamp');

        return parent::update(FALSE);
    }

    /**
     * restore model after soft delete
     *
     * @return boolean
     */
    public function restore()
    {
        if ($this->isSoftDeleteEnabled == FALSE)
        {
            return FALSE;
        }

        $this->setAttribute('recordStatus', $this->recordStatus_active());
        $this->setAttribute('deleted_at', NULL);
        $this->setAttribute('deleted_by', NULL);

        return $this->update(FALSE);
    }

    /**
     * permanently delete model
     *
     * @return boolean
     */
    public function hardDelete()
    {
        return parent::delete();
    }

}