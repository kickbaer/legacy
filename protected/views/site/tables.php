<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

$connection=Yii::app()->db;
?>
<p>ELO Difference: <input type="number" id="displayDifference" min="-400" max="400" value="0" />
<br/><input value="0" type="range" id="difference" name="difference" min="-400" max="400">
<p><span id='field-legend'></span>
<table>
<?php
for($i=-1;$i<9;$i++){
  if ($i < 0){
   echo "<tr><th></th>";
  }
  else{
   echo "<tr><th>$i</th>";
  }
for($j=0;$j<9;$j++){
  if ($i < 0){
   echo "<th>$j</th>";
  } 
  else{
    echo "<td id='field-".$i."-".$j."'>ELO</td>";
  }
 }
 echo "</tr>";
}
?>
</table>
<script src="http://code.jquery.com/jquery.js"></script>
<script>
function updateELO(difference){
$("#field-legend").html("elo: y=0 \\ x="+difference);
for ( var Sa = 0; Sa < 9; Sa++ ) {
 for ( var Sb = 0; Sb < 9; Sb++ ) {
  Sab = (1/(Sa+Sb))*Sa;
  diff = difference;
  Ea = 1/(1+ Math.pow(10, (diff)/400));
  k = 30;
  //only add 
  Ra = k*(Sab - Ea);
  $("#field-"+Sa+"-"+Sb).html((Ra.toFixed(2)));
 }
}
return False;
}

$(function() {
 $("#difference").on("input", function() {
  $("#displayDifference").val($("#difference").val());
 });
 $("#difference").on("change", function() {
  $("#displayDifference").val($("#difference").val());
  updateELO($("#difference").val());
 });
  $("#displayDifference").on("change", function() {
  $("#difference").val($("#displayDifference").val());
  updateELO($("#difference").val());
 });
updateELO($("#difference").val());
});
</script>

<?php
$Rteam1 = ELO($team1,$team2,$game["score1"],$game["score2"]);
$Rteam2 = ELO($team2,$team1,$game["score2"],$game["score1"]);

function ELO($Ra,$Rb,$Sa,$Sb){
  $Sa = (1/($Sa+$Sb))*$Sa;
 if (abs($Rb-$Ra) < 400){
  $diff = $Rb-$Ra;
 }
 else{
  if ($Rb-$Ra > 0){
    $diff  = 400;
  }
  else{
    $diff  = -400;
  }
 }
 $Ea = 1/(1+ Pow(10, ($diff)/400));
 $k = 30;
 #only add 
 $Ra = $k*($Sa - $Ea);
 return $Ra;
// return $diff;
}

