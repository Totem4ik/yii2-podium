<?php

/**
 * Podium Module
 * Yii 2 Forum Module
 * @author Paweł Bizley Brzozowski <pawel@positive.codes>
 * @since 0.1
 */

use bizley\podium\assets\HighlightAsset;
use bizley\podium\models\User;
use bizley\podium\Podium;
use bizley\podium\rbac\Rbac;
use bizley\podium\widgets\Avatar;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ModuleQuiz;
use app\models\Module;
use app\models\Client;

$this->registerJs("$('[data-toggle=\"tooltip\"]').tooltip();");
$this->registerJs(<<<JS
$('.podium-quote').click(function(e) {
    e.preventDefault();
    var selection = '';
    if (window.getSelection) {
        selection = window.getSelection().toString();
    } else if (document.selection && document.selection.type != 'Control') {
        selection = document.selection.createRange().text;
    }
    $(this).parent().find('.quote-selection').val(selection);
    $(this).parent().find('.quick-quote-form').submit();
});
JS
);
$urlThumb = Url::to(['forum/thumb']);
$this->registerJs(<<<JS
function thumbVote(link, type, thumb, removeClass, addClass) {
    var parent = link.closest('.popover.podium');
    link.removeClass(removeClass).addClass('disabled text-muted');
    $.post('$urlThumb', {thumb: type, post: link.data('post-id')}, null, 'json')
        .fail(function() {
            console.log('Thumb ' + type + ' error!');
        })
        .done(function(data) {
            parent.find('.podium-thumb-info').html(data.msg);
            if (data.error == 0) {
                var cl = 'default';
                if (data.summ > 0) {
                    cl = 'success';
                } else if (data.summ < 0) {
                    cl = 'danger';
                }
                parent.find('.podium-rating').removeClass('label-default label-danger label-success').addClass('label-' + cl).text(data.summ);
                parent.find('.podium-rating-details').text(data.likes + ' / ' + data.dislikes);
            }
            parent.find(thumb).removeClass('disabled text-muted').addClass(addClass);
        });
}
JS
);
$this->registerJs("$('.podium-thumb-up').click(function(e) { e.preventDefault(); thumbVote($(this), 'up', '.podium-thumb-down', 'btn-success', 'btn-danger'); });");
$this->registerJs("$('.podium-thumb-down').click(function(e) { e.preventDefault(); thumbVote($(this), 'down', '.podium-thumb-up', 'btn-danger', 'btn-success'); });");
$this->registerJs("$('.podium-rating').click(function (e) { e.preventDefault(); $('.podium-rating-details').removeClass('hidden'); });");
$this->registerJs("$('.podium-rating-details').click(function (e) { e.preventDefault(); $('.podium-rating-details').addClass('hidden'); });");

if (!Podium::getInstance()->user->isGuest) {
    $model->markSeen();
}

$rating = $model->likes - $model->dislikes;
$ratingClass = 'default';
if ($rating > 0) {
    $ratingClass = 'success';
    $rating = '+' . $rating;
} elseif ($rating < 0) {
    $ratingClass = 'danger';
}

