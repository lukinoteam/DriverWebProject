<?php
require_once 'DBInterface.php';
require_once 'TableName.php';
require_once __DIR__ . '/../../Model/DataAvatar.php';
require_once __DIR__ . '/../../Model/DataCountContent.php';
require_once __DIR__ . '/../../Model/DataFileInfo.php';
require_once __DIR__ . '/../../Model/DataThumbnail.php';
require_once __DIR__ . '/../../Model/DataFileContent.php';
require_once __DIR__ . '/../../Model/DataFolderInfo.php';
require_once __DIR__ . '/../../Model/DataFolder.php';
require_once __DIR__ . '/../Connectdb.php';

final class CassandraDA implements DataBaseAccess
{

    //MARK:- Variables
    private $_connection;

    //MARK:- Constructor
    public function __construct()
    {
        $this->_connection = CassandraConnectivity::get_instance();
    }

    //MARK:- Functuion

    //TO-DO: empty table
    public function truncate($table)
    {
        /*
         *@var($table) is a string as table name
         */
        try {
            $statement = new Cassandra\SimpleStatement(
                "truncate " . $table . ""
            );
            $this->_connection->get_connection()->execute($statement);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /*
     *Usage:
    - create a data table object like DataAvatar, DataFileInfo, ...
    - set all field (as table's column's name) for object
    - then add this object to insert()
     */

    //TO-DO: - insert data to table
    public function insert($table, $dataObject)
    {
        /* @var($avatar) is a variable store "Avatar" table's name,
         * same as @var($file_info), @var($file_content),...
         * @var($dataObject) is a class which contain data of table, use getters to get to data
         * @var($table) is a string as table name
         */

        //TO-DO: table name store at TableName.php file
        switch ($table) {
            case TableName::$avatar:
                try {
                    // Prepare statement
                    $insert = $this->_connection->get_connection()->prepare(
                        "insert into " . TableName::$avatar . " (user_id, image_id, status) values (?,?,?)"
                    );

                    //TO-DO: Ready some data to prepare statement
                    $data = array(
                        array(
                            'user_id' => $dataObject->getUserId(),
                            'image_id' => $dataObject->getImageId(),
                            'status' => $dataObject->getStatus(),
                        ),
                    );

                    $options = array('arguments' => $data[0]);
                    $this->_connection->get_connection()->execute($insert, $options);

                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                break;
            case TableName::$file_info:
                try {
                    // Prepare statement
                    $insert = $this->_connection->get_connection()->prepare(
                        "insert into " . TableName::$file_info . " (user_id, file_id, name, size, date_modify, description, type, status) values (?,?,?,?,?,?,?,?)"
                    );
                    //TO-DO: Ready some data to prepare statement
                    $data = array(
                        array(
                            'user_id' => $dataObject->getUserId(),
                            'file_id' => $dataObject->getFileId(),
                            'name' => $dataObject->getName(),
                            'size' => new Cassandra\Bigint($dataObject->getSize()),
                            'date_modify' => new \Cassandra\Timestamp($dataObject->getDateModify()),
                            'description' => $dataObject->getDescription(),
                            'type' => $dataObject->getType(),
                            'status' => $dataObject->getStatus(),
                        ),
                    );

                    $options = array('arguments' => $data[0]);
                    $this->_connection->get_connection()->execute($insert, $options);

                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                break;
            case TableName::$file_content:
                try {
                    // Prepare statement
                    $insert = $this->_connection->get_connection()->prepare(
                        "insert into " . TableName::$file_content . " (file_id, part, content) values (?,?,?)"
                    );
                    //TO-DO: Ready some data to prepare statement
                    $data = array(
                        array(
                            'file_id' => $dataObject->getFileId(),
                            'part' => $dataObject->getPart(),
                            'content' => $dataObject->getContent(),
                        ),
                    );

                    $options = array('arguments' => $data[0]);
                    $this->_connection->get_connection()->execute($insert, $options);

                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                break;
            case TableName::$count_content:

                //TO-DO: insert command doesn't work for counter table. using updateCount(@var(key)). definition below.

                echo "insert command doesn't work for counter table. Try using updateCount(@var(key)) instead";
                break;
            case TableName::$thumbnail:
                try {
                    // Prepare statement
                    $insert = $this->_connection->get_connection()->prepare(
                        "insert into " . TableName::$thumbnail . " (file_id, id, type, image, status) values (?,?,?,?,?)"
                    );

                    //TO-DO: Ready some data to prepare statement
                    $data = array(
                        array(
                            'file_id' => $dataObject->getFileId(),
                            'id' => $dataObject->getId(),
                            'type' => $dataObject->getType(),
                            'image' => $dataObject->getImage(),
                            'status' => $dataObject->getStatus(),
                        ),
                    );

                    $options = array('arguments' => $data[0]);
                    $this->_connection->get_connection()->execute($insert, $options);

                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                break;
            case TableName::$folder:
                try {
                    // Prepare statement
                    $insert = $this->_connection->get_connection()->prepare(
                        "insert into " . TableName::$folder . " (folder_id, file_id, status) values (?,?,?)"
                    );

                    //TO-DO: Ready some data to prepare statement
                    $data = array(
                        array(
                            'folder_id' => $dataObject->getFolderId(),
                            'file_id' => $dataObject->getFileId(),
                            'status' => $dataObject->getStatus(),
                        ),
                    );
                    $options = array('arguments' => $data[0]);
                    $this->_connection->get_connection()->execute($insert, $options);

                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                break;
            case TableName::$folder_info:
                try {
                    // Prepare statement
                    $insert = $this->_connection->get_connection()->prepare(
                        "insert into " . TableName::$folder_info . " (user_id, folder_id, name, date_modify, description, status) values (?,?,?,?,?,?)"
                    );

                    //TO-DO: Ready some data to prepare statement
                    $data = array(
                        array(
                            'user_id' => $dataObject->getUserId(),
                            'folder_id' => $dataObject->getFolderId(),
                            'name' => $dataObject->getName(),
                            'date_modify' => new \Cassandra\Timestamp($dataObject->getDateModify()),
                            'description' => $dataObject->getDescription(),
                            'status' => $dataObject->getStatus(),
                        ),
                    );

                    $options = array('arguments' => $data[0]);
                    $this->_connection->get_connection()->execute($insert, $options);

                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                break;
            default:
                echo "Wrong table name";
        }
    }

    public function get_connection()
    {
        return $this->_connection->get_connection();
    }

    //TO-DO: update counter table (count_content table)
    public function updateCount($key)
    {
        $statement = new Cassandra\SimpleStatement(
            "update count_content set count = count + 1 where file_id = " . $key . " "
        );
        $this->_connection->get_connection()->execute($statement);
    }

    public function deleteRow($table, $dataObject)
    {
        switch ($table) {
            case TableName::$avatar:
                try {
                    $delete = $this->_connection->get_connection()->prepare("DELETE from " . Table::$avatar . " WHERE user_id = ?");
                    $data = array(
                        array(
                            'user_id' => $dataObject->getUserId(),
                        ),
                    );
                    $option = array('arguments' => $data[0]);
                    $this->_connection->get_connection()->execute($delete, $option);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                break;
            case TableName::$file_info:
                try {
                    $delete = $this->_connection->get_connection()->prepare("DELETE from " . TableName::$file_info . " WHERE user_id = ? and file_id = ?");
                    $data = array(
                        array(
                            'user_id' => $dataObject->getUserId(),
                            'file_id' => $dataObject->getFileId(),
                        ),
                    );
                    $option = array('arguments' => $data[0]);
                    $this->_connection->get_connection()->execute($delete, $option);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                break;
            case TableName::$file_content:
                try {
                    $delete = $this->_connection->get_connection()->prepare("DELETE from " . TableName::$file_content . " WHERE part = ? and file_id = ?");
                    $data = array(
                        array(
                            'part' => $dataObject->getPart(),
                            'file_id' => $dataObject->getFileId(),
                        ),
                    );
                    $option = array('arguments' => $data[0]);
                    $this->_connection->get_connection()->execute($delete, $option);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                break;
            case TableName::$count_content:
                try {
                    $delete = $this->_connection->get_connection()->prepare("DELETE from " . TableName::$count_content . " WHERE file_id=?");
                    $data = array(
                        array(
                            'file_id' => $dataObject->getFileId(),
                        ),
                    );
                    $option = array('arguments' => $data[0]);
                    $this->_connection->get_connection()->execute($delete, $option);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                break;
            case TableName::$thumbnail:
                try {
                    $delete = $this->_connection->get_connection()->prepare("DELETE from " . TableName::$thumbnail . " WHERE file_id = ?");
                    $data = array(
                        array(
                            'file_id' => $dataObject->getFileId(),
                        ),
                    );
                    $option = array('arguments' => $data[0]);
                    $this->_connection->get_connection()->execute($delete, $option);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                break;
            case TableName::$folder:
                try {
                    $delete = $this->_connection->get_connection()->prepare("DELETE from " . TableName::$folder . "WHERE folder_id = ?");
                    $data = array(
                        array(
                            'file_id' => $dataObject->getFolderId(),
                        ),
                    );
                    $option = array('arguments' => $data[0]);
                    $this->_connection->get_connection()->execute($delete, $option);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                break;
            default:
                echo "Delete con cac";
        }
    }
}
