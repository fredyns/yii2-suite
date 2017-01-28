<?php

namespace fredyns\suite\behaviors;

use Yii;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 * Handling related attribute and insert as new model
 * sample usage:
 *
 * ```php
 * use fredyns\suite\behaviors\BelongingModelBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => BelongingModelBehavior::className(),
 *             'modelClass' => Model::className(),
 *             'relatedAttribute' => 'model_id',
 *             'valueAttribute' => 'name',
 *         ],
 *     ];
 * }
 * ```
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class BelongingModelBehavior extends AttributeBehavior
{
    /**
     * @var string model class name which related
     */
    public $modelClass;

    /**
     * function to call before saving
     *
     * @var callable
     */
    public $modelHook;

    /**
     * @var string related attribute which belong to another model
     */
    public $relatedAttribute;

    /**
     * @var string attribute to store value
     */
    public $valueAttribute = 'name';

    /**
     * @var string[] other attributes to complements model
     */
    public $otherAttributes = [];
    public $value;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->modelClass)) {
            throw new InvalidConfigException("Model class behavior must be set.");
        }

        if (empty($this->relatedAttribute)) {
            throw new InvalidConfigException("Related attribute behavior must be set.");
        }

        if (empty($this->valueAttribute)) {
            throw new InvalidConfigException("Value attribute behavior must be set.");
        }

        if (is_array($this->otherAttributes) == false) {
            throw new InvalidConfigException("Related attributes must be an array.");
        }

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => $this->relatedAttribute,
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->relatedAttribute,
            ];
        }
    }

    /**
     * Evaluates the value of the user.
     * The return result of this method will be assigned to the current attribute(s).
     * @param Event $event
     * @return mixed the value of the user.
     */
    protected function getValue($event)
    {
        $value = ArrayHelper::getValue($this->owner, $this->relatedAttribute);

        if (is_numeric($value)) {
            return $value;
        } elseif (empty($value)) {
            return NULL;
        } else {
            $model = Yii::createObject([
                    'class' => $this->modelClass,
                    $this->valueAttribute => $value,
            ]);

            foreach ($this->otherAttributes as $modelAttribute => $sourceAttribute) {
                $model->$modelAttribute = ArrayHelper::getValue($this->owner, $this->$sourceAttribute);
            }

            if ($this->modelHook instanceof \Closure) {
                call_user_func($this->modelHook, $model);
            }

            return $model->save(FALSE) ? $model->id : null;
        }
    }
}