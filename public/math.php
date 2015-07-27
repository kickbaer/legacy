<?php
/*
 * Created on Sep 26, 2011
 *
 */
Class Math {
  public $MeleeTable = array();

  function Math(){
    $this->MeleeTable[1][1] = 3;
    $this->MeleeTable[2][1] = 2;
  }
  function W($Limit) {
    return mt_rand(1, $Limit);
  }
  function Test($DiceAmount, $Limit, $Dice = "6") {
    $hits = 0;
    for ($i = 0; $i < $DiceAmount; $i++) {
      $roll = $this->W($Dice);
      if ($roll >= $Limit) {
        $hits++;
      }
    }
    return $hits; 
  }
}
Class Unit extends Math{
  function MeleeHit($target) {
    
    $MeleeHitTable = array();
    for ($i = 1; $i < 11; $i ++){
      for ($j = 1; $j < 11; $j ++){
         $factor = $j / $i;
         if ($factor >= 1 && $factor <= 2){
          $MeleeHitTable[$i][$j] = 4;
         }
         if ($factor < 1 && $factor > 1/3){
          $MeleeHitTable[$i][$j] = 3;
         }
         if ($factor <= 1/3){
          $MeleeHitTable[$i][$j] = 2;
         }
         if ($factor > 2 && $factor <= 3){
          $MeleeHitTable[$i][$j] = 5;
         }
         if ($factor > 3){
          $MeleeHitTable[$i][$j] = 6;
         }
      }
    }
echo "<pre>";
print_r($MeleeHitTable);
echo "</pre>";    
    $Limit =  $MeleeHitTable[$this->stats["KG"]][$target->stats["KG"]];
    $DiceAmount = ($this->stats["A"] * $this->MeleeWeapon["A"] * count($this->formation[0])) + count($this->formation[1]);
    echo "<p>DiceAmount = $DiceAmount"; 
    echo "<p>Limit = $Limit";
    $Wounds = $this->Test($DiceAmount,$Limit);
    return $Wounds;
  }
}

$Zigeuner = new Unit;
$Zigeuner->stats = array(
"A" => 1,
"KG" => 9,
"W" => 4, 
);
$Zigeuner->MeleeWeapon["A"] = 1;
$Zigeuner->formation[0] = array(1,1,1,1,1);
$Zigeuner->formation[1] = array(1,1,1,1,1);

$Rudes = new Unit;
$Rudes->stats = array(
"A" => 1,
"KG" => 4,
"W" => 3, 
);
$Rudes->formation[0] = array(1,1,1,1,1);
$Rudes->formation[1] = array(1,1,1,1,1);

echo "<p>".$Zigeuner->MeleeHit($Rudes);

?>
