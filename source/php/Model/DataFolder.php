<?php
class DataFolder
{
    // Thumbnail table's column's name
    // All have getters and setters
    private $_folderId; //uuid
    private $_fileId; // uuid


    public function __construct()
    {

    }

    // All functions below are getters and setters

    public function setFolderId($folderId)
    {
        $this->_folderId = $folderId;
    }

    public function setFileId($fileId)
    {
        $this->_fileId = $fileId;
    }
    

    public function getFolderId()
    {
        return $this->_folderId;
    }

    public function getFileId()
    {
        return $this->_fileId;
    }


}
