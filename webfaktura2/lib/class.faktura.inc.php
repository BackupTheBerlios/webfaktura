<?
### class.faktura.inc.php - Basisfunktinen

class faktura{
	function table($query, $db, $id="tabelle", $anzahlstring="&nbsp;", $fehlerstring="", $function1="", $function2="", $gfunction1="&nbsp", $gfunction2="&nbsp"){
		$return="";
		$result=$db->query($query);
		if($db->num($result)>0){
			$return.="<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\">\n";
			$nr=0;
			if($function1!=""){$nr+=1;}
			if($function2!=""){$nr+=1;}
			$return.="<tr><td colspan=\".$num+$nr-1.\">";
			if($anzahlstring!=("" OR "&nbsp;"){$return.=$db->num($result)." ";}
			$return.=$anzahlstring."</td><td align=\"right\">$gfunction1</td><td align=\"right\">$gfunction2</td></tr>\n";
			$return.="<tr>";
			if($function1!=""){
				$return.="<td>&nbsp;</td>";
			}
			if($function2!=""){
				$return.="<td>&nbsp;</td>";
			}
			$num=$db->num_fields($result);
			for($i=1; $i<$num; $i++){
				$return.="<td style=\"background-color: lightblue\">".$db->fieldname($result, $i)."</td>";
			}
			$return.="</tr>\n";
			while($posten=$db->get_row($result)){
				$zeile="zeile".$id.$posten[0];
				$return.="<tr id=\"$zeile\" style=\"background-color: lightgrey\" onmouseover=\"changecolor('$zeile','lightgreen');\" onmouseout=\"changecolor('$zeile','lightgrey');\">";
				if($function1!=""){
					$func1=str_replace("ID", $posten[0], $function1);
					$return.="<td>$func1</td>";
				}
				if($function2!=""){
					$func2=str_replace("ID", $posten[0], $function2);
					$return.="<td>$func2</td>";
				}
				for($i=1; $i<$num; $i++){
					$return.="<td>$posten[$i]</td>";
				}
				$return.="</tr>\n";
			}
			$return.="</table>\n";
		}else {
			$return.=$fehlerstring;
		}
		return $return;
	}
}
?>
