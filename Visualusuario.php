<!DOCTYPE html>
<html>

<head>
	<!--<link rel="stylesheet" type="text/css" href="miEstilo2 B.css"> -->

	<title> Visual </title>
</head>

<body>

	<nav style="background-color:#669CD2; padding:2px; border:2.5px solid 
    #274E9E">
    <p>
	<?php
		echo "Usuario: " . $_SESSION["usu"] . " ";
	?>
    
	<input type='button' value='Cerrar Sesion' class="login__submit" onClick='location="CierreSesion.php"'>
	</p>
    </nav>
    
</body>

</html>