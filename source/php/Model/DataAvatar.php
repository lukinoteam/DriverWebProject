<?php
class DataAvatar
{
    // Thumbnail table's column's name
    // All have getters and setters

    private $_userId; // uuid
    private $_imageId; // uuid
    private $_status; // int

    public function __construct()
    {
    }

    // All functions below are getters and setters

    public function setUserId($userId)
    {
        $this->_userId = $userId;
    }

    public function setImageId($imageId)
    {
        $this->_imageId = $imageId;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function getUserId()
    {
        return $this->_userId;
    }

    public function getImageId()
    {
        return $this->_imageId;
    }

    public function getStatus()
    {
        return $this->_status;
    }

}
