<?php
// URL de la página que queremos scrapear
$url = "https://datos.bne.es/edicion/bimo0001291967.html";

// Función para obtener el contenido HTML de la página usando cURL
function getHtmlContent($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}

// Obtener el contenido HTML de la página
$html = getHtmlContent($url);

// Crear un nuevo objeto DOMDocument
$dom = new DOMDocument();
@$dom->loadHTML($html);

// Crear un nuevo objeto DOMXPath
$xpath = new DOMXPath($dom);

// Expresiones XPath para seleccionar las filas de la tabla
$rows = $xpath->query("//tr");

// Inicializar el array para almacenar los datos
$data = [];

// Función para limpiar y normalizar el texto
function cleanText($text) {
    return trim(preg_replace('/\s+/', ' ', $text));
}

// Recorrer las filas y extraer los datos usando preg_match
foreach ($rows as $row) {
    $label = $xpath->query("td[@class='label-row']/strong", $row)->item(0);
    $value = $xpath->query("td[2]", $row)->item(0);
    
    if ($label && $value) {
        $labelText = cleanText($label->nodeValue);
        $valueText = cleanText($value->nodeValue);
        
        switch ($labelText) {
            case 'Título':
                $data['titulo'] = $valueText;
                break;
            case 'Lugar de publicación':
                $data['lugarPublicacion'] = $valueText;
                break;
            case 'Editorial':
                $data['editorial'] = $valueText;
                break;
            case 'Fecha de publicación':
                $data['fechaPublicacion'] = $valueText;
                break;
            case 'Descripción física o extensión':
                $data['descripcionFisica'] = $valueText;
                break;
            case 'Otras características físicas':
                $data['otrasCaracteristicasFisicas'] = $valueText;
                break;
            case 'Dimensiones':
                $data['dimensiones'] = $valueText;
                break;
            case 'Tipo de material':
                $data['tipoMaterial'] = $valueText;
                break;
            case 'Signatura':
                $data['signatura'] = $valueText;
                break;
            case 'Localización':
                $data['localizacion'] = $valueText;
                break;
            case 'Sede':
                $data['sede'] = $valueText;
                break;
        }
    }
}

// Imprimir los resultados en pantalla
foreach ($data as $key => $value) {
    echo "<li><strong>$key:</strong> $value</li>";
}

// Imprimir el array para comprobar los datos
echo '<pre>';
print_r($data);
echo '</pre>';
?>