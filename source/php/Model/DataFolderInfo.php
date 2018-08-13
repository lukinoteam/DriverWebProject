<?php
final class DataFolderInfo
{
    // Thumbnail table's column's name
    // All have getters and setters
    private $_userId; //uuid
    private $_folderId; // uuid
    private $_name; //text
    private $_size; //bigint
    private $_dateModify; //date
    private $_description; // text
    private $_status; // int

    public function __construct()
    {

    }

    // All functions below are getters and setters

    public function setUserId($userId)
    {
        $this->_userId = $userId;
    }

    public function setFolderId($folderId)
    {
        $this->_folderId = $folderId;
    }
    public function setName($name)
    {
        $this->_name = $name;
    }
    public function setDateModify($dateModify)
    {
        $this->_dateModify = $dateModify;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function getUserId()
    {
        return $this->_userId;
    }

    public function getFolderId()
    {
        return $this->_folderId;
    }

    public function getName()
    {
        return $this->_name;
    }
    public function getDateModify()
    {
        return $this->_dateModify;
    }
    public function getDescription()
    {
        return $this->_description;
    }

    public function getStatus()
    {
        return $this->_status;
    }

}
