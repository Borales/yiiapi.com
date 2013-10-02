<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="language" content="en"/>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
	<title><?= CHtml::encode($this->pageTitle); ?></title>
	<script type="text/javascript">
		var siteName = '<?=Yii::app()->name?>';
	</script>
	<?php if (!YII_DEBUG) { ?>
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-8920748-6']);
			_gaq.push(['_trackPageview']);

			(function () {
				var ga = document.createElement('script');
				ga.type = 'text/javascript';
				ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(ga, s);
			})();
		</script>
	<?php } ?>
</head>
<body>
	<?php echo $content; ?>
</body>
</html>