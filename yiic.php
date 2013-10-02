<?php
require('./app/lib/vendor/autoload.php');
Yiinitializr\Helpers\Initializer::create('./app', 'console', array('common', 'env', 'local'))->run();
