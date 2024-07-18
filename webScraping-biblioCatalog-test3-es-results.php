<?php
// Configuración de la conexión a la base de datos
$host = 'localhost';
$dbname = 'nombre_de_tu_base_de_datos';
$username = 'tu_usuario';
$password = 'tu_contraseña';

// Crear conexión
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a la base de datos.<br>";
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

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

// Función para limpiar y normalizar el texto
function cleanText($text) {
    return trim(preg_replace('/\s+/', ' ', $text));
}

// Función para manejar valores nulos o vacíos
function handleNullValue($value) {
    return ($value !== null && $value !== '') ? $value : null;
}

// Preparar la consulta SQL de inserción
$sql = "INSERT INTO datosBNE (url, author, title, placeOfPublication, publisher, publicationDate, physicalDescription, otherPhysicalCharacteristics, dimensions, materialType, signature, location, headquarters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

// Bucle principal para recorrer los registros
for ($i = 1; $i <= 9999999999; $i++) {
    $number = str_pad($i, 10, '0', STR_PAD_LEFT);
    $url = "https://datos.bne.es/edicion/bimo{$number}.html";

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
    $data = [
        'url' => $url,
        'author' => null,
        'title' => null,
        'placeOfPublication' => null,
        'publisher' => null,
        'publicationDate' => null,
        'physicalDescription' => null,
        'otherPhysicalCharacteristics' => null,
        'dimensions' => null,
        'materialType' => null,
        'signature' => null,
        'location' => null,
        'headquarters' => null
    ];

    // Recorrer las filas y extraer los datos
    foreach ($rows as $row) {
        $label = $xpath->query("td[@class='label-row']/strong", $row)->item(0);
        $value = $xpath->query("td[2]", $row)->item(0);
        
        if ($label && $value) {
            $labelText = cleanText($label->nodeValue);
            $valueText = cleanText($value->nodeValue);
            
            switch ($labelText) {
                case 'Título':
                    $data['title'] = $valueText;
                    break;
                case 'Lugar de publicación':
                    $data['placeOfPublication'] = $valueText;
                    break;
                case 'Editorial':
                    $data['publisher'] = $valueText;
                    break;
                case 'Fecha de publicación':
                    $data['publicationDate'] = $valueText;
                    break;
                case 'Descripción física o extensión':
                    $data['physicalDescription'] = $valueText;
                    break;
                case 'Otras características físicas':
                    $data['otherPhysicalCharacteristics'] = $valueText;
                    break;
                case 'Dimensiones':
                    $data['dimensions'] = $valueText;
                    break;
                case 'Tipo de material':
                    $data['materialType'] = $valueText;
                    break;
                case 'Signatura':
                    $data['signature'] = $valueText;
                    break;
                case 'Localización':
                    $data['location'] = $valueText;
                    break;
                case 'Sede':
                    $data['headquarters'] = $valueText;
                    break;
            }
        }
    }

    // Si el título es nulo o no está especificado, salta a la siguiente URL
    if (handleNullValue($data['title']) === null) {
        continue;
    }

    // Preparar los valores para la inserción, manejando valores nulos
    $values = array_map('handleNullValue', array_values($data));

    // Ejecutar la consulta SQL
    try {
        $stmt->execute($values);
        echo "Registro insertado con éxito: {$url}<br>";
    } catch(PDOException $e) {
        echo "Error al insertar registro: " . $e->getMessage() . "<br>";
    }

    // Pausa de 3 segundos
    sleep(3);
}

// Cerrar la conexión
$pdo = null;
?>
