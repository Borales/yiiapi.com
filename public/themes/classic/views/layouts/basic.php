<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div id="navigation"><div id="loader">&nbsp;</div></div>

<div id="header">
    <h2>Yii API Documentation - For Version <?=Yii::getVersion()?></h2>
    <a class="fork-me" target="_blank" href="https://github.com/Borales/yii-searchable-api-doc">
        <img style="position: absolute; top: 3px; right: 16px; border: 0;" src="/images/github.png" alt="Fork me on GitHub">
    </a>
</div>

<div id="content">
    <?=$content?>

    <div class="clear"></div>
    <div id="footer">
        <?=Yii::powered();?>
    </div>

</div>
<?php $this->endContent(); ?>