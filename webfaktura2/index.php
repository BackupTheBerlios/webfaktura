<?
### index.php - where everything starts
#
#
#


#########################################
# init

define('FPDF_FONTPATH','font/');

#########################################
# Includes
require_once("etc/config.inc.php");
require_once("fpdf/fpdf.php");
require_once("lib/init.php");



#########################################
# Create Page-Object
if(!isset($_GET["sub"])){$sub="start";}
if(!isset($sub)){$sub=$_GET["sub"];}

$page=new $sub($_GET["action"]);

echo $page->render();


?>
