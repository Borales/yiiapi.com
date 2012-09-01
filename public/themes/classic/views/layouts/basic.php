<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div id="navigation"><div id="loader">&nbsp;</div></div>

<div id="header">
    <h2>Yii API Documentation - For Version <?=Yii::getVersion()?></h2>
</div>

<div id="content">
    <?=$content?>

    <div class="clear"></div>
    <div id="footer">
        <?=Yii::powered();?>
    </div>

</div>
<?php $this->endContent(); ?>