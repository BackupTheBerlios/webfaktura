<?
### class.datenbank.inc.php - Klasse für Datenbankzugriff

class datenbank{
	var $link;

	function constructor(){
		$this->datenbank();
	}

	function datenbank(){
		$this->link=mysql_connect($GLOBALS["conf"]["db"]["host"], $GLOBALS["conf"]["db"]["username"], $GLOBALS["conf"]["db"]["passwort"]);
		mysql_select_db($GLOBALS["conf"]["db"]["db"], $this->link);
	}

	function query($query){
		$result=mysql_query($query);
		return $result;
	}
	
	function get_object($result){
		return mysql_fetch_object($result);
	}

	function get_row($result){
		return mysql_fetch_row($result);
	}
	function num($result){
		return mysql_num_rows($result);
	}
	
	function num_fields($result){
		return mysql_num_fields($result);
	}
	
	function fieldname($result, $index){
		return mysql_field_name($result, $index);
	}
}


?>
