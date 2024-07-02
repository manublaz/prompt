<?php
// Include the simple_html_dom library
require 'simple_html_dom.php';

// URL of the National Library of Spain record
$url = 'https://datos.bne.es/edicion/bimo0001291967.html';

// Create an instance of simple_html_dom
$html = file_get_html($url);

if(!$html) {
    die('Could not access the page');
}

// Extract the bibliographic data
$title = $html->find('h1[property="dc:title"]', 0);
$author = $html->find('span[property="dc:creator"]', 0);
$date = $html->find('span[property="dc:date"]', 0);
$publisher = $html->find('span[property="dc:publisher"]', 0);
$description = $html->find('div[property="dc:description"]', 0);

echo "Title: " . ($title ? $title->plaintext : 'Not available') . "\n";
echo "Author: " . ($author ? $author->plaintext : 'Not available') . "\n";
echo "Date: " . ($date ? $date->plaintext : 'Not available') . "\n";
echo "Publisher: " . ($publisher ? $publisher->plaintext : 'Not available') . "\n";
echo "Description: " . ($description ? $description->plaintext : 'Not available') . "\n";
?>
