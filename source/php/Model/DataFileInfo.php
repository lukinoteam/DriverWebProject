<?php
final class DataFileInfo
{
    // Thumbnail table's column's name
    // All have getters and setters
    private $_userId; //uuid
    private $_fileId; // uuid
    private $_name; //text
    private $_size; //bigint
    private $_dateModify; //date
    private $_description; // text
    private $_type; // int
    private $_status; // int

    public function __construct()
    {

    }
    public function getInfo()
    {
        $result = array(
            'user_id' => $this->_userId->uuid(),
            'file_id' => $this->_fileId->uuid(),
            'name' => $this->_name,
            'size' => $this->_size,
            'date' => $this->_dateModify,
            'descr' => $this->_description,
            'type' => $this->_type,
            'status'=> $this->_status
            );
            return $result;
    }

    // All functions below are getters and setters

    public function setUserId($userId)
    {
        $this->_userId = $userId;
    }

    public function setFileId($fileId)
    {
        $this->_fileId = $fileId;
    }
    public function setName($name)
    {
        $this->_name = $name;
    }

    public function setSize($size)
    {
        $this->_size = $size;
    }
    public function setDateModify($dateModify)
    {
        $this->_dateModify = $dateModify;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
    }

    public function setType($type)
    {
        $this->_type = $type;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function getUserId()
    {
        return $this->_userId;
    }

    public function getFileId()
    {
        return $this->_fileId;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getSize()
    {
        return $this->_size;
    }
    public function getDateModify()
    {
        return $this->_dateModify;
    }
    public function getDescription()
    {
        return $this->_description;
    }
    public function getType()
    {
        return $this->_type;
    }

    public function getStatus()
    {
        return $this->_status;
    }

}
