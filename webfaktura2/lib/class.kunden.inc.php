<?
### class.kunden.inc.php - Klasse für die Kundenverwaltung
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
		case "rechnung_neu":	$this->content.=$this->rechnung_neu($_GET["id"]);
					$this->content.=$this->detail($_GET["id"]);
					break;
		case "rechnung_fertig":	$this->content.=$this->rechnung_fertig($_GET["id"]);
					$this->content.=$this->rechnung_pdf($_GET["id"]);
					break;
		case "rechnung_pdf":	$this->content.=$this->rechnung_pdf($_GET["id"]);
					break;
		case "za_neu":		$this->content.=$this->za_neu($_GET["id"]);
					break;
		case "za_pdf":		$this->content.=$this->za_pdf($_GET["id"]);
					break;
		case "ma_neu":	$this->content.=$this->ma_neu($_GET["id"]);
					break;
		case "mahnung_stufe2":	$this->content.=$this->ma_stufe2($_GET["id"]);
					break;
		case "ma2_pdf":		$this->content.=$this->ma2_pdf($_GET["id"]);
					break;
		case "ma_pdf":		$this->content.=$this->ma_pdf($_GET["id"]);
					break;
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
		$return.=faktura::table("select kunden.kdnr as id, kunden.kdnr as Kundennummer, kunden.firma as Firma, CONCAT(kunden.strasse, ' ', kunden.hausnummer) AS Adresse, kunden.plz as PLZ, kunden.ort AS Ort from kunden order by firma asc", $db, "kundentabelle", "", "Keine Kunden vorhanden...", "<a href=\"index.php?sub=kunden&action=edit&id=ID\">Bearbeiten</a>", "<a href=\"index.php?sub=kunden&action=detail&id=ID\">Anzeigen</a>");
		return $return;
	}

	function detail($id){
		$return="";
		$db=new datenbank();
		$result=$db->query("select * from kunden where kdnr=$id");
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
		//Offene Posten
		$return.=faktura::table("select posten.id as id, posten.datum as Datum, produkte.name as Produkt, posten.anzahl as Anzahl, posten.kommentar as Kommentar from posten,produkte where kunde=$kunde->kdnr and isnull(rechnung) and produkte.id=posten.produkt order by datum", $db, "offeneposten", "fakturierbare(r) Posten gefunden:", "", "Bearbeiten", "Löschen", "<a href=\"index.php?sub=kunden&action=rechnung_neu&id=$kunde->kdnr\">Rechnung stellen</a>");
		//fertige Rechnungen
		$return.=faktura::table("select rechnungen.renr as id, rechnungen.renr as Rechnungsnummer from rechnungen where rechnungen.datum is NULL and rechnungen.kunde=$kunde->kdnr", $db, "fertigrechnung", "fertige Rechnung(en) gefunden", "", "<a href=\"index.php?sub=kunden&action=rechnung_fertig&id=ID\">Freigeben</a>");
		//offene Rechnungen
		$return.=faktura::table("select rechnungen.renr as id, rechnungen.renr as Rechnungsnummer, rechnungen.datum as Datum, rechnungen.faellig as Faellig from rechnungen where rechnungen.kunde=$kunde->kdnr and rechnungen.datum is not null and rechnungen.bezahlt='Nein'", $db, "offenerechnung", "offene Rechnung(en) gefunden", "", "<a href=\"index.php?sub=kunden&action=rechnung_pdf&id=ID\">Ansicht</a>","<a href=\"index.php?sub=kunden&action=za_neu&id=ID\">Zahlungserinnerung</a>");
		//Zahlungserinnerungen
		$return.=faktura::table("select zahlungserinnerungen.zanr as id, zahlungserinnerungen.zanr as Nummer, zahlungserinnerungen.renr as Rechnungsnummer, zahlungserinnerungen.datum as Datum, zahlungserinnerungen.faellig as Faellig from zahlungserinnerungen where zahlungserinnerungen.kdnr=$kunde->kdnr and zahlungserinnerungen.bezahlt='Nein'", $db, "offeneza", "offene Zahlungserinnerung(en) gefunden", "", "<a href=\"index.php?sub=kunden&action=za_pdf&id=ID\">Ansicht</a>","<a href=\"index.php?sub=kunden&action=ma_neu&id=ID\">Mahnung</a>");
		//Mahnungen Stufe 1
		$return.=faktura::table("select mahnungen.manr as id, mahnungen.manr as Nummer, mahnungen.renr as Rechnungsnummer, mahnungen.datum as Datum, mahnungen.faellig as Faellig from mahnungen where mahnungen.kdnr=$kunde->kdnr and mahnungen.bezahlt='Nein' and mahnungen.stufe='1'", $db, "offenema1", "offene Mahnung(en)  Stufe 1 gefunden", "", "<a href=\"index.php?sub=kunden&action=ma_pdf&id=ID\">Ansicht</a>","<a href=\"index.php?sub=kunden&action=mahnung_stufe2&id=ID\">Stufe 2</a>");
		//Mahnungen Stufe 2
		$return.=faktura::table("select mahnungen.manr as id, mahnungen.manr as Nummer, mahnungen.renr as Rechnungsnummer, mahnungen.datum as Datum, mahnungen.faellig as Faellig from mahnungen where mahnungen.kdnr=$kunde->kdnr and mahnungen.bezahlt='Nein' and mahnungen.stufe='2'", $db, "offenema2", "offene Mahnung(en)  Stufe 2 gefunden", "", "<a href=\"index.php?sub=kunden&action=ma2_pdf&id=ID\">Ansicht</a>","<a href=\"index.php?sub=kunden&action=mahnung_stufe3&id=ID\">Stufe 3</a>");
		//Mahnungen Stufe 3
		$return.=faktura::table("select mahnungen.manr as id, mahnungen.manr as Nummer, mahnungen.renr as Rechnungsnummer, mahnungen.datum as Datum, mahnungen.faellig as Faellig from mahnungen where mahnungen.kdnr=$kunde->kdnr and mahnungen.bezahlt='Nein' and mahnungen.stufe='3'", $db, "offenema3", "offene Mahnung(en)  Stufe 3 gefunden", "", "<a href=\"index.php?sub=kunden&action=ma_pdf&id=ID\">Ansicht</a>","<a href=\"index.php?sub=kunden&action=mahnung_stufe4&id=ID\">Stufe 4</a>");

		return $return;
	}

	function gen_renr($praefix){
		$return="";
		mt_srand((double)microtime()*1000000);
		for($i=1; $i<=5; $i++){
			$return.=mt_rand(0,9);
		}
		return $praefix.$return;
	}

	function rechnung_neu($id){
		$return="";
		$db=new datenbank();
		$result=$db->query("select * from kunden where kdnr=$id");
		$kunde=$db->get_object($result);
		$renr=$this->gen_renr("RE");
		$query="begin work;";
		$result=$db->query($query);
		$query="insert into `rechnungen` (`renr`, `kunde`) values('$renr', $id);";
		$result=$db->query($query);
		$query="update posten set rechnung='$renr' where kunde=$id and rechnung is NULL;";
		$result=$db->query($query);
		$query="commit;";
		$result=$db->query($query);
		//$return.="Rechnung generiert!";
		return $return;
	}

	function rechnung_fertig($id){
		$return="";
		$db=new datenbank();
		$query="update rechnungen set datum='".date("Y-m-d")."', faellig='".date("Y-m-d",time()+1209600)."' where renr='$id'";
		$result=$db->query($query);
		$return.=$query;
		return $return;
	}

	function rechnung_pdf($id){
		$return="";
		$db=new datenbank();
		$query="select * from rechnungen where renr='$id'";
		$result=$db->query($query);
		$rechnung=$db->get_object($result);
		$result_kunde=$db->query("select * from kunden where kdnr=$rechnung->kunde");
		$kunde=$db->get_object($result_kunde);
		$pdf=new pdf('P', 'mm', 'A4');
		$pdf->Open();
		$pdf->AddPage();
		$pdf->empfaenger($kunde->firma, $kunde->strasse." ".$kunde->hausnummer, $kunde->plz." ".$kunde->ort, $rechnung->renr, $rechnung->datum);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(80,5,"RECHNUNG");
		$pdf->Ln(10);
		$pdf->SetFont("Arial", "", 10);
		$pdf->Write(5, "Sehr geehrte Damen und Herren,\nhiermit erlaube ich mir folgendes in Rechnung zu stellen:\n\n");
		$query="select posten.datum as Datum, posten.kommentar as Beschreibung, posten.anzahl as Anzahl, produkte.name as Artikel, produkte.preis as Preis, (produkte.preis*posten.anzahl) as Summe from posten, produkte where posten.rechnung='$id' and produkte.id=posten.produkt and produkte.id!='3'";
		$result=$db->query($query);
		$header=array("Datum", "Beschreibung", "Anzahl", "Artikel", "Preis", "Summe");
		while($data[]=$db->get_row($result))
		{
		}
		$gammel=array_pop($data);
		$pdf->table($header, $data);
		$pdf->Ln();
		$result=$db->query("SELECT Sum( posten.anzahl * produkte.preis )  AS Gesamt, Sum( posten.anzahl * produkte.preis * mwst.satz / 100  ) AS MWST, mwst.satz FROM posten, produkte, mwst WHERE produkte.id = posten.produkt AND mwst.id = produkte.mwst AND posten.rechnung =  '$rechnung->renr' AND produkte.id!='3' GROUP BY mwst.satz");
		$betrag=$db->get_object($result);
		//$result=$db->query("SELECT Sum( posten.anzahl * produkte.preis )  AS Gesamt, Sum( posten.anzahl * produkte.preis * mwst.satz / 100  ) AS MWST, mwst.satz FROM posten, produkte, mwst WHERE produkte.id = posten.produkt AND mwst.id = produkte.mwst AND posten.rechnung =  '$rechnung->renr' AND produkte.id = '3' GROUP BY mwst.satz");
		//$betrag2=$db->get_object($result);
		$pdf->Cell(100,5,"", 0, 0, 'L');
		$pdf->Cell(35,5,"Gesamt:",0,0,'L');
		$pdf->Cell(20,5,number_format(($betrag->Gesamt),2,",",".").EURO, 0, 1, 'R');
		$pdf->Cell(100,5,"", 0, 0, 'L');
		$pdf->Cell(35,5,"Mehrwertsteuer (".number_format($betrag->satz)."%):", 0, 0, 'L');
		$pdf->Cell(20,5,number_format($betrag->MWST,2,",",".").EURO, 0, 1, 'R');
		$pdf->Ln();
		$pdf->Cell(100,5,"", 0, 0, 'L');
		$pdf->Cell(35,5,"Rechnungsbetrag:", 0, 0, 'L');
		$pdf->Cell(20,5,number_format($betrag->Gesamt+$betrag->MWST,2,",",".").EURO, 0, 1, 'R');
		$pdf->Ln();
		$pdf->Write(5, "Bitte überweisen Sie den oben genannten Rechnungsbetrag bis spätestens zum $rechnung->faellig auf das unten aufgeführte Konto.\nÜber eine weitere Zusammenarbeit mit Ihnen würde ich mich sehr freuen und verbleibe mit freundlichen Grüßen\n");
		$pdf->Ln(15);
		$pdf->Write(5, $GLOBALS["conf"]["rechnung"]["adresse"]["name"]);
		$pdf->Image($GLOBALS["conf"]["rechnung"]["unterschrift"],25,$pdf->GetY()-10,50);
		$this->output=0;
		$pdf->Output();
		return $return;
	}

	function za_neu($id){
		$return="";
		$db=new datenbank();
		$query="select rechnungen.kunde from rechnungen where rechnungen.renr='$id'";
		$result=$db->query($query);
		$kd=$db->get_object($result);
		$kdnr=$kd->kunde;
		$zanr=$this->gen_renr("ZA");
		$query="insert into zahlungserinnerungen values('$zanr','$id','$kdnr','".date("Y-m-d")."', '".date("Y-m-d",time()+1209600)."', 'Nein')";
		$db->query($query);
		return $return;
	}

	function za_pdf($id){
		$return="";
		$db=new datenbank();
		$query="select * from zahlungserinnerungen where zanr='$id'";
		$result=$db->query($query);
		$za=$db->get_object($result);
		$query="select * from rechnungen where renr='$za->renr'";
		$result=$db->query($query);
		$re=$db->get_object($result);
		$kunde=$db->get_object($db->query("select * from kunden where kdnr='$za->kdnr'"));
		$result=$db->query("SELECT Sum( posten.anzahl * produkte.preis )  AS Gesamt, Sum( posten.anzahl * produkte.preis * mwst.satz / 100  ) AS MWST, mwst.satz FROM posten, produkte, mwst WHERE produkte.id = posten.produkt AND mwst.id = produkte.mwst AND posten.rechnung =  '$re->renr' GROUP BY mwst.satz");
		$betrag=$db->get_object($result);

		$pdf=new pdf('P', 'mm', 'A4');
		$pdf->Open();
		$pdf->AddPage();
		$pdf->empfaenger($kunde->firma, $kunde->strasse." ".$kunde->hausnummer, $kunde->plz." ".$kunde->ort, $za->zanr, $za->datum);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(80,5,"Zahlungserinnerung");
		$pdf->Ln(10);
		$pdf->SetFont("Arial", "", 10);
		$pdf->Write(5, "Sehr geehrte Damen und Herren,\nsicherlich haben Sie es nur übersehen, die ausstehende Rechnung $re->renr vom $re->datum über ".number_format($betrag->Gesamt+$betrag->MWST,2,",",".").EURO." EUR zu begleichen.\nIch bitte Sie, dieses bis spätestens zum $za->faellig nachzuholen.\n\nSollte sich ihre Zahlung mit diesem Schreiben gekreuzt haben, so ist dieses Schreiben natürlich gegenstandslos. Für Fragen stehen ich ihnen gerne unter der oben genannten Adresse zur Verfügung.");
		$pdf->Ln(30);
		$pdf->Write(5, "Mit freundlichen Grüßen");
		$pdf->Ln(20);
		$pdf->Write(5, $GLOBALS["conf"]["rechnung"]["adresse"]["name"]);
		$pdf->Image($GLOBALS["conf"]["rechnung"]["unterschrift"],25,$pdf->GetY()-10,50);
		$this->output=0;
		$pdf->Output();
		return $return;
	}

	function ma_neu($id){
		$return="";
		$db=new datenbank();
		$query="select zahlungserinnerungen.kdnr, zahlungserinnerungen.renr  from zahlungserinnerungen where zahlungserinnerungen.zanr='$id'";
		$result=$db->query($query);
		$kd=$db->get_object($result);
		$renr=$kd->renr;
		$kdnr=$kd->kdnr;
		$manr=$this->gen_renr("MA");
		$query="insert into mahnungen values('$manr','$renr','$kdnr','".date("Y-m-d")."', '".date("Y-m-d",time()+1209600)."', 'Nein','1')";
		$db->query($query);
		$query="insert into posten values('','$kdnr','".date("Y-m-d")."','3','1.00','','$renr')";
		$db->query($query);
		return $return;
	}

	function ma_stufe2($id){
		$return="";
		$db=new datenbank();
		$query="select zahlungserinnerungen.kdnr, zahlungserinnerungen.renr  from zahlungserinnerungen where zahlungserinnerungen.zanr='$id'";
		$result=$db->query($query);
		$kd=$db->get_object($result);
		$renr=$kd->renr;
		$kdnr=$kd->kdnr;
		$manr=$this->gen_renr("MA");
		$query="insert into mahnungen values('$manr','$renr','$kdnr','".date("Y-m-d")."', '".date("Y-m-d",time()+1209600)."', 'Nein','2')";
		$db->query($query);
		$query="insert into posten values('','$kdnr','".date("Y-m-d")."','4','1.00','','$renr')";
		$db->query($query);
		return $return;
	}


		function ma_pdf($id){
		$return="";
		$db=new datenbank();
		$query="select * from rechnungen,mahnungen  where rechnungen.renr=mahnungen.renr and mahnungen.manr='$id'";
		$result=$db->query($query);
		$rechnung=$db->get_object($result);
		$result_kunde=$db->query("select * from kunden where kdnr=$rechnung->kunde");
		$kunde=$db->get_object($result_kunde);
		$pdf=new pdf('P', 'mm', 'A4');
		$pdf->Open();
		$pdf->AddPage();
		$pdf->empfaenger($kunde->firma, $kunde->strasse." ".$kunde->hausnummer, $kunde->plz." ".$kunde->ort, $rechnung->manr, $rechnung->datum);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(80,5,"Mahnung");
		$pdf->Ln(10);
		$pdf->SetFont("Arial", "", 10);
		$pdf->Write(5, "Sehr geehrte Damen und Herren,\nleider konnte ich noch keinen Zahlungseingang zur Rechnung $rechnung->renr feststellen. Hier die Auflistung der aufgrund dieser Rechnung unbezahlten Posten::\n\n");
		$query="select posten.datum as Datum, posten.kommentar as Beschreibung, posten.anzahl as Anzahl, produkte.name as Artikel, produkte.preis as Preis, (produkte.preis*posten.anzahl) as Summe from posten, produkte where posten.rechnung='$rechnung->renr' and produkte.id=posten.produkt";
		$result=$db->query($query);
		$header=array("Datum", "Beschreibung", "Anzahl", "Artikel", "Preis", "Summe");
		while($data[]=$db->get_row($result))
		{
		}
		$gammel=array_pop($data);
		$pdf->table($header, $data);
		$pdf->Ln();
		$result=$db->query("SELECT Sum( posten.anzahl * produkte.preis )  AS Gesamt, Sum( posten.anzahl * produkte.preis * mwst.satz / 100  ) AS MWST, mwst.satz FROM posten, produkte, mwst WHERE produkte.id = posten.produkt AND mwst.id = produkte.mwst AND posten.rechnung =  '$rechnung->renr' AND produkte.id!='3' GROUP BY mwst.satz");
		$betrag=$db->get_object($result);
		$result=$db->query("SELECT Sum( posten.anzahl * produkte.preis )  AS Gesamt, Sum( posten.anzahl * produkte.preis * mwst.satz / 100  ) AS MWST, mwst.satz FROM posten, produkte, mwst WHERE produkte.id = posten.produkt AND mwst.id = produkte.mwst AND posten.rechnung =  '$rechnung->renr' AND produkte.id = '3' GROUP BY mwst.satz");
		$betrag2=$db->get_object($result);
		$pdf->Cell(100,5,"", 0, 0, 'L');
		$pdf->Cell(35,5,"Gesamt:",0,0,'L');
		$pdf->Cell(20,5,number_format(($betrag->Gesamt+$betrag2->Gesamt),2,",",".").EURO, 0, 1, 'R');
		$pdf->Cell(100,5,"", 0, 0, 'L');
		$pdf->Cell(35,5,"Mehrwertsteuer (".number_format($betrag->satz)."%):", 0, 0, 'L');
		$pdf->Cell(20,5,number_format($betrag->MWST,2,",",".").EURO, 0, 1, 'R');
		$pdf->Ln();
		$pdf->Cell(100,5,"", 0, 0, 'L');
		$pdf->Cell(35,5,"Mahnbetrag:", 0, 0, 'L');
		$pdf->Cell(20,5,number_format($betrag->Gesamt+$betrag2->Gesamt+$betrag->MWST,2,",",".").EURO, 0, 1, 'R');
		$pdf->Ln();
		$pdf->Write(5, "Bitte überweisen Sie den oben genannten Betrag bis spätestens zum $rechnung->faellig auf das unten aufgeführte Konto.\nÜber eine weitere Zusammenarbeit mit Ihnen würde ich mich sehr freuen und verbleibe mit freundlichen Grüßen\n");
		$pdf->Ln(15);
		$pdf->Write(5, $GLOBALS["conf"]["rechnung"]["adresse"]["name"]);
		$pdf->Image($GLOBALS["conf"]["rechnung"]["unterschrift"],25,$pdf->GetY()-10,50);
		$this->output=0;
		$pdf->Output();
		return $return;
	}

		function ma2_pdf($id){
		$return="";
		$db=new datenbank();
		$query="select * from rechnungen,mahnungen  where rechnungen.renr=mahnungen.renr and mahnungen.manr='$id'";
		$result=$db->query($query);
		$rechnung=$db->get_object($result);
		$result_kunde=$db->query("select * from kunden where kdnr=$rechnung->kunde");
		$kunde=$db->get_object($result_kunde);
		$pdf=new pdf('P', 'mm', 'A4');
		$pdf->Open();
		$pdf->AddPage();
		$pdf->empfaenger($kunde->firma, $kunde->strasse." ".$kunde->hausnummer, $kunde->plz." ".$kunde->ort, $rechnung->manr, $rechnung->datum);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(80,5,"Mahnung");
		$pdf->Ln(10);
		$pdf->SetFont("Arial", "", 10);
		$pdf->Write(5, "Sehr geehrte Damen und Herren,\nleider konnte ich noch keinen Zahlungseingang zur Rechnung $rechnung->renr feststellen. Hier die Auflistung der aufgrund dieser Rechnung unbezahlten Posten::\n\n");
		$query="select posten.datum as Datum, posten.kommentar as Beschreibung, posten.anzahl as Anzahl, produkte.name as Artikel, produkte.preis as Preis, (produkte.preis*posten.anzahl) as Summe from posten, produkte where posten.rechnung='$rechnung->renr' and produkte.id=posten.produkt";
		$result=$db->query($query);
		$header=array("Datum", "Beschreibung", "Anzahl", "Artikel", "Preis", "Summe");
		while($data[]=$db->get_row($result))
		{
		}
		$gammel=array_pop($data);
		$pdf->table($header, $data);
		$pdf->Ln();
		$result=$db->query("SELECT Sum( posten.anzahl * produkte.preis )  AS Gesamt, Sum( posten.anzahl * produkte.preis * mwst.satz / 100  ) AS MWST, mwst.satz FROM posten, produkte, mwst WHERE produkte.id = posten.produkt AND mwst.id = produkte.mwst AND posten.rechnung =  '$rechnung->renr' AND produkte.id!='3' AND produkte.id!=4 GROUP BY mwst.satz");
		$betrag=$db->get_object($result);
		$result=$db->query("SELECT Sum( posten.anzahl * produkte.preis )  AS Gesamt, Sum( posten.anzahl * produkte.preis * mwst.satz / 100  ) AS MWST, mwst.satz FROM posten, produkte, mwst WHERE produkte.id = posten.produkt AND mwst.id = produkte.mwst AND posten.rechnung =  '$rechnung->renr' AND produkte.id = '3' AND produkte.id='4' GROUP BY mwst.satz");
		$betrag2=$db->get_object($result);
		$pdf->Cell(100,5,"", 0, 0, 'L');
		$pdf->Cell(35,5,"Gesamt:",0,0,'L');
		$pdf->Cell(20,5,number_format(($betrag->Gesamt+$betrag2->Gesamt),2,",",".").EURO, 0, 1, 'R');
		$pdf->Cell(100,5,"", 0, 0, 'L');
		$pdf->Cell(35,5,"Mehrwertsteuer (".number_format($betrag->satz)."%):", 0, 0, 'L');
		$pdf->Cell(20,5,number_format($betrag->MWST,2,",",".").EURO, 0, 1, 'R');
		$pdf->Ln();
		$pdf->Cell(100,5,"", 0, 0, 'L');
		$pdf->Cell(35,5,"Mahnbetrag:", 0, 0, 'L');
		$pdf->Cell(20,5,number_format($betrag->Gesamt+$betrag2->Gesamt+$betrag->MWST,2,",",".").EURO, 0, 1, 'R');
		$pdf->Ln();
		$pdf->Write(5, "Bitte überweisen Sie den oben genannten Betrag bis spätestens zum $rechnung->faellig auf das unten aufgeführte Konto.\nÜber eine weitere Zusammenarbeit mit Ihnen würde ich mich sehr freuen und verbleibe mit freundlichen Grüßen\n");
		$pdf->Ln(15);
		$pdf->Write(5, $GLOBALS["conf"]["rechnung"]["adresse"]["name"]);
		$pdf->Image($GLOBALS["conf"]["rechnung"]["unterschrift"],25,$pdf->GetY()-10,50);
		$this->output=0;
		$pdf->Output();
		return $return;
	}


}
