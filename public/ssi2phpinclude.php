<?php
$dir    = '/www/apac.redhat.com/htdocs/roadtour/';
$listdir = $dir.'files.txt';
$files = file($listdir,FILE_IGNORE_NEW_LINES);
#$html = '<!--#include virtual="/roadtour/include.doctype.html"-->Welcome<!--#include virtual="/roadtour/include.head.flash.html"-->';
foreach ($files as $filename){
  $file = $dir.$filename;
  $html = file_get_contents($file);
  $html = preg_replace('[<!--#include virtual="/roadtour/(.*?).html"-->]','<?php include_once("$1.php"); ?>',$html);
  $html = preg_replace('[<!--#include virtual="/promo/summit/2010/data/(.*?).html"-->]','<?php include_once("include/$1.php"); ?>',$html);
  
  if ($html){
    $unlink = unlink ($file);
    $fp = fopen($file, 'w');
    $bytes = fwrite($fp, $html);
    echo "<p>unlinked: $unlink written $bytes bytes to $file";
  }
}
$html = '<!--#include virtual="/promo/summit/2010/data/roadtour-upcoming.html"-->';
echo "<p>try".preg_replace('[<!--#include virtual="/promo/summit/2010/data/(.*?).html"-->]','GO$1',$html);
?>
