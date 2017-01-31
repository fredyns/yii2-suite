<?php
/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \dektrium\user\models\Profile $profile
 */
$this->title                   = Html::encode($profile->user->username);
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">

        <?php if (!empty($profile->picture_id)): ?>
            <div class="col-sm-6 col-md-4">
                <h4>
                    <?=
                    Html::img(
                        ['/file', 'id' => $profile->picture_id],
                        [
                        'class' => 'img-rounded img-responsive',
                        'alt'   => $profile->user->username,
                        ]
                    )
                    ?>
                </h4>
            </div>
        <?php endif; ?>

        <div class="col-sm-6 col-md-8">

            <h4>
                <?= empty($profile->name) ? Html::encode($profile->user->username) : Html::encode($profile->name); ?>
            </h4>

            <ul style="padding: 0; list-style: none outside none;">

                <?php if (!empty($profile->location)): ?>
                    <li><i class="glyphicon glyphicon-map-marker text-muted"></i> <?= Html::encode($profile->location) ?></li>
                <?php endif; ?>

                <?php if (!empty($profile->timezone)): ?>
                    <li><i class="glyphicon glyphicon-time text-muted"></i> Timezone: <?= Html::encode($profile->timezone) ?></li>
                <?php endif; ?>

                <?php if (!empty($profile->website)): ?>
                    <li>
                        <i class="glyphicon glyphicon-globe text-muted"></i>
                        <?=
                        Html::a(Html::encode($profile->website), Html::encode($profile->website))
                        ?>
                    </li>
                <?php endif; ?>

                <?php if (!empty($profile->public_email)): ?>
                    <li>
                        <i class="glyphicon glyphicon-envelope text-muted"></i>
                        <?=
                        Html::a(Html::encode($profile->user->email), 'mailto:'.Html::encode($profile->public_email))
                        ?>
                    </li>
                <?php endif; ?>

                <li>
                    <i class="glyphicon glyphicon-time text-muted"></i>
                    <?= Yii::t('user', 'Joined on {0, date}', $profile->user->created_at) ?>
                </li>

            </ul>

            <?php if (!empty($profile->bio)): ?>
                <p><?= Html::encode($profile->bio) ?></p>
            <?php endif; ?>

        </div>
    </div>
</div>

<br/>

<?php if ($profile->user_id == Yii::$app->user->id): ?>
    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?=
            Html::a(
                '<span class="glyphicon glyphicon-pencil"></span> Edit', ['/user/settings/profile'],
                ['class' => 'btn btn-info'])
            ?>
            <?=
            Html::a(
                '<span class="glyphicon glyphicon-lock"></span> Account', ['/user/settings/account'],
                ['class' => 'btn btn-info'])
            ?>
        </div>
    </div>
<?php endif; ?>
