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
                    <li><?= Html::a('Profile', ['/community/profile']) ?></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-haspopup="true" aria-expanded="false">Community <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><?= Html::a('Depression', ['/community/home']) ?></li>
                            <li><?= Html::a('Anxiety', ['/community/home']) ?></li>
                            <li><?= Html::a('Helping Fellow Members', ['/community/help']) ?></li>
                            <li class=""><?= Html::a(Yii::t('common', 'Members'), ['/community/members'], ['class' => 'profile-link']) ?></li>
                            <li><?= Html::a('Community Settings', ['community/profile/forum']) ?></li>
                            <li><?= Html::a('Messages', ['community/messages/inbox']) ?></li>

                        </ul>
                    </li>

                    <?php if (Yii::$app->user->isGuest) { ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <?php echo (Yii::$app->user->isGuest) ? Yii::t('common', 'Login') : 'Logout' ?><span
                                        class="caret"></span></a>

                            <ul class="dropdown-menu">
                                <li>
                                    <?= Html::a(Yii::t('common', 'Login'), ['clinic/login']) ?>
                                </li>
                                <?php $domain = $_SERVER['HTTP_HOST'];
                                if ($domain != 'homewood.evolutionhealth.care' && $domain != 'www.homewood.evolutionhealth.care') { ?>
                                    <li>
                                        <?= Html::a(Yii::t('common', 'Signup'), ['clinic/signup']) ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php }
                    else { ?>
                    <li class="eh_input_btn_in_nav_box">
                        <?= Html::beginForm(['/site/logout'], 'post') ?>
                        <?= Html::submitButton(
                            Yii::t('common', 'Logout / (') . Yii::$app->user->identity->username . ' )',
                            ['class' => 'btn btn-link logout']
                        ); ?>
                        <?= Html::endForm() ?>
                        <?php } ?>

                        <?php if (!Yii::$app->user->isGuest) {
                        $podiumUser = User::findMe();
                        $messageCount = $podiumUser->newMessagesCount;
                        $subscriptionCount = $podiumUser->subscriptionsCount;


                        if (User::can(Rbac::ROLE_ADMIN)) { ?>
                    <li class=""><?= Html::a('Administration', ['admin/index'], ['class' => 'profile-link'])
                        ?></li>
                <?php } ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            <?php if (Yii::$app->language === 'fr-FR') { ?>
                                <span class="flag-icon"></span>
                                Français
                                <span class="caret"></span>
                            <?php } else { ?>
                                <span class="flag-icon"></span>
                                English
                                <span class="caret"></span>
                            <?php } ?>
                        </a>
                        <ul class="dropdown-menu">

                            <?php if (Yii::$app->language === 'fr-FR') : ?>?
                                <li>
                                    <?= Html::a('ENGLISH', ['/clinic/language', 'id' => 'en-EN']); ?>
                                </li>
                            <?php else: ?>
                                <li>
                                    <?= Html::a('Français', ['/clinic/language', 'id' => 'fr-FR']); ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php } ?>


                </ul>
            </nav>
        </div>
    </div>
</header>
