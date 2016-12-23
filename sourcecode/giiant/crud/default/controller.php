<?php

use yii\helpers\StringHelper;

/*
 * This is the template for generating a CRUD controller class file.
 *
 * @var yii\web\View $this
 * @var schmunk42\giiant\generators\crud\Generator $generator
 */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
$searchModelClassName = $searchModelClass;

if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass.'Search';
    $searchModelClassName = $searchModelAlias;
}

$pks = $generator->getTableSchema()->primaryKey;
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

$actioncontrolPath = ltrim($generator->modelClass, '\\').'ActControl';
$actioncontrolPath = str_replace('models', 'actioncontrols', $actioncontrolPath);
$actioncontrolClass = StringHelper::basename($actioncontrolPath);
$formPath = ltrim($generator->searchModelClass, '\\');
$formPath = str_replace('search', 'form', $formPath);
$formPath = str_replace('Search', 'Form', $formPath);

echo "<?php\n";
?>
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>\base;

use Yii;
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\HttpException;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use cornernote\returnurl\ReturnUrl;
<?php
echo "use ".ltrim($generator->modelClass, '\\').";\n";

if ($searchModelClass !== ''){
    echo "use ".ltrim($generator->searchModelClass,'\\');
    echo (isset($searchModelAlias))?" as ".$searchModelAlias:"";
    echo ";\n";
}

echo "use ".$formPath.";\n";
echo "use ".$actioncontrolPath.";\n";
echo "\n";
?>
/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass)."\n" ?>
{

<?php

$traits = $generator->baseTraits;

if ($traits) {
    echo "    use {$traits};";
}
?>

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;

    <?php if ($generator->accessFilter): ?>
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    <?php foreach($accessDefinitions['roles'] as $roleName => $actions): ?>
                    [
                        'allow' => true,
                        'actions' => ['<?=implode("', '",$actions)?>'],
                        'roles' => ['<?=$roleName?>'],
                    ],
                    <?php endforeach;?>
                ],
            ],
        ];
    }
    <?php endif; ?>

    /**
     * Lists all active <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new <?= $searchModelClassName ?>;
        $dataProvider = $searchModel->searchIndex($_GET);
        $actionControl = <?= $actioncontrolClass ?>::checkAccess('index', $searchModel);

        Tabs::clearLocalStorage();
        Url::remember();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'actionControl' => $actionControl,
            'searchModel' => $searchModel,
        ]);
    }

    <?php if (in_array('fredyns\suite\traits\ModelSoftDelete', class_uses($generator->modelClass))): ?>

    /**
     * Lists deleted active <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionDeleted()
    {
        $searchModel = new <?= $searchModelClassName ?>;
        $dataProvider = $searchModel->searchDeleted($_GET);
        $actionControl = <?= $actioncontrolClass ?>::checkAccess('deleted', $searchModel);

        Tabs::clearLocalStorage();
        Url::remember();

        return $this->render('deleted', [
            'dataProvider' => $dataProvider,
            'actionControl' => $actionControl,
            'searchModel' => $searchModel,
        ]);
    }

    <?php endif; ?>

    /**
     * Displays a single <?= $modelClass ?> model.
     * <?= implode("\n\t * ", $actionParamComments)."\n" ?>
     *
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);
        $actionControl = <?= $actioncontrolClass ?>::checkAccess('view', $model);

        Url::remember();
        Tabs::rememberActiveState();

        return $this->render('view', [
            'model' => $model,
            'actionControl' => $actionControl,
        ]);
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new <?= $modelClass ?>Form;
        $actionControl = <?= $actioncontrolClass ?>::checkAccess('create', $model);

        try {
            if ($model->load($_POST) && $model->save()) {
                Yii::$app->getSession()->addFlash('success', "Data successfully saved!");

                return $this->redirect(ReturnUrl::getUrl(Url::previous()));
            } elseif (!Yii::$app->request->isPost) {
                $model->load($_GET);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
            $model->addError('_exception', $msg);
        }

        return $this->render('create', [
            'model' => $model,
            'actionControl' => $actionControl,
        ]);
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n\t * ", $actionParamComments)."\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        $model = $this->findForm(<?= $actionParams ?>);
        $actionControl = <?= $actioncontrolClass ?>::checkAccess('update', $model);

        if ($model->load($_POST) && $model->save()) {
            Yii::$app->getSession()->addFlash('success', "Data successfully updated!");

            return $this->redirect(ReturnUrl::getUrl(Url::previous()));
        }

        return $this->render('update',
                [
                'model' => $model,
                'actionControl' => $actionControl,
        ]);
    }

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the previous page.
     * <?= implode("\n\t * ", $actionParamComments)."\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {
        try {
            $model = $this->findModel(<?= $actionParams ?>);

            <?= $actioncontrolClass ?>::checkAccess('delete', $model);

            if ($model->delete() !== FALSE) {
                Yii::$app->getSession()->addFlash('info', "Data successfully deleted!");
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
            Yii::$app->getSession()->addFlash('error', $msg);
        } finally {
            return $this->redirect(ReturnUrl::getUrl(Url::previous()));    
        }
    }

    <?php if (in_array('fredyns\suite\traits\ModelSoftDelete', class_uses($generator->modelClass))): ?>

    /**
     * Restores an deleted <?= $modelClass ?> model.
     * If restoration is successful, the browser will be redirected to the previous page.
     * <?= implode("\n\t * ", $actionParamComments)."\n" ?>
     * @return mixed
     */
    public function actionRestore(<?= $actionParams ?>)
    {
        try {
            $model = $this->findModel(<?= $actionParams ?>);

            <?= $actioncontrolClass ?>::checkAccess('restore', $model);

            if ($model->restore() !== FALSE) {
                Yii::$app->getSession()->addFlash('success', "Data successfully restored!");
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
            Yii::$app->getSession()->addFlash('error', $msg);
        } finally {
            return $this->redirect(ReturnUrl::getUrl(Url::previous()));    
        }
    }

    <?php endif; ?>

    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n\t * ", $actionParamComments)."\n" ?>
     * @return <?= $modelClass ?> the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParams ?>)
    {<?php
        if (count($pks) === 1) {
            $condition = '$'.$pks[0];
        } else {
            $condition = [];

            foreach ($pks as $pk) {
                $condition[] = "'$pk' => \$$pk";
            }

        $condition = '['.implode(', ', $condition).']';
        }
        ?>        
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Finds the <?= $modelClass ?> form model for modification.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n\t * ", $actionParamComments)."\n" ?>
     * @return <?= $modelClass ?> the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findForm(<?= $actionParams ?>)
    {<?php
        if (count($pks) === 1) {
            $condition = '$'.$pks[0];
        } else {
            $condition = [];
    
            foreach ($pks as $pk) {
                $condition[] = "'$pk' => \$$pk";
            }
    
            $condition = '['.implode(', ', $condition).']';
        }
        ?>
        if (($model = <?= $modelClass ?>Form::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
