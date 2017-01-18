<?php
/**
 * Customizable controller class.
 */
echo "<?php\n";
?>

namespace <?= \yii\helpers\StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use yii\filters\VerbFilter;
use fredyns\suite\filters\AdminLTELayout;

/**
 * This is the class for controller "<?= $controllerClassName ?>".
 */
class <?= $controllerClassName ?> extends <?= (isset($generator->controllerNs) ? '\\'.$generator->controllerNs.'\\' : '') .'base\\'.$controllerClassName."\n" ?>
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'layout' => [
                'class' => AdminLTELayout::className(),
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

}
