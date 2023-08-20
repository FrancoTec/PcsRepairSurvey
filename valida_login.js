function validacion() {
	var i, car=0, cant=0, contmin=0, contmay=0, contnum=0, concar=0;
	var usu = document.getElementById("usuario").value;
	var contr = document.getElementById("clave").value;
	
	for (i=0 ; i<usu.length ; i++) {
		car = usu.charAt(i);
		car = car.toLowerCase();
		if ( (car >= 0 && car <=9) || (car >= "a" && car <="z" ) ) cant++;
	}

	for (k=0 ; k<contr.length ; k++){
		concar = contr.charAt(k);
		if (concar >= 0 && concar <= 9) contnum++;
		if (concar >= "a" && concar <= "z") contmin++;
		if (concar >= "A" && concar <= "Z") contmay++;
	}
	
	
	if (cant == usu.length && contnum >= 1 && contmin >= 1 && contmay >= 1) {
		
		alert("Datos Completos")
		return true;
		
	}
	else { 
	
		alert ("Datos Incorrectos")
		alert("Usted ingreso en su clave "+contmin+" minusculas, "+contmay+" mayusculas, "+contnum+" Numeros, Se requiere al menos uno de cada tipo.")
		alert("El Usuario ingresado tiene "+cant+" caracteres validos, Total ingresado "+ usu.length +" caracteres , evite usar caracteres especiales.")
		return false;
	
	}
	
	
	
}
