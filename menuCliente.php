<?php
	include ("conexion.php");
	$link = conectarse();
	
	$error = 0;

	if (isset ($_POST ['insertar'])) {
		$a = $_POST ['apellido'];
		$n = $_POST ['name'];
		$em = $_POST ['email'];
		$dni = $_POST ['dni'];
		/*
		$NumFilas =  $link -> query("select * from clientes where dni =$dni") -> fetch(PDO::FETCH_NUM);
		if (!$NumFilas == 0)  $error = 1;
		else
        {
            $sql = "insert into Clientes (Apellido, Nombre, email, dni) values ('$a','$n', '$em', '$dni')";
            $resultado = $link->prepare($sql);
            $resultado -> execute();
        }*/
		try {
			$sql = "insert into Clientes (Apellido, Nombre, email, dni) values ('$a','$n', '$em', '$dni')";
			$link -> query($sql);
		} catch (PDOException $error) {
			$error = 1;
		}
	}
	
	if (isset($_POST['actualizar'])) {
		$id = $_POST['id'];
		$a = $_POST ['apellido'];
		$n = $_POST ['name'];
		$em = $_POST ['email'];
		$dni = $_POST ['dni'];
		/*
        $NumFilas =  $link -> query("select * from clientes where dni =$dni") -> fetch(PDO::FETCH_NUM);
		if (!$NumFilas == 0)  $error = 1;
		else
        {
            $sql = "update clientes set Apellido='$a', Nombre='$n', email='$em', dni='$dni' where id_cliente=$id";
            $resultado = $link->prepare($sql);
            $resultado -> execute();
        }
		*/
		/*
		$sql = "update clientes set Apellido='$a', Nombre='$n', email='$em', dni='$dni' where id_cliente=$id";
		if (!($link -> query($sql)) ) $error = 1;
		*/
		try {
			$sql = "update clientes set Apellido='$a', Nombre='$n', email='$em', dni='$dni' where id_cliente=$id";
			$link -> query($sql);
		} catch (PDOException $error) {
			$error = 1;
		}
	}
	
	if (isset($_GET['eliminar'])) {
		$id = $_GET['eliminar'];
		/*
        $NumFilas =  $link -> query("SELECT * from reparacion, clientes, equipos WHERE $id = equipos.Cliente AND equipos.ID_E = reparacion.Equipo") -> fetch(PDO::FETCH_NUM);
		if (!$NumFilas == 0)  $error = 2;
		else
		{
            $sql = "delete from clientes where id_cliente=$id";
            $resultado = $link->prepare($sql);
            $resultado -> execute();
        }*/
		try {
			$sql = "delete from clientes where id_cliente=$id";
			$link -> query($sql);
		} catch (PDOException $error) {
			$error = 2;
		}
	}
	
	if (isset($_GET['mod'])) {
		$id = $_GET['mod'];
		
		$sql="select * from clientes where id_cliente=$id";
		$resultado=$link->prepare($sql);
		$resultado -> execute();
		$acambiar = $resultado->fetch(PDO::FETCH_NUM);
		
		/*
		if ($acambiar =  $link -> query("select * from clientes where id_cliente=$id") -> fetch(PDO::FETCH_NUM);)
		*/
	}

	//----------------PAGINACION-------------------------

    $tam_pag=10;
    if(isset($_GET["Pagina"]))
	{
		if($_GET["Pagina"]==1)
		{
			header("location:menuCliente.php");
		}else
        {
            $pagina=$_GET["Pagina"];
        }
	}else{
		$pagina=1;
	}
	
	$empezar_desde=($pagina-1)*$tam_pag;
	$sql_total="Select * from clientes";
	$resultado= $link->prepare($sql_total);
	$resultado->execute(array());
	$num_filas=$resultado->rowCount();
	$total_paginas=ceil($num_filas/$tam_pag);

	//------------------------------------------------------
?>

<html>

<head>
	<link rel="stylesheet" type="text/css" href="miEstiloA.css"> 
	<title> Clientes </title>
</head>

<body>
<?php
	include ("Verificarusuario.php");
