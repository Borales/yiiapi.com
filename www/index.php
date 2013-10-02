<?php
require('./../app/lib/vendor/autoload.php');
Yiinitializr\Helpers\Initializer::create('./../app', 'main', array('common', 'env', 'local'))->run();