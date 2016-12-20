<?php
/**
 * contents web configuration sample
 * copy needed lines only
 */
$confidential = require(__DIR__.'/confidential.php');
$config = [
    'id' => 'yii2app',
    'name' => 'Application',
    'language' => 'id-ID',
    'timeZone' => 'Asia/Jakarta',
    'components' => [
        'response' => [
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => TRUE,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'sessionTable' => 'yii_session',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'formatter' => [
            'locale' => 'id_ID',
            'thousandSeparator' => '.',
            'decimalSeparator' => ',',
            'currencyCode' => 'IDR',
        ],
        'mailer' => $confidential['mailer'],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/views/user'
                ],
            ],
        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableUnconfirmedLogin' => TRUE,
            'admins' => ['admin'],
            'modelMap' => [
                'User' => 'app\models\User',
                'Profile' => 'app\models\Profile',
            ],
            'controllerMap' => require(__DIR__.'/module/user-controllerMap.php'),
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ],
    ],
    'controllerMap' => [
        'file' => 'mdm\\upload\\FileController', // use to show or download file
    ],
];

if (YII_ENV_DEV) {
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'giiant-model' => [
                'class' => 'fredyns\suite\giiant\model\Generator',
            ],
            'giiant-crud' => [
                'class' => 'fredyns\suite\giiant\crud\Generator',
            ],
        ],
    ];
}

return $config;
