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
$page->generate_menu();
$mod = new $action($page, $db);
$mod->start();
$mod->render();
$page->render();
$page->output();


?>
