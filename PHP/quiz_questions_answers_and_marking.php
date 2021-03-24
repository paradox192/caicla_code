<?php
$sql_query="SELECT Question, Answer1, Answer2, Answer3, Correct FROM quiz /*ORDER BY RAND()*/ LIMIT 10";
$result=query($sql_query);
$answersMade;//what the user answerd
$correctAnswers;//contains the corect answer
$i=0;//track the question nr
$k=0;//make the labels clickeble fore the radio buttons
echo '<form method="POST">';
while($row = mysqli_fetch_assoc($result)){
	echo '<div id="question'.$i.'" class="quests"><h3>Fråga '.($i+1).'</h3></ br>';

  	echo '<name="question'.$i.'" value="'.$row['Question'].'"><label for="question'.$i.'">'.$row['Question'].'</label><br>';

	echo '<input  type="radio" name="answer'.$i.'" id="answer'.$k.'" value="'.$row['Answer1'].'"><label for="answer'.$k.'">'.$row['Answer1'].'</label><br>';
	$k++;
	echo '<input  type="radio" name="answer'.$i.'" id="answer'.$k.'" value="'.$row['Answer2'].'"><label for="answer'.$k.'">'.$row['Answer2'].'</label><br>';
	$k++;
	echo '<input  type="radio" name="answer'.$i.'" id="answer'.$k.'" value="'.$row['Answer3'].'"><label for="answer'.$k.'">'.$row['Correct'].'</label><br></div>';
	$k++;
  	$i++;
  
	$correctAnswers[] = $row['Correct'];
}
echo '<input type="submit" id="fin" class="quiz_btn float_left" name="done" value="Klar">';
echo '</form>';
if(isset($_POST['done']) && $_POST['done'] == 'Klar') {
	$score = 0;//the score of the user
	for($j = 0; $j < $i; $j++){
		$answersMade[]=$_POST['answer'.$j];
	}
	//echo 'score: ', $score.'<br>';
	for($j = 0; $j < $i; $j++){
      //echo $correctAnswers[$j].'='.$answersMade[$j].'<br>';
		if($correctAnswers[$j] == $answersMade[$j]){
			$score++;
		}
	}
  echo'<div id"quiz_score">';
  //print_r($correctAnswers);
  //print_r($answersMade);
  if($score == 10){
	echo '<h3>Du fick '.$score.' poäng!<br>';
	echo ' Full pott!<br> En riktig Caiclare!</h3>';
  }
  else if($score < 10 && $score >=7){
	echo '<h3>Du fick '.$score.' poäng!<br>';
	echo ' Bra jobbat!</h3>';
  }else{
	echo '<h3>Du fick '.$score.' poäng!</h3>';
  }
  echo'</div>';
//to hide every thng on the page exeptthe score
?>
<script type="text/javascript">
  window.onload = function() {
  var class_arr = document.getElementsByClassName('quests');
    var i;
    for (i = 0; i < class_arr.length; i++) {
        class_arr[i].style.display = 'none';
   }
  	var next_btn = document.getElementById('next');
	var back_btn = document.getElementById('back');
	var fin_btn = document.getElementById('fin');
	back_btn.style.display = 'none';
	fin_btn.style.display = 'none';
  	next_btn.style.display = 'none';
};
</script>
<?php
}