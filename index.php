<html>

<head>
	<link rel="stylesheet" type="text/css" href="miEstiloA.css"> 
	<title> Logueo de Usuario </title>
	<script src="valida_login.js"> </script>
</head>

<body>
<?php
		if(isset ($_POST["Comprobar"])){	
	
			try
			{
				include ("conexion.php");
				$base=conectarse();
				$sql="SELECT * FROM usuarios WHERE user= :usuario AND pass= :clave";
				$resultado=$base->prepare($sql);
				$usern=htmlentities(addslashes($_POST["usuario"]));
				$passw=htmlentities(addslashes($_POST["clave"]));
			
				$resultado->bindValue(":usuario", $usern);
				$resultado->bindValue(":clave", $passw);
			
				$resultado->execute();
				$numero_registro=$resultado->rowCount();
			
					if($numero_registro!=0)
					{
						session_start();
						$_SESSION["usu"]=$usern;
						//header("location:menuPrincipal.php");
					}else{
						echo '<script language="javascript">alert("Usuario y Clave no existen o no coinciden");</script>';
						//echo "<input type='button' value='Volver' onClick='location=\"index.html\"'> ";
					}
			}catch (Exception $e){
				die ("Error: " . $e->getMessage());
			}
		}
	
	?>

 <?php

	if(!isset($_SESSION["usu"]))
	{	
		echo "<section id='LoginA'>";
		include ("LoginForm.html");
		echo "</section>";
	}
	else
	{
		header("Location:menuPrincipal.php");
	}
 ?>
 
</body>
</html>