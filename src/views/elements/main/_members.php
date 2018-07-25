<?php

/**
 * Podium Module
 * Yii 2 Forum Module
 * @author Paweł Bizley Brzozowski <pawel@positive.codes>
 * @since 0.1
 */

use bizley\podium\helpers\Helper;
use bizley\podium\models\Activity;

$lastActive = Activity::lastActive();
$lastActiveMembers=!empty($lastActive['members']) ? $lastActive['members'] : 0;

?>
<div class="panel panel-default">
    <div class="panel-body small">
        <p>
            <?= Yii::t('podium/view', '{n, plural, =1{# active user} other{# active users}} (in the past 15 minutes)', ['n' => \app\models\SiteUserActivity::getUserCount()]) ?><br>
            <?= Yii::t('podium/view', '{n, plural, =1{# member} other{# members}}', ['n' => $lastActiveMembers]) ?>,
            <?= Yii::t('podium/view', '{n, plural, =1{# guest} other{# guests}}', ['n' =>  \app\models\SiteUserActivity::getUserCount()-$lastActiveMembers]) ?>

        </p>
<?php if (!empty($lastActive['names'])): ?>
        <p>
<?php foreach ($lastActive['names'] as $id => $name): ?>
            <?= Helper::podiumUserTag($name['name'], $name['role'], $id, $name['slug']) ?>
<?php endforeach; ?>
        </p>
<?php endif; ?>
    </div>
    <div class="panel-footer small">
        <ul class="list-inline">
            <li><?= Yii::t('podium/view', 'Members') ?> <span class="badge"><?= Activity::totalMembers() ?></span></li>
            <li><?= Yii::t('podium/view', 'Threads') ?> <span class="badge"><?= Activity::totalThreads() ?></span></li>
            <li><?= Yii::t('podium/view', 'Posts') ?> <span class="badge"><?= Activity::totalPosts() ?></span></li>
        </ul>
    </div>
</div>
