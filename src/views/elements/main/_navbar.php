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

<?php
                        $showCanadianEnglish = Client::showEnEnglishLanguageLink();
                        $showCanadianFrench = Client::showFranceLanguageLink();
                        $showUsEnglish = Client::showUsEnglishLanguageLink();
                        ?>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle flag-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true"
                               aria-expanded="false">

                                <?php if (Yii::$app->language === 'fr-FR' && $showCanadianFrench) : ?>
                                    <?= HTML::img(Client::LOGO_PATH_UPLOAD . 'flags/Canada.png', $options = ['alt' => 'logo']); ?>
                                    Français Canadien
                                    <span class="caret"></span>

                                <?php elseif ((Yii::$app->language === 'en-US' && $showUsEnglish)): ?>
                                    <?= HTML::img(Client::LOGO_PATH_UPLOAD . 'flags/United-States.png', $options = ['alt' => 'flag']); ?>
                                    US English
                                    <span class="caret"></span>

                                <?php else: ?>
                                    <?= HTML::img(Client::LOGO_PATH_UPLOAD . 'flags/Canada.png', $options = ['alt' => 'flag']); ?>
                                    Canadian English
                                    <span class="caret"></span>
                                <?php endif ?>

                            </a>


                            <ul class="dropdown-menu flag">
                                <?php if (Yii::$app->language === 'fr-FR') : ?>
                                    <li>
                                        <?php if ($showCanadianEnglish) : ?>
                                            <div class="flag-select-box">
                                                <?= HTML::img(Client::LOGO_PATH_UPLOAD . 'flags/24/Canada.png', $options = ['alt' => 'flag']);
                                                echo (Client::showEnEnglishLanguageLink())
                                                    ? Html::a('Canadian English', ['/clinic/language', 'id' => 'en-EN'])
                                                    : Html::a('Canadian English'); ?>
                                            </div>
                                        <?php endif; ?>


                                        <?php if ($showUsEnglish) : ?>
                                            <div class="flag-select-box">
                                                <?= HTML::img(Client::LOGO_PATH_UPLOAD . 'flags/24/United-States.png', $options = ['alt' => 'flag', 'style' => 'padding-bottom:5px']);
                                                echo Html::a('US ENGLISH', ['/clinic/language', 'id' => 'en-US']); ?>

                                            </div>
                                        <?php endif; ?>
                                    </li>

                                <?php elseif (Yii::$app->language === 'en-US'): ?>

                                    <li>
                                        <?php if ($showCanadianEnglish) : ?>
                                            <div class="flag-select-box">
                                                <?= HTML::img(Client::LOGO_PATH_UPLOAD . 'flags/24/Canada.png', $options = ['alt' => 'flag']);
                                                echo (Client::showEnEnglishLanguageLink())
                                                    ? Html::a('Canadian English', ['/clinic/language', 'id' => 'en-EN'])
                                                    : Html::a('Canadian English'); ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($showCanadianFrench) : ?>
                                            <div class="flag-select-box">
                                                <?= HTML::img(Client::LOGO_PATH_UPLOAD . 'flags/24/Canada.png', $options = ['alt' => 'flag']);
                                                echo (Client::showFranceLanguageLink())
                                                    ? Html::a('Français Canadien', ['/clinic/language', 'id' => 'fr-FR'])
                                                    : Html::a('Français Canadien'); ?>
                                            </div>

                                        <?php endif; ?>
                                    </li>

                                <?php else: ?>
                                    <li>
                                        <?php if ($showCanadianFrench) : ?>
                                            <div class="flag-select-box">
                                                <?= HTML::img(Client::LOGO_PATH_UPLOAD . 'flags/24/Canada.png', $options = ['alt' => 'flag']);
                                                echo (Client::showFranceLanguageLink())
                                                    ? Html::a('Français Canadien', ['/clinic/language', 'id' => 'fr-FR'])
                                                    : Html::a('Français Canadien'); ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($showUsEnglish) : ?>
                                            <div class="flag-select-box">
                                                <?= HTML::img(Client::LOGO_PATH_UPLOAD . 'flags/24/United-States.png', $options = ['alt' => 'flag']);
                                                echo (Client::showUsEnglishLanguageLink())
                                                    ? Html::a('US ENGLISH', ['/clinic/language', 'id' => 'en-US'])
                                                    : Html::a('US ENGLISH'); ?>
                                            </div>
                                        <?php endif; ?>

                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
