<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * This is the template for generating the model class of a specified table.
 *
 * @var yii\web\View $this
 * @var yii\gii\generators\model\Generator $generator
 * @var string $tableName full table name
 * @var string $className class name
 * @var yii\db\TableSchema $tableSchema
 * @var string[] $labels list of attribute labels (name => label)
 * @var string[] $rules list of validation rules
 * @var array $relations list of relations (name => relation declaration)
 */

echo "<?php\n";

$routes = [Inflector::camel2id($className, '-', true)]; 

// detect additional routes
$namespaceArray = explode('\\', $generator->ns);
$namespaceCount = count($namespaceArray);

if ($namespaceCount > 2) {
    $idx = $namespaceCount - 2;
    array_unshift($routes, $namespaceArray[$idx]);
}
?>

namespace <?= $generator->actionNs ?>;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use cornernote\returnurl\ReturnUrl;
use kartik\icons\Icon;
use <?= $generator->ns . '\\' . $className ?>;

/**
 * <?= $className ?> model action control
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 *
 * @property <?= $className ?> $model data model
 */
class <?= $className ?>ActControl extends \fredyns\suite\libraries\ActionControl
{

    /**
     * @inheritdoc
     */
    public function controllerRoute()
    {
        return '/<?= implode('/', $routes) ?>';
    }

    /**
     * @inheritdoc
     */
    public function breadcrumbLabels()
    {
        return ArrayHelper::merge(
            parent::breadcrumbLabels(), [
                'index' => '<?= $className ?>',
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function modelLabel()
    {
        return parent::modelLabel();
    }

    /**
     * @inheritdoc
     */
    public function messages()
    {
        return [
            'forbidden' => "%s is not allowed.",
            'notconfigured' => "%s is not configured properly.",
            'model-unknown' => "Unknown Data.",
            'model-unsaved' => "Can't %s unsaved data.",
            'model-deleted' => "Data already (soft) deleted.",
            'model-active' => "Data is not deleted.",
            'softdelete-unsupported' => "Data doesn't support soft-delete.",
        ];
    }

    /**
     * @inheritdoc
     */
    public function actionPersistentModel()
    {
        return ArrayHelper::merge(
                parent::actionPersistentModel(), [
                    #  additional action name
                ]
        );
    }

    /**
     * @inheritdoc
     */
    public function actionUnspecifiedModel()
    {
        return ArrayHelper::merge(
                parent::actionUnspecifiedModel(), [
                    # additional action name
                ]
        );
    }

    /**
     * @inheritdoc
     */
    public function messages()
    {
        return [
            'forbidden' => "%s is not allowed.",
            'notconfigured' => "%s is not configured properly.",
            'model-unknown' => "Unknown Data.",
            'model-unsaved' => "Can't %s unsaved data.",
            'model-deleted' => "Data already (soft) deleted.",
            'model-active' => "Data is not deleted.",
            'softdelete-unsupported' => "Data doesn't support soft-delete.",
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return ArrayHelper::merge(
                parent::actions(),
                [
                /* / action sample / */
                
                # 'action_name' => [
                #     'label'         => 'Action_Label',
                #     'url'           => $this->urlAction,
                #     'icon'          => Icon::show('star'),
                #     'linkOptions'   => [
                #         'title'      => 'click to do action',
                #         'aria-label' => 'Action_Label',
                #         'data-pjax'  => '0',
                #     ],
                #     'buttonOptions' => [
                #         'class' => 'btn btn-default',
                #     ],
                # ],
                ]
        );
    }

<?php if ($tableSchema->getColumn('deleted_at') !== null): ?>
    /**
     * check permission to access Deleted page
     *
     * @return boolean
     */
    public function getAllowDeleted($params = [])
    {
        return true;
    }
<?php endif; ?>

    ################################ sample : additional action ################################ 

    /**
     * get URL param to do action
     *
     * @return array
     */
    public function getUrlAction()
    {
        if ($this->model instanceof ActiveRecord)
        {
            $param       = $this->modelParam();
            $param[0]    = $this->actionRoute('action_slug');
            $param['ru'] = ReturnUrl::getToken();

            return $param;
        }

        return [];
    }

    /**
     * check permission to do action
     *
     * @return boolean
     */
    public function getAllowAction($params = [])
    {
        return true;
    }

}