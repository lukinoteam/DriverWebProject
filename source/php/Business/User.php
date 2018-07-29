<?php 
	class Users{
//MARK:- Properties
		private $id="";
		private $username="";
		private $password="";
		private $fullname=""; 
		private $email=""; 
		private $dob=0; 
		private $gender=0;
		
// MARK:- constructor
		public function __construct($id,$username,$password,$fullname,$email,$dob,$gender) {
			$this->id=$id;
			$this->username=$username;
			$this->password=$password;
			$this->fullname=$fullname;
			$this->email=$email;
			$this->dob=$dob;
			$this->gender=$gender;
		}	
//MARK:- Getter and Setter
		public function getId() {
			return $this->id;  
		}
		public function setId($id) {
			$this->id=$id;
		}
		public function getUsername() {
			return $this->username;  
		}
		public function setUsername($username) {
			$this->username=$username;
		}
		public function getPassword() {
			return $this->password;  
		}
		public function setPassword($password) {
			$this->password=$password;
		}
		public function getFullname() {
			return $this->fullname;  
		}
		public function setFullname($fullname) {
			$this->fullname=$fullname;
		}
		public function getEmail() {
			return $this->email;  
		}
		public function setEmail($email) {
			$this->email=$email;
		}
		public function getDob() {
			return $this->dob;  
		}
		public function setDob($dob) {
			$this->dob=$dob;
		}public function getGender($gender) {
			return $this->$gender;  
		}
		public function setGender($gender) {
			$this->gender=$gender;
		}
	}
 ?>