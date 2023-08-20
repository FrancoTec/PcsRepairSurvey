<?php
	session_start();
	if(!isset($_SESSION["usu"]))
	{
		header("location:index.php");
	}
	else
	{
		include ("Visualusuario.php");
	};
?>