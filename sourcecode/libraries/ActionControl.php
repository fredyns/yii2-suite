<?php

namespace fredyns\suite\libraries;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
USE yii\web\ForbiddenHttpException;
use cornernote\returnurl\ReturnUrl;
use kartik\icons\Icon;

/**
 * Description of ActionControl
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 *
 * @property string $allowIndex is allowing accessing index page
 * @property string $allowDeleted is allowing accessing deleted page
 * @property string $allowView is allowing accessing view page
 * @property string $allowCreate is allowing accessing create page
 * @property string $allowUpdate is allowing accessing update page
 * @property string $allowDelete is allowing to delete model
 * @property string $allowRestore is allowing to restore model
 *
 * @property array $urlIndex url config for Index page
 * @property array $urlDeleted url config for Deleted page
 * @property array $urlCreate url config for Create page
 * @property array $urlView url config for View page
 * @property array $urlUpdate url config for Update page
 * @property array $urlDelete url config for Delete
 * @property array $urlRestore url config for Restore
 *
 * @property string $linkTo link o view detail
 */
class ActionControl extends \yii\base\Object
{
    const ACTION_INDEX = 'index';
    const ACTION_DELETED = 'deleted';
    const ACTION_CREATE = 'create';
    const ACTION_VIEW = 'view';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';
    const ACTION_RESTORE = 'restore';

    /**
     * menu divider in dropdown menu
     */
    const MENU_DIVIDER = '<li role="presentation" class="divider"></li>';

    /**
     * @var ActiveRecord
     */
    public $model;

    /**
     * @var array buffering action permission. useful for complex checking.
     */
    public $allowed = [];

    /**
     * @var array store any error messages
     */
    public $errors = [];

    /**
     * add new error message
     *
     * @param string $name
     * @param string $message
     */
    public function addError($name, $message)
    {
        $this->errors[$name][] = $message;
    }

    /**
     * get error messages for spesific key
     *
     * @param string $name
     * @param bool $asString
     * @return array|string
     */
    public function getError($name, $asString = false)
    {
        $msg = ArrayHelper::getValue($this->errors, $name, []);

        if ($msg && $asString) {
            return implode("<br/>\n", $msg);
        }

        return $msg;
    }

    /**
     * check whether there is any error
     *
     * @param string $name
     * @return bool
     */
    public function isError($name)
    {
        return (bool) $this->getError($name);
    }

    /**
     * Checks the privilege of the current user.
     *
     * If the user does not have access, a [[ForbiddenHttpException]] should be thrown.
     *
     * @param string $action the ID of the action to be executed
     * @throws ForbiddenHttpException if the user does not have access
     */
    public function check($action, $params = [])
    {
        if ($this->allow($action, $params) == FALSE) {
            $message = $this->getError($action, TRUE);

            throw new ForbiddenHttpException($message);
        }
    }

    /**
     * create action control instance & check spesific action
     *
     * @param type $action
     * @param type $model
     * @return \static
     */
    public static function checkAccess($action, $model = null, $params = [])
    {
        $actControl = new static([
            'model' => $model,
        ]);

        $actControl->check($action, $params);

        return $actControl;
    }

    /**
     * check permission for an action.
     * using buffer as addition.
     *
     * @param string $action
     * @return boolean
     */
    public function allow($action, $params = [])
    {
        if (array_key_exists($action, $this->allowed) == FALSE) {
            $function = 'getAllow'.Inflector::camelize($action);

            if (method_exists($this, $function)) {
                $this->allowed[$action] = call_user_func_array([$this, $function], [$params]);
            } else {
                $this->addError($action, "Action '{$action}' Not Allowed.");
                $this->allowed[$action] = FALSE;
            }
        }

        return $this->allowed[$action];
    }

    /**
     * check permission to access index page
     *
     * @return boolean
     */
    public function getAllowIndex($params = [])
    {
        return TRUE;
    }

    /**
     * check permission to access Deleted page
     *
     * @return boolean
     */
    public function getAllowDeleted($params = [])
    {
        $this->addError(static::ACTION_DELETED, "Deleted model page is not configured properly.");

        /**
         * default to be false.
         * because not all models support soft-delete
         * must be configured properly
         */
        return FALSE;
    }

    /**
     * check permission to create model
     *
     * @return boolean
     */
    public function getAllowCreate($params = [])
    {
        return TRUE;
    }

