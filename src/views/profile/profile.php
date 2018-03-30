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
                        <?= Html::encode($model->email) ?>
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
                    <?php if (!empty(AppUser::getUserDepressionImageList($model->inherited_id))) : ?>
                        <hr/>
                        <div>
                            <p><b>Depression :</b></p>
                            <ul class="list-inline">
                                <?php
                                $moduleSessions = ModuleQuiz::getModuleQuiz(Module::DEPRESSION_MODULE, false);
                                for ($i = 0; $i < count($moduleSessions); $i++) :?>
                                    <li>
                                        <?php
                                        $logo = $i + 1;
                                        if ($i == 0 && $moduleSessions[$i]['passed'] == 0) {
                                            echo Html::a(Html::img(Client::LOGO_CUP_PATH. $logo . '-faded.png', ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px',  'rel' => 'myModalBox' . $i, 'alt' => 'My Logo']));
                                        }
                                        if ($i == 0 && $moduleSessions[$i]['passed'] == 1) {
                                            echo Html::a(Html::img(Client::LOGO_CUP_PATH . $logo . '.png', ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px',  'rel' => 'myModalBox' . $i]));
                                        }
                                        if ($moduleSessions[$i]['passed'] == 0 && $i != 0) {
                                            echo Html::a(Html::img(Client::LOGO_CUP_PATH . $logo . '-faded.png', ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'rel' => 'myModalBox' . $i, 'alt' => 'My Logo']));
                                        }
                                        if ($moduleSessions[$i]['passed'] == 1 && $i != 0) {
                                            echo Html::a(Html::img(Client::LOGO_CUP_PATH . $logo . '.png', ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px',  'rel' => 'myModalBox' . $i, 'alt' => 'My Logo']));
                                        }
                                        ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty(AppUser::getUserAnxietyImageList($model->inherited_id))) : ?>
                        <hr/>
                        <div>
                            <p><b>Anexiety:</b></p>
                            <ul class="list-inline">
                                <?php
                                $moduleSessions = ModuleQuiz::getModuleQuiz(Module::ANXIETY_MODULE, false);
                                for ($i = 0; $i < count($moduleSessions); $i++) :?>
                                    <li>
                                        <?php
                                        $logo = $i + 1;
                                        if ($i == 0 && $moduleSessions[$i]['passed'] == 0) {
                                            echo Html::a(Html::img(Client::LOGO_CUP_PATH_ANXIETY . $logo . '-faded.png', ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px',  'rel' => 'myModalBox' . $i, 'alt' => 'My Logo']));
                                        }
                                        if ($i == 0 && $moduleSessions[$i]['passed'] == 1) {
                                            echo Html::a(Html::img(Client::LOGO_CUP_PATH_ANXIETY . $logo . '.png', ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'rel' => 'myModalBox' . $i]));

                                        }
                                        if ($moduleSessions[$i]['passed'] == 0 && $i != 0) {
                                            echo Html::a(Html::img(Client::LOGO_CUP_PATH_ANXIETY . $logo . '-faded.png', ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'rel' => 'myModalBox' . $i, 'alt' => 'My Logo']));
                                        }
                                        if ($moduleSessions[$i]['passed'] == 1 && $i != 0) {
                                            echo Html::a(Html::img(Client::LOGO_CUP_PATH_ANXIETY . $logo . '.png', ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px','rel' => 'myModalBox' . $i, 'alt' => 'My Logo']));
                                        }
                                        ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($model->inherited_id) && !empty(AppUser::getUserMoreHelpImageList($model->inherited_id))) : ?>
                        <hr/>
                        <div>
                            <p><b>More Help :</b></p>
                            <ul class="list-inline">
                                <?php
                                $moduleSessions = ModuleQuiz::getModuleQuiz(Module::MORE_HELP_MODULE, false);
                                for ($i = 0; $i < count($moduleSessions); $i++) :?>
                                    <li>
                                        <?php
                                        $logo = $i + 1;
                                        if ($i == 0 && $moduleSessions[$i]['passed'] == 0) {
                                            echo Html::a(Html::img(Client::LOGO_CUP_PATH_MORE_HELP . $logo . '-faded.png', ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'class' => ['logocup'], 'rel' => 'myModalBox' . $i, 'alt' => 'My Logo']));
                                        }
                                        if ($i == 0 && $moduleSessions[$i]['passed'] == 1) {
                                            echo Html::a(Html::img(Client::LOGO_CUP_PATH_MORE_HELP . $logo . '.png', ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'class' => ['logocup'], 'rel' => 'myModalBox' . $i]));

                                        }
                                        if ($moduleSessions[$i]['passed'] == 0 && $i != 0) {
                                            echo Html::a(Html::img(Client::LOGO_CUP_PATH_MORE_HELP . $logo . '-faded.png', ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'class' => ['logocup'], 'rel' => 'myModalBox' . $i, 'alt' => 'My Logo']));
                                        }
                                        if ($moduleSessions[$i]['passed'] == 1 && $i != 0) {
                                            echo Html::a(Html::img(Client::LOGO_CUP_PATH_MORE_HELP . $logo . '.png', ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'class' => ['logocup'], 'rel' => 'myModalBox' . $i, 'alt' => 'My Logo']));
                                        }
                                        ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                            </ul>

                        </div>
                    <?php endif; ?>
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
