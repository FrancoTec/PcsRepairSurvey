<?php
	include ("conexion.php");
	$link = conectarse();
	
	$error = 0;

	if (isset ($_POST ['insertar'])) {
		$n = $_POST ['name'];
		$pp = $_POST ['puntopedido'];
		$precio = $_POST ['precio'];
		$stock = $_POST ['stock'];
		$est = $_POST ['estante'];
        $sql = "insert into productos (Nombre, Punto_Pedido, Precio, Stock, Estante) values ('$n','$pp', '$precio', '$stock', '$est')";
        $resultado = $link->prepare($sql);
        $resultado -> execute();

	}
	
	if (isset($_POST['actualizar'])) {
		$id = $_POST['id'];
		$n = $_POST ['name'];
		$pp = $_POST ['puntopedido'];
		$precio = $_POST ['precio'];
		$stock = $_POST ['stock'];
        $est = $_POST ['estante'];
      
		$sql = "update productos set Nombre='$n', Punto_Pedido='$pp', Precio='$precio', Stock='$stock', Estante='$est' where ID='$id'";
        $resultado = $link->prepare($sql);
        $resultado -> execute();
	}
	
	if (isset($_GET['eliminar'])) {
		$id = $_GET['eliminar'];		

        try {
			$sql = "delete from productos where ID=$id";
			$link -> query($sql);
			}
        catch (PDOException $error)
        {
			$error = 2;
		}
	}
	
	if (isset($_GET['mod'])) {
		$id = $_GET['mod'];
		
		$sql="select * from productos where ID=$id";
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
			header("Location:menuProductos.php");
		}else
        {
            $pagina=$_GET["Pagina"];
        }
	}else{
		$pagina=1;
	}
	
	$empezar_desde=($pagina-1)*$tam_pag;
	$sql_total="Select * from productos";
	$resultado= $link->prepare($sql_total);
	$resultado->execute(array());
	$num_filas=$resultado->rowCount();
	$total_paginas=ceil($num_filas/$tam_pag);

	//------------------------------------------------------
?>

<html>

<head>
	<link rel="stylesheet" type="text/css" href="miEstiloA.css"> 
	<title> Productos </title>
</head>

<body>
<?php
	include ("Verificarusuario.php");