    /**
     * check permission to view model detail
     *
     * @return boolean
     */
    public function getAllowView($params = [])
    {
        // prerequisites
        if (($this->model instanceof ActiveRecord) == FALSE) {
            $this->addError(static::ACTION_VIEW, "Unknown Data.");

            return FALSE;
        }

        // blacklist
        if ($this->model->isNewRecord) {
            $this->addError(static::ACTION_VIEW, "Can't view unsaved Data.");
        }

        // conclusion
        return ($this->isError(static::ACTION_VIEW) == FALSE);
    }

    /**
     * check permission to update model
     *
     * @return boolean
     */
    public function getAllowUpdate($params = [])
    {
        // prerequisites
        if (($this->model instanceof ActiveRecord) == FALSE) {
            $this->addError(static::ACTION_UPDATE, "Unknown Data.");

            return FALSE;
        }

        // blacklist
        if ($this->model->isNewRecord) {
            $this->addError(static::ACTION_UPDATE, "Can't update unsaved Data.");
        }

        // conclusion
        return ($this->isError(static::ACTION_UPDATE) == FALSE);
    }

    /**
     * check permission to delete model
     *
     * @return boolean
     */
    public function getAllowDelete($params = [])
    {
        // prerequisites
        if (($this->model instanceof ActiveRecord) == FALSE) {
            $this->addError(static::ACTION_DELETE, "Unknown Data.");

            return FALSE;
        }

        // blacklist
        if ($this->model->isNewRecord) {
            $this->addError(static::ACTION_DELETE, "Can't delete unsaved Data.");
        }

        if ($this->model->hasAttribute('recordStatus') && $this->model->hasAttribute('deleted_at')) {
            if ($this->model->getAttribute('recordStatus') == 'deleted') {
                $this->addError(static::ACTION_DELETE, "Data already (soft) deleted.");
            }
        }

        // conclusion
        return ($this->isError(static::ACTION_DELETE) == FALSE);
    }

    /**
     * check permission to restore model
     *
     * @return boolean
     */
    public function getAllowRestore($params = [])
    {
        // prerequisites
        if (($this->model instanceof ActiveRecord) == FALSE) {
            $this->addError(static::ACTION_RESTORE, "Unknown Data.");

            return FALSE;
        }

        // blacklist
        if ($this->model->isNewRecord) {
            $this->addError(static::ACTION_RESTORE, "Can't restore undeleted Data.");
        }

        if ($this->model->hasAttribute('recordStatus') == FALSE OR $this->model->hasAttribute('deleted_at') == FALSE) {
            $this->addError(static::ACTION_RESTORE, "Data doesn't support soft-delete.");
        } elseif ($this->model->getAttribute('recordStatus') != 'deleted') {
            $this->addError(static::ACTION_RESTORE, "Data is not deleted.");
        }

        // conclusion
        return ($this->isError(static::ACTION_RESTORE) == FALSE);
    }

    /**
     * get complete controller route
     *
     * @return string
     */
    public function controllerRoute()
    {
        return '';
    }

    /**
     * get route to action
     *
     * @param string $action
     * @return string
     */
    public function actionRoute($action = '')
    {
        $controller = $this->controllerRoute();

        if ($controller) {
            $action = '/'.$controller.'/'.$action;
        }

        return $action;
    }

    /**
     * get key-parameters
     *
     * @return array
     */
    public function modelParam()
    {
        if ($this->model instanceof ActiveRecord) {
            return $this->model->getPrimaryKey(TRUE);
        }

        return [];
    }

    /**
     * get URL param for action
     *
     * @param string $name
     * @return array
     */
    public function url($name)
    {
        $function = 'getUrl'.ucfirst($name);

        if (method_exists($this, $function)) {
            return $this->$function();
        } else {
            return [];
        }
    }

    /**
     * get URL param for index page
     *
     * @return array
     */
    public function getUrlIndex()
    {
        return [
            $this->actionRoute(static::ACTION_INDEX),
            'ru' => ReturnUrl::getToken(),
        ];
    }

    /**
     * get URL param for deleted page
     *
     * @return array
     */
    public function getUrlDeleted()
    {
        return [
            $this->actionRoute(static::ACTION_DELETED),
            'ru' => ReturnUrl::getToken(),
        ];
    }

