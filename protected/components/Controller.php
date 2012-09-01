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
            $cs->registerPackage('history');
            $cs->registerPackage('bbq');
            $cs->registerScriptFile( $theme->getBaseUrl() . '/js/highlight.js' );
            $cs->registerScriptFile( $theme->getBaseUrl() . '/js/jquery.ba-dotimeout.min.js' );
            $cs->registerScriptFile( $theme->getBaseUrl() . '/js/script.js' );
            $cs->registerCssFile($theme->getBaseUrl() . '/css/reset.css');
            $cs->registerCssFile($theme->getBaseUrl() . '/css/main.css');
            return true;
        }
        return false;
    }

    /**
     * Disabling layout and resetting client scripts
     */
    protected function cleanLayout() {
        $this->layout = false;
        Yii::app()->clientScript->reset();
    }
}