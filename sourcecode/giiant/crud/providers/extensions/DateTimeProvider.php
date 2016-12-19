<?php

namespace fredyns\suite\giiant\crud\providers\extensions;

class DateTimeProvider extends \schmunk42\giiant\base\Provider
{

    public function activeField($attribute)
    {
        switch (true) {
            case in_array($attribute, $this->columnNames):

                return <<<EOS
\$form
    ->field(\$model, '{$attribute}')
    ->widget(\kartik\datetime\DateTimePicker::className(), [
        'type'          => \kartik\datetime\DateTimePicker::TYPE_INLINE,
        'pluginOptions' => [
            'initialDate' => \$model->{$attribute},
            'format'      => 'yyyy-mm-dd hh:ii',
            'endDate'     => date('Y-m-d H:i'),
            'startView'   => 0,
        ]
    ])
EOS;
                break;
            default:
                return null;
        }
    }
}