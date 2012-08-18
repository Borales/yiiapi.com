<?php /* @var $this Controller */?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />
    <title><?=CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
<?=$content?>

<div class="clear"></div>

<div id="footer">
    <?=Yii::powered();?>
</div>

</body>
</html>