<?
### class.produkte.inc.php - Klasse für die Kundenverwaltung
#
require_once("class.page.inc.php");
class produkte extends page{
	function constructor($action){
		$this->produkte($action);
	}
	
	function produkte($action){
		parent::constructor($action);
		$this->content.="Produkte! $action";
	}

}
