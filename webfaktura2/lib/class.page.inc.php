<?
### class.page.inc.php - Klasse für die einzeilnen Seiten ###
#

class page{
	#####################################################
	# Variable für den Header
	var $header;
	var $javascript;

	#####################################################
	# Variable für den Footer
	var $footer;

	#####################################################
	# Variable für den Inhalt
	var $content;

	#####################################################
	# Variable für das Menü
	var $menue;

	#####################################################
	# Variable für action
	var $action;
	var $output=1;
	
	#####################################################
	# Konstruktor: nutzt input, um Seite zu erkennen
	function constructor($action){ $this->page($action); }
	function page($action){
		$this->action=$action;
		$this->header="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n<html>\n<head>\n<title>$action</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-15\">
<link rel=\"stylesheet\" href=\"lib/style.css\">
<script language=\"JavaScript\" src=\"lib/script_menu.js\">\n<!--\n -->\n</script>\n</head>\n<body>\n";
		$this->footer="\n</body>\n</html>";
		$this->menue=$this->menu();
	}

	function menu(){
		$output="";
		$output.="<div id=\"Menu\" style=\"font-family: Helvetica, Arial, sans-serif; background-color: blue; color: white; position: absolute; height: 20px; top:0px; left: 0px; text-align: left; width: 100%; padding-left: 50px; padding-top: 2px; \"><a href=\"\" style=\"color: white; text-decoration: none\" onmouseover=\"menu_kunden();\">Kunden</a>&nbsp;<a href=\"\" style=\"color: white; text-decoration: none\" onmouseover=\"menu_produkte();\">Produkte</a></div>";
		$output.="<div id=\"Kunden\" style=\"display: none; font-family: Helvetica, Arial, sans-serif; background-color: green; color: white; position: absolute; top: 20px; left: 50px; text-align: left; width: 100px; padding: 2px;\"><a href=\"?sub=kunden&action=index\" style=\"color: white; text-decoration: none\">Übersicht</a><br><a href=\"?sub=kunden&action=neu\" style=\"color: white; text-decoration: none\">Neu</a><br></div>";
		$output.="<div id=\"Produkte\" style=\"display: none; font-family: Helvetica, Arial, sans-serif; background-color: green; color: white; position: absolute; top: 20px; left: 100px; text-align: left; width: 100px; padding: 2px;\"><a href=\"?sub=produkte&action=index\" style=\"color: white; text-decoration: none\">Übersicht</a><br><a href=\"?sub=produkte&action=neu\" style=\"color: white; text-decoration: none\">Neu</a><br></div>";
		return $output;
	}

	#####################################################
	# Funktion, die die Seite rendert
	function render(){
		$output=$this->header;
		$output.=$this->menue;
		$output.="<div id=\"content\" style=\"position: absolute; top: 30px; left: 10px; width: 100%; height: 100%;\" onmouseover=\"reset();\">".$this->content."</div>";
		$output.="<script language=\"JavaScript\">init();</script>";
		$output.=$this->footer;
		if($this->output){return $output;}
	}
}
?>
