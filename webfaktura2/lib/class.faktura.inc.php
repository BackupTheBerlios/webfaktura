<?
### class.faktura.inc.php - Basisfunktinen

class faktura{
	function table($query, $db, $id, $anzahlstring, $fehlerstring, $function="&nbsp";){
		$return="";
		$result=$db->query($query);
		if($db->num($result)>0){
			$return.="<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">\n";
			$return.="<tr><td colspan=\"3\">".$db->num($result)." ".$anzahlstring."</td><td align=\"right\">$function</td></tr>\n";
			$return.="<tr style=\"background-color: lightblue\">";
			$num=$db->num_fields($result);
			for($i=1; $i<$num; $i++){
				$return.="<td>".$db->fieldname($result, $i)."</td>";
			}
			$return.="</tr>\n";
			while($posten=$db->get_row($result)){
				$zeile="zeile".$id.$posten[0];
				$return.="<tr id=\"$zeile\" style=\"background-color: lightgrey\" onmouseover=\"changecolor('$zeile','lightgreen');\" onmouseout=\"changecolor('$zeile','lightgrey');\">";
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
