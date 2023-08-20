
<?php  

	function conectarse() {
	  include("parametros.php");
	
	  try {	
		  $link = new PDO("mysql:host=$db_host; dbname=$db_nombre", $db_usuario, $db_pass);
		  $link -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  $link -> exec("SET CHARACTER SET utf8");
	  } catch(Exception $e) {
		  die('Error: ' . $e->GetMessage());	
	  }
	
	return $link;
	
	}
?>
