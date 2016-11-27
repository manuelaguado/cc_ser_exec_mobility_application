<?php
class geoCerca {
    var $puntoEnVertice = true; // Compruebe si el punto se encuentra exactamente en uno de los vértices
 
    function puntoEnPoligono($punto, $poligono, $puntoEnVertice = true) {
        $this->puntoEnVertice = $puntoEnVertice;
 
        // Transforma las coordenadas en matrices con valores x e y
        $punto = $this->puntosaLatitudes($punto);
        $vertices = array();
		
		$poligono = explode(' ',$poligono);
		
        foreach ($poligono as $vertice) {
            $vertices[] = $this->puntosaCoordenadas($vertice); 
        }
 
        // Compruebe si el punto se encuentra exactamente en un vértice
        if ($this->puntoEnVertice == true and $this->puntoEnVertice($punto, $vertices) == true) {
            return "in"; //vertice
        }
 
        // Compruebe si el punto está dentro del polígono o en el límite
        $intersecciones = 0; 
        $cuenta_vertices = count($vertices);
 
        for ($i=1; $i < $cuenta_vertices; $i++) {
            $vertice1 = $vertices[$i-1]; 
            $vertice2 = $vertices[$i];
            if ($vertice1['y'] == $vertice2['y'] and $vertice1['y'] == $punto['y'] and $punto['x'] > min($vertice1['x'], $vertice2['x']) and $punto['x'] < max($vertice1['x'], $vertice2['x'])) { // Comprobar si el punto está en un límite de polígono horizontal
                return "in"; //limite
            }
            if ($punto['y'] > min($vertice1['y'], $vertice2['y']) and $punto['y'] <= max($vertice1['y'], $vertice2['y']) and $punto['x'] <= max($vertice1['x'], $vertice2['x']) and $vertice1['y'] != $vertice2['y']) { 
                $xintersec = ($punto['y'] - $vertice1['y']) * ($vertice2['x'] - $vertice1['x']) / ($vertice2['y'] - $vertice1['y']) + $vertice1['x']; 
                if ($xintersec == $punto['x']) { // Compruebe si el punto está en el límite del polígono (que no sea horizontal)
                    return "in"; //limite
                }
                if ($vertice1['x'] == $vertice2['x'] || $punto['x'] <= $xintersec) {
                    $intersecciones++; 
                }
            } 
        } 
        // Si el número de aristas que pasamos por el poligono es impar , entonces esta dentro.
        if ($intersecciones % 2 != 0) {
            return "in";
        } else {
            return "out";
        }
    }
	
    function puntoEnVertice($punto, $vertices) {
        foreach($vertices as $vertice) {
            if ($punto == $vertice) {
                return true;
            }
        }
 
    }
	function puntosaLatitudes($puntoString) {
        $coordinates = explode(",", $puntoString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }
    function puntosaCoordenadas($puntoString) {
        $coordinates = explode(",", $puntoString);
        return array("x" => $coordinates[1], "y" => $coordinates[0]);
    }
 
}
?>