<?php

/**
 * Podium Module
 * Yii 2 Forum Module
 * @author Paweł Bizley Brzozowski <pawel@positive.codes>
 * @since 0.1
 */

use bizley\podium\assets\PodiumAsset;
use bizley\podium\helpers\Helper;
use bizley\podium\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Client;
use app\models\Font;
use app\models\Theme;
use app\components\CheckAccessClient;

PodiumAsset::register($this);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Url::to(["/uploads/favicon.ico"])]);
$this->beginPage();
$this->title = Yii::t('common', 'Are You Struggling with Depression or Anxiety?');


$model=Theme::find()->select('font_id')->where(['client_id'=>Yii::$app->site->id])->one();
$font=Font::findOne($model->font_id);


$lastActive = \bizley\podium\models\Activity::lastActive();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=<?php echo $font['name']?>:400,400i,700,700i" rel="stylesheet">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode(Helper::title($this->title)) ?></title>
    <?php $this->head() ?>

    <style>
        body {
            font-family: <?php echo $font['name']?>, sans-serif !important;
        }
    </style>
</head>

<body>

<div class="eh_top_slider eh_clear_top_slider">
    <div class="schedule-tab">
        <div class="tab-list text-center">
            <?php if(Yii::$app->session->getFlash('loginMessage')) : ?>
                <div class="text_without_login"> <?= Yii::$app->session->getFlash('loginMessage') ?></div>
            <?php endif;?>
            <h3 class="eh_subtitle eh_subtitle_forum"><?= Html::encode($this->title) ?></h3>

            <p class="eh_top_slider_bigtitle"><?php echo Yii::t('common', 'Start to feel like yourself again.')?></p>
            <div class="eh_top_slider_bigtitle_container">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <p>
                                <?php echo Yii::t('common', 'This free, interactive online program has helped thousands of people like you to feel more optimistic and at peace.')?>
                            </p>
                            <p>
                                <?php echo Yii::t('common', 'The program is divided into sessions that you can work through at your own pace, with a private community of other members who are here to support you in your journey.')?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->beginBody() ?>

<?= $this->render('/elements/main/_navbar') ?>

<div class="eh_top_slider_text_box">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <p>
                    There are <?= (Yii::$app->session->get('activity')) ? Yii::$app->session->get('activity') : $lastActive['count'] ?> guests browsing.
                    Browse through <?= \bizley\podium\models\Post::getTotalPosts(); ?> posts in <?= \bizley\podium\models\Thread::getTotalThreads(); ?> topics.
                </p>
                <p>
                    Please welcome our newest members:
                    <?= Client::getMembersLink() ?>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="eh_content_wrapper">
    <div class="eh_only_middle-content middle-content eh_middle_cont_top_padding">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <?= $this->render('/elements/main/_breadcrumbs') ?>
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="eh_footer">
    <div class="text-footer text-center">
        <p><?= Yii::t('common', 'Evolution Health is not a healthcare provider and does not provide medical advice, diagnosis, or treatment.
            If you are currently thinking about or planning to harm yourself or someone else please call 911 or go to
            the nearest hospital emergency room.') ?> </p>
    </div>
    <div class="text-footer text-center">
        <div class="row">
            <div class="col-sm-3">
                <?= HTML::a(Yii::t('common', 'CONTACT'), 'http://EvolutionHealth.Systems', ["target" => "_blank"]) ?>
            </div>
            <div class="col-sm-3">
                <?= HTML::a(Yii::t('common','ABOUT'),\yii\helpers\Url::to(['/about']))?>
            </div>
            <div class="col-sm-3">
                <?= HTML::a( Yii::t('common','TERMS OF USE'),\yii\helpers\Url::to(['/site/terms']))?>
            </div>
            <div class="col-sm-3">
                <?= HTML::a( Yii::t('common','PRIVACY'),\yii\helpers\Url::to(['/site/privacy']))?>
            </div>
        </div>
    </div>
    <div class="container text-center">
        <p>© <?= Yii::t('common', 'Copyright');
            echo date('Y') ?> <a href="http://www.evolutionhs.com" target="_blank">Evolution Health
                Systems</a>.
            <?= Yii::t('common','All Rights Reserved.')?></p>
    </div>
</footer>
<?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>
