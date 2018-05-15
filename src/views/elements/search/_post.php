<?php

/**
 * Podium Module
 * Yii 2 Forum Module
 * @author Paweł Bizley Brzozowski <pawel@positive.codes>
 * @since 0.1
 */

use bizley\podium\Podium;
use bizley\podium\widgets\Avatar;
use yii\helpers\Html;
use yii\helpers\Url;

$this->registerJs("$('[data-toggle=\"tooltip\"]').tooltip();");

if ($type == 'topics') {
    $model = $model->postData;
}

$content = $model->parsedContent;
$thread  = Html::encode($model->thread->name);

if ($type == 'topics') {
    foreach ($words as $word) {
        $thread = preg_replace("/$word/", '<mark>' . $word . '</mark>', $thread);
    }
}
else {
    foreach ($words as $word) {
        $content = preg_replace("/$word/", '<mark>' . $word . '</mark>', $content);
    }
}

?>
<div class="row" id="post<?= $model->id ?>">
    <div class="col-sm-2 text-center" id="postAvatar<?= $model->id ?>">
        <?= Avatar::widget(['author' => $model->author, 'showName' => false]) ?>
    </div>
    <div class="col-sm-10" id="postContent<?= $model->id ?>">
        <div class="popover right podium">
            <div class="arrow"></div>
            <div class="popover-title">
                <small class="pull-right">
                    <span data-toggle="tooltip" data-placement="top" title="<?= Podium::getInstance()->formatter->asDatetime($model->created_at, 'long') ?>"><?= Podium::getInstance()->formatter->asRelativeTime($model->created_at) ?></span>
                    <?php if ($model->edited && $model->edited_at): ?>
                        <em>(<?= Yii::t('podium/view', 'Edited') ?> <span data-toggle="tooltip" data-placement="top" title="<?= Podium::getInstance()->formatter->asDatetime($model->edited_at, 'long') ?>"><?= Podium::getInstance()->formatter->asRelativeTime($model->edited_at) ?>)</span></em>
                    <?php endif; ?>
                </small>
                <?= $model->author->podiumTag ?>
                <small>
                    <span class="label label-info" data-toggle="tooltip" data-placement="top" title="<?= Yii::t('podium/view', 'Number of posts') ?>"><?= $model->author->postsCount ?></span>
                </small>
            </div>
            <div class="popover-content podium-content">
                <a href="<?= Url::to(['forum/thread', 'cid' => $model->thread->category_id, 'fid' => $model->thread->forum_id, 'id' => $model->thread_id, 'slug' => $model->thread->slug]) ?>"><span class="glyphicon glyphicon-comment"></span> <?= $thread ?></a><br><br>
                <?= $content ?>
                <div class="podium-action-bar">
                    <a href="<?= Url::to(['forum/show', 'id' => $model->id]) ?>" class="btn btn-default btn-xs" data-pjax="0" data-toggle="tooltip" data-placement="top" title="<?= Yii::t('podium/view', 'Direct link to this post') ?>"><span class="glyphicon glyphicon-link"></span></a>
                </div>
            </div>
        </div>
    </div>
</div>
