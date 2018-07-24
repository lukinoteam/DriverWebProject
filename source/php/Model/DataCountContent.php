<?php
class DataCountContent
{
    // Thumbnail table's column's name
    // All have getters and setters
    private $_fileId; //uuid
    private $_count; // counter

    public function __construct()
    {

    }

    // All functions below are getters and setters

    public function setFileId($fileId)
    {
        $this->_fileId = $fileId;
    }

    public function setCount($count)
    {
        $this->_count = $count;
    }

    public function getFileId()
    {
        return $this->_fileId;
    }

    public function getCount()
    {
        return $this->_count;
    }
}
