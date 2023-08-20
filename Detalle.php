<?php
	include ("conexion.php");
	$link = conectarse();
?>

<html>

<head>
	<link rel="stylesheet" type="text/css" href="miEstiloA.css"> 
	<title>Facturacion</title>
</head>

<body>

<script>
    window.addEventListener("DOMContentLoaded", function() 
    {
        var urlParams = new URLSearchParams(window.location.search);
        var error = urlParams.get("error");
        
        if (error === "1") 
        {
            alert("Datos invalidos, revise si los datos existen.");
        }
    });
</script>

<?php


	include ("Verificarusuario.php");
	
	$idrep = '';	
	$equiporep = '';
	$fecharep = '';
	$totalrep = 0; 
	
	$idpro = ''; 
    $nompro = ''; 
    $cantpro = 0;
    $preciopro = 0; 

	$total =0;
	if (isset ($_SESSION['reparacion']))
	{
		
		$idrep = $_SESSION['reparacion']-> ID_R;	
		$equiporep = $_SESSION['reparacion']-> equipo;
		$fecharep = $_SESSION['reparacion']-> Fecha;
		$totalrep = $_SESSION['reparacion']-> Total;
		
	}
	
		if (isset ($_SESSION['productos']))
	{

		$productos = $_SESSION['productos'];
	}
	else {$productos = array();}
	


?>
	<h1 id="titulo"> Facturar </h1>
	<nav>
			<input id="Cliente" type='button' value='Clientes' onClick='location="menuCliente.php"'  /> 
			
			<input id="Equipo" type='button' value='Equipos' onClick='location="menuEquipos.php"'  />

			<input id="Producto" type='button' value='Productos' onClick='location="menuProductos.php"'  /> 
			
			<input id="Reparacion" type='button' value='Reparaciones' onClick='location="menuReparaciones.php"' />
            
            <input class="acitvado" id="Detalle" type='button' value='Detalle' onClick='location="menuPrincipal.php"'  />
			
	</nav>
    <section id="DetMen">
		<form action="modelo.php" method="post">	
            <table id="tabdet">
            	<tr id="tabdet" style="background-color: #36C;">
                    
                    <td><input type="text" name="Rep" placeholder="Codigo Reparacion" required> </td>
    
                    <td><input type="submit" name="operacion" value="Asignar"></td>
					<td></td>
                    <td></td>
                </tr>
                <tr id="tabdet" style="background-color: #36F;">
                	<td>ID Reparacion</td>
                    <td>Equipo</td>
                    <td>Fecha</td>
                    <td>Total</td>
                </tr>
                <tr>
                	<td><?php echo $idrep; ?></td>
                    <td><?php echo $equiporep; ?></td>
                    <td><?php echo $fecharep; ?></td>
                    <td><?php echo $totalrep; ?></td>
                </tr>
            </table>
            </form>
            
         
         	<form action="modelo.php" method="post">	
            <table id="tabdet">
                <tr id="tabdet" style="background-color: #36C">
                	<td><input type="text" name="cod"  placeholder="Codigo producto" required></td>
                    <td><input type="text" name="cant" placeholder="Unidades" required></td>
                    <td><input type="submit" name="operacion" value="Agregar"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr id="tabdet" style="background-color: #36F;">
                	<td>Codigo</td>
                    <td>Producto</td>
                    <td>Cantidad</td>
                    <td>Precio</td>
                    <td>Subtotal</td>
                </tr>
                	<?php foreach ($productos as $p) :?> 
                <tr>
                	<td><?php echo $p->ID; ?></td>
                    <td><?php echo $p->Nombre; ?></td>
                    <td><?php echo $p->cantidad; ?></td>
                    <td><?php echo $p->Precio; ?></td>
                    <td><?php echo $p->Precio * $p->cantidad; ?></td>
                </tr>
                	<?php $total += ($p->Precio * $p->cantidad);?>
                	<?php endforeach;?>
                    <tr>
                    	<td style="background-color: #36F;">Total</td>
                        <?php $total += $totalrep;?>
                        <?php $_SESSION['total'] = $total; ?>
                        <td><?php echo $total; ?></td>
                    </tr>
            </table>
            	
            </form>
            
           <form action="modelo.php" method="post">
           <input class="login__submit" type="submit" name="operacion" value="Facturar">
           <input class="login__submit" type="submit" name="operacion" value="Cancelar">
           
           </form>

           </section>
</body>	


</html>