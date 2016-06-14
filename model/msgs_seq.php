<?php

require_once (dirname(dirname(__FILE__)).'/lib/common.php');

class MsgsSeq{
	/*static function next_value(){
		$conn = connect_db();
		$sql = "select ".MSGS_SEQ.".nextval from dual";
		$stmt = oci_parse($conn,$sql);
		oci_execute($stmt);
		oci_close($conn);
		
		$row = oci_fetch_row($stmt);
		return $row[0];
	}
	
	static function current_value($conn){
		$sql = "select ".MSGS_SEQ.".currval from dual";
		$stmt = oci_parse($conn,$sql);
		oci_execute($stmt);
		
		$row = oci_fetch_row($stmt);
		return $row[0];
	}*/
	
	static function create(){
		$conn = connect_db();
		$sql = "CREATE SEQUENCE ".MSGS_SEQ;
		$stmt = oci_parse($conn,$sql);
		$result = oci_execute($stmt);
		oci_close($conn);
		
		return $result;
	}
	
	static function drop(){
		$conn = connect_db();
		$sql = "DROP SEQUENCE ".MSGS_SEQ;
		$stmt = oci_parse($conn, $sql);
		$result = oci_execute($stmt);
		oci_close($conn);
		return $result;
	}
}

?>
