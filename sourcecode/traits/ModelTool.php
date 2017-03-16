<?php

namespace fredyns\suite\traits;

use yii\helpers\ArrayHelper;

/**
 * adding model functionality
 *
 * @author fredy
 */
trait ModelTool
{

    /**
     * copy values from other model
     * attribute list patterns:
     * ```php
     * $attributes = [
     *      // pattern 1. source & target field have the same name.
     *      'fieldName',
     *      // pattern 2. define source & target field name
     *      'targetField' => 'sourceField',
     * ];
     * ```
     *
     * @param Array|Object $source array, model or object as data source to copy
     * @param Array $attributes list of field to copy
     */
    public function copy($source, $attributes)
    {
        foreach ($attributes as $targetAttribute => $sourceAttribute) {
            if (is_integer($targetAttribute)) {
                $targetAttribute = $sourceAttribute;
            }

            $value = ArrayHelper::getValue($source, $sourceAttribute);

            $this->$targetAttribute = $value;
        }
    }

    /**
     * return an attribute from hasOne-relationship
     *
     * @param String $attribute
     * @param String $default
     * @return String
     */
    public function subAttribute($attribute = NULL, $default = NULL)
    {
        return ArrayHelper::getValue($this, $attribute, $default);
    }
}