<?php

/**
 * Podium Module
 * Yii 2 Forum Module
 * @author Paweł Bizley Brzozowski <pawel@positive.codes>
 * @since 0.1
 */

use bizley\podium\helpers\Helper;
use bizley\podium\models\User;
use bizley\podium\Podium;
use bizley\podium\widgets\Avatar;
use bizley\podium\widgets\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User as AppUser;
use app\models\ModuleQuiz;
use app\models\Client;
use app\models\Module;

$this->title = Yii::t('podium/view', 'Member View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('podium/view', 'Members List'), 'url' => ['members/index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("$('[data-toggle=\"tooltip\"]').tooltip();");
if (!Podium::getInstance()->user->isGuest) {
    $this->registerJs("$('#podiumModalIgnore').on('show.bs.modal', function(e) { var button = $(e.relatedTarget); $('#ignoreUrl').attr('href', button.data('url')); });");
    $this->registerJs("$('#podiumModalUnIgnore').on('show.bs.modal', function(e) { var button = $(e.relatedTarget); $('#unignoreUrl').attr('href', button.data('url')); });");
}

$loggedId = User::loggedId();
$ignored = $friend = false;
if (!Podium::getInstance()->user->isGuest) {
    $ignored = $model->isIgnoredBy($loggedId);
    $friend = $model->isBefriendedBy($loggedId);
}
$uid = $model->mainUser->parent_id ?: $model->mainUser->id;
?>
    <ul class="nav nav-tabs">
        <li role="presentation">
            <a href="<?= Url::to(['members/index']) ?>">
                <span class="glyphicon glyphicon-user"></span>
                <?= Yii::t('podium/view', 'Members List') ?>
            </a>
        </li>
        <li role="presentation">
            <a href="<?= Url::to(['members/mods']) ?>">
                <span class="glyphicon glyphicon-scissors"></span>
                <?= Yii::t('podium/view', 'Moderation Team') ?>
            </a>
        </li>
        <li role="presentation" class="active">
            <a href="#">
                <span class="glyphicon glyphicon-eye-open"></span>
                <?= Yii::t('podium/view', 'Member View') ?>
            </a>
        </li>
    </ul>
    <br>
    <div class="row">
        <div class="col-sm-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php if (!Podium::getInstance()->user->isGuest): ?>
                        <div class="pull-right">
                            <?php if ($model->id !== $loggedId): ?>
                                <a href="<?= Url::to(['messages/new', 'user' => $model->id]) ?>"
                                   class="btn btn-default btn-lg" data-toggle="tooltip" data-placement="top"
                                   title="<?= Yii::t('podium/view', 'Send Message') ?>"><span
                                            class="glyphicon glyphicon-envelope"></span></a>
                            <?php else: ?>
                                <a href="#" class="btn btn-lg disabled text-muted"><span
                                            class="glyphicon glyphicon-envelope"></span></a>
                            <?php endif; ?>
                            <?php if ($model->id !== $loggedId && $model->role !== User::ROLE_ADMIN): ?>
                                <?php if (!$friend): ?>
                                    <a href="<?= Url::to(['members/friend', 'id' => $model->id]) ?>"
                                       class="btn btn-success btn-lg" data-toggle="tooltip" data-placement="top"
                                       title="<?= Yii::t('podium/view', 'Add as a Friend') ?>"><span
                                                class="glyphicon glyphicon-plus-sign"></span></a>
                                <?php else: ?>
                                    <a href="<?= Url::to(['members/friend', 'id' => $model->id]) ?>"
                                       class="btn btn-warning btn-lg" data-toggle="tooltip" data-placement="top"
                                       title="<?= Yii::t('podium/view', 'Remove Friend') ?>"><span
                                                class="glyphicon glyphicon-minus-sign"></span></a>
                                <?php endif; ?>
                                <?php if (!$ignored): ?>
                                    <span data-toggle="modal" data-target="#podiumModalIgnore"
                                          data-url="<?= Url::to(['members/ignore', 'id' => $model->id]) ?>"><button
                                                class="btn btn-danger btn-lg" data-toggle="tooltip" data-placement="top"
                                                title="<?= Yii::t('podium/view', 'Ignore Member') ?>"><span
                                                    class="glyphicon glyphicon-ban-circle"></span></button></span>
                                <?php else: ?>
                                    <span data-toggle="modal" data-target="#podiumModalUnIgnore"
                                          data-url="<?= Url::to(['members/ignore', 'id' => $model->id]) ?>"><button
                                                class="btn btn-success btn-lg" data-toggle="tooltip"
                                                data-placement="top"
                                                title="<?= Yii::t('podium/view', 'Unignore Member') ?>"><span
                                                    class="glyphicon glyphicon-ok-circle"></span></button></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="#" class="btn btn-lg disabled text-muted"><span
                                            class="glyphicon glyphicon-ban-circle"></span></a>
                            <?php endif; ?>
                        </div>
                        <?php if ($ignored): ?>
                            <h4 class="text-danger"><?= Yii::t('podium/view', 'You are ignoring this user.') ?></h4>
                        <?php endif; ?>
                        <?php if ($friend): ?>
                            <h4 class="text-success"><?= Yii::t('podium/view', 'You are friends with this user.') ?></h4>
                        <?php endif; ?>
                    <?php endif; ?>
                    <h2>
                        <?= Html::encode($model->podiumName) ?>
                        <small><?= Helper::roleLabel($model->role) ?></small>

                    </h2>


                    <p><?= Yii::t('podium/view', 'Member since {date}', ['date' => Podium::getInstance()->formatter->asDatetime($model->created_at, 'long')]) ?>
                        (<?= Podium::getInstance()->formatter->asRelativeTime($model->created_at) ?>)

                        <span class="popup-medals" id="#start_modal1" style="margin-left:20px">
                            <span class="label label-info" data-toggle="tooltip" data-placement="top"
                                  title="<?= Yii::t('podium/view', 'Number of posts') ?>">
                                <?= $model->postsCount ?>
                             </span>
                            <?php
                            $imageTrophy = $model->getImageByPostCount();
                            $files = Client::getImagesFromTrophyFolder();
                            $countStar = 0;
                            if (!empty($imageTrophy)) {
                                $countStar = Client::getStars($imageTrophy);
                            }
                            if (isset($files)):
                                foreach ($files as $key => $file) :
                                    if ($file == '.' || $file == '..') {
                                        continue;
                                    }
                                    $countStar--;
                                    $path = Client::MEDALS_PATH . $file;
                                    if ($countStar >= 0) :?>
                                        <a href="#start_modal1"
                                           data-toggle="modal"><?php echo HTML::img($path, $options = ['title' => 'Main Logo', 'alt' => 'logo', 'width' => '20px', 'height' => '20px']); ?>
                                         </a>
                                    <?php else: ?>
                                        <a href="#start_modal1"
                                           data-toggle="modal"><?php echo HTML::img($path, $options = ['title' => 'Main Logo', 'class' => 'medal-opacity', 'alt' => 'logo', 'width' => '20px', 'height' => '20px']); ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </span>

                    </p>
                    <?php if ($model->status != User::STATUS_REGISTERED): ?>
                        <p>
                            <a href="<?= Url::to(['members/threads', 'id' => $model->id, 'slug' => $model->podiumSlug]) ?>"
                               class="btn btn-default"><span
                                        class="glyphicon glyphicon-search"></span> <?= Yii::t('podium/view', 'Find all threads started by {name}', ['name' => Html::encode($model->podiumName)]) ?>
                            </a>
                            <a href="<?= Url::to(['members/posts', 'id' => $model->id, 'slug' => $model->podiumSlug]) ?>"
                               class="btn btn-default"><span
                                        class="glyphicon glyphicon-search"></span> <?= Yii::t('podium/view', 'Find all posts created by {name}', ['name' => Html::encode($model->podiumName)]) ?>
                            </a>
                        </p>
                    <?php endif; ?>
                    <?php if ($model->role != User::ROLE_ADMIN): ?>
                    <div class="margin-bottom-trophies">
                        <div>
                            <p><b><?php echo Yii::t('podium/view', 'Depression');?></b>:</p>
                            <ul class="list-inline">
                                <?php
                                $moduleSessions = ModuleQuiz::getModuleQuiz(Module::DEPRESSION_MODULE, $model->inherited_id);
                                $imageCupList = \app\models\Trophy::getMainCupByType(\app\models\Trophy::TYPE_DEPRESSION, $uid);
                                for ($i = 0; $i < count($moduleSessions); $i++) :?>
                                    <li>
                                        <?php
                                        $logo = $i + 1;
                                        if ($i == 0 && $moduleSessions[$i]['passed'] == 0) {
                                            echo Html::a(Html::img($imageCupList[$i]['hide_img'], ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'alt' => 'My Logo']));
                                        }
                                        if ($i == 0 && $moduleSessions[$i]['passed'] == 1) {
                                            echo Html::a(Html::img($imageCupList[$i]['active_img'], ['title' => 'Main Logo', 'width' => '40px', 'height' => '60px', 'alt' => 'My Logo']));
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
                        <div>
                            <p><b><?php echo Yii::t('podium/view', 'Anxiety');?></b>:</p>
                            <ul class="list-inline">
                                <?php
                                $moduleSessions = ModuleQuiz::getModuleQuiz(Module::ANXIETY_MODULE, $model->inherited_id);
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
                        <div>
                            <p><b><?php echo Yii::t('podium/view', 'More Help');?></b>:</p>
                            <ul class="list-inline">
                                <?php
                                $moduleSessions = ModuleQuiz::getModuleQuiz(Module::MORE_HELP_MODULE, $model->inherited_id);
                                $imageCupListMH = \app\models\Trophy::getMainCupByType(\app\models\Trophy::TYPE_MORE_HELP, $uid);
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
                    </div>

                    <?php endif; ?>
                </div>
                <?php if ($model->role == User::ROLE_MODERATOR && !empty($model->mods)): ?>
                    <div class="panel-body">
                        <?= Yii::t('podium/view', 'Moderator of') ?>
                        <?php foreach ($model->mods as $mod): ?>
                            <?php if (!$mod->forum) continue; ?>
                            <a href="<?= Url::to(['forum/forum', 'cid' => $mod->forum->category_id, 'id' => $mod->forum->id, 'slug' => $mod->forum->slug]) ?>"
                               class="btn btn-default btn-xs"><?= Html::encode($mod->forum->name) ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="panel-footer">
                    <ul class="list-inline">
                        <li><?= Yii::t('podium/view', 'Threads') ?> <span
                                    class="badge"><?= $model->threadsCount ?></span></li>
                        <li><?= Yii::t('podium/view', 'Posts') ?> <span class="badge"><?= $model->postsCount ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-3 hidden-xs">
            <?= Avatar::widget([
                'author' => $model,
                'showName' => false
            ]) ?>
        </div>
    </div>
<?php if (!Podium::getInstance()->user->isGuest): ?>
    <?php Modal::begin([
        'id' => 'podiumModalIgnore',
        'header' => Yii::t('podium/view', 'Ignore user'),
        'footer' => Yii::t('podium/view', 'Ignore user'),
        'footerConfirmOptions' => ['class' => 'btn btn-danger', 'id' => 'ignoreUrl']
    ]) ?>
    <p><?= Yii::t('podium/view', 'Are you sure you want to ignore this user?') ?></p>
    <p><?= Yii::t('podium/view', 'The user will not be able to send you messages.') ?></p>
    <p>
        <strong><?= Yii::t('podium/view', 'You can always unignore the user if you change your mind later on.') ?></strong>
    </p>
    <?php Modal::end() ?>
    <?php Modal::begin([
        'id' => 'podiumModalUnIgnore',
        'header' => Yii::t('podium/view', 'Unignore user'),
        'footer' => Yii::t('podium/view', 'Unignore user'),
        'footerConfirmOptions' => ['class' => 'btn btn-success', 'id' => 'unignoreUrl']
    ]) ?>
    <p><?= Yii::t('podium/view', 'Are you sure you want to ignore this user?') ?></p>
    <p><?= Yii::t('podium/view', 'The user will not be able to send you messages.') ?></p>
    <p>
        <strong><?= Yii::t('podium/view', 'You can always unignore the user if you change your mind later on.') ?></strong>
    </p>
    <?php Modal::end() ?>
<?php endif; ?>
<div id="start_modal1" class="modal fade">
    <div class="modal-dialog modal-dialog-medals">
        <div class="modal-content">
            <!-- Заголовок модального окна -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?php echo HTML::img('/uploads/img-medals.jpg', $options = ['title' => 'Main Logo', 'alt' => 'logo']); ?></h4>
            </div>
        </div>
    </div>
</div>
