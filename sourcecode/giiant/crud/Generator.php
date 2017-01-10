<?php

namespace fredyns\suite\giiant\crud;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use schmunk42\giiant\helpers\SaveForm;

/**
 * This generator generates an extended version of Giiant CRUDs.
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 *
 * @since 1.0
 */
class Generator extends \schmunk42\giiant\generators\crud\Generator
{
    public $modelMessageCategory = 'app';
    public $actionButtonClass = 'fredyns\\suite\\grid\\KartikActionColumn';
    public $indexGridClass = 'kartik\\grid\\GridView';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->providerList = static::getProviders();
    }

    /**
     * @return array List of providers. Keys and values contain the same strings
     */
    public function generateProviderCheckboxListData()
    {
        $coreProviders = static::getProviders();

        return array_combine($coreProviders, $coreProviders);
    }

    public static function getProviders()
    {
        $files = FileHelper::findFiles(
                Yii::getAlias('@vendor/fredyns/yii2-suite/sourcecode/giiant/crud/providers'),
                [
                'only' => ['*.php'],
                'recursive' => TRUE,
                ]
        );

        foreach ($files as $file) {
            require_once $file;
        }

        return array_filter(
            get_declared_classes(),
            function ($a) {
            return stripos($a, __NAMESPACE__.'\providers') !== false;
        }
        );
    }

    public function render($template, $params = [])
    {
        $this->tidyOutput = true;
        $this->fixOutput = true;

        return parent::render($template, $params);
    }

    /**
     * search suficient model label
     *
     * @return string
     */
    public function getModelLabel()
    {
        $model = new $this->modelClass;
        $alternatives = [
            'name',
            'title',
            'label',
            'number',
        ];

        foreach ($alternatives as $attribute) {
            if ($model->hasAttribute($attribute)) {
                return $attribute;
            }
        }

        $safeAttributes = $model->safeAttributes();
        $primaryKeys = $model->primaryKey();
        $altAttributes = array_diff($safeAttributes, $primaryKeys);

        return $altAttributes[0];
    }
}