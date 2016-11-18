<?php

namespace fredyns\suites\models;

use yii\helpers\ArrayHelper;
use dektrium\user\models\Profile as BaseProfile;
use fredyns\suites\helpers\StringHelper;
use fredyns\suites\traits\ModelTool;
use fredyns\suites\traits\ModelBlame;

/**
 * This is the model class for table "profile".
 *
 * @property integer $picture_id
 */
class Profile extends BaseProfile
{

    use ModelTool,
        ModelBlame;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
                parent::behaviors(),
                [
                [
                    'class'          => 'mdm\upload\UploadBehavior',
                    'attribute'      => 'picture', // required, use to receive input file
                    'savedAttribute' => 'picture_id', // optional, use to link model with saved file.
                    'uploadPath'     => '@app/upload/profile', // saved directory. default to '@runtime/upload'
                    'autoSave'       => true, // when true then uploaded file will be save before ActiveRecord::save()
                    'autoDelete'     => true, // when true then uploaded file will deleted before ActiveRecord::delete()
                    'deleteOldFile'  => TRUE,
                ],
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
                'plaintextFilter' => [
                    ['name', 'public_email', 'location', 'website', 'bio'],
                    'filter',
                    'filter' => function ($value)
                {
                    return StringHelper::plaintextFilter($value);
                },
                ],
                'nameRequired' => ['name', 'required'],
                /* upload */
                'dp'           => [
                    'picture',
                    'file',
                    'extensions' => ['jpg', 'png'],
                    'maxSize'    => 4096000,
                ],
                ]
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(
                parent::attributeLabels(), [
                'picture_id' => 'Picture',
                ]
        );
    }

}