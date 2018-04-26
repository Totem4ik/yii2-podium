<?php

/**
 * Podium Module
 * Yii 2 Forum Module
 * @author Paweł Bizley Brzozowski <pawel@positive.codes>
 * @since 0.1
 */

use bizley\podium\Podium;

?>


<footer class="eh_footer">
    <div class="text-footer text-center">
        <p><?= Yii::t('common', 'Evolution Health is not a healthcare provider and does not provide medical advice, diagnosis, or treatment.
            If you are currently thinking about or planning to harm yourself or someone else please call 911 or go to
            the nearest hospital emergency room.') ?> </p>
    </div>
    <div class="text-footer text-center">
        <div class="row">
            <div class="col-sm-3">
                <?= HTML::a( Yii::t('common','CONTACT'),'http://www.evolutionhs.com/contact.html',["target"=>"_blank"])?>
            </div>
            <div class="col-sm-3">
                <?= Yii::t('common','ABOUT')?>
            </div>
            <div class="col-sm-3">
                <?= Yii::t('common','THERMS OF USE')?>
            </div>
            <div class="col-sm-3">
                <?= Yii::t('common','PRIVACY')?>
            </div>
        </div>
    </div>
    <div class="container text-center">
        <p>© <?= Yii::t('common', 'Copyright');
            echo date('Y') ?> <a href="http://www.evolutionhs.com" target="_blank">Evolution Health
                Systems</a>.
            <?= Yii::t('common','All Rights Reserved.')?>All Rights Reserved.</p>
    </div>
</footer>
