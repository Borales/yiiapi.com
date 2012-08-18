<?php
/**
 * Helper class for parsing functions
 */
class ParseHelper
{
    /* PRIVATE */
    private static $_instance = null;

    /* CONST */
    const FOLDER_DOCS_NAME = 'doc';

    /**
     * @static
     * @return ParseHelper
     */
    public static function getInstance() {
        if( self::$_instance == null ) {
            self::$_instance = new ParseHelper();
        }
        return self::$_instance;
    }

    /**
     * Short-cut for getInstance()
     * @static
     * @return ParseHelper
     */
    public static function i() {
        return self::getInstance();
    }

    /**
     * Get full path to docs folder
     * @return string
     */
    public function getDocsFolder() {
        $dir = Yii::getPathOfAlias('application') . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . self::FOLDER_DOCS_NAME;
        return realpath($dir);
    }

}
