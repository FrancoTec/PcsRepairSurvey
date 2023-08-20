<?php
	include ("conexion.php");
	$link = conectarse();
	
	$error = 0;

	if (isset ($_POST ['insertar'])) {
		$est = $_POST ['estado'];
		$tot = $_POST ['total'];
		$diag = $_POST ['diag'];
		$fech = $_POST ['fecha'];
		$equ = $_POST ['equipo'];
        $sql = "insert into reparacion (Estado, Total, Diagnostico, Fecha, Equipo) values ('$est','$tot','$diag','$fech','$equ')";
        $resultado = $link->prepare($sql);
        $resultado -> execute();

	}
	
	if (isset($_POST['actualizar'])) {
		$id = $_POST['id'];
		$est = $_POST ['estado'];
		$tot = $_POST ['total'];
		$diag = $_POST ['diag'];
		$fech = $_POST ['fecha'];
		$equ = $_POST ['equipo'];
		
		$sql = "update reparacion set Estado='$est', Total ='$tot', Diagnostico ='$diag', Fecha ='$fech', Equipo ='$equ' where ID_R='$id'";
        $resultado = $link->prepare($sql);
        $resultado -> execute();
	}
	
	if (isset($_GET['eliminar'])) {
		$id = $_GET['eliminar'];		

        try {
			$sql = "delete from reparacion where ID_R=$id";
			$link -> query($sql);
			}
        catch (PDOException $error)
        {
			$error = 2;
		}
	}
	
	if (isset($_GET['mod'])) {
		$id = $_GET['mod'];
		
		$sql="select * from reparacion where ID_R=$id";
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
			header("Location:menuReparaciones.php");
		}else
        {
            $pagina=$_GET["Pagina"];
        }
	}else{
		$pagina=1;
	}
	
	$empezar_desde=($pagina-1)*$tam_pag;
	$sql_total="Select * from reparacion";
	$resultado= $link->prepare($sql_total);
	$resultado->execute(array());
	$num_filas=$resultado->rowCount();
	$total_paginas=ceil($num_filas/$tam_pag);

	//------------------------------------------------------
?>

<html>

<head>
	<link rel="stylesheet" type="text/css" href="miEstiloA.css"> 
	<title>Reparaciones</title>
</head>

<body>
<?php
	include ("Verificarusuario.php");
