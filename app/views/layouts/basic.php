<?php /* @var $this EController|DocController */ ?>
<?php $this->beginContent('//layouts/main'); ?>
	<div id="navigation">
		<div id="loader">&nbsp;</div>
	</div>

	<div id="header">
		<h2>Yii API Documentation - For Version <?= Yii::getVersion() ?></h2>
		<a class="fork-me" target="_blank" href="https://github.com/Borales/yiiapi.com">
			<img style="position: absolute; top: 3px; right: 16px; border: 0;" src="/img/github.png" alt="Fork me on GitHub">
		</a>
	</div>

	<div id="content">
		<?php echo $content; ?>

		<div class="clear"></div>
		<div id="footer">
			<?php echo Yii::powered(); ?>
		</div>

	</div>
<?php $this->endContent(); ?>