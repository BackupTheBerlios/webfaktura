<?
### index.php - index ###
#
#
#
#
# 
# initializiere alles
include("lib/init.php");

$page = new page();
$page->init("WebFaktura");

$mod_name="mod_$action";
$mod = new $action($page, $db);
$mod->start();
$page->render();
$page->output();


?>
