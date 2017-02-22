<?php

use yii\helpers\StringHelper;

/*
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

/** @var \yii\db\ActiveRecord $model */
## TODO: move to generator (?); cleanup
$model = new $generator->modelClass();
$model->setScenario('crud');
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $model->setScenario('default');
    $safeAttributes = $model->safeAttributes();
}
if (empty($safeAttributes)) {
    $safeAttributes = $model->getTableSchema()->columnNames;
}

echo "<?php\n";
?>

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use cornernote\returnurl\ReturnUrl;
use dmstr\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form ActiveForm */
?>

<div class="<?= \yii\helpers\Inflector::camel2id(
    StringHelper::basename($generator->modelClass),
    '-',
    true
) ?>-form">

    <?= "<?php\n" ?>
    $form = ActiveForm::begin([
        'id' => '<?= $model->formName() ?>',
        'layout' => '<?= $generator->formLayout ?>',
        'enableClientValidation' => true,
        'errorSummaryCssClass' => 'error-summary alert alert-error'
    ]);
    
    echo Html::hiddenInput('ru', ReturnUrl::getRequestToken());
    ?>

    <div class="">
        <?php echo "<?php \$this->beginBlock('main'); ?>\n"; ?>

        <p><?php

    $hidenAttributes = [
        'id',
        'recordStatus',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

        $safeAttributes = array_diff($safeAttributes, $hidenAttributes);

            foreach ($safeAttributes as $attribute) {
                echo "\n\n            <!-- attribute {$attribute} -->";
                $prepend = $generator->prependActiveField($attribute, $model);
                $field = $generator->activeField($attribute, $model);
                $append = $generator->appendActiveField($attribute, $model);

                if ($prepend) {
                    echo "\n            ".$prepend;
                }

                if ($field) {
                    echo "\n            <?=\n            "
                    .str_replace("\n", "\n            ", $field)
                    ."\n            ?>";
                }

                if ($append) {
                    echo "\n            ".$append;
                }
            }
            ?>

        </p>

        <?php echo '<?php $this->endBlock(); ?>'; ?>

        <?php
        $label = substr(strrchr($model::className(), '\\'), 1);

        $items = <<<EOS
                [
                    'label'   => Yii::t('$generator->modelMessageCategory', '$label'),
                    'content' => \$this->blocks['main'],
                    'active'  => true,
                ],
EOS;
        ?>

        <?=
        "<?=
        Tabs::widget([
            'encodeLabels' => false,
            'items' => [\n$items
            ],
        ]);
        ?>\n";
        ?>

        <hr/>

        <?= '<?php ' ?>echo $form->errorSummary($model); ?>

        <?= "<?=\n" ?>
        Html::submitButton(
            '<span class="glyphicon glyphicon-check"></span> ' .
            ($model->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Save') ?>),
            [
            'id' => 'save-' . $model->formName(),
            'class' => 'btn btn-success'
            ]
        );
        ?>

    </div>

    <?= '<?php ' ?>ActiveForm::end(); ?>

</div>