    /**
     * get URL param for create action
     *
     * @return array
     */
    public function getUrlCreate()
    {
        return [
            $this->actionRoute(static::ACTION_CREATE),
            'ru' => ReturnUrl::getToken(),
        ];
    }

    /**
     * get URL param for view action
     *
     * @return array
     */
    public function getUrlView()
    {
        if ($this->model instanceof ActiveRecord) {
            $param = $this->modelParam();
            $param[0] = $this->actionRoute(static::ACTION_VIEW);
            $param['ru'] = ReturnUrl::getToken();

            return $param;
        }

        return [];
    }

    /**
     * get URL param for update action
     *
     * @return array
     */
    public function getUrlUpdate()
    {
        if ($this->model instanceof ActiveRecord) {
            $param = $this->modelParam();
            $param[0] = $this->actionRoute(static::ACTION_UPDATE);
            $param['ru'] = ReturnUrl::getToken();

            return $param;
        }

        return [];
    }

    /**
     * get URL param for delete action
     *
     * @return array
     */
    public function getUrlDelete()
    {
        if ($this->model instanceof ActiveRecord) {
            $param = $this->modelParam();
            $param[0] = $this->actionRoute(static::ACTION_DELETE);
            $param['ru'] = ReturnUrl::getToken();

            return $param;
        }

        return [];
    }

    /**
     * get URL param for restore action
     *
     * @return array
     */
    public function getUrlRestore()
    {
        if ($this->model instanceof ActiveRecord) {
            $param = $this->modelParam();
            $param[0] = $this->actionRoute(static::ACTION_RESTORE);
            $param['ru'] = ReturnUrl::getToken();

            return $param;
        }

        return [];
    }

    /**
     * get default action list
     *
     * @return array
     */
    public function actionDefault()
    {
        if ($this->model instanceof ActiveRecord) {
            if ($this->model->isNewRecord == FALSE) {
                return $this->actionPersistentModel();
            }
        }

        return $this->actionUnspecifiedModel();
    }

    /**
     * list action for persistent model / DB record
     *
     * @return array
     */
    public function actionPersistentModel()
    {
        return [static::ACTION_VIEW, static::ACTION_UPDATE, static::ACTION_DELETE, static::ACTION_RESTORE];
    }

    /**
     * list action for unspecified/unsaved model
     *
     * @return array
     */
    public function actionUnspecifiedModel()
    {
        return [static::ACTION_INDEX, static::ACTION_CREATE, static::ACTION_DELETED];
    }

    /**
     * all possible actions & configuration
     *
     * @return array
     */
    public function actions()
    {
        return [
            static::ACTION_INDEX => [
                'label' => 'List',
                'url' => $this->urlIndex,
                'icon' => Icon::show('list'),
                'linkOptions' => [
                    'title' => 'click to open all active data',
                    'aria-label' => 'Index',
                    'data-pjax' => '0',
                ],
                'buttonOptions' => [
                    'class' => 'btn btn-default',
                ],
            ],
            static::ACTION_DELETED => [
                'label' => 'Deleted',
                'url' => $this->urlDeleted,
                'icon' => Icon::show('trash'),
                'linkOptions' => [
                    'title' => 'click to open all deleted data',
                    'aria-label' => 'Deleted',
                    'data-pjax' => '0',
                ],
                'buttonOptions' => [
                    'class' => 'btn btn-info',
                ],
            ],
            static::ACTION_CREATE => [
                'label' => 'Create',
                'url' => $this->urlCreate,
                'icon' => Icon::show('plus'),
                'linkOptions' => [
                    'title' => 'click to create new record',
                    'aria-label' => 'Create',
                    'data-pjax' => '0',
                ],
                'buttonOptions' => [
                    'class' => 'btn btn-info',
                ],
            ],
            static::ACTION_VIEW => [
                'label' => 'View',
                'url' => $this->urlView,
                'icon' => Icon::show('zoom-in'),
                'linkOptions' => [
                    'title' => 'click to view this data',
                    'aria-label' => 'View',
                    'data-pjax' => '0',
                ],
                'buttonOptions' => [
                    'class' => 'btn btn-primary',
                ],
            ],
            static::ACTION_UPDATE => [
                'label' => 'Update',
                'url' => $this->urlUpdate,
                'icon' => Icon::show('pencil'),
                'linkOptions' => [
                    'title' => 'click to edit this data',
                    'aria-label' => 'Update',
                    'data-pjax' => '0',
                ],
                'buttonOptions' => [
                    'class' => 'btn btn-success',
                ],
            ],
            static::ACTION_DELETE => [
                'label' => 'Delete',
                'url' => $this->urlDelete,
                'icon' => Icon::show('trash'),
                'linkOptions' => [
                    'title' => 'click to delete this data',
                    'aria-label' => 'Delete',
                    'data-pjax' => '0',
                    'data-confirm' => 'Are you sure to delete this item?',
                    'data-method' => 'post',
                ],
                'buttonOptions' => [
                    'class' => 'btn btn-danger',
                ],
            ],
            static::ACTION_RESTORE => [
                'label' => 'Restore',
                'url' => $this->urlRestore,
                'icon' => Icon::show('retweet'),
                'linkOptions' => [
                    'title' => 'click to restore this data',
                    'aria-label' => 'Restore',
                    'data-pjax' => '0',
                    'data-confirm' => 'Are you sure to restore this item?',
                ],
                'buttonOptions' => [
                    'class' => 'btn btn-info',
                ],
            ],
        ];
    }

