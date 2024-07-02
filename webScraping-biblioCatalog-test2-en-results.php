<?php
// URL of the webpage to scrape
$url = "https://datos.bne.es/edicion/bimo0001291967.html";

// Function to obtain the HTML content of the page using cURL
function getHtmlContent($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}

// Obtain the HTML content of the page
$html = getHtmlContent($url);

// Create a new DOMDocument object
$dom = new DOMDocument();
@$dom->loadHTML($html);

// Create a new DOMXPath object
$xpath = new DOMXPath($dom);

// XPath expressions to select the table rows
$rows = $xpath->query("//tr");

// Initialise the array to store the data
$data = [];

// Function to clean and normalise the text
function cleanText($text) {
    return trim(preg_replace('/\s+/', ' ', $text));
}

// Iterate through the rows and extract the data using preg_match
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

// Print the results on screen
foreach ($data as $key => $value) {
    echo "<li><strong>$key:</strong> $value</li>";
}

// Print the array to verify the data
echo '<pre>';
print_r($data);
echo '</pre>';
?>