$loggedId = User::loggedId();
$uid = $model->author->mainUser->parent_id ?: $model->author->mainUser->id;
if (strpos($model->content, '<pre class="ql-syntax">') !== false) {
    HighlightAsset::register($this);
}
?>
<div class="row podium-post" id="post<?= $model->id ?>">
    <div class="col-sm-2 podium-avatar" id="postAvatar<?= $model->id ?>">
        <?= Avatar::widget(['author' => $model->author, 'showName' => false]) ?>
        <ul class="list-inline small-trophies">
            <?php

            $client = User::findOne($model->author_id);
            $moduleSessions = ModuleQuiz::getModuleQuiz(Module::DEPRESSION_MODULE, $client->inherited_id);

            $imageCupList = \app\models\Trophy::getMainCupByType(\app\models\Trophy::TYPE_DEPRESSION, $uid);
            for ($i = 0; $i < count($moduleSessions); $i++) :?>
                <li>
                    <?php
                    $logo = $i + 1;
                    if ($i == 0 && $moduleSessions[$i]['passed'] == 0) {
                        echo Html::a(Html::img($imageCupList[$i]['hide_img'], ['title' => 'Main Logo', 'width' => '19px', 'height' => '19px', 'alt' => 'My Logo']));
                    }
                    if ($i == 0 && $moduleSessions[$i]['passed'] == 1) {
                        echo Html::a(Html::img($imageCupList[$i]['active_img'], ['title' => 'Main Logo', 'width' => '19px', 'height' => '19px', 'alt' => 'My Logo']));
                    }
                    if ($moduleSessions[$i]['passed'] == 0 && $i != 0) {
                        echo Html::a(Html::img($imageCupList[$i]['hide_img'], ['title' => 'Main Logo', 'width' => '19px', 'height' => '19px', 'alt' => 'My Logo']));
                    }
                    if ($moduleSessions[$i]['passed'] == 1 && $i != 0) {
                        echo Html::a(Html::img($imageCupList[$i]['active_img'], ['title' => 'Main Logo', 'width' => '19px', 'height' => '19px', 'alt' => 'My Logo']));
                    }
                    ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
        <ul class="list-inline small-trophies">
            <?php
            $moduleSessions = ModuleQuiz::getModuleQuiz(Module::ANXIETY_MODULE, $client->inherited_id);
            $imageCupListAn = \app\models\Trophy::getMainCupByType(\app\models\Trophy::TYPE_ANXIETY, $uid);
            for ($i = 0; $i < count($moduleSessions); $i++) :?>
                <li>
                    <?php
                    $logo = $i + 1;
                    if ($i == 0 && $moduleSessions[$i]['passed'] == 0) {
                        echo Html::a(Html::img($imageCupListAn[$i]['hide_img'], ['title' => 'Main Logo', 'width' => '19px', 'height' => '19px', 'alt' => 'My Logo']));
                    }
                    if ($i == 0 && $moduleSessions[$i]['passed'] == 1) {
                        echo Html::a(Html::img($imageCupListAn[$i]['active_img'], ['title' => 'Main Logo', 'width' => '19px', 'height' => '19px']));

                    }
                    if ($moduleSessions[$i]['passed'] == 0 && $i != 0) {
                        echo Html::a(Html::img($imageCupListAn[$i]['hide_img'], ['title' => 'Main Logo', 'width' => '19px', 'height' => '19px', 'alt' => 'My Logo']));
                    }
                    if ($moduleSessions[$i]['passed'] == 1 && $i != 0) {
                        echo Html::a(Html::img($imageCupListAn[$i]['active_img'], ['title' => 'Main Logo', 'width' => '19px', 'height' => '19px', 'alt' => 'My Logo']));
                    }
                    ?>
                    </a>
                </li>
            <?php endfor; ?>

        </ul>
        <ul class="list-inline small-trophies">
            <?php
            $moduleSessions = ModuleQuiz::getModuleQuiz(Module::MORE_HELP_MODULE, $client->inherited_id);
            $imageCupListMH = \app\models\Trophy::getMainCupByType(\app\models\Trophy::TYPE_MORE_HELP, $uid);
            for ($i = 0; $i < count($moduleSessions); $i++) :?>
                <li>
                    <?php
                    $logo = $i + 1;
                    if ($i == 0 && $moduleSessions[$i]['passed'] == 0) {
                        echo Html::a(Html::img($imageCupListMH[$i]['hide_img'], ['title' => 'Main Logo', 'width' => '19px', 'height' => '19px', 'alt' => 'My Logo']));
                    }
                    if ($i == 0 && $moduleSessions[$i]['passed'] == 1) {
                        echo Html::a(Html::img($imageCupListMH[$i]['active_img'], ['title' => 'Main Logo', 'width' => '19px', 'height' => '19px']));

                    }
                    if ($moduleSessions[$i]['passed'] == 0 && $i != 0) {
                        echo Html::a(Html::img($imageCupListMH[$i]['hide_img'], ['title' => 'Main Logo', 'width' => '19px', 'height' => '19px', 'alt' => 'My Logo']));
                    }
                    if ($moduleSessions[$i]['passed'] == 1 && $i != 0) {
                        echo Html::a(Html::img($imageCupListMH[$i]['active_img'], ['title' => 'Main Logo', 'width' => '19px', 'height' => '19px', 'alt' => 'My Logo']));
                    }
                    ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
    <div class="col-sm-10 podium-text" id="postContent<?= $model->id ?>">
        <div class="popover right podium">
            <div class="arrow"></div>
            <div class="popover-title">

                <small class="pull-right">
                    <span data-toggle="tooltip" data-placement="top"
                          title="<?= Podium::getInstance()->formatter->asDatetime($model->created_at, 'long') ?>">
                        <?= Podium::getInstance()->formatter->asRelativeTime($model->created_at) ?>
                    </span>
                    <?php if ($model->edited && $model->edited_at): ?>
                        <em>
                            (<?= Yii::t('podium/view', 'Edited') ?>
                            <span data-toggle="tooltip" data-placement="top"
                                  title="<?= Podium::getInstance()->formatter->asDatetime($model->edited_at, 'long') ?>">
                            <?= Podium::getInstance()->formatter->asRelativeTime($model->edited_at) ?>)
                        </span>
                        </em>
                    <?php endif; ?>
                    &mdash;
                    <span class="podium-rating label label-<?= $ratingClass ?>" data-toggle="tooltip"
                          data-placement="top" title="<?= Yii::t('podium/view', 'Rating') ?>">
                        <?= $rating ?>
                    </span>
                    <span class="podium-rating-details hidden label label-default">+<?= $model->likes ?>
                        / -<?= $model->dislikes ?></span>
                </small>
                <?= $model->author->podiumTag ?>
                <small>
                    <span class="label label-info" data-toggle="tooltip" data-placement="top"
                          title="<?= Yii::t('podium/view', 'Number of posts') ?>">
                        <?= $model->author->postsCount ?>
                    </span>
                </small>
                <span class="popup-medals" id="#start_modal1">
                <?php
                $imageTrophy = $model->author->getImageByPostCount();