?>
	<h1 id="titulo"> Clientes </h1>
	<nav>
			<input class="acitvado" id="Cliente" type='button' value='Clientes' onClick='location="menuPrincipal.php"'  />
			
			<input id="Equipo" type='button' value='Equipos' onClick='location="menuEquipos.php"'  />

			<input id="Producto" type='button' value='Productos' onClick='location="menuProductos.php"'  /> 
			
			<input id="Reparacion" type='button' value='Reparaciones' onClick='location="menuReparaciones.php"'  />
            
            <input id="Detalle" type='button' value='Detalle' onClick='location="Detalle.php"' />
			
	</nav>
	<br></br>
	<section>
	<?php
	
	echo "<body>";
	//echo "<div>";
	?>
		<h3> Cargar Cliente </h3>

		<?php 
		if ($error == 1) Echo "<p id='error'>ERROR: DNI Existente en base de datos </p>";
		if ($error == 2) Echo "<p id='error'>ERROR: Cliente Vinculado a tabla Reparaciones </p>";
		?>

		<form id="Carga" action="menuCliente.php" method="post">
		<input type="hidden" name="id" value="<?php if(isset($_GET['mod'])) echo $_GET['mod']; else echo '';?>" ></input>
		Apellido <input type="text" name="apellido" 
		value="<?php if(isset($_GET['mod'])) echo $acambiar[1]; else echo''; ?>" 
		placeholder="Apellido" autofocus required maxlength ="20"> </input>
		&nbsp;&nbsp;&nbsp;
		Nombre <input type="text" name="name" 
		value="<?php if(isset($_GET['mod'])) echo $acambiar[2]; else echo''; ?>" placeholder="Nombre" required maxlength ="20"> </input>
		&nbsp;&nbsp;&nbsp;
		E-Mail <input type="email" name="email" 
		value="<?php if(isset($_GET['mod'])) echo $acambiar[3]; else echo''; ?>" 
		placeholder="JoeDoe@..." required maxlength ="100"> </input>
		&nbsp;&nbsp;&nbsp;
		DNI <input type="text" name="dni" 
		value="<?php if(isset($_GET['mod'])) echo $acambiar[4]; else echo''; ?>" 
		placeholder="--------" required minlength="8" maxlength="8" > </input>
		&nbsp;&nbsp;&nbsp;<br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" name="<?php if(isset($_GET['mod'])) echo 'actualizar'; else echo 'insertar';?>"
		value="<?php if(isset($_GET['mod'])) echo 'Modificar'; else echo 'Crear';?>">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="cancelar" value="Cancelar" onClick="window.location.href = 'menuCliente.php'">
		</form>

	</section>	
	

	<?php
	echo "<br></br>";
	echo "<section id='tabla'>";
	echo "<h3> Lista Clientes </h3>"; 
	
	$sql = "select * from clientes limit $empezar_desde, $tam_pag";
	$resultado = $link->prepare($sql);
	$resultado -> execute();
	
	if ( !($fila = $resultado->fetch(PDO::FETCH_NUM)) ) echo "<h3>Sin Clientes</h3>";
	else {	echo "<table>"; 
			
			echo '<tr id="tabhead">';  
			echo "<th>ID</th>";  
			echo "<th>Apellido</th>";  
			echo "<th>Nombre</th>";
			echo "<th>Email</th>";
			echo "<th>DNI</th>";
			echo "<th>Modificar</th>";
			echo "<th>Eliminar</th>";
			echo "</tr>";
			echo "<tr>";  
			echo "<td>$fila[0]</td>";  
			echo "<td>$fila[1]</td>"; 
			echo "<td>$fila[2]</td>"; 
			echo "<td>$fila[3]</td>"; 
			echo "<td>$fila[4]</td>";  
			echo		"<td><center><a href='menuCliente.php?mod=".$fila['0']."'><img src='Upd2.jpeg' width='30' height='25'></a></center></td>";
			echo		"<td><center><a href=\"javascript:preguntar('".$fila['0']."')\"><img src='Spr2.jpeg' width='30' height='25'></a></center></td>";
			echo "</tr>";  
			
			while ( $fila = $resultado->fetch(PDO::FETCH_NUM) ) {  
				echo "<tr>";  
				echo "<td>$fila[0]</td>";  
				echo "<td>$fila[1]</td>";  
				echo "<td>$fila[2]</td>";  
				echo "<td>$fila[3]</td>";  
				echo "<td>$fila[4]</td>"; 
				echo		"<td><center><a href='menuCliente.php?mod=".$fila['0']."'><img src='Upd2.jpeg' width='30' height='25'></a></center></td>";
				echo		"<td><center><a href=\"javascript:preguntar('".$fila['0']."')\"><img src='Spr2.jpeg' width='30' height='25'></a></center></td>";
				echo "</tr>";  
			}  
			echo "</table>";  
			
			
			
			for($i=1; $i<=$total_paginas; $i++){
            echo "<a href='?Pagina=" . $i . "'>" . $i . "</a>";
			}
			echo "</section>";
	} 		
?>
			<script>
			function preguntar(valor) {
				rpta = confirm("Estas seguro de eliminar el cliente " + valor + "?");
				if (rpta) window.location.href = "menuCliente.php?eliminar=" + valor;
			}
			</script>
<?php
	echo "<br></br>";
?>

</body>

</html>