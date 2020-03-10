<?php
$html = '<HTML><HEAD></HEAD><BODY>Hello World</BODY></HTML>'; 
$tidy = new Tidy; 
$config = array( 'indent' => true, 'output-xhtml' => true, 'wrap' => 200); 
$tidy->parseString($html, $config, 'utf8'); $tidy->cleanRepair(); 
$html = $tidy; 

/*
ini_set('post_max_size', '128M');
include "h_dom.php";
if ($_POST["go"]) {

  $html = str_get_html($_POST["text"]);
  echo $html;
}
?>
<form method="POST">
  <textarea name="text"></textarea><br>
  <input type="submit" name="go" value="go">
</form>