//                var_dump($model);die;
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
            </div>
            <div class="popover-content podium-content">
                <?php if (isset($parent) && $parent): ?>
                    <a href="<?= Url::to(['forum/thread',
                        'cid' => $model->thread->category_id,
                        'fid' => $model->forum_id,
                        'id' => $model->thread_id,
                        'slug' => $model->thread->slug
                    ]) ?>"><span class="glyphicon glyphicon-comment"></span> <?= Html::encode($model->thread->name) ?>
                    </a><br><br>
                <?php endif; ?>
                <?= $model->parsedContent ?>
                <?php if (!empty($model->author->meta->signature)): ?>
                    <div class="podium-footer small text-muted">
                        <hr><?= $model->author->meta->parsedSignature ?>
                    </div>
                <?php endif; ?>
                <ul class="podium-action-bar list-inline">
                    <li><span class="podium-thumb-info"></span></li>
                    <?php if (!Podium::getInstance()->user->isGuest && $model->author_id != $loggedId): ?>
                        <li><?= Html::beginForm(['forum/post',
                                'cid' => $model->thread->category_id,
                                'fid' => $model->forum_id,
                                'tid' => $model->thread_id,
                                'pid' => $model->id
                            ], 'post', ['class' => 'quick-quote-form']) ?>
                            <?= Html::hiddenInput('quote', '', ['class' => 'quote-selection']); ?>
                            <?= Html::endForm(); ?>
                            <button
                                    class="btn btn-primary btn-xs podium-quote"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="<?= Yii::t('podium/view', 'Reply with quote') ?>">
                                <span class="glyphicon glyphicon-leaf"></span>
                            </button>
                        </li>
                    <?php endif; ?>
                    <?php if ($model->author_id == $loggedId || User::can(Rbac::PERM_UPDATE_POST, ['item' => $model->thread])): ?>
                        <li><a
                                    href="<?= Url::to(['forum/edit', 'cid' => $model->thread->category_id, 'fid' => $model->forum_id, 'tid' => $model->thread_id, 'pid' => $model->id]) ?>"
                                    class="btn btn-info btn-xs"
                                    data-pjax="0"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="<?= Yii::t('podium/view', 'Edit Post') ?>">
                                <span class="glyphicon glyphicon-edit"></span>
                            </a></li>
                    <?php endif; ?>
                    <li><a
                                href="<?= Url::to(['forum/show', 'id' => $model->id]) ?>"
                                class="btn btn-default btn-xs"
                                data-pjax="0"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="<?= Yii::t('podium/view', 'Direct link to this post') ?>">
                            <span class="glyphicon glyphicon-link"></span>
                        </a></li>
                    <?php if (!Podium::getInstance()->user->isGuest && $model->author_id != $loggedId): ?>
                        <?php if ($model->thumb && $model->thumb->thumb == 1): ?>
                            <li><a
                                        href="#"
                                        class="btn btn-xs disabled text-muted podium-thumb-up"
                                        data-post-id="<?= $model->id ?>"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="<?= Yii::t('podium/view', 'Thumb up') ?>">
                                    <span class="glyphicon glyphicon-thumbs-up"></span>
                                </a></li>
                        <?php else: ?>
                            <li><a
                                        href="#"
                                        class="btn btn-success btn-xs podium-thumb-up"
                                        data-post-id="<?= $model->id ?>"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="<?= Yii::t('podium/view', 'Thumb up') ?>">
                                    <span class="glyphicon glyphicon-thumbs-up"></span>
                                </a></li>
                        <?php endif; ?>

                        <li><a
                                    href="<?= Url::to(['forum/report', 'cid' => $model->thread->category_id, 'fid' => $model->forum_id, 'tid' => $model->thread_id, 'pid' => $model->id]) ?>"
                                    class="btn btn-warning btn-xs"
                                    data-pjax="0"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="<?= Yii::t('podium/view', 'Report post') ?>">
                                <span class="glyphicon glyphicon-flag"></span>
                            </a></li>
                    <?php endif; ?>
                    <?php if ($model->author_id == $loggedId || User::can(Rbac::PERM_DELETE_POST, ['item' => $model->thread])): ?>
                        <li><a
                                    href="<?= Url::to(['forum/deletepost', 'cid' => $model->thread->category_id, 'fid' => $model->forum_id, 'tid' => $model->thread_id, 'pid' => $model->id]) ?>"
                                    class="btn btn-danger btn-xs"
                                    data-pjax="0"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="<?= Yii::t('podium/view', 'Delete Post') ?>">
                                <span class="glyphicon glyphicon-trash"></span>
                            </a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
