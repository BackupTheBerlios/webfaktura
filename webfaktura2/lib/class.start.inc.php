<?
### class.start.inc.php
#
require_once("class.page.inc.php");

class start extends page{
	#################################################
	### Konstruktor
	function constructor($action){
		$this->start($action);
	}
	function start($action){
		parent::constructor($action);
		$this->content.="Start!";
	}
}
?>
