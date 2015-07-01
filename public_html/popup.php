<?php

session_start();

function popupHTML($id, $title, $content, $visible) {
  echo "<div class='popup'";
  if($id != NULL) {echo " id='$id'";}
  if($visible) {echo " style='display: block;'";}
  echo ">";
  echo "
  	<div class='buttons'>
  		<button class='button blue-hover smaller'>X</button>
  	</div>
  	<h1 class='popup-title blue'>$title</h1>
    $content
  </div>";
}

function createPopup($id, $title, $content) {
  popupHTML($id, $title, $content, false);
}

function createPopupVisible($id, $title, $content) {
  popupHTML($id, $title, $content, true);
}
?>
