<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/basic';

    public function beforeAction($action) {
        if( parent::beforeAction($action) ) {
            /* @var $cs CClientScript */
            $cs = Yii::app()->clientScript;
            /* @var $theme CTheme */
            $theme = Yii::app()->theme;
            $cs->registerPackage('jquery');
            $cs->registerScriptFile( $theme->getBaseUrl() . '/js/script.js' );
            $cs->registerCssFile($theme->getBaseUrl() . '/css/styles.css');
            return true;
        }
        return false;
    }
}