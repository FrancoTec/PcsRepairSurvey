<html>

<head>
	<title> Busqueda por nombre de Clientes </title>
	<link rel="stylesheet" type="text/css" href="miEstilo2.css"> 
	<style>
		table{
			witdh: 50%;
			border: 1px solid #000;
			margin: left;
			background-color:#999;
		}
	</style>
</head>


<body>

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

    <?php  
if (isset($_GET["search"]))
{		
		$busCli = $_GET['busC'];
		echo "<h1 id='titulo'> Busqueda de Clientes </h1>";	
        echo'<form>
		<br><br><b>Ingrese criterio de busqueda:</b> &nbsp; <input type="text" minlength="3"  name="busC" value="'.$busCli.'" disabled /> 
		</form>';
		
		

		include("conexion.php");
		$link = conectarse();
		
		echo "<body>";
		
		echo "<br></br>";
		
		$busCli = htmlentities(addslashes($busCli));
		$sql = "select * from clientes where Nombre like '%$busCli%' order by nombre asc";
		$resultado = $link->prepare($sql);
		$resultado -> execute();
		
		if ( !($row = $resultado->fetch(PDO::FETCH_ASSOC)) ) echo "<h3>Sin Coincidencias</h3>";
		else 
		{		 
				echo "<table>";  
				echo "<tr>";  
				echo "<th>NOMBRE</th>";  
				echo "<th>APELLIDO</th>";  
				echo "<th>MAIL</th>";
				echo "<th>DNI</th>";
				echo "</tr>";
				echo "<tr>";  
				echo "<td>".$row['Nombre']."</td>";  
				echo "<td>".$row['Apellido']."</td>";
				echo "<td>".$row['email']."</td>";
				echo "<td>".$row['dni']."</td>";  
				echo "</tr>";  
				while ( $row = $resultado->fetch(PDO::FETCH_ASSOC) ) 
				{   
					echo "<tr>";  
					echo "<td>".$row['Nombre']."</td>";  
					echo "<td>".$row['Apellido']."</td>";
					echo "<td>".$row['email']."</td>";
					echo "<td>".$row['dni']."</td>";
					echo "</tr>"; 
				}
				echo "</table>";  
		} 
	} else
	{
	$mipagina = $_SERVER['PHP_SELF'];
	echo '<h1 id="titulo"> Busqueda de Clientes </h1>
<form name="loguin" method="get" action ="'. $mipagina .'">
 
    <br><br><b>Ingrese criterio de busqueda:</b> &nbsp; <input type="text" minlength="3"  name="busC" required autofocus /> 
	<br><br>
	<input type="reset" value="Borrar" class="boton" /> &nbsp;&nbsp;&nbsp;&nbsp; 
	<input type="submit" name="search" value="Buscar" class="boton" />  
	</form>' ;
	}
	
	echo "<br></br>";

	echo "<input type='button' id='boton' value='Volver' class='boton' onClick='location=\"menuPrincipal.php\"'> ";
	echo "<input type='button' id='boton' value='Reiniciar Busqueda' class='boton' onClick='location=\"buscarCliente.php\"'> ";
	

	
	$link = null;
  ?>  


</body>
<footer>
	<p> Pagina en Construccion</p>
</footer> 

</html>