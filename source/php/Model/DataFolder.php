<?php
class DataFolder
{
    // Thumbnail table's column's name
    // All have getters and setters
    private $_folderId; //uuid
    private $_fileId; // uuid
    private $_status; // int



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

    public function setStatus($status)
    {
        $this->_status = $status;
    }
    

    public function getFolderId()
    {
        return $this->_folderId;
    }

    public function getFileId()
    {
        return $this->_fileId;
    }

    public function getStatus()
    {
        return $this->_status;
    }
}
