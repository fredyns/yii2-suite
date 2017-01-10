<?php
/**
 * @link http://www.phundament.com
 *
 * @copyright Copyright (c) 2014 herzog kommunikation GmbH
 * @license http://www.phundament.com/license/
 */

namespace fredyns\suite\giiant\model;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\StringHelper;
use schmunk42\giiant\helpers\SaveForm;

/**
 * This generator will generate one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class Generator extends \schmunk42\giiant\generators\model\Generator
{
    public $generateRelationsFromCurrentSchema = false;
    public $useSchemaName = false;
    public $actionNs = 'app\actioncontrols';
    public $formNs = 'app\models\form';

    /**
     * {@inheritdoc}
     */
    public function requiredTemplates()
    {
        return ['model.php', 'model-extended.php', 'model-form.php', 'action-control.php'];
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $files = [];
        $relations = $this->generateRelations();
        $db = $this->getDbConnection();

        /**
         * fredyns: start
         * preparing additional variables
         */
        $this->actionNs = str_replace('models', 'actioncontrols', $this->ns);
        $this->formNs = $this->ns.'\form';

        /**
         * fredyns: end
         */
        foreach ($this->getTableNames() as $tableName) {
            list($relations, $translations) = array_values($this->extractTranslations($tableName, $relations));

            $className = php_sapi_name() === 'cli' ? $this->generateClassName($tableName) : $this->modelClass;
            $queryClassName = ($this->generateQuery) ? $this->generateQueryClassName($className) : false;
            $tableSchema = $db->getTableSchema($tableName);

            $params = [
                'tableName' => $tableName,
                'className' => $className,
                'queryClassName' => $queryClassName,
                'tableSchema' => $tableSchema,
                'labels' => $this->generateLabels($tableSchema),
                'hints' => $this->generateHints($tableSchema),
                'rules' => $this->generateRules($tableSchema),
                'relations' => isset($relations[$tableName]) ? $relations[$tableName] : [],
                'ns' => $this->ns,
                'enum' => $this->getEnum($tableSchema->columns),
            ];

            if (!empty($translations)) {
                $params['translation'] = $translations;
            }

            $params['blameable'] = $this->generateBlameable($tableSchema);
            $params['timestamp'] = $this->generateTimestamp($tableSchema);

            $files[] = new CodeFile(
                Yii::getAlias('@'.str_replace('\\', '/', $this->ns))
                .'/base/'.$className.$this->baseClassSuffix.'.php'
                , $this->render('model.php', $params)
            );

            $modelClassFile = Yii::getAlias('@'.str_replace('\\', '/', $this->ns)).'/'.$className.'.php';

            if ($this->generateModelClass || !is_file($modelClassFile)) {
                $files[] = new CodeFile(
                    $modelClassFile, $this->render('model-extended.php', $params)
                );

                /**
                 * fredyns: start
                 *
                 * add form model & action-control
                 */
                $formFilename = $className.'Form.php';
                $formFilepath = Yii::getAlias('@'.str_replace('\\', '/', $this->formNs)).'/'.$formFilename;

                $files[] = new CodeFile(
                    $formFilepath, $this->render('model-form.php', $params)
                );

                $actionFilename = $className.'ActControl.php';
                $actionFilepath = Yii::getAlias('@'.str_replace('\\', '/', $this->actionNs)).'/'.$actionFilename;

                $files[] = new CodeFile(
                    $actionFilepath, $this->render('action-control.php', $params)
                );
                /**
                 * fredyns: end
                 */
            }

            if ($queryClassName) {
                $queryClassFile = Yii::getAlias(
                        '@'.str_replace('\\', '/', $this->queryNs)
                    ).'/'.$queryClassName.'.php';
                if ($this->generateModelClass || !is_file($queryClassFile)) {
                    $params = [
                        'className' => $queryClassName,
                        'modelClassName' => $className,
                    ];
                    $files[] = new CodeFile(
                        $queryClassFile, $this->render('query.php', $params)
                    );
                }
            }

            /*
             * create gii/[name]GiiantModel.json with actual form data
             */
            $suffix = str_replace(' ', '', $this->getName());
            $formDataDir = Yii::getAlias('@'.str_replace('\\', '/', $this->ns));
            $formDataFile = StringHelper::dirname($formDataDir)
                .'/gii'
                .'/'.$tableName.$suffix.'.json';

            $formData = json_encode(SaveForm::getFormAttributesValues($this, $this->formAttributes()));
            $files[] = new CodeFile($formDataFile, $formData);
        }

        return $files;
    }
}