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
echo "<?php\n";
?>
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>\base;

use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if ($searchModelClass !== ''): ?>
use <?= ltrim(
        $generator->searchModelClass,
        '\\'
    ) ?><?php if (isset($searchModelAlias)): ?> as <?= $searchModelAlias ?><?php endif ?>;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use kartik\grid\EditableColumnAction;
use yii\data\ActiveDataProvider;

/**
* <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
*/
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass)."\n" ?>
{
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
<?php 
foreach($accessDefinitions['roles'] as $roleName => $actions){
?>
                    [
                        'allow' => true,
                        'actions' => [
                            '<?=implode("',".PHP_EOL."                            '",$actions)?>'
                        ],
                        'roles' => ['<?=$roleName?>'],
                    ],
<?php    
}
?>                ],
            ],
        ];
    }
<?php endif; ?>

    public function actions() {
        return [
            'editable-column-update' => [
                'class' => EditableColumnAction::className(), // action class name
                'modelClass' => <?=$modelClass?>::className(),
            ],
        ];
    }    
    
    /**
    * Lists all <?= $modelClass ?> models.
    * @return mixed
    */
    public function actionIndex()
    {
<?php if ($searchModelClass !== '') {
    ?>
        $searchModel  = new <?= $searchModelClassName ?>;
        $dataProvider = $searchModel->search($_GET);
<?php 
} else {
    ?>
        $dataProvider = new ActiveDataProvider([
        'query' => <?= $modelClass ?>::find(),
        ]);
<?php 
} ?>

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
<?php if ($searchModelClass !== ''): ?>
            'searchModel' => $searchModel,
<?php endif; ?>
        ]);
    }

    /**
     * Displays a single <?= $modelClass ?> model.
     * <?= implode("\n\t * ", $actionParamComments)."\n" ?>
     *
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();

        return $this->render('view', [
            'model' => $this->findModel(<?= $actionParams ?>),
        ]);
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected 
     *  to the 'view' page or back, if parameter $goBack is true.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new <?= $modelClass ?>;
        $model->load($_GET);
        $relAttributes = $model->attributes;
        
        try {
            if ($model->load($_POST) && $model->save()) {
                if($relAttributes){
                    return $this->goBack();
                }      
                return $this->redirect(['view', <?= $urlParams ?>]);
            } elseif (!\Yii::$app->request->isPost) {
                $model->load($_GET);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
            $model->addError('_exception', $msg);
        }
        
        return $this->render('create', [
            'model' => $model,
            'relAttributes' => $relAttributes,            
            ]);
    }
    
    /**
     * Add a new TestContacts record for relation grid and redirect back.
     * @return mixed
     */
    public function actionCreateForRel()
    {
        $model = new <?= $modelClass ?>;
        $model->load($_GET);
        $relAttributes = $model->attributes;
        $model->save();
        return $this->goBack();
    }
    
    /**
    * Updates an existing <?= $modelClass ?> model.
    * If update is successful, the browser will be redirected to the 'view' page.
    * <?= implode("\n\t * ", $actionParamComments)."\n" ?>
    * @return mixed
    */
    public function actionUpdate(<?= $actionParams ?>)
    {
        $model = new <?= $modelClass ?>;
        $model->load($_GET);
        $relAttributes = $model->attributes;
        
        $model = $this->findModel(<?= $actionParams ?>);

        if ($model->load($_POST) && $model->save()) {
            return $this->redirect(Url::previous());
        } else {
            return $this->render('update', [
                'model' => $model,
                'relAttributes' => $relAttributes                
            ]);
        }
    }

    /**
    * Deletes an existing <?= $modelClass ?> model.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * <?= implode("\n\t * ", $actionParamComments)."\n" ?>
    * @return mixed
    */
    public function actionDelete(<?= $actionParams ?>)
    {
        try {
            $this->findModel(<?= $actionParams ?>)->delete();
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
            \Yii::$app->getSession()->addFlash('error', $msg);
            return $this->redirect(Url::previous());
        }

        $model = new <?= $modelClass ?>;
        $model->load($_GET);
        $relAttributes = $model->attributes;       
        if($relAttributes){
            return $this->redirect(Url::previous());
        }        
        
        // TODO: improve detection
        $isPivot = strstr('<?= $actionParams ?>',',');
        if ($isPivot == true) {
            return $this->redirect(Url::previous());
        } elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/') {
            Url::remember(null);
            $url = \Yii::$app->session['__crudReturnUrl'];
            \Yii::$app->session['__crudReturnUrl'] = null;

            return $this->redirect($url);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
    * Update <?= $modelClass ?> model record by editable.
    * <?= implode("\n\t * ", $actionParamComments)."\n" ?>
    * @return mixed
    */    
    public function actionEditable(<?= $actionParams ?>){
        
        // Check if there is an Editable ajax request
        if (!isset($_POST['hasEditable'])) {
            return false;
        }
        
        $post = [];
        foreach($_POST as $name => $value){
            //if(in_array($name,$this->editAbleFileds)){
                $post[$name] = $value;
            //}
        }
        
        // use Yii's response format to encode output as JSON
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;        
        if(!$post){
            return ['output' => '', 'message' => <?=$generator->generateString('Can not update this field') ?>];
        }

        $model = $this->findModel(<?= $actionParams ?>);
        $model->setAttributes($post, true);
        // read your posted model attributes
        if ($model->save()) {
            // read or convert your posted information
            $value = $model->$name;

            // return JSON encoded output in the below format
            return ['output' =>$value, 'message' => ''];

            // alternatively you can return a validation error
            // return ['output' => '', 'message' => <?=$generator->generateString('Validation error') ?>];
        }
        // else if nothing to do always return an empty JSON encoded output
        else {
            //  return ['output' => '', 'message' => ''];
            $errors = [];
            foreach($model->errors as $field => $messages){
                foreach($messages as $message){
                    $errors[] = $model->getAttributeLabel($field) 
                            . ': '
                            . $message;
                }
            }
            return ['output' => '', 'message' => implode('<br>',$errors)];
            
        }
        
    }    

    /**
    * Finds the <?= $modelClass ?> model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * <?= implode("\n\t * ", $actionParamComments)."\n" ?>
    * @return <?= $modelClass ?> the loaded model
    * @throws HttpException if the model cannot be found
    */
    protected function findModel(<?= $actionParams ?>)
    {
<?php
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
}
