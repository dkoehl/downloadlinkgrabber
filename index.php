<?php

$baseURL                    = 'http://ebook-hell.to';
$baseURLwithCategory        = ['http://ebook-hell.to/?cat=8&start=', 'http://ebook-hell.to/?cat=53&start='];
$resultFile                 = 'result.txt';
$datetime                   = date('Y.m.d-H:i');
$newLinkCounter             = 0;
$newSubLinkCounter          = 0;
$saveContentToFile          = '';
$saveContentToFileEnd       = '';

if (!is_file($resultFile)) {
    file_put_contents($resultFile, '');
}
$resultFileContent = file($resultFile);

foreach($baseURLwithCategory as $page){
    for ($i = 1; $i <= 10; $i++) {
        $doc = new DOMDocument();
        @$doc->loadHTMLFile($page . $i);
        foreach ($doc->getElementsByTagName('a') as $tag) {
            if ($tag->nodeValue == 'Download') {
                if (in_array($baseURL . $tag->getAttribute('href') . PHP_EOL, $resultFileContent)) {
                    continue;
                }
                $htmlString = @file_get_contents(trim($baseURL . $tag->getAttribute('href')));
                if(strstr($htmlString, 'filecrypt')){
                    preg_match_all('/(filecrypt.cc\/Container\/)(.*?)(")/', $htmlString, $matches);
                    if(!is_array($matches[0])) {
                        continue;
                    }
                    // Saves main link
                    file_put_contents($resultFile, $baseURL . $tag->getAttribute('href') . PHP_EOL, FILE_APPEND);
                    foreach($matches[0] as $match){
                        $saveContentToFile .= str_replace('"', '', 'https://' . $match) . PHP_EOL;
                        $newSubLinkCounter++;
                    }
                    // Saves all sublinks of main link to file
                    file_put_contents($resultFile, $saveContentToFile, FILE_APPEND);
                    $saveContentToFile = '';
                }
                $newLinkCounter++;
            }
        }
    }

    echo ':::: Result-URL: ' . $page .  PHP_EOL;
    echo ':::::: Incoming: ' . count($resultFileContent) . PHP_EOL;
    echo '::::: New-Links: ' . $newLinkCounter . PHP_EOL;
    echo ':: New-SubLinks: ' . $newSubLinkCounter . PHP_EOL;
    echo '::::: All Links: ' . (count($resultFileContent) + $newLinkCounter) . PHP_EOL. PHP_EOL;
}