?>
	<h1 id="titulo"> Productos </h1>
	<nav>
			<input id="Cliente" type='button' value='Clientes' onClick='location="menuCliente.php"'  /> 
			
			<input id="Equipo" type='button' value='Equipos' onClick='location="menuEquipos.php"'  />

			<input class="acitvado" id="Producto" type='button' value='Productos' onClick='location="menuPrincipal.php"'  /> 
			
			<input id="Reparacion" type='button' value='Reparaciones' onClick='location="menuReparaciones.php"'  />
            
            <input id="Detalle" type='button' value='Detalle' onClick='location="Detalle.php"' />
			
	</nav>
	<br></br>
	<section>
	<?php
	
	echo "<body>";
	echo "<Div>";
	?>
		<h3> Cargar Producto </h3>

		<?php 
		if ($error == 2) Echo "<p id='error'>ERROR: Elemento Vinculado a tabla Reparaciones </p>";
		?>

		<form action="menuProductos.php" method="post">
		<input type="hidden" name="id" value="<?php if(isset($_GET['mod'])) echo $_GET['mod']; else echo '';?>" ></input>
		Nombre <input type="text" name="name" 
		value="<?php if(isset($_GET['mod'])) echo $acambiar[1]; else echo''; ?>" 
		placeholder="Elemento" autofocus required maxlength ="35"> </input>
		&nbsp;&nbsp;&nbsp;
		Punto de Pedido <input type="text" name="puntopedido" 
		value="<?php if(isset($_GET['mod'])) echo $acambiar[2]; else echo''; ?>" placeholder="0" required maxlength ="2"> </input>
		&nbsp;&nbsp;&nbsp;
		Precio <input type="text" name="precio" 
		value="<?php if(isset($_GET['mod'])) echo $acambiar[3]; else echo''; ?>" 
		placeholder="000000" required maxlength ="6"> </input>
		&nbsp;&nbsp;&nbsp;
		Stock <input type="text" name="stock" 
		value="<?php if(isset($_GET['mod'])) echo $acambiar[4]; else echo''; ?>" 
		placeholder="000" required maxlength ="11" > </input>
		Estante 
		<select name="estante" required> 
		<option value="" selected disabled>Ubicacion...</option>
		<?php
        if(isset($_GET['mod'])){
            echo "<option value='".$acambiar[5]."' selected>".$acambiar[5]."</option>";
        }
        ?> 
		<option value="A"> A </option>
		<option value="B"> B </option>
		<option value="C"> C </option>
		<option value="D"> D </option>
		</select>
		&nbsp;&nbsp;&nbsp; <br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" name="<?php if(isset($_GET['mod'])) echo 'actualizar'; else echo 'insertar';?>"
		value="<?php if(isset($_GET['mod'])) echo 'Modificar'; else echo 'Crear';?>">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="cancelar" value="Cancelar" onClick="window.location.href = 'menuProductos.php'">
		</form>

	</section>	
	</div>
	<?php
	echo "<br></br>";
	echo "<section id='tabla'>";
	echo "<h3> Lista Productos </h3>";	
	$sql = "select * from productos limit $empezar_desde, $tam_pag";
	$resultado = $link->prepare($sql);
	$resultado -> execute();
	
	if ( !($fila = $resultado->fetch(PDO::FETCH_NUM)) ) echo "<h3>Sin Productos</h3>";
	else {	echo "<table>";  
			echo '<tr id="tabhead">';  
			echo "<th>ID</th>";  
			echo "<th>Nombre</th>";  
			echo "<th>Punto Pedido</th>";
			echo "<th>Precio</th>";
			echo "<th>Stock</th>";
            echo "<th>Estante</th>";
			echo "<th>Modificar</th>";
			echo "<th>Eliminar</th>";
			echo "<td></td>";
			echo "</tr>";
				if($fila[4] == 0)
				{
					echo "<tr style='background-color:red'>";  
					echo "<td>$fila[0]</td>";  
					echo "<td>$fila[1]</td>"; 
					echo "<td>$fila[2]</td>"; 
					echo "<td>$fila[3]</td>"; 
					echo "<td>$fila[4]</td>";  
					echo "<td>$fila[5]</td>";  
					echo		"<td><center><a href='menuProductos.php?mod=".$fila['0']."'><img src='Upd2.jpeg' width='30' height='25'></a></center></td>";
					echo		"<td><center><a href=\"javascript:preguntar('".$fila['0']."')\"><img src='Spr2.jpeg' width='30' height='25'></a></center></td>";
					echo "<td>Sin Existencias</td>";
					echo "</tr>"; 
				}
					else{
							if($fila[2] > $fila[4])
							{
								echo "<tr style='background-color:#ffad33'>";  
								echo "<td>$fila[0]</td>";  
								echo "<td>$fila[1]</td>"; 
								echo "<td>$fila[2]</td>"; 
								echo "<td>$fila[3]</td>"; 
								echo "<td>$fila[4]</td>";  
								echo "<td>$fila[5]</td>";  
								echo		"<td><center><a href='menuProductos.php?mod=".$fila['0']."'><img src='Upd2.jpeg' width='30' height='25'></a></center></td>";
								echo		"<td><center><a href=\"javascript:preguntar('".$fila['0']."')\"><img src='Spr2.jpeg' width='30' height='25'></a></center></td>";
								echo "<td>Se necesita reponer</td>";
								echo "</tr>"; 
							}
							else{
									echo "<tr>";  
									echo "<td>$fila[0]</td>";  
									echo "<td>$fila[1]</td>"; 
									echo "<td>$fila[2]</td>"; 
									echo "<td>$fila[3]</td>"; 
									echo "<td>$fila[4]</td>";  
									echo "<td>$fila[5]</td>";  
									echo		"<td><center><a href='menuProductos.php?mod=".$fila['0']."'><img src='Upd2.jpeg' width='30' height='25'></a></center></td>";
									echo		"<td><center><a href=\"javascript:preguntar('".$fila['0']."')\"><img src='Spr2.jpeg' width='30' height='25'></a></center></td>";
									echo "</tr>"; 
							} 
						}
			
			while ( $fila = $resultado->fetch(PDO::FETCH_NUM) ) {  
			
				
				
				if($fila[4] == 0)
				{
					echo "<tr style='background-color:red'>";  
					echo "<td>$fila[0]</td>";  
					echo "<td>$fila[1]</td>"; 
					echo "<td>$fila[2]</td>"; 
					echo "<td>$fila[3]</td>"; 
					echo "<td>$fila[4]</td>";  
					echo "<td>$fila[5]</td>";  
					echo		"<td><center><a href='menuProductos.php?mod=".$fila['0']."'><img src='Upd2.jpeg' width='30' height='25'></a></center></td>";
					echo		"<td><center><a href=\"javascript:preguntar('".$fila['0']."')\"><img src='Spr2.jpeg' width='30' height='25'></a></center></td>";
					echo "<td>Sin Existencias</td>";
					echo "</tr>"; 
				}
				else{
				
						if($fila[2] > $fila[4])
						{
							echo "<tr style='background-color:#ffad33'>";  
							echo "<td>$fila[0]</td>";  
							echo "<td>$fila[1]</td>"; 
							echo "<td>$fila[2]</td>"; 
							echo "<td>$fila[3]</td>"; 
							echo "<td>$fila[4]</td>";  
							echo "<td>$fila[5]</td>";  
							echo		"<td><center><a href='menuProductos.php?mod=".$fila['0']."'><img src='Upd2.jpeg' width='30' height='25'></a></center></td>";
							echo		"<td><center><a href=\"javascript:preguntar('".$fila['0']."')\"><img src='Spr2.jpeg' width='30' height='25'></a></center></td>";
							echo "<td>Se necesita reponer</td>";
							echo "</tr>"; 
						}
						else{
						echo "<tr>";  
						echo "<td>$fila[0]</td>";  
						echo "<td>$fila[1]</td>";  
						echo "<td>$fila[2]</td>";
						echo "<td>$fila[3]</td>";  
						echo "<td>$fila[4]</td>"; 
						echo "<td>$fila[5]</td>"; 
						echo		"<td><center><a href='menuProductos.php?mod=".$fila['0']."'><img src='Upd2.jpeg' width='30' height='25'></a></center></td>";
						echo		"<td><center><a href=\"javascript:preguntar('".$fila['0']."')\"><img src='Spr2.jpeg' width='30' height='25'></a></center></td>";
						echo "</tr>";  
						}
					}
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
				if (rpta) window.location.href = "menuProductos.php?eliminar=" + valor;
			}
			</script>
<?php
	echo "<br></br>";
?>

</body>

</html>