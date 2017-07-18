<?php

$baseURL                    = 'http://ebook-hell.to';
$baseURLwithCategory        = 'http://ebook-hell.to/?cat=53&start=';
$resultFile                 = 'result.txt';

$newLinkCounter             = 0;

if (!is_file($resultFile)) {
    file_put_contents($resultFile, '');
}
$resultFileContent = file($resultFile);
for ($i = 1; $i <= 10; $i++) {
    $doc = new DOMDocument();
    @$doc->loadHTMLFile($baseURLwithCategory . $i);
    foreach ($doc->getElementsByTagName('a') as $tag) {
        if ($tag->nodeValue == 'Download') {
            if (in_array($baseURL . $tag->getAttribute('href') . PHP_EOL, $resultFileContent)) {
                continue;
            }
            file_put_contents($resultFile, $baseURL . $tag->getAttribute('href') . PHP_EOL, FILE_APPEND);
            $newLinkCounter++;
        }
    }
}
echo ':::: Result ::::' . PHP_EOL;
echo '::  Incoming: ' . count($resultFileContent) . PHP_EOL;
echo ':: New-Links: ' . $newLinkCounter . PHP_EOL;
echo ':: All Links: ' . (count($resultFileContent) + $newLinkCounter) . PHP_EOL;
