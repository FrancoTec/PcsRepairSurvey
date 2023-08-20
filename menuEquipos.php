<?php
	include ("conexion.php");
	$link = conectarse();
	
	$error = 0;

	if (isset ($_POST ['insertar'])) {
		$det = $_POST ['detalle'];
		$cli = $_POST ['cliente'];
        $sql = "insert into equipos (Detalle, Cliente) values ('$det','$cli')";
        $resultado = $link->prepare($sql);
        $resultado -> execute();

	}
	
	if (isset($_POST['actualizar'])) {
		$id = $_POST['id'];
		$det = $_POST ['detalle'];
		$cli = $_POST ['cliente'];
		
		$sql = "update equipos set Detalle='$det', Cliente ='$cli' where ID_E='$id'";
        $resultado = $link->prepare($sql);
        $resultado -> execute();
	}
	
	if (isset($_GET['eliminar'])) {
		$id = $_GET['eliminar'];		

        try {
			$sql = "delete from equipos where ID_E=$id";
			$link -> query($sql);
			}
        catch (PDOException $error)
        {
			$error = 2;
		}
	}
	
	if (isset($_GET['mod'])) {
		$id = $_GET['mod'];
		
		$sql="select * from equipos where ID_E=$id";
		$resultado=$link->prepare($sql);
		$resultado -> execute();
		$acambiar = $resultado->fetch(PDO::FETCH_NUM);
	}

	//----------------PAGINACION-------------------------

    $tam_pag=10;
    if(isset($_GET["Pagina"]))
	{
		if($_GET["Pagina"]==1)
		{
			header("Location:menuEquipos.php");
		}else
        {
            $pagina=$_GET["Pagina"];
        }
	}else{
		$pagina=1;
	}
	
	$empezar_desde=($pagina-1)*$tam_pag;
	$sql_total="Select * from equipos";
	$resultado= $link->prepare($sql_total);
	$resultado->execute(array());
	$num_filas=$resultado->rowCount();
	$total_paginas=ceil($num_filas/$tam_pag);

	//------------------------------------------------------
?>

<html>

<head>
	<link rel="stylesheet" type="text/css" href="miEstiloA.css"> 
	<title> Equipos </title>
</head>

<body>
<?php
	include ("Verificarusuario.php");
?>
	<h1 id="titulo"> Equipos </h1>
	<nav>
			<input id="Cliente" type='button' value='Clientes' onClick='location="menuCliente.php"' /> 
			
			<input class="acitvado" id="Equipo" type='button' value='Equipos' onClick='location="menuPrincipal.php"'  />

			<input id="Producto" type='button' value='Productos' onClick='location="menuProductos.php"'  /> 
			
			<input id="Reparacion" type='button' value='Reparaciones' onClick='location="menuReparaciones.php"'  />
            
            <input id="Detalle" type='button' value='Detalle' onClick='location="Detalle.php"'  />
			
	</nav>
	<br></br>
	<section>
	<?php
	
	echo "<body>";
	echo "<Div>";
	?>
		<h3> Cargar Equipos </h3>

		<?php 
		if ($error == 2) Echo "<p id='error'>ERROR: Elemento Vinculado a tabla Reparaciones </p>";
		?>

		<form action="menuEquipos.php" method="post">
		<input type="hidden" name="id" value="<?php if(isset($_GET['mod'])) echo $_GET['mod']; else echo '';?>" ></input>
		Detalle <input type="text" name="detalle" 
		value="<?php if(isset($_GET['mod'])) echo $acambiar[1]; else echo''; ?>" 
		placeholder="Detalle..." autofocus required maxlength ="200"> </input>
		&nbsp;&nbsp;&nbsp;
		
		Cliente <select name="cliente" autofocus required >
		<option selected disabled>seleccione cliente...</option>
		<?php
			if(isset($_GET['mod'])){
				$sql= "select * from clientes where id_cliente=".$acambiar['2'];
				$resultado = $link->prepare($sql);
				$resultado -> execute();
				$fila = $resultado->fetch(PDO::FETCH_NUM);
				echo "<option value='".$fila['0']."' selected>".$fila['1']." ".$fila['2']." - ".$fila['3']." - ".$fila['4']."</option>";
			}
			$sql= "select * from clientes";
				$resultado = $link->prepare($sql);
				$resultado -> execute();
				
			while ($fila = $resultado->fetch(PDO::FETCH_NUM)) {
				echo "<option value='".$fila['0']."'>".$fila['0']." - ".$fila['1']." ".$fila['2']." - ".$fila['3']." - ".$fila['4']."</option>";
			}
		?>   </select>
		&nbsp;&nbsp;&nbsp; <br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" name="<?php if(isset($_GET['mod'])) echo 'actualizar'; else echo 'insertar';?>"
		value="<?php if(isset($_GET['mod'])) echo 'Modificar'; else echo 'Crear';?>">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="cancelar" value="Cancelar" onClick="window.location.href = 'menuEquipos.php'">
		</form>

	</section>	
	</div>
	<?php
	echo "<br></br>";
	echo "<section id='tabla'>";
	echo "<h3> Lista Equipos </h3>";	
	
	//$sql = "select * from Equipos limit $empezar_desde, $tam_pag";
	$sql = "SELECT equipos.ID_E, equipos.Detalle, concat (clientes.id_cliente, ' - ' ,clientes.Apellido, clientes.Nombre, ' - ', clientes.email, ' - ' ,clientes.dni) as cliente FROM equipos
inner JOIN clientes 
on equipos.Cliente = clientes.id_cliente
limit $empezar_desde, $tam_pag";
	$resultado = $link->prepare($sql);
	$resultado -> execute();
	
	if ( !($fila = $resultado->fetch(PDO::FETCH_NUM)) ) echo "<h3>Sin Registros</h3>";
	else {	echo "<table>";  
			echo '<tr id="tabhead">';  
			echo "<th>ID</th>";  
			echo "<th>Detalle</th>";  
			echo "<th>Cliente</th>";
			echo "<th>Modificar</th>";
			echo "<th>Eliminar</th>";
			echo "</tr>";
			echo "<tr>";  
			echo "<td>$fila[0]</td>";  
			echo "<td>$fila[1]</td>"; 
			echo "<td>$fila[2]</td>"; 
			echo		"<td><center><a href='menuEquipos.php?mod=".$fila['0']."'><img src='Upd2.jpeg' width='30' height='25'></a></center></td>";
			echo		"<td><center><a href=\"javascript:preguntar('".$fila['0']."')\"><img src='Spr2.jpeg' width='30' height='25'></a></center></td>";
			echo "</tr>";  
			
			while ( $fila = $resultado->fetch(PDO::FETCH_NUM) ) {  
				echo "<tr>";  
				echo "<td>$fila[0]</td>";  
				echo "<td>$fila[1]</td>";  
				echo "<td>$fila[2]</td>";   
				echo		"<td><center><a href='menuEquipos.php?mod=".$fila['0']."'><img src='Upd2.jpeg' width='30' height='25'></a></center></td>";
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
				rpta = confirm("Estas seguro de eliminar " + valor + "?");
				if (rpta) window.location.href = "menuEquipos.php?eliminar=" + valor;
			}
			</script>
<?php
	echo "<br></br>";
?>

</body>

</html>