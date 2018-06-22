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
                    <?= HTML::img(Client::LOGO_PATH_UPLOAD .Yii::$app->site->logo, $options = ['title' => 'Main Logo', 'class' => ['img-responsive']]); ?>
                </a>
            </div>
            <nav id="main-menu" class="navbar-collapse navbar-right collapse" aria-expanded="false">
                <ul class="nav navbar-nav">

                    <li class=""><?= Html::a(Yii::t('podium/view', 'Home'), ['/clinic/index'], ['class' => 'profile-link']) ?></li>
                    <li><?= Html::a(Yii::t('podium/view', 'Profile'), ['/community/profile']) ?></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-haspopup="true" aria-expanded="false"><?php echo(Yii::t('podium/view', 'Community')) ?><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><?= Html::a(Yii::t('podium/view', 'Anxiety'), ['/community/category/3/anxiety']) ?></li>
                            <li><?= Html::a(Yii::t('podium/view', 'Depression'), ['/community/category/4/depression']) ?></li>
                            <li><?= Html::a(Yii::t('podium/view', 'Helping Fellow Members'), ['/community/help']) ?></li>
                            <li class=""><?= Html::a(Yii::t('podium/view', 'Members'), ['/community/members'], ['class' => 'profile-link']) ?></li>
                            <li><?= Html::a(Yii::t('podium/view', 'Community Settings'), ['profile/forum']) ?></li>
                            <li><?= Html::a(Yii::t('podium/view', 'Messages'), ['messages/inbox']) ?></li>

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
                                    <?= Html::a(Yii::t('common', 'Login'), ['/clinic/login']) ?>
                                </li>
                                <?php $domain = $_SERVER['HTTP_HOST'];
                                if ($domain != 'homewood.evolutionhealth.care' && $domain != 'www.homewood.evolutionhealth.care') { ?>
                                    <li>
                                        <?= Html::a(Yii::t('podium/view', 'Signup'), ['/clinic/signup']) ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php }
                    else { ?>
                    <li class="eh_input_btn_in_nav_box" data-inline="<?php echo Yii::$app->user->identity->username?>">
                        <?= Html::beginForm(['/site/logout'], 'post') ?>
                        <?= Html::submitButton(
                            Yii::t('podium/view', 'Logout / ') . mb_strimwidth(Yii::$app->user->identity->username,0,8,"...") ,
                            ['class' => 'btn btn-link logout']
                        ); ?>
                        <?= Html::submitButton(
                            Yii::t('podium/view', 'Logout / (') . Yii::$app->user->identity->username . ' )',
                            ['class' => 'btn btn-link logout logout_hidden_hover']
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

                            <?php if (Yii::$app->language === 'fr-FR') : ?>
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
                </ul>
            </nav>
        </div>
    </div>
</header>
