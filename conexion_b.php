<?php  


	  include("parametros.php");
	
	  try {	
		  $base = new PDO("mysql:host=$db_host; dbname=$db_nombre", $db_usuario, $db_pass);
		  $base -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  $base -> exec("SET CHARACTER SET utf8");
	  } catch(Exception $e) {
		  die('Error: ' . $e->GetMessage());	
		  echo "Error" . $e->getLine();
	  
	  }
	

	

?>