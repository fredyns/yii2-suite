<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?=
    Html::a('<span class="logo-mini">'.Yii::$app->params['initials'].'</span><span class="logo-lg">'.Yii::$app->name.'</span>',
        Yii::$app->homeUrl, ['class' => 'logo'])
    ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success">4</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 4 messages</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <!-- start message -->
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle"
                                                 alt="User Image"/>
                                        </div>
                                        <h4>
                                            Support Team
                                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="<?= $directoryAsset ?>/img/user3-128x128.jpg" class="img-circle"
                                                 alt="user image"/>
                                        </div>
                                        <h4>
                                            AdminLTE Design Team
                                            <small><i class="fa fa-clock-o"></i> 2 hours</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <!-- end message -->
                            </ul>
                        </li>
                        <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <div class="pull-left image img-circle" style="width: 25px; height: 25px; overflow: hidden">
                            <?php
                            $profile = Yii::$app->user->identity->profile;

                            if (!empty($profile->picture_id))
                            {
                                echo Html::img(
                                    ['/file', 'id' => $profile->picture_id],
                                    [
                                    'alt'   => $profile->user->username,
                                    'style' => 'max-length: 25px; max-width: 25px;',
                                    ]
                                );
                            }
                            else
                            {
                                echo Html::img(
                                    '@web/image/user-160.png',
                                    [
                                    'alt'   => "User Image",
                                    'style' => 'max-length: 25px; max-width: 25px;',
                                    ]
                                );
                            }
                            ?>
                        </div>
                        <span class="hidden-xs">
                            &nbsp; <?= Yii::$app->user->identity->username; ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <div class="image img-circle" style="width: 90px; height: 90px; overflow: hidden; margin: 0 auto;">
                                <?php
                                $profile = Yii::$app->user->identity->profile;

                                if (!empty($profile->picture_id))
                                {

                                    echo Html::img(
                                        ['/file', 'id' => $profile->picture_id],
                                        [
                                        'style' => 'max-length: 90px; max-width: 90px;',
                                        'alt'   => $profile->user->username,
                                        ]
                                    );
                                }
                                else
                                {
                                    echo Html::img(
                                        '@web/image/user-160.png',
                                        [
                                        'alt'   => "User Image",
                                        'style' => 'max-length: 90px; max-width: 90px;',
                                        ]
                                    );
                                }
                                ?>
                            </div>
                            <p>
                                <?php
                                $name = Yii::$app->user->identity->profile->name;
                                echo empty($name) ? Yii::$app->user->identity->username : $name;
                                ?>
                                <small>
                                    <?=
                                    Yii::t('user', 'Member Since {0, date}', $profile->user->created_at)
                                    ?>
                                </small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">Followers</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Sales</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Friends</a>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?=
                                Html::a(
                                    'Profile', ['/user/profile/show', 'id' => Yii::$app->user->id],
                                    ['class' => "btn btn-default btn-flat"]
                                );
                                ?>
                            </div>
                            <div class="pull-right">
                                <?=
                                Html::a(
                                    'Sign out', ['/user/security/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                )
                                ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less ->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
                <!-- User Account: style can be found in dropdown.less -->

            </ul>
        </div>
    </nav>
</header>
