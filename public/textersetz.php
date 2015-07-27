<?php
require_once "XML/RSS.php";
$rss =& new XML_RSS("http://rss.slashdot.org/Slashdot/slashdot");
$rss->parse();
print_r($rss)
function schurz($fetched_html){
  $suchmuster = array('der',"Der",'Dem','Den','den','dem',"die","Die");
  #Name:
  $ersetzungen = array(
    "Hartwin","Hoyer","Christian","Ritter","Marc","Schlensomat"
  );
  $randomfun = array("bierige","häßliche","besoffene","lange","lustige","widerwärtige","übel riechende","stinkende","laute","verquarzte","schnitzlige","hungrige","schöne","niedliche");
  $found_search = array();
  $html = $fetched_html; 
  foreach ($suchmuster as $needle){
    preg_match_all('/'.$needle.' [A-Z]\w+/',$fetched_html,$treffer);
    $sex = "";
    foreach ($treffer[0] as $row){
      $rand_keys = array_rand($ersetzungen);
      $rand_fun_keys = array_rand($randomfun);
      switch ($needle){
        case "den";
        case "Den";
          $random = $randomfun[$rand_fun_keys]."n";
          $sex = "en";
          break;
        case "Dem";
        case "dem";
          $random = $randomfun[$rand_fun_keys]."n";
          break;
        case "die";
        case "Die";
          $random = $randomfun[$rand_fun_keys];
          $sex = "in";
          break;
      }
      $sub[] = $needle." ".$random." ".$ersetzungen[$rand_keys].$sex;
    }
    $found_search = array_merge($found_search,$treffer[0]);
  }
  $html = str_replace($found_search,$sub,$fetched_html);
  return $html;
}
echo schurz();
?>
</div>