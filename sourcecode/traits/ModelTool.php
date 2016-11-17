<?php

namespace fredyns\components\traits;

use Yii;
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
     *
     * @param Model $source
     * @param Array $attributes
     */
    public function copy($source, $attributes)
    {
        foreach ($attributes as $key => $param)
        {
            $defaultValue = null;
            $option       = [];
            $pattern_1    = (is_integer($key) && is_string($param));
            $pattern_2    = (is_string($key) && is_string($param));
            $pattern_3    = (is_array($param));
            $pattern_4    = FALSE;

            if ($pattern_1)
            {
                $sourceAttribute = $param;
                $targetAttribute = $param;
            }
            elseif ($pattern_2)
            {
                $sourceAttribute = $key;
                $targetAttribute = $param;
            }
            elseif ($pattern_3)
            {
                if (isset($param[0]) && isset($param[1]))
                {
                    $targetAttribute = $param[0];
                    $sourceAttribute = is_integer($key) ? $param[0] : $key;

                    if (is_scalar($param[1]))
                    {
                        $defaultValue = $param[1];
                    }
                    elseif (is_array($param[1]))
                    {
                        $defaultValue = ArrayHelper::getValue($param[1], 0);
                        $option       = $param[1];
                        $pattern_4    = TRUE;
                    }
                }
                else
                {
                    $pattern_3 = FALSE;
                }
            }

            if ($pattern_1 OR $pattern_2 OR $pattern_3)
            {
                $value = ArrayHelper::getValue($source, $sourceAttribute, $defaultValue);

                if ($pattern_4)
                {
                    $value = ArrayHelper::getValue($option, $value, $defaultValue);
                }

                $this->setAttribute($targetAttribute, $value);
            }
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