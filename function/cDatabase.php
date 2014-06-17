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
	  $this->ExecuteNonQuery($sqlNames . $sqlValues);
  }

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
    $this->ExecuteNonQuery($sql);
  }

  function ExecuteNonQuery($sql){
	  $conn = mysql_connect($this->host, $this->user, $this->password);
    mysql_select_db ($this->database);
	  $rs = mysql_query($sql,$conn);
	  if($rs){
		echo "berhasil";
	  }else{
		echo "tidak";
	  }
    settype($rs, "null");
	  mysql_close($conn);


  }

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

  }
}
?>
