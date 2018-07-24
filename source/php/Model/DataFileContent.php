<?php
class DataFileContent
{
    // Thumbnail table's column's name
    // All have getters and setters
    private $_fileId; // uuid
    private $_part; //int
    private $_content; //blob

    public function __construct()
    {

    }

    // All functions below are getters and setters

    public function setFileId($fileId)
    {
        $this->_fileId = $fileId;
    }

    public function setPart($part)
    {
        $this->_part = $part;
    }

    public function setContent($content)
    {
        $this->_content = $content;
    }

    public function getFileId()
    {
        return $this->_fileId;
    }

    public function getPart()
    {
        return $this->_part;
    }
    public function getContent()
    {
        return $this->_content;
    }
}
