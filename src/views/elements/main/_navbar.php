<?php

/**
 * Podium Module
 * Yii 2 Forum Module
 * @author Paweł Bizley Brzozowski <pawel@positive.codes>
 * @since 0.1
 */

use bizley\podium\models\User;
use bizley\podium\Podium;
use bizley\podium\rbac\Rbac;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use app\models\Client;

?>
<header id="navigation">
    <div class="navbar navbar-fixed-top" role="banner">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">
                    <?= HTML::img(Client::LOGO_PATH_UPLOAD . $_SESSION['logo'], $options = ['title' => 'Main Logo', 'class' => ['img-responsive']]); ?>
                </a>
            </div>
            <nav id="main-menu" class="navbar-collapse navbar-right collapse" aria-expanded="false">
                <ul class="nav navbar-nav">

                    <li class=""><?= Html::a('Home', ['/clinic/index'], ['class' => 'profile-link']) ?></li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-haspopup="true" aria-expanded="false">Community <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><?= Html::a('Depression', ['/community/home']) ?></li>
                            <li><?= Html::a('Anxiety', ['/community/home']) ?></li>
                            <li><?= Html::a('Helping Fellow Members', ['/community/help']) ?></li>
                        </ul>
                    </li>


                    <li class=""><?= Html::a('Members', ['/community/members'], ['class' => 'profile-link']) ?></li>
                    <?php if (!Yii::$app->user->isGuest) {
                        $podiumUser = User::findMe();
                        $messageCount = $podiumUser->newMessagesCount;
                        $subscriptionCount = $podiumUser->subscriptionsCount;


                        if (User::can(Rbac::ROLE_ADMIN)) { ?>
                            <li class=""><?= Html::a('Administration', ['admin/index'], ['class' => 'profile-link'])
                                ?></li>
                        <?php } ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true"
                               aria-expanded="false">Community Settings/<?php echo Yii::$app->user->identity->username ?> <span
                                        class="caret"></span></a>
                            <?php if ($subscriptionCount > 0){ ?>
                            <span class="badge">
                                         <?php echo $subscriptionCount;?>
                                    </span>
                            <?php } ?></a>
                            <ul class="dropdown-menu">
                                <li><?= Html::a('Profile', ['/community/profile']) ?></li>
                                <li >
                                    <?= Html::beginForm(['/site/logout'], 'post') ?>
                                    <?= Html::submitButton(
                                        Yii::t('common', 'LOGOUT / ( ') . Yii::$app->user->identity->username . ' )',
                                        ['class' => 'btn btn-link logout']
                                    ); ?>
                                    <?= Html::endForm() ?>
                                </li>
<!--                                <li>--><?//= Html::a('Account Details', ['profile/details']) ?><!--</li>-->
                                <li><?= Html::a('Forum Details', ['profile/forum']) ?></li>
                                <li><?= Html::a('Subscriptions', ['profile/subscriptions']) ?></li>

                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">Messages <span class="caret"></span>
                                <?php if ($messageCount > 0) { ?>
                                    <span class="badge">
                                             <?php echo $messageCount; ?>
                                        </span>
                                <?php } ?></a>

                            <ul class="dropdown-menu">
                                <li><?= Html::a('Inbox', ['messages/inbox']) ?></li>
                                <li><?= Html::a('Sent', ['messages/sent']) ?></li>
                                <li><?= Html::a('New Message', ['messages/new']) ?></li>
                            </ul>
                        </li>
                        <li class=""><?= Html::a('About', ['/clinic/about'], ['class' => 'profile-link']) ?></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <span class="flag-icon"></span>
                                English
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <?= Html::a('<span class="profile-link "></span> english', "#", ['language' => 'en']
                                    ); ?>
                                </li>
                                <li>
                                    <a href="#"><span class="profile-link"></span> francaise</a>
                                </li>
                            </ul>
                        </li>
                    <?php } ?>


                </ul>
            </nav>
        </div>
    </div>
</header>
