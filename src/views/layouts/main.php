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

PodiumAsset::register($this);
$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?= Html::csrfMetaTags() ?>
<title><?= Html::encode(Helper::title($this->title)) ?></title>
<?php $this->head() ?>
</head>
<body>

<div class="eh_top_slider eh_clear_top_slider">
    <div class="schedule-tab">
        <div class="tab-list text-center">
            <h3 class="eh_subtitle eh_subtitle_forum"><?= Html::encode($this->title) ?></h3>
        </div>
    </div>
</div>

<?php $this->beginBody() ?>
        <header id="navigation">
                <?= $this->render('/elements/main/_navbar') ?>
        </header>

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
    <?= $this->render('/elements/main/_footer') ?>
<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
