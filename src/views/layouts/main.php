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

PodiumAsset::register($this);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Url::to(["/uploads/favicon.ico"])]);
$this->beginPage();
$this->title = 'Are you struggling with Depression or Anxiety?';

if (isset($_SESSION['clientId'])) {
    $model=Theme::find()->select('font_id')->where(['client_id'=>$_SESSION['clientId']])->one();
    $font=Font::findOne($model->font_id);
}

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
            <h3 class="eh_subtitle eh_subtitle_forum"><?= Html::encode($this->title) ?></h3>

            <p class="eh_top_slider_bigtitle">Start to feel like yourself again.</p>
            <div class="eh_top_slider_bigtitle_container">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <p>
                                This free, interactive online program has helped thousands of people like you to feel more optimistic and at peace.
                            </p>
                            <p>
                                The program is divided into sessions that you can work through at your own pace, with a private community of other members who are here to support you in your journey.
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
                    There are <?= $lastActive['count'] ?> guests browsing.
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
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="eh_footer">
    <div class="container text-center">
        <p>© Copyright <?php echo date('Y') ?> <a  href="http://www.evolutionhs.com" target="_blank">Evolution Health Systems</a>.
            All Rights Reserved.</p>
    </div>
</footer>
<?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>
