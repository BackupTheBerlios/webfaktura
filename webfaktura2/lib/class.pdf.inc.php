<?
### pdf.class
#
class pdf extends FPDF
{
	//Page header
	function Header(){
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
		//Line break
		$this->Ln(60);
	}

	function empfaenger($firma, $strasse, $ort){
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
}

?>