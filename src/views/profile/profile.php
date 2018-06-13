<?php

/**
 * Podium Module
 * Yii 2 Forum Module
 * @author Paweł Bizley Brzozowski <pawel@positive.codes>
 * @since 0.1
 */

use bizley\podium\helpers\Helper;
use bizley\podium\Podium;
use bizley\podium\widgets\Avatar;
use yii\helpers\Html;
use yii\helpers\Url;
use \app\models\User as AppUser;
use bizley\podium\models\User;
use app\models\ModuleQuiz;
use app\models\Client;
use app\models\Module;

$this->title = Yii::t('podium/view', 'My Profile');
$this->params['breadcrumbs'][] = $this->title;
$uid = $model->mainUser->parent_id ?: $model->mainUser->id;
?>
<div class="row">
    <div class="col-md-3 col-sm-4">
        <?= $this->render('/elements/profile/_navbar', ['active' => 'profile']) ?>
    </div>
    <div class="col-md-6 col-sm-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <h2>
                    <?= Html::encode($model->podiumName) ?>
                    <small>
                        <div class="hide"><?= Html::encode($model->email) ?></div>
                        <?= Helper::roleLabel($model->role) ?>
                    </small>
                </h2>
                <br>

                <p><?= Yii::t('podium/view', 'Member since {date}', ['date' => Podium::getInstance()->formatter->asDatetime($model->created_at, 'long')]) ?> (<?= Podium::getInstance()->formatter->asRelativeTime($model->created_at) ?>)</p>
                <p>
                    <a href="<?= Url::to(['members/threads', 'id' => $model->id, 'slug' => $model->podiumSlug]) ?>" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> <?= Yii::t('podium/view', 'Show all threads started by me') ?></a>
                    <a href="<?= Url::to(['members/posts', 'id' => $model->id, 'slug' => $model->podiumSlug]) ?>" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> <?= Yii::t('podium/view', 'Show all posts created by me') ?></a>
                </p>
                <?php if ($model->role != User::ROLE_ADMIN): ?>
                    <!--                    --><?php //if (!empty(AppUser::getUserDepressionImageList($model->inherited_id))) : ?>

                    <div>
                        <p class="module-name-forum"><b>Depression:</b></p>
                        <ul class="list-inline">
                            <?php
                            $moduleSessions = ModuleQuiz::getModuleQuiz(Module::DEPRESSION_MODULE, false);
                            $imageCupList = \app\models\Trophy::getMainCupByType(\app\models\Trophy::TYPE_DEPRESSION, $uid);
                            for ($i = 0; $i < count($moduleSessions); $i++) :?>
                                <li>
                                    <?php
                                    $logo = $i + 1;
                                    if ($i == 0 && $moduleSessions[$i]['passed'] == 0) {
                                        echo Html::a(Html::img(Client::LOGO_CUP_PATH . $logo . '-faded.png', ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'alt' => 'My Logo']));
                                    }
                                    if ($i == 0 && $moduleSessions[$i]['passed'] == 1) {
                                        echo Html::a(Html::img($imageCupList[$i]['hide_img'], ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'alt' => 'My Logo']));
                                    }
                                    if ($moduleSessions[$i]['passed'] == 0 && $i != 0) {
                                        echo Html::a(Html::img($imageCupList[$i]['hide_img'], ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'alt' => 'My Logo']));
                                    }
                                    if ($moduleSessions[$i]['passed'] == 1 && $i != 0) {
                                        echo Html::a(Html::img($imageCupList[$i]['active_img'], ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'alt' => 'My Logo']));
                                    }
                                    ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                    <!--                    --><?php //endif; ?>
                    <!--                    --><?php //if (!empty(AppUser::getUserAnxietyImageList($model->inherited_id))) : ?>

                    <div>
                        <p class="module-name-forum"><b>Anexiety:</b></p>
                        <ul class="list-inline">
                            <?php
                            $moduleSessions = ModuleQuiz::getModuleQuiz(Module::ANXIETY_MODULE, false);
                            $imageCupListAn = \app\models\Trophy::getMainCupByType(\app\models\Trophy::TYPE_ANXIETY, $uid);
                            for ($i = 0; $i < count($moduleSessions); $i++) :?>
                                <li>
                                    <?php
                                    $logo = $i + 1;
                                    if ($i == 0 && $moduleSessions[$i]['passed'] == 0) {
                                        echo Html::a(Html::img($imageCupListAn[$i]['hide_img'], ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'alt' => 'My Logo']));
                                    }
                                    if ($i == 0 && $moduleSessions[$i]['passed'] == 1) {
                                        echo Html::a(Html::img($imageCupListAn[$i]['active_img'], ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px']));

                                    }
                                    if ($moduleSessions[$i]['passed'] == 0 && $i != 0) {
                                        echo Html::a(Html::img($imageCupListAn[$i]['hide_img'], ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'alt' => 'My Logo']));
                                    }
                                    if ($moduleSessions[$i]['passed'] == 1 && $i != 0) {
                                        echo Html::a(Html::img($imageCupListAn[$i]['active_img'], ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'alt' => 'My Logo']));
                                    }
                                    ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                        </ul>

                    </div>
                    <!--                    --><?php //endif; ?>
                    <!--                    --><?php //if (isset($model->inherited_id) && !empty(AppUser::getUserMoreHelpImageList($model->inherited_id))) : ?>
                    <hr/>
                    <div>
                        <p class="module-name-forum"><b>More Help:</b></p>
                        <ul class="list-inline">
                            <?php
                            $moduleSessions = ModuleQuiz::getModuleQuiz(Module::MORE_HELP_MODULE, false);
                            $imageCupListMH = \app\models\Trophy::getMainCupByType(\app\models\Trophy::TYPE_ANXIETY, $uid);
                            for ($i = 0; $i < count($moduleSessions); $i++) :?>
                                <li>
                                    <?php
                                    $logo = $i + 1;
                                    if ($i == 0 && $moduleSessions[$i]['passed'] == 0) {
                                        echo Html::a(Html::img($imageCupListMH[$i]['hide_img'], ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'alt' => 'My Logo']));
                                    }
                                    if ($i == 0 && $moduleSessions[$i]['passed'] == 1) {
                                        echo Html::a(Html::img($imageCupListMH[$i]['active_img'], ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px']));

                                    }
                                    if ($moduleSessions[$i]['passed'] == 0 && $i != 0) {
                                        echo Html::a(Html::img($imageCupListMH[$i]['hide_img'], ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'alt' => 'My Logo']));
                                    }
                                    if ($moduleSessions[$i]['passed'] == 1 && $i != 0) {
                                        echo Html::a(Html::img($imageCupListMH[$i]['active_img'], ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'alt' => 'My Logo']));
                                    }
                                    ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>

                    </div>
                    <!--                    --><?php //endif; ?>
                <?php endif; ?>
            </div>
            <div class="panel-footer">
                <ul class="list-inline">
                    <li><?= Yii::t('podium/view', 'Threads') ?> <span class="badge"><?= $model->threadsCount ?></span></li>
                    <li><?= Yii::t('podium/view', 'Posts') ?> <span class="badge"><?= $model->postsCount ?></span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-3 hidden-sm hidden-xs">
        <?= Avatar::widget([
            'author' => $model,
            'showName' => false
        ]) ?>
    </div>
</div><br>
