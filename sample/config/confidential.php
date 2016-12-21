<?php
/**
 * contain secret information such as password & API secret
 * should be ignored by GIT
 */
return [
    'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        'viewPath' => '@app/mail',
        // send all mails to a file by default. You have to set
        // 'useFileTransport' to false and configure a transport
        // for the mailer to send real emails.
        'useFileTransport' => FALSE,
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'domain.tld', // e.g. smtp.mandrillapp.com or smtp.gmail.com
            'username' => 'user@domain.tld',
            'password' => '__your_password_here___',
            'port' => '587', // Port 587/25 is a very common port too
            'encryption' => 'tls', // It is often used, check your provider or mail server specs
        ],
    ],
];
