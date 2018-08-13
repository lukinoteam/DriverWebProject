<?php
final class DataThumbnail
{

    // Thumbnail table's column's name
    // All have getters and setters
    private $_fileId; // uuid
    private $_id; // uuid
    private $_type; //int
    private $_image; // blob
    private $_status; //int

    public function __construct()
    {

    }

    // All functions below are getters and setters

    public function setFileId($fileId)
    {
        $this->_fileId = $fileId;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function setType($type)
    {
        $this->_type = $type;
    }

    public function setImage($image)
    {
        $this->_image = $image;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function getFileId()
    {
        return $this->_fileId;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function getImage()
    {
        return $this->_image;
    }

    public function getStatus()
    {
        return $this->_status;
    }
}
