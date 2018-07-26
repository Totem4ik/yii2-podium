<?php

namespace bizley\podium\assets;

use yii\web\AssetBundle;

/**
 * Podium Assets
 *
 * @author Paweł Bizley Brzozowski <pawel@positive.codes>
 * @since 0.1
 */
class PodiumAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@podium';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/podium.css',
		'css/forum-styles.css',
        'js/quill-emoji-master/dist/quill-emoji.css',


    ];
	public $publishOptions = [
        'forceCopy'=>true,
      ];

    /**
     * @inheritdoc
     */
    public $depends = [
        '\yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
