<?php
//File written by Ryan Campbell
//June 2005 - Particletree

//Class to handle database operations
class cDatabase {
	function cDatabase(){
		$this->host = "localhost";
		$this->user = "root";
		$this->password = "root";
		$this->database = "kelas"; 
	}
  
  //loop through paired arrays buildng an sql INSERT statement
  function sqlInsert($dataNames, $dataValues, $tableName){
	  $sqlNames = "INSERT INTO " . $tableName . "(";
	  for($x = 0; $x < count($dataNames); $x++) {
		  if($x != (count($dataNames) - 1)) {
			  $sqlNames = $sqlNames . $dataNames[$x] . ", ";
			  $sqlValues = $sqlValues . "'" . $dataValues[$x] . "', ";
		  }
		  else {
			  $sqlNames = $sqlNames . $dataNames[$x] . ") VALUES(";
			  $sqlValues = $sqlValues . "'" . $dataValues[$x] . "')";
		  }
	  }
	  // echo $sqlNames . $sqlValues;
	  $this->ExecuteNonQuery($sqlNames . $sqlValues);
  }

  //loop through paired arrays buildng an sql UPDATE statement
  function sqlUpdate($dataNames, $dataValues, $tableName, $condition){
	  $sql = "UPDATE " . $tableName . " SET ";
	  for($x = 0; $x < count($dataNames); $x++) {
		  if($x != (count($dataNames) - 1)) {
			  $sql = $sql . $dataNames[$x] . "= '" . $dataValues[$x] . "', ";
		  }
		  else {
			  $sql = $sql . $dataNames[$x] . "= '" . $dataValues[$x] . "' ";
		  }
	  }
	  $sql = $sql . $condition;
	  // echo $sql;
    $this->ExecuteNonQuery($sql);
  }

  //execute a query
  function ExecuteNonQuery($sql){
	  $conn = mysql_connect($this->host, $this->user, $this->password);
    mysql_select_db ($this->database);
	  $rs = mysql_query($sql,$conn);
	  //pengecekan jika data berhasil masuk atau tidak
	  if($rs){
		//jika berhasil masuk
		echo "berhasil";
	  }else{
		//jika tidak
		echo "tidak";
	  }
    settype($rs, "null");
	  mysql_close($conn);
	  // return $rs;
	  
  }
  
  //execute a query and return a recordset
  function ExecuteReader($query){
    $conn = mysql_connect($this->host, $this->user, $this->password);
    mysql_select_db ($this->database);
	$rs = mysql_query($query,$conn);
	if($rs){
		$hasil=array();
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC)){
			$hasil[]=$row;
		}
		return $hasil;
	}
    mysql_close($conn);
    // return $rs;
  }  
  
  function ExecuteReaderOne($query){
    $conn = mysql_connect($this->host, $this->user, $this->password);
    mysql_select_db ($this->database);
	$rs = mysql_query($query,$conn);
	if($rs){
		$hasil=array();
		$row = mysql_fetch_array($rs, MYSQL_ASSOC);
		return $row;
	}
    mysql_close($conn);
    // return $rs;
  } 
}
?>