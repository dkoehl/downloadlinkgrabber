<?php

$baseURL                    = 'http://ebook-hell.to';
$baseURLwithCategory        = 'http://ebook-hell.to/?cat=53&start=';
$resultFile                 = 'result.txt';

$newLinkCounter             = 0;
$oldLinkCounter             = 0;

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
                $oldLinkCounter++;
                continue;
            }
            $newLinkCounter++;
            file_put_contents($resultFile, $baseURL . $tag->getAttribute('href') . PHP_EOL, FILE_APPEND);
        }
    }
}
$incomingLinkCounts = count($resultFileContent);
echo ':::: Result ::::' . PHP_EOL;
echo '::  Incoming: ' . $incomingLinkCounts . PHP_EOL;
echo ':: New-Links: ' . $newLinkCounter . PHP_EOL;
echo ':: All Links: ' . ($incomingLinkCounts + $newLinkCounter) . PHP_EOL;
