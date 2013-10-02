<?php

Yii::import('application.components.ParseHelper');

class DocController extends EController
{
	public function filters()
	{
		return array(
			array(
				'COutputCache + navigation',
				'duration'   => 3600 * 24 * 5,
				'dependency' => array(
					'class'    => 'system.caching.dependencies.CFileCacheDependency',
					'fileName' => ParseHelper::i()->getDocsApiFolder() . DIRECTORY_SEPARATOR . 'index.html'
				),
			),
		);
	}

	public function actionIndex()
	{
		$this->actionView();
	}

	public function actionView($name = 'index')
	{
		$text = ParseHelper::i()->getDocsApiFile(sprintf("%s.html", $name));
		$this->pageTitle = ($name == 'index' ? "" : $name . " - ") . Yii::app()->name;

		$data = array('text' => $text);
		if (Yii::app()->request->isAjaxRequest) {
			$this->renderPartial('view', $data);
		} else {
			$this->render('view', $data);
		}
	}

	public function actionNavigation()
	{
		$this->cleanLayout();

		$packages = unserialize(ParseHelper::i()->getDocsPackages());
		$this->render('navigation', array('packs' => $packages));
	}

	/**
	 * Get description for API Section
	 *
	 * @param $name string Section name
	 *
	 * @return string
	 */
	public function getSectionDescription($name)
	{
		$description = "";
		$content = ParseHelper::i()->getDocsApiFile($name . '.html');
		if (preg_match('#<div id="classDescription">(.+?)</div>#ims', $content, $matches)) {
			$description = strip_tags($matches[1]);
		}

		return $description;
	}

	/**
	 * Error handler action
	 */
	public function actionError()
	{
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest) {
				echo $error['message'];
			} else {
				$this->render('error', $error);
			}
		}
	}
}