    /**
     * get parameter for an action
     *
     * @param string $key
     * @param array $options additional/overide parameter
     * @return array
     */
    public function param($key = '', $options = [])
    {
        $param = ArrayHelper::getValue($this->actions(), $key);

        if ($param && is_array($param) && $options && is_array($options)) {
            $param = ArrayHelper::merge($param, $options);
        }

        return $param;
    }

    /**
     * generate regular link
     *
     * @param String $name
     * @param Array $options
     * @return String
     */
    public function a($name, $options = [])
    {
        if (is_string($options)) {
            $options = ['label' => $options];
        }

        $allow = $this->allow($name);
        $params = $this->param($name, $options);

        $label = ArrayHelper::getValue($params, 'label');
        $linkOptions = ArrayHelper::getValue($params, 'linkOptions', []);
        $urlOptions = ArrayHelper::getValue($params, 'urlOptions', []);

        if ($allow) {
            $url = ArrayHelper::merge($params['url'], $urlOptions);
        } else {
            $url = '#';
            $linkOptions['title'] = $this->getError($name, TRUE);
        }

        return Html::a($label, $url, $linkOptions);
    }

    /**
     * generate link if allowed
     *
     * @param String $name
     * @param Array $options
     * @return String
     */
    public function link($name, $options = [])
    {
        $allow = $this->allow($name);

        if ($allow) {
            return $this->a($name, $options);
        }

        return NULL;
    }

    /**
     * generate button link
     *
     * @param String $name
     * @param Array $options
     * @return String
     */
    public function btn($name, $options = [])
    {
        if (is_string($options)) {
            $options = ['label' => $options];
        }

        $allow = $this->allow($name);
        $params = $this->param($name, $options);

        $icon = ArrayHelper::getValue($params, 'icon');
        $text = ArrayHelper::getValue($params, 'label');
        $urlOptions = ArrayHelper::getValue($params, 'urlOptions', []);
        $linkOptions = ArrayHelper::getValue($params, 'linkOptions', []);
        $buttonOptions = ArrayHelper::getValue($params, 'buttonOptions', []);

        $label = trim($icon.' '.$text);
        $linkOptions = ArrayHelper::merge($linkOptions, $buttonOptions);

        if ($allow) {
            $url = ArrayHelper::merge($params['url'], $urlOptions);
        } else {
            $url = '#';
            $linkOptions['title'] = $this->getError($name, TRUE);
        }

        return Html::a($label, $url, $linkOptions);
    }

    /**
     * generate button link if allowed
     *
     * @param String $name
     * @param Array $options
     * @return String
     */
    public function button($name, $options = [])
    {
        $allow = $this->allow($name);

        if ($allow) {
            return $this->btn($name, $options);
        }

        return NULL;
    }

    /**
     * generate button widget
     *
     * @param array $items
     * @return string
     */
    public function buttons($items = [])
    {
        if (empty($items)) {
            $items = $this->actionDefault();
        }

        $buttons = [];

        foreach ($items as $item => $options) {
            if (is_int($item)) {
                $item = $options;
                $options = [];
            }

            if ($this->allow($item)) {
                $buttons[] = $this->btn($item, $options);
            }
        }

        if ($buttons) {
            return implode("\n", $buttons);
        }

        return '';
    }

