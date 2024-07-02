<?php
// Incluye la biblioteca simple_html_dom
require 'simple_html_dom.php';

// URL del registro de la Biblioteca Nacional de España
$url = 'https://datos.bne.es/edicion/bimo0001291967.html';

// Crea una instancia de simple_html_dom
$html = file_get_html($url);

if(!$html) {
    die('No se pudo acceder a la página');
}

// Extrae los datos bibliográficos
$title = $html->find('h1[property="dc:title"]', 0);
$author = $html->find('span[property="dc:creator"]', 0);
$date = $html->find('span[property="dc:date"]', 0);
$publisher = $html->find('span[property="dc:publisher"]', 0);
$description = $html->find('div[property="dc:description"]', 0);

echo "Título: " . ($title ? $title->plaintext : 'No disponible') . "\n";
echo "Autor: " . ($author ? $author->plaintext : 'No disponible') . "\n";
echo "Fecha: " . ($date ? $date->plaintext : 'No disponible') . "\n";
echo "Editorial: " . ($publisher ? $publisher->plaintext : 'No disponible') . "\n";
echo "Descripción: " . ($description ? $description->plaintext : 'No disponible') . "\n";
?>