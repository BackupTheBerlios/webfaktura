<?
### class.kunden.inc.php - Klasse f�r die Kundenverwaltung
#
require_once("class.page.inc.php");
class kunden extends page{
	function constructor($action){
		$this->kunden($action);
	}
	
	function kunden($action){
		parent::constructor($action);
		switch($action){
		case "index":	$this->content.=$this->index(); break;
		case "detail":	$this->content.=$this->detail($_GET["id"]); break;
		case "rechnung_neu":	$this->content.=$this->rechnung_neu($_GET["id"]); break;
		default:	$this->content.=$this->not_implemented();
		}
	}

	function not_implemented(){
		$return="";
		$return.="Dieses Feature ist noch nicht implementiert!";
		return $return;
	}

	function index(){
		$return="";
		$db=new datenbank();
		$kundenliste=$db->query("select * from kunden order by firma;");
		$this->content.="<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\"><tr style=\"background-color: lightblue;\" id=\"ueberschrift\"><td>Kd-Nr</td><td>Firma</td><td>Adresse</td><td>PLZ</td><td>Ort</td></tr>\n";
		while($kunde=$db->get_object($kundenliste)){
			$zeile="zeile".$kunde->id;
			$return.="<tr id=\"$zeile\" bgcolor=\"lightgrey\" onmouseover=\"changecolor('$zeile', 'lightgreen');\" onmouseout=\"changecolor('$zeile', 'lightgrey');\" onclick=\"document.location.href='index.php?action=detail&sub=kunden&id=$kunde->id'\"><td>$kunde->kdnr</td><td>$kunde->firma</td><td>$kunde->strasse $kunde->hausnummer</td><td>$kunde->plz</td><td>$kunde->ort</td></tr>\n";
		}
		$return.="</table>\n";
		return $return;
	}

	function detail($id){
		$return="";
		$db=new datenbank();
		$result=$db->query("select * from kunden where id=$id");
		$kunde=$db->get_object($result);
		$return.="<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">\n";
		$return.="<tr><td>\n";
		$return.="<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n";
		$return.="<tr><td>$kunde->firma</td></tr>\n";
		$return.="<tr><td>$kunde->anrede $kunde->vorname $kunde->nachname</td></tr>\n";
		$return.="<tr><td>$kunde->strasse $kunde->hausnummer</td></tr>\n";
		$return.="<tr><td>$kunde->postfach</td></tr>\n";
		$return.="<tr><td>$kunde->plz $kunde->ort</td></tr>\n";
		$return.="</table>\n";
		$return.="</td><td>\n";
		$return.="<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n";
		$return.="<tr><td>Tel: 0$kunde->vorwahl $kunde->telefon</td></tr>\n";
		$return.="<tr><td>Tel: 0$kunde->vorwahl $kunde->durchwahl</td></tr>\n";
		$return.="<tr><td>Fax: 0$kunde->vorwahl $kunde->fax</td></tr>\n";
		$return.="</table>\n";
		$return.="</td><td>\n";
		$return.="<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n";
		$return.="<tr><td>$kunde->email</td></tr>\n";
		$return.="<tr><td>$kunde->www</td></tr>\n";
		$return.="</table>\n";
		$return.="</td></tr>\n</table><br><br><br>\n";
		$fakturaliste=$db->query("select posten.id, posten.datum, posten.anzahl, posten.kommentar, produkte.name as produktname from posten,produkte where kunde=$kunde->id and isnull(rechnung) and produkte.id=posten.produkt order by datum");
		if($db->num($fakturaliste)>0){
			$return.="<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">\n";
			$return.="<tr><td colspan=\"3\">".$db->num($fakturaliste)." fakturierbare Posten gefunden:</td><td align=\"right\"><a href=\"index.php?sub=kunden&action=rechnung_neu&id=$kunde->id\">Rechnung stellen</a></td></tr>\n";
			$return.="<tr style=\"background-color: lightblue\"><td>Datum</td><td>Produkt</td><td>Anzahl</td><td>Kommentar</td></tr>\n";
			while($posten=$db->get_object($fakturaliste)){
				$zeile="zeile"+$posten->id;
				$return.="<tr id=\"$zeile\" style=\"background-color: lightgrey\" onmouseover=\"changecolor('$zeile','lightgreen');\" onmouseout=\"changecolor('$zeile','lightgrey');\"><td>$posten->datum</td><td>$posten->produktname</td><td>$posten->anzahl</td><td>$posten->kommentar</td></tr>\n";
			}
			$return.="</table>\n";
		}else {
			$return.="Keine fakturierbaren Posten vorhanden...";
		}
		return $return;
	}
	
	function rechnung_neu($id){
		$return="";
		$return.="$id";
		$db=new datenbank();
		$result=$db->query("select * from kunden where id=$id");
		$kunde=$db->get_object($result);
		$pdf=new pdf('P', 'mm', 'A4');
		$pdf->Open();
		$pdf->AddPage();
		$pdf->empfaenger($kunde->firma, $kunde->strasse." ".$kunde->hausnummer, $kunde->plz." ".$kunde->ort);
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(40,10,'Hello World!');
		$pdf->Output();
		return $return;
	}
}
