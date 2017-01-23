<?php
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

$modelTrait = ['ModelTool', 'ModelBlame'];

if ($tableSchema->getColumn('deleted_at') !== null) {
    $modelTrait[] = 'ModelSoftDelete';
}
?>

namespace <?= $generator->ns ?>;

use Yii;
use yii\helpers\ArrayHelper;
use fredyns\suite\traits\ModelTool;
use fredyns\suite\traits\ModelBlame;
use fredyns\suite\traits\ModelSoftDelete;
use <?= $generator->ns ?>\base\<?= $className ?> as Base<?= $className ?>;

/**
 * This is the model class for table "<?= $tableName ?>".
 */
class <?= $className ?> extends Base<?= $className . "\n" ?>
{

    use <?= implode(', ', $modelTrait) ?>;
<?php if (!empty($relations)): ?>    
<?php foreach ($relations as $name => $relation): ?>
<?php if (!$relation[2] && $name != StringHelper::basename($relation[1])): ?>
    const ALIAS_<?= strtoupper($name) ?> = '<?= lcfirst($name) ?>';<?= "\n" ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(
             parent::rules(),
             [
                  # custom validation rules
             ]
        );
    }
<?php if (!empty($relations)): ?>
<?php foreach ($relations as $name => $relation): ?>
<?php if (!$relation[2] && $name != StringHelper::basename($relation[1])): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        return parent::get<?= $name ?>()->alias(static::ALIAS_<?= strtoupper($name) ?>);
    }
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
}
