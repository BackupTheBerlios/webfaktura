<?
### class.page.inc.php - Klasse f�r die einzeilnen Seiten ###
#

class page{
	#####################################################
	# Variable f�r den Header
	var $header;

	#####################################################
	# Variable f�r den Footer
	var $footer;

	#####################################################
	# Variable f�r den Inhalt
	var $content;
	
	#####################################################
	# Konstruktor: nutzt input, um Seite zu erkennen
	function page($action){
		$this->header="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">
<html>
<head>
  <title>$action</title>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-15\">
</head>
<body>";
		$this->footer="</body>
</html>";
	}

	#####################################################
	# Funktion, die die Seite rendert
	function render(){
		$output=$this->header;
		$output.=$this->content;
		$output.=$this->footer;
		return $output;
	}
}
?>
