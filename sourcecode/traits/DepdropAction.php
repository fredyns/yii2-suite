<?php

namespace fredyns\suite\traits;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * generate option data for depdrop widget
 * bound to controllers
 *
 * @author fredy
 */
trait DepdropAction
{

    /**
     * Generate Json data for depdrop option
     *
     * @param array $params
     * @return mixed
     */
    public static function getDepdropOptions($params = [])
    {
        $modelClass = $params['modelClass'];
        $parents    = static::getDepdropParents($params['parents']);
        $selected   = ArrayHelper::getValue($params, 'selected', 0);
        $idField    = ArrayHelper::getValue($params, 'idField', 'id');
        $nameField  = ArrayHelper::getValue($params, 'nameField', 'name');
        $filter     = isset($params['filter']) ? $params['filter'] : NULL;
        $output     = [];

        if (empty($parents) == FALSE)
        {
            $query = $modelClass::find();
            $query->where($parents);

            if ($filter instanceof \Closure)
            {
                $filter($query);
            }
            else if (is_array($filter))
            {
                $condition = [];

                foreach ($filter as $key => $value)
                {
                    if (is_string($key) && is_scalar($value))
                    {
                        $condition[$key] = $value;
                    }
                    else if (is_array($value))
                    {
                        $query->andFilterWhere($value);
                    }
                }

                if ($condition)
                {
                    $query->andFilterWhere($condition);
                }
            }

            $data   = $query->all();
            $output = ArrayHelper::toArray($data, ['id' => $idField, 'name' => $nameField]);
        }

        return Json::encode(['output' => $output, 'selected' => $selected]);
    }

    /**
     * Parse parents data condition before generate options
     *
     * @param array $parents
     * @return array
     */
    public static function getDepdropParents($parents = [])
    {
        $conditions = [];

        if (empty($parents) == FALSE && isset($_POST['depdrop_parents']))
        {
            $parentIndex = 0;

            foreach ($parents as $field => $filter)
            {
                $value = trim(ArrayHelper::getValue($_POST['depdrop_parents'], $parentIndex, ""));

                if (is_integer($field) && is_string($filter))
                {
                    $field = $filter;
                }
                else if (is_string($field) && $filter instanceof \Closure)
                {
                    $value = $filter($value);
                }

                if ($value !== "")
                {
                    $conditions[$field] = $value;
                }

                $parentIndex++;
            }
        }

        return $conditions;
    }

}