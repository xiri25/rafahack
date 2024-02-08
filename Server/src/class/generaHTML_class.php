<?php

class generaHTML 
{

	public static function tabla ($registros, $atributos="", $enlaces = "")   {

		$atributos = ($atributos==="") ? "border = '0'" : $atributos ;
		$html = "<table $atributos> \n" ;
		$tr = "<tr>" ;
		foreach ($registros[0] as $campo => $valor) {
			$tr .= "<th>$campo</th>" ;
		}
		$columnaEnlaces = ( $enlaces == "" ) ? "" : "<th>Acciones</th>" ;
		$html .= "$tr$columnaEnlaces</tr>\n" ;

		foreach ($registros as $registro) {
			$tr = "<tr>" ;
			foreach ($registro as $campo => $valor) {
				$tr .= "<td>$valor</td>" ;
			}
			$enlacesConId = ( $enlaces == "" ) ? "" : "<td>" . str_replace ( 'XXX' , $registro['id'] , $enlaces ) . "</td>";
			$html .= "$tr$enlacesConId</tr>\n" ;
		} 
		$html .= "</table>\n" ;

		return $html ;
	} 

	public static function select ($registros, $valorSeleccionado="", $atributos="")   {

		$html = "<select $atributos>\n" ;

		foreach ($registros as $registro) {
			$valores = [] ;
			foreach ($registro as $campo => $valor) {
				$valores[] = $valor ;
			}
			$attrSelected = ( $valorSeleccionado == $valores[0] ) ? 'selected="selected"' : '' ;
			$html .= "<option $attrSelected value=\"{$valores[0]}\" >{$valores[1]}</option>\n";
		}

		$html .= "</select>\n" ;
		return $html ;
	}

	public static function options ($registros, $valorSeleccionado="")   {
		
		$html = "";
		foreach ($registros as $registro) {
			$valores = [] ;
			foreach ($registro as $campo => $valor) {
				$valores[] = $valor ;
			}
			$attrSelected = ( $valorSeleccionado == $valores[0] ) ? 'selected="selected"' : '' ;
			$html .= "<option $attrSelected value=\"{$valores[0]}\" >{$valores[1]}</option>\n";
		}

		return $html ;
	}

}