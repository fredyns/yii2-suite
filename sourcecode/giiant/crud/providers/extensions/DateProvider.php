<?php

namespace fredyns\suite\giiant\crud\providers\extensions;

class DateProvider extends \schmunk42\giiant\base\Provider
{

    public function activeField($attribute)
    {
        if (isset($this->generator->getTableSchema()->columns[$attribute])) {
            $column = $this->generator->getTableSchema()->columns[$attribute];
        } else {
            return;
        }

        $method = __METHOD__;

        switch ($column->type) {
            case 'date':
                return <<<EOS
// generated by {$method}
\$form
    ->field(\$model, '{$column->name}')
    // TODO: must configured properly together with model
    //->widget(\yii\jui\DatePicker::classname(),
    //    [
    //        'dateFormat' => 'yyyy-MM-dd',
    //    ])
EOS;
                break;
            default:
                return;
        }
    }

    /**
     * Formatter for detail view attributes, who have get[..]ValueLabel function.
     *
     * @param $attribute ColumnSchema
     * @param $model ActiveRecord
     *
     * @return null|string
     */
    public function columnFormat($attribute, $model)
    {
        if (isset($this->generator->getTableSchema()->columns[$attribute])) {
            $column = $this->generator->getTableSchema()->columns[$attribute];
        } else {
            return;
        }

        if ($column->type != 'date') {
            return;
        }

        $method = __METHOD__;

        return <<<EOS
// generated by {$method}
[
    'attribute' => '{$attribute}',
    // TODO: must configured properly together with search model
    //'value' => function (\$model)
    //{
    //    return date('Y-m-d', \$model->{$attribute});
    //},
    //'filter' => \kartik\daterange\DateRangePicker::widget([
    //    'model' => \$searchModel,
    //    'attribute' => '{$attribute}_range',
    //    'pluginOptions' => [
    //        'format' => 'm/d/Y',
    //        'autoUpdateInput' => false,
    //    ],
    //]),
]        
EOS;
    }
}