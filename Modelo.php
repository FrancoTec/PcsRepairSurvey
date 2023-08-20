<?php
	session_start();
	$operacion = $_REQUEST['operacion']	;
	
	switch ($operacion) 
	{
		case 'Asignar'	: buscarrep() ;
			break;
		case 'Agregar'	: buscarpro() ;
			break;
		case 'Facturar'	: facturar() ;
			break;
		case 'Cancelar'	: cancelar() ;
			break;
	}


	function buscarrep() 
	{
		include ('conexion.php');	
		$link = conectarse();
		$repid = $_REQUEST['Rep'];
		$reparacion = $link->query("SELECT reparacion.ID_R, reparacion.Estado, reparacion.Total, reparacion.Diagnostico, reparacion.Fecha, concat (equipos.ID_E, ' - ',equipos.Detalle) as equipo FROM reparacion inner JOIN equipos on reparacion.Equipo = equipos.ID_E where ID_R = $repid")->fetch(PDO::FETCH_OBJ);
		
		if (!$reparacion) 
		{	
			header("location:Detalle.php?error=1");
			exit(); 
		}

		$_SESSION['reparacion'] = $reparacion;
		header("location:Detalle.php");
		


		
	}
	
		function buscarpro() 
	{
		include ('conexion.php');	
		$link = conectarse();
		$codigo = $_REQUEST['cod'];
		$cantidad = $_REQUEST['cant'];
		$producto = $link->query("SELECT * from productos where ID = $codigo")->fetch(PDO::FETCH_OBJ);
		
		if (!$producto) 
		{	
			header("location:Detalle.php?error=1");
			exit(); 
		}

		$producto-> cantidad = $cantidad;
		$_SESSION['productos'][] = $producto;
		header("location:Detalle.php");
	
	}
	
	
		function facturar() 
	{
		include ('conexion.php');	
		$link = conectarse();
		$reparacion = $_SESSION['reparacion'] ;
		$productos = $_SESSION['productos'];
		
		$idr = $_SESSION['reparacion'] -> ID_R;
		
		foreach ($productos as $pr)
		{
				$link->query("INSERT INTO rep_comp (Num_Rep, Num_Comp, Cantidad) VALUES ($idr,$pr->ID,$pr->cantidad)");
		}
		
		imprimir();
	}
	
	function imprimir()
	{
		$link = conectarse();
		include ("fpdf/fpdf.php");	
		$reparacion = $_SESSION['reparacion'] ;
		$productos = $_SESSION['productos'];
		$total = $_SESSION['total'];
		
		$Detalle = $link->query ("select rep_comp.ID_Detalle, rep_comp.Num_Rep, reparacion.Equipo, rep_comp.Num_Comp, productos.Nombre, rep_comp.Cantidad from rep_comp, productos, reparacion
where rep_comp.Num_Rep = reparacion.ID_R AND rep_comp.Num_Comp = productos.ID");
		
		$ticket =new FPDF;
		$ticket->AddPage();
		$ticket->SetFont('Arial','B', 28);
		$ticket->Image('Marca.png',18,0);
		$ticket->SetXY(0,0);
		$ticket->Cell(210,20,"Joe Doe",0,1,'C');
		$ticket->SetFont('Arial','B', 20);
		$ticket->SetXY(0,12);
		$ticket->Cell(210,20,"Reparacion",0,1,'C');
		$ticket->SetFont('Arial','B', 12);
		
		$ticket->Cell(40,10,"Reparacion",1,0,'C');
		$ticket->Cell(40,10,"Equipo",1,0,'C');
		$ticket->Cell(40,10,"Diagnostico",1,0,'C');
		$ticket->Cell(50,10,"Valor Reparacion",1,1,'C');
		$ticket->Cell(40,10,$reparacion->ID_R,1,0,'C');
		$ticket->Cell(40,10,$reparacion->Diagnostico,1,0,'C');
		$ticket->Cell(40,10,$reparacion->equipo,1,0,'C');
		$ticket->Cell(50,10,$reparacion->Total,1,1,'C');
		$ticket->Cell(10,15,'',0,1);
		
		$ticket->SetFont('Arial','B', 10);
				$ticket->Cell(40,10,"Num Art",1,0,'C');
				$ticket->Cell(40,10,"Articulo",1,0,'C');
				$ticket->Cell(40,10,"Cantidad",1,0,'C');
				$ticket->Cell(40,10,"Valor",1,0,'C');
				$ticket->Cell(40,10,"Subtotal",1,1,'C');
		foreach ($productos as $pr)
			{
				$ticket->Cell(40,10,$pr->ID,1,0,'C');
				$ticket->Cell(40,10,$pr->Nombre,1,0,'C');
				$ticket->Cell(40,10,$pr->cantidad,1,0,'C');
				$ticket->Cell(40,10,$pr->Precio,1,0,'C');
				$ticket->Cell(40,10,$pr->Precio * $pr->cantidad,1,1,'C');
				
			}
				$ticket->Cell(10,15,'',0,1);
				$ticket->Cell(40,10,"Total",1,1,'C');
				$ticket->Cell(40,10,$total,1,1,'C');
				

				$ticket->SetFont('Arial','B', 10);
				$ticket->Cell( 0,0,"Contacto:",0,1,'C');
				$ticket->Cell( 0,10,"JoeDoeSoIn@xmail.com",0,1,'C');
				$ticket->Cell( 0,0,"3402142200",0,1,'C');
				
		
		$ticket->Output();
	}
	
	function cancelar()
	{
		unset($_SESSION['reparacion']);
		unset($_SESSION['productos']);
		header("location:Detalle.php");	
	}


	
	
	/*
	select rep_comp.ID_Detalle, rep_comp.Num_Rep, reparacion.Equipo, rep_comp.Num_Comp, productos.Nombre, rep_comp.Cantidad from rep_comp, productos, reparacion
where rep_comp.Num_Rep = reparacion.ID_R AND rep_comp.Num_Comp = productos.ID*/

?>