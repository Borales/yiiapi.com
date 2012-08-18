<?php

Yii::import('application.components.ParseHelper');

class ParseCommand extends CConsoleCommand
{
    const PHP_EXEC_CMD = 'php';

    /**
     * @return string
     */
    public function getHelp() {
        $text = "\n";

        $text .= "Parsing Yii API docs to ";
        $text .= '"' . $this->getDocsDirectory()  . '" folder';

        return "$text\n\n";
    }

    /**
     * @return string
     */
    protected function getDocsDirectory() {
        return ParseHelper::i()->getDocsFolder();
    }

    /**
     * @return string
     */
    protected function getBaseBuildCmd() {
        return self::PHP_EXEC_CMD . ' ' . YII_BUILD_COMMANDS_PATH . DIRECTORY_SEPARATOR . 'build api ';
    }

    /**
     * @param bool $online
     * @return string
     */
    protected function getGenerateApiCmd( $online = true ) {
        $cmd =  $this->getBaseBuildCmd() . $this->getDocsDirectory();
        if( $online )
            $cmd .= " online";
        return $cmd;
    }

    /**
     * @return string
     */
    protected function getCheckApiCmd() {
        return $this->getBaseBuildCmd() . "check";
    }

    /**
     * @param string $action
     * @param array $params
     * @return bool
     */
    public function beforeAction($action, $params) {
        if( parent::beforeAction($action, $params) ) {
            echo "\nStarting to parse Yii API docs...\n";
            return true;
        }
        return false;
    }

    /**
     * @param string $action
     * @param array $params
     * @param int $exitCode
     * @return int
     */
    public function afterAction($action,$params,$exitCode=0) {
        $resultCode = parent::afterAction($action,$params,$exitCode);
        echo "\n" . ($resultCode == $exitCode ? "Parsing completed" : "Parsing failed") . "\n\n";
        return $resultCode;
    }

    public function actionIndex() {
        system($this->getGenerateApiCmd());
    }

    public function actionCheckApi(){
        system($this->getCheckApiCmd());
    }
}
