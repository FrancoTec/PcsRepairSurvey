<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="miEstiloA.css"> 
	<title> Principal </title>
</head>

<body>
<?php
	include ("Verificarusuario.php");
?>
	<h1 id="titulo"> Menu Principal </h1>
	
	<nav>
			<input  id="Cliente" type='button' value='Clientes' onClick='location="menuCliente.php"' class="boton" /> 
			
			<input id="Equipo" type='button' value='Equipos' onClick='location="menuEquipos.php"' class="boton" />

			<input id="Producto" type='button' value='Productos' onClick='location="menuProductos.php"' class="boton" /> 
			
			<input id="Reparacion" type='button' value='Reparaciones' onClick='location="menuReparaciones.php"' class="boton" />
            
            <input id="Detalle" type='button' value='Detalle' onClick='location="Detalle.php"' class="boton" />
			
	
	</nav>
	<br></br>

</body>
<footer>

</footer> 

</html>