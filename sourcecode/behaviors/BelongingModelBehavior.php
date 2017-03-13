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
     * @var string attribute which referenced to another model
     */
    public $relatedAttribute;

    /**
     * @var string attribute to store value
     */
    public $valueAttribute = 'name';

    /**
     * @var string model class name which related
     */
    public $modelClass;

    /**
     * @var array related model attributes
     */
    public $modelAttributes = [];

    /**
     * @var string[] attibut list to copy from current model (form) to referenced model
     */
    public $copyAttributes = [];

    /**
     * @var string[] same with $copyAttributes. just to maintain backward compatibility
     */
    public $otherAttributes = [];

    /**
     * @var callable function to call before saving
     */
    public $modelHook;

    /**
     * @var string attribute value
     */
    public $value;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->relatedAttribute)) {
            throw new InvalidConfigException("Related attribute behavior must be set.");
        }

        if (empty($this->valueAttribute)) {
            throw new InvalidConfigException("Value attribute behavior must be set.");
        }

        if (empty($this->modelClass)) {
            throw new InvalidConfigException("Referenced Model class must be set.");
        }

        if (is_array($this->modelAttributes) == false) {
            throw new InvalidConfigException("Model attributes must be an array.");
        }

        if (is_array($this->copyAttributes) == false) {
            throw new InvalidConfigException("Attributes list to copy must be an array.");
        }

        if (is_array($this->otherAttributes) == false) {
            throw new InvalidConfigException("Other attributes list to copy must be an array.");
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
            $options = $this->modelAttributes;
            $options['class'] = $this->modelClass;
            $options[$this->valueAttribute] = $value;

            foreach ($this->copyAttributes as $modelAttribute => $sourceAttribute) {
                $options[$modelAttribute] = ArrayHelper::getValue($this->owner, $sourceAttribute);
            }

            foreach ($this->otherAttributes as $modelAttribute => $sourceAttribute) {
                $options[$modelAttribute] = ArrayHelper::getValue($this->owner, $sourceAttribute);
            }

            $model = Yii::createObject($options);

            if ($this->modelHook instanceof \Closure) {
                call_user_func($this->modelHook, $model);
            }

            return $model->save(FALSE) ? $model->id : null;
        }
    }
}