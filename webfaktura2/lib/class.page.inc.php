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

	#####################################################
	# Konstruktor: nutzt input, um Seite zu erkennen
	function constructor($action){ $this->page($action); }
	function page($action){
		$this->action=$action;
		$this->header="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD
	#HTML
	#4.0//EN\">\n<html>\n<head>\n<title>$action</title>\n<meta
	#http-equiv=\"Content-Type\" content=\"text/html;
	#charset=iso-8859-15\">\n<script
	#language=\"JavaScript\" src=\"lib/script.js\"><!--\n //-->\n</script>\n</head>\n<body>";
		$this->footer="</body></html>";
		$this->menue=$this->menu();
	}

	function menu(){
		$output="";
		$output.="<div id=\"Menu\" style=\"font-family: Helvetica, Arial, sans-serif; background-color: blue; color: white; position: absolute; top:0px; left: 0px; text-align: center; width: 100%; \"><a href=\"javascript:menu_kunden();\" style=\"color: white; text-decoration: none\">Kunden</a>&nbsp;<a href=\"javascript:menu_produkte();\" style=\"color: white; text-decoration: none\">Produkte</a></div>";
		$output.="<div></div>";
		return $output;
	}

	#####################################################
	# Funktion, die die Seite rendert
	function render(){
		$output=$this->header;
		$output.=$this->menue;
		$output.="<div id=\"content\" style=\"position: absolute; top: 30px; left: 10px;\">".$this->content."</div>";
		$output.=$this->footer;
		return $output;
	}
}
?>
