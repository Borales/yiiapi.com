<?php
/**
 * Helper class for parsing functions
 */
class ParseHelper
{
    /* PRIVATE */
    private static $_instance = null;

    /* CONST */
    const FOLDER_DOCS_NAME  = 'doc';
    const INDEX_FILE        = 'index.html';
    const TOC_SEARCH        = ".";
    const TOC_REPLACE       = "_";

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
     * @throws ParseException
     * @return string
     */
    public function getDocsFolder() {
        $dir = Yii::getPathOfAlias('application') . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . self::FOLDER_DOCS_NAME;

        if( ($realDir = realpath($dir)) == false )
            throw new ParseException("Yii API Docs folder does not exists!");

        return $realDir;
    }

    /**
     * Get doc/api directory path
     * @return string Directory path
     * @throws ParseException
     */
    public function getDocsApiFolder() {
        $dir = $this->getDocsFolder() . DIRECTORY_SEPARATOR . "api";
        if( !is_dir($dir) )
            throw new ParseException("API folder does not exists");

        return $dir;
    }

    /**
     * @param string $name Filename to get its content
     * @throws ParseException
     * @return string File content
     */
    public function getDocsApiFile( $name ) {
        $file = $this->getDocsApiFolder() . DIRECTORY_SEPARATOR . $name;
        if( !is_file($file) )
            throw new ParseException('File "'.$name.'" does not exists');

        $content = Yii::app()->cache->get( $file );

        if( !$content ) {
            $content = file_get_contents($file);
            $content = preg_replace('#href="(/doc/api)(.*?[^"])"#', "href=\"\$2\"", $content);

            if( preg_match_all('#<a href="/(\#system..*?[^"])"#', $content, $matches) ) {
                foreach($matches[1] as $match) {
                    $content = str_replace($match, str_replace(self::TOC_SEARCH, self::TOC_REPLACE, $match), $content);
                }
            }

            if( $name == self::INDEX_FILE && preg_match_all('#<a name=("system.*?[^"]")>#', $content, $systemMatches) ) {
                foreach($systemMatches[1] as $systemMatch) {
                    $_systemMatch = str_replace(self::TOC_SEARCH, self::TOC_REPLACE, $systemMatch);
                    $content = str_replace($systemMatch, $_systemMatch, $content);
                }
            }

            Yii::app()->cache->set($file, $content, 3600 * 24 * 5, new CFileCacheDependency($file));
        }

        return $content;
    }

    /**
     * Content of packages.txt file
     * @return string
     */
    public function getDocsPackages() {
        return $this->getDocsApiFile("packages.txt");
    }

    /**
     * Content of keywords.txt file
     * @return string
     */
    public function getDocsKeywords() {
        return $this->getDocsApiFile("keywords.txt");
    }

}

class ParseException extends CException {}