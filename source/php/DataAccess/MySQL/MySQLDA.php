<?php 
require_once "Connectdb.php";

class MySQLDA{
	//MARK:- Properties
	private static $da;
	private static $conn;

	public function __construct() {
		//TO-DO: create MySQL connection 
		$this->db = MySQLConnectivity::getInstance();
		$this->conn = $this->$db->getConnection(); 
	}

	//MARK:- Functions
	/* TO-DO: insert function
	 * @var($table) : String
	 * @var($user) array with field and their values
	 */
	public function insert($table, $user){
		//MARK:- Variables
		$field_list = '';
		$value_list = '';
		foreach ($user as $key => $value) {
			$field_list .= ",$key";
			$value_list .= ",'".$value."'";
		}
		//TO-DO: use trim to delete "," after foreach
		$field_list = trim($field_list,',');
		$value_list = trim($value_list,',');
		$sql = "INSERT INTO ".$table." (".$field_list.") VALUES (".$value_list.")";
		return mysqli_query($this->conn, $sql);
	}
	/* TO-DO: update function
	 * @var($table) : String
	 * @var($user) array with field and their values
	 */
	public function update($table, $user, $where){
		$sql= "";
		foreach ($user as $key => $value) {
			$sql.= "$key = '".$value."'";
		}
		trim($sql);
		$sql = "UPDATE ".$table." SET ".$sql." WHERE ".$where."";
		return mysqli_query($this->$conn, $sql);
	}

	/* TO-DO: delete function
	 * @var($table) : String
	 * @var($user) array with field and their values
	 */
	public function delete($table, $where){
		$sql="DElETE FROM ".$table." WHERE ".$where."";
		echo $sql;
		return mysqli_query($this->conn, $sql);
	}
}
?>