<?php
final class MySQLConnectivity {
    /*
     * TO-DO: Create Singleton for connectivity to Server at @var($_host)
     * as @var($_username) and @var($_password).
     * Interracting with @var($_database) database
     */
    
    //MARK: Properties

    //@var: Server's properties for connection
    private $_host="127.0.0.1";
    private $_username="root";
    private $_password="";
    private $_database="manage_user";

    //@var: Connection to open
    private $_connection;

    //@var: mySQLi instance
    private static $_instance;
    
    //MARK:-Private constructor to prevent creating instance from outside
    private function __construct() {
        $this->_connection = new mysqli($this->_host,$this->_username,$this->_password,$this->_database);
        if (mysqli_connect_error()) {
            trigger_error("Failed to connect to MYSQL:".mysqli_connect_error(), E_USER_ERROR);
        }
    }

    //TO-DO:Public function to get Singleton instance
    public static function get_instance() {
        if(!static::$_instance){
            static::$_instance= new static();
        }
        return static::$_instance;
    }
    //TO-DO:Public instance's function to open a connection
    public function get_connection() {
        return $this->_connection;
    }
}
final class CassandraConnectivity {
    /* TO-DO: Creat Singleton for connectivity of Cassandra server 
     * with @var($_serverId) at @var($ke)
     */

    //MARK:- Properties 
    
    //@var:Database's attributes
    private $_server_id="192.168.0.110";
    private $_keyspace="driveweb";

    
    //@var:Connection to database
    private $_connection;

    //@var:Singleton instance of Connectivity
    private static $_instance;

    //MARK:- Private constructor to prevent creating object from outside
    private function __construct() {
        $cluster = Cassandra::cluster()
        ->withContactPoints($this->_server_id)
        ->build();
        $this->_connection = $cluster->connect($this->_keyspace);
    }

    //MARK:- Function

    //TO-DO:Public function to get Singleton instance
    public static function get_instance() {
        if(!static::$_instance) {
            static::$_instance= new static();
        }
        return static::$_instance;
    }

    //TO-DO:Public function to open a connection
    public function get_connection() {
        return $this->_connection;
    }
}
?>