    /**
     * generate items parameter for dropdown menu
     *
     * @param array $items access list to be shown
     * @return array
     */
    public function dropdownItems($items = [])
    {
        if (empty($items)) {
            $items = $this->actionDefault();
        }

        $params = [];
        $count = 0;
        $lastParam = NULL;

        foreach ($items as $item) {
            if (is_string($item) && $item !== static::MENU_DIVIDER) {
                $allow = $this->allow($item);
                $param = $this->param($item);

                if ($param && $allow) {
                    $icon = ArrayHelper::remove($param, 'icon');
                    $param['label'] = $icon.'&nbsp; '.$param['label'];
                    $params[] = $param;
                    $lastParam = $param;
                    $count++;
                }
            } else if (is_array($item) OR ( $count > 0 && $item !== $lastParam )) {
                $params[] = $param;
                $lastParam = $param;
                $count++;
            }
        }

        return $params;
    }

    /**
     * generate dropdown widget
     *
     * @param array $items
     * @param array $options
     * @return string
     */
    public function dropdown($config = [])
    {
        if ($this->model instanceof ActiveRecord) {
            $elementId = Inflector::camel2id($this->model->tableName());
            $elementId .= '_'.implode('_', $this->modelParam());
        } else {
            $elementId = get_called_class().'_'.$this->id;
        }

        $items = ArrayHelper::getValue($config, 'items', $this->actionDefault());
        $buttonConfig = [
            'id' => $elementId,
            'encodeLabel' => false,
            'label' => 'Action',
            'dropdown' => [
                'options' => [
                    'class' => 'dropdown-menu-'.ArrayHelper::getValue($config, 'align', 'right'),
                ],
                'encodeLabels' => false,
                'items' => $this->dropdownItems($items),
            ],
            'options' => [
                'data-pjax' => '0',
                'class' => 'btn btn-primary',
            ],
        ];

        $options = ArrayHelper::getValue($config, 'options');

        if ($options) {
            $buttonConfig = ArrayHelper::merge($buttonConfig, $options);
        }

        /* dropdown menu */
        return \yii\bootstrap\ButtonDropdown::widget($buttonConfig);
    }

    /**
     * get model label
     *
     * @return string
     */
    public function modelLabel()
    {
        $alternatives = ['label', 'name', 'title', 'number', 'id'];

        foreach ($alternatives as $attribute) {
            if ($this->model->hasAttribute($attribute)) {
                $label = $this->model->getAttribute($attribute);

                if ($label) {
                    return $label;
                }
            }
        }

        return 'view';
    }

    /**
     * generate link to page that show model detail
     *
     * @param array $options
     * @return string
     */
    public function getLinkTo($options = [])
    {
        if (is_scalar($options)) {
            $options = ['label' => $options];
        } elseif (is_array($options) == FALSE) {
            $options = [];
        }

        $options = ArrayHelper::merge(['label' => $this->modelLabel()], $options);

        if ($this->allow(static::ACTION_VIEW)) {
            return $this->a(static::ACTION_VIEW, $options);
        }

        return $options['label'];
    }

    /**
     * all breadcrumb labels
     *
     * @return array
     */
    public function breadcrumbLabels()
    {
        return [
            static::ACTION_INDEX => 'List',
            static::ACTION_CREATE => 'Create',
            static::ACTION_VIEW => '#'.implode('-', $this->modelParam()),
            static::ACTION_UPDATE => 'Edit',
            static::ACTION_DELETE => 'Delete',
            static::ACTION_RESTORE => 'Restore',
        ];
    }

    /**
     * spesific breadcrumb label
     *
     * @return array
     */
    public function breadcrumbLabel($name)
    {
        return ArrayHelper::getValue($this->breadcrumbLabels(), $name, '#');
    }

    /**
     * generate breadcrumb link item
     * if user is not permitted to access page/url, only label will be returned
     *
     * @param string $name
     * @param array $options
     * @return array|string
     */
    public function breadcrumbItem($name, $options = [])
    {
        if ($this->allow($name)) {
            return ArrayHelper::merge([
                    'label' => $this->breadcrumbLabel($name),
                    'url' => $this->url($name),
                    ], $options);
        } else {
            return $this->breadcrumbLabel($name);
        }
    }
}