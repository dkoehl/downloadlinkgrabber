<?php

getPageData();

function getPageData(){
  $fileContent = '';
  $resultFileContent = file_get_contents('result.txt');

  $oldLinkFileCounter = explode(PHP_EOL, trim($resultFileContent));
  $newLinkCounter = 0;
  $oldLinkCounter = 0;

  for($i=1; $i<=10; $i++){
    $doc = new DOMDocument();
    @$doc->loadHTMLFile('http://ebook-hell.to/?cat=53&start=' . $i);
    foreach ($doc->getElementsByTagName('a') as $tag) {
      if($tag->nodeValue == 'Download'){
        if(strstr($resultFileContent, 'http://ebook-hell.to' . $tag->getAttribute('href'). PHP_EOL)){
          $oldLinkCounter++;
          continue;
        }
        $newLinkCounter++;
        file_put_contents('result.txt', 'http://ebook-hell.to' . $tag->getAttribute('href'). PHP_EOL, FILE_APPEND);
      }
    }
  }
  $incomingLinkCounts = count($oldLinkFileCounter);
  echo ':::: Result ::::' . PHP_EOL;
  echo ':: Incoming: ' . $incomingLinkCounts . PHP_EOL;
  echo ':: New-Links: ' . $newLinkCounter . PHP_EOL;
  echo ':: All Links: ' . ($incomingLinkCounts+$newLinkCounter) . PHP_EOL;
}
