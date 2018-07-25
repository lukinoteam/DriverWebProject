<?php 
require_once __DIR__. "/../Connectdb.php";

class MySQLDA{
	//MARK:- Properties
	private  $db;
	private $conn;

	public function __construct() {
		//TO-DO: create MySQL connection 
		$this->db = MySQLConnectivity::get_instance();
		$this->conn = $this->db->get_connection(); 
	}
	public function select($table,$attr,$condition){
		if($condition == ""){
			$sql = "SELECT ".$attr." FROM ".$table;
			return mysqli_query($this->conn,$sql);
		}
		else{
			$sql = "SELECT ".$attr." FROM ".$table." WHERE ".$condition;
			return mysqli_query($this->conn,$sql);
		}
	}
	public function takeUUID($table){
		$sql = "SELECT UUID()";
		$result = mysqli_query($this->conn,$sql);
		if($result->num_rows >0){
			while($row = $result->fetch_assoc()){
				return $row['UUID()'];
			}
		}
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
	public function update($table, $data, $condition){
		$sql = "UPDATE ".$table." SET ".$data." WHERE ".$condition;
		return mysqli_query($this->conn, $sql);
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
