<?
### index.php - where everything starts
#
#
#


#########################################
# init

$action="TESTER";

#########################################
# Includes

include("lib/class.page.inc.php");

#########################################
# Create Page-Object

$page=new page($action);

echo $page->render();


?>
