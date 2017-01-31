<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/*
 * @var yii\web\View $this
 * @var schmunk42\giiant\generators\crud\Generator $generator
 */

/** @var \yii\db\ActiveRecord $model */
/** @var $generator \schmunk42\giiant\generators\crud\Generator */

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

$modelName = Inflector::camel2words(StringHelper::basename($model::className()));
$className = $model::className();
$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use cornernote\returnurl\ReturnUrl;
use dmstr\bootstrap\Tabs;
use fredyns\suite\helpers\ActiveUser;
use fredyns\suite\widgets\DetailView;
use kartik\grid\GridView;

/**
 * @var yii\web\View $this
 * @var <?= ltrim($generator->modelClass, '\\') ?> $model
 */
$copyParams = $model->attributes;

$this->title = $actionControl->breadcrumbLabel('index')." "
    .$actionControl->breadcrumbLabel('view');

$this->params['breadcrumbs'][] = $actionControl->breadcrumbItem('index');
$this->params['breadcrumbs'][] = $actionControl->breadcrumbLabel('view');
?>
<div class="giiant-crud <?= Inflector::camel2id(StringHelper::basename($generator->modelClass), '-', true) ?>-view">

    <h1>
        <?= "<?= Yii::t('{$generator->modelMessageCategory}', '{$modelName}') ?>\n" ?>
        <small>
            <?= '<?= $model->'.$generator->getModelNameAttribute($generator->modelClass)." ?>\n" ?>
<?php if (in_array('fredyns\suite\traits\ModelSoftDelete', class_uses($generator->modelClass))): ?>
            <?= "<?php" ?> if ($model->recordStatus == 'deleted'): ?>
                <span class="badge">deleted</span>
            <?= "<?php" ?> endif; ?>            
<?php endif; ?>
        </small>
    </h1>

    <div class="clearfix crud-navigation">

        <!-- menu buttons -->
        <div class='pull-left'>
            <?= '<?= ' ?>$actionControl->buttons(['index', 'create']); ?>
        </div>

        <div class="pull-right">
            <?= '<?= ' ?>$actionControl->buttons(['update', 'delete', 'restore']); ?>
        </div>

    </div>

    <hr />

    <?= "<?php \$this->beginBlock('{$generator->modelClass}'); ?>"; ?>
    <?= $generator->partialView('detail_prepend', $model); ?>

    <?= "<?= \n" ?>
    DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
            $systemAttributes = [
                'recordStatus',
                'created_at',
                'created_by',
                'updated_at',
                'updated_by',
                'deleted_at',
                'deleted_by',
            ];
            $infoAttributes = array_intersect((new $generator->modelClass)->attributes(), $systemAttributes);
            $systemAttributes[] = 'id';
            $safeAttributes = array_diff($safeAttributes, $systemAttributes);

            foreach ($safeAttributes as $attribute) {
                $format = $generator->attributeFormat($attribute);
                $format = str_replace("\n", "\n            ", $format);

                if (!$format) {
                    continue;
                } else {
                    echo "            ".trim($format).",\n";
                }
            }
            ?>
        ],
    ]);
    ?>
    <?= $generator->partialView('detail_append', $model); ?>

    <?= "<?php \$this->endBlock(); ?>\n"; ?>
<?php

    // get relation info $ prepare add button
    $model = new $generator->modelClass();

    $items = <<<EOS
            [
                'label' => '<b class=""># '.\$model->{$model->primaryKey()[0]}.'</b>',
                'content' => \$this->blocks['{$generator->modelClass}'],
                'active' => true,
            ],\n
EOS;

    // formulate action controls
    $actControlNamespace = StringHelper::dirname(ltrim($generator->controllerClass, '\\'));
    $actControlNamespace = str_replace('controllers', 'actioncontrols', $actControlNamespace);

    foreach ($generator->getModelRelations($generator->modelClass, ['has_many']) as $name => $relation) {
        echo "\n    <?php \$this->beginBlock('$name'); ?>\n\n";

        $showAllRecords = false;

        // render pivot grid
        if ($relation->via !== null) {
            $pjaxId = "pjax-{$pivotName}";
            $gridRelation = $pivotRelation;
            $gridName = $pivotName;
        } else {
            $pjaxId = "pjax-{$name}";
            $gridRelation = $relation;
            $gridName = $name;
        }

        $output = $generator->relationGrid($gridName, $gridRelation, $showAllRecords);

        // render relation grid
        if (!empty($output)):
            $output = str_replace("\n", "\n    ", $output);

            echo <<<EOS
    <?php 
    Pjax::begin([
        'id' => 'pjax-{$name}', 
        'enableReplaceState' => false, 
        'linkSelector' => '#pjax-{$name} ul.pagination a, th a', 
        'clientOptions' => [
            'pjax:success' => 'function(){alert(\"yo\")}',
        ],
    ]);

    {$output}

    Pjax::end();
    ?>\n\n
EOS;
        endif;

        echo "    <?php \$this->endBlock(); ?>\n";

        // build tab items
        $label = Inflector::camel2words($name);
        $items .= <<<EOS
            [
                'content' => \$this->blocks['$name'],
                'label' => '<small>$label <span class="badge badge-default">'
                    .\$model->get{$name}()->count()
                    .'</span></small>',
                'active' => false,
            ],\n
EOS;
    }

    if (empty($infoAttributes) == false)
    {
        echo "\n    <?php \$this->beginBlock('info'); ?>\n";
        echo <<<EOS
    <?=
    DetailView::widget([
        'model' => \$model,
        'profileActControl' => 'app\actioncontrols\ProfileActControl',
        'attributes' => [\n
EOS;

        if (in_array('recordStatus', $infoAttributes))
        {
            echo <<<EOS
            [
                'attribute' => 'recordStatus',
                'format' => 'html',
                'value' => '<span class="badge">'.\$model->recordStatus.'</span>',
            ],\n
EOS;
        }

        if (in_array('created_at', $infoAttributes))
        {
            echo <<<EOS
            [
                'label' => 'Created',
                'blamed' => 'createdBy',
                'timestamp' => 'created_at',
            ],\n
EOS;
        }

        if (in_array('updated_at', $infoAttributes))
        {
            echo <<<EOS
            [
                'label' => 'Updated',
                'blamed' => 'updatedBy',
                'timestamp' => 'updated_at',
            ],\n
EOS;
        }

        if (in_array('deleted_at', $infoAttributes))
        {
            echo <<<EOS
            [
                'label' => 'Deleted',
                'blamed' => 'deletedBy',
                'timestamp' => 'deleted_at',
            ],\n
EOS;
        }

        echo <<<EOS
        ],
    ]);
    ?>\n
EOS;
        echo "    <?php \$this->endBlock(); ?>\n";

    $items .= <<<EOS
            [
                'content' => \$this->blocks['info'],
                'label' => '<small>info</small>',
                'active' => false,
                'visible' => ActiveUser::isAdmin(),
            ],\n
EOS;
    }
    ?>

    <?= "<?= \n"; ?>
    Tabs::widget([
        'id' => 'relation-tabs',
        'encodeLabels' => false,
        'items' => [
<?= $items; ?>
        ],
    ]);
    ?>

</div>
