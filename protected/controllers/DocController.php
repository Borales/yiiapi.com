<?php

Yii::import('application.components.ParseHelper');

class DocController extends Controller
{
    public function filters() {
        return array(
            array(
                'COutputCache + navigation',
                'duration' => 3600 * 24,
                'dependency'=>array(
                    'class' => 'system.caching.dependencies.CFileCacheDependency',
                    'fileName' => ParseHelper::i()->getDocsApiFolder() . DIRECTORY_SEPARATOR . 'index.html'
                ),
            ),
        );
    }

    public function actionIndex() {
        /* @var $cs CClientScript */
        //$cs = Yii::app()->clientScript;
        //$cs->registerMetaTag(ParseHelper::i()->getDocsKeywords(), 'keywords');
        $this->render('index', array('index' => ParseHelper::i()->getDocsApiFile('index.html')));
    }

    public function actionApi( $name = 'index' ) {

    }


    public function actionNavigation() {
        $this->cleanLayout();

        $packages = unserialize(ParseHelper::i()->getDocsPackages());
        $this->render('navigation', array('packs' => $packages));
    }

    /**
     * Get description for API Section
     * @param $name string Section name
     * @return string
     */
    public function getSectionDescription( $name ) {
        $description = "";
        $content = ParseHelper::i()->getDocsApiFile($name . '.html');
        if( preg_match('#<div id="classDescription">(.+?)</div>#ims', $content, $matches) ) {
            $description = strip_tags($matches[1]);
        }

        return $description;
    }

    /**
     * Error handler action
     */
    public function actionError() {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }
}
