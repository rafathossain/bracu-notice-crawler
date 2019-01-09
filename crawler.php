<?php

include("DOM_Library.php");

//base url
$base = "https://www.bracu.ac.bd/news-events/news-archive?field_news_department_tid_selective=46";
$details_url = "";

$curl = curl_init();
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_URL, $base);
curl_setopt($curl, CURLOPT_REFERER, $base);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$str = curl_exec($curl);
curl_close($curl);

// Create a DOM object
$html_base = new simple_html_dom();

// Load HTML from a string
$html_base->load($str);

echo "
<table style='border:2px solid #000; width: 100%; border-collapse: collapse;'>
    <tr>
        <th style='border:2px solid #000; border-collapse: collapse;'>SN.</th>
        <th style='border:2px solid #000; border-collapse: collapse;'>Headline</th>
        <th style='border:2px solid #000; border-collapse: collapse;'>URL</th>
        <th style='border:2px solid #000; border-collapse: collapse;'>Publish Date</th>
        <th style='border:2px solid #000; border-collapse: collapse;'>Description</th>
    </tr>";

$counter = 1;

foreach ($html_base->find('div[class=m-box]') as $element1) {
    foreach ($element1->find('div[class=box-content]') as $element2) {
        foreach ($element2->find('h3[class=headline]') as $element3) {
            foreach ($element3->find('a') as $element4) {
                echo "
                <tr>
                    <td style='border:2px solid #000; border-collapse: collapse;'>" . $counter . "</td>
                    <td style='border:2px solid #000; border-collapse: collapse;'>" . $element4->plaintext . "</td>
                    <td style='border:2px solid #000; border-collapse: collapse;'>https://www.bracu.ac.bd" . $element4->href . "</td>";
                $details_url = "https://www.bracu.ac.bd" . $element4->href;
            }
            foreach ($element2->find('div[class=by-line]') as $element5) {
                echo "
                    <td style='border:2px solid #000; border-collapse: collapse;'>" . $element5->plaintext . "</td>";
            }

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_URL, $details_url);
            curl_setopt($curl, CURLOPT_REFERER, $details_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $str = curl_exec($curl);
            curl_close($curl);

            // Create another DOM object
            $html_base_2 = new simple_html_dom();

            // Load HTML from a string
            $html_base_2->load($str);

            foreach ($html_base_2->find('div[class=body field row margin-bottom clearfix]') as $element6) {
                echo "
                    <td style='border:2px solid #000; border-collapse: collapse;'>" . $element6 . "</td>
                </tr>    
                ";
            }

            $counter++;
        }
    }
}

echo "</table>";

?>