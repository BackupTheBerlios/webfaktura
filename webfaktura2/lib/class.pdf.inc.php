<?
### pdf.class
#
class pdf extends FPDF
{
	//Page header
	function Header(){
		$this->SetLeftMargin(25);
		$this->SetRightMargin(20);
		//Logo
		$this->Image($GLOBALS["conf"]["rechnung"]["logo"],150,20,0,38);
		//Arial bold 15
		$this->SetFont('Arial','',10);
		//Move to the right
		$this->SetXY($GLOBALS["conf"]["rechnung"]["adresse"]["x"],$GLOBALS["conf"]["rechnung"]["adresse"]["y"]);
		//Absender
		$this->Cell(50,4,$GLOBALS["conf"]["rechnung"]["adresse"]["name"]);
		$this->Ln();
		$this->SetX($GLOBALS["conf"]["rechnung"]["adresse"]["x"]);
		$this->Cell(50,4,$GLOBALS["conf"]["rechnung"]["adresse"]["adresse"]);
		$this->Ln();
		$this->SetX($GLOBALS["conf"]["rechnung"]["adresse"]["x"]);
		$this->Cell(50,4,$GLOBALS["conf"]["rechnung"]["adresse"]["plzort"]);
		$this->Ln();
		$this->SetX($GLOBALS["conf"]["rechnung"]["adresse"]["x"]);
		$this->Cell(50,4,"Telefon: ".$GLOBALS["conf"]["rechnung"]["adresse"]["telefon"]);
		$this->Ln();
		$this->SetX($GLOBALS["conf"]["rechnung"]["adresse"]["x"]);
		$this->Cell(50,4,"E-Mail: ".$GLOBALS["conf"]["rechnung"]["adresse"]["mail"]);
		//Absender im Empfängerfeld
		$this->SetFont('Arial','',8);
		$this->SetXY(18,38);
		$this->Cell(75,4,$GLOBALS["conf"]["rechnung"]["adresse"]["name"]." - ".$GLOBALS["conf"]["rechnung"]["adresse"]["adresse"]." - ".$GLOBALS["conf"]["rechnung"]["adresse"]["plzort"],0,0,"C");
		$this->Line(18,42,95,42);
		//Falzlinien
		$this->Line(0,138,6,138);
		$this->Line(0,93,4,93);
		//Datum & Rechnungsnummer
		$this->SetY(90);
		$this->Cell(60, 4, "Unser Zeichen");
		$this->Cell(100, 4, "Datum", 0, 0, "R");
		//Line break
		$this->Ln(15);
		$this->SetFont("Arial","",10);
	}

	function empfaenger($firma, $strasse, $ort, $renr, $datum){
		$x=$this->GetX();
		$y=$this->GetY();
		$this->SetFont("Arial","",10);
		$this->SetXY(23,46);
		$this->Cell(60,4,$firma);
		$this->Ln();
		$this->SetX(23);
		$this->Cell(60,4, $strasse);
		$this->Ln();
		$this->Ln();
		$this->SetX(23);
		$this->Cell(60,4, $ort);
		$this->SetY(94);
		$this->Cell(60,4, $renr);
		$this->Cell(100, 4, $datum, 0, 0, "R");
		$this->SetXY($x, $y);
	}

	//Page footer
	function Footer(){
		//draw line
		$this->Line(10,272,200,272);
		//Position at 1.5 cm from bottom
		$this->SetY(-25);
		//Arial italic 8
		$this->SetFont('Arial','',8);
		//Page number
		$this->Cell(0,10,$GLOBALS["conf"]["rechnung"]["bankverbindung"],0,0,'C');
	}
	
	function table($header, $data){
		//Column widths
    		$w=array(20,56,15,43,15,15);
    		//Header
    		for($i=0;$i<count($header);$i++)
        		$this->Cell($w[$i],5,$header[$i],1,0,'C');
    		$this->Ln();
    		//Data
    		foreach($data as $row)
    		{
        		$this->Cell($w[0],4,$row[0],'LR');
        		$this->Cell($w[1],4,substr($row[1],0,30),'LR');
			$this->Cell($w[2],4,number_format($row[2],2,",","."),'LR',0,'R');
        		$this->Cell($w[3],4,$row[3],'LR');
			$this->Cell($w[4],4,number_format($row[4],2,",",".").EURO, 'LR', 0, 'R');
			$this->Cell($w[5],4,number_format($row[5],2,",",".").EURO, 'LR', 0, 'R');
        		$this->Ln();
    		}
    		//Closure line
    		$this->Cell(array_sum($w),0,'','T');
	}
}
?>