?>
	<h1 id="titulo"> Reparaciones </h1>
	<nav>
			<input id="Cliente" type='button' value='Clientes' onClick='location="menuCliente.php"'  /> 
			
			<input id="Equipo" type='button' value='Equipos' onClick='location="menuEquipos.php"'  />

			<input id="Producto" type='button' value='Productos' onClick='location="menuProductos.php"'  /> 
			
			<input class="acitvado" id="Reparacion" type='button' value='Reparaciones' onClick='location="menuPrincipal.php"'  />
            
            <input id="Detalle" type='button' value='Detalle' onClick='location="Detalle.php"'  />
			
	</nav>
	<br></br>
	<section>
	<?php
	
	echo "<body>";
	echo "<Div>";
	?>
		<h3> Cargar Reparacion </h3>

		<?php 
		if ($error == 2) Echo "<p id='error'>ERROR: Elemento Vinculado a tabla Reparaciones </p>";
		?>

		<form action="menuReparaciones.php" method="post">
		
        <input type="hidden" name="id" value="<?php if(isset($_GET['mod'])) echo $_GET['mod']; else echo '';?>" ></input>
        
				Estado 
		<select name="estado" required> 
		
		<?php
        if(isset($_GET['mod'])){
            echo "<option value='".$acambiar[1]."' selected>".$acambiar[1]."</option>";
        }
        ?> 
		<option value="Pendiente"> Pendiente </option>
		<option value="Solucionado"> Solucionado </option>
		<option value="En Espera"> En Espera </option>
		</select>
        
        
        
        &nbsp;&nbsp;&nbsp;
        
        		Total <input type="text" name="total" 
		value="<?php if(isset($_GET['mod'])) echo $acambiar[2]; else echo''; ?>" 
		placeholder="..." autofocus required maxlength ="11"> </input>
		&nbsp;&nbsp;&nbsp;
        
        		Diagnostico <input type="text" name="diag" 
		value="<?php if(isset($_GET['mod'])) echo $acambiar[3]; else echo''; ?>" 
		placeholder="..." autofocus required maxlength ="250"> </input>
		&nbsp;&nbsp;&nbsp;
        
        		Fecha <input type="date" name="fecha" 
		value="<?php if(isset($_GET['mod'])) echo $acambiar[4]; else echo''; ?>" 
		placeholder="..." autofocus required > </input>
		&nbsp;&nbsp;&nbsp;
        
		Equipo <select name="equipo" autofocus required >
		<option selected disabled>seleccione equipo...</option>
		<?php
			if(isset($_GET['mod'])){
				$sql= "select * from equipos where ID_E=".$acambiar['5'];
				$resultado = $link->prepare($sql);
				$resultado -> execute();
				$fila = $resultado->fetch(PDO::FETCH_NUM);
				echo "<option value='".$fila['0']."' selected>".$fila['0']." - ".$fila['1']."</option>";
			}
			$sql= "select * from equipos";
				$resultado = $link->prepare($sql);
				$resultado -> execute();
				
			while ($fila = $resultado->fetch(PDO::FETCH_NUM)) {
				echo "<option value='".$fila['0']."'>".$fila['0']." - ".$fila['1']."</option>";
			}
		?>   </select>
        &nbsp;&nbsp;&nbsp; <br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" name="<?php if(isset($_GET['mod'])) echo 'actualizar'; else echo 'insertar';?>"
		value="<?php if(isset($_GET['mod'])) echo 'Modificar'; else echo 'Crear';?>">
		
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="cancelar" value="Cancelar" onClick="window.location.href = 'menuReparaciones.php'">
		</form>

	</section>	
	</div>
	<?php
	echo "<br></br>";
	echo "<section id='tabla'>";
	echo "<h3> Lista Reparaciones </h3>";	
	$sql = "SELECT reparacion.ID_R, reparacion.Estado, reparacion.Total, reparacion.Diagnostico, reparacion.Fecha, concat (equipos.ID_E, ' - ',equipos.Detalle) as equipo FROM reparacion
inner JOIN equipos
on reparacion.Equipo = equipos.ID_E
limit $empezar_desde, $tam_pag";
	$resultado = $link->prepare($sql);
	$resultado -> execute();
	
	if ( !($fila = $resultado->fetch(PDO::FETCH_NUM)) ) echo "<h3>Sin Registros</h3>";
	else {	echo "<table>";  
			echo '<tr id="tabhead">';  
			echo "<th>ID</th>";  
			echo "<th>Estado</th>";  
			echo "<th>Total</th>";
			echo "<th>Diagnostico</th>";
			echo "<th>Fecha</th>";
			echo "<th>Equipo</th>";									
			echo "<th>Modificar</th>";
			echo "<th>Eliminar</th>";
			echo "</tr>";
			echo "<tr>";  
			echo "<td>$fila[0]</td>";  
			echo "<td>$fila[1]</td>"; 
			echo "<td>$fila[2]</td>"; 
			echo "<td>$fila[3]</td>";
			echo "<td>$fila[4]</td>";
			echo "<td>$fila[5]</td>";
			echo		"<td><center><a href='menuReparaciones.php?mod=".$fila['0']."'><img src='Upd2.jpeg' width='30' height='25'></a></center></td>";
			echo		"<td><center><a href=\"javascript:preguntar('".$fila['0']."')\"><img src='Spr2.jpeg' width='30' height='25'></a></center></td>";
			echo "</tr>";  
			
			while ( $fila = $resultado->fetch(PDO::FETCH_NUM) ) {  
				echo "<tr>";  
			echo "<td>$fila[0]</td>";  
			echo "<td>$fila[1]</td>"; 
			echo "<td>$fila[2]</td>"; 
			echo "<td>$fila[3]</td>";
			echo "<td>$fila[4]</td>";
			echo "<td>$fila[5]</td>";   
				echo		"<td><center><a href='menuReparaciones.php?mod=".$fila['0']."'><img src='Upd2.jpeg' width='30' height='25'></a></center></td>";
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
				if (rpta) window.location.href = "menuReparaciones.php?eliminar=" + valor;
			}
			</script>
<?php
	echo "<br></br>";
?>

</body>

</html>