<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Isla @amolinao961</title>
	<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href="terrain.css">
</head>
<body>
	<div class="canvas"></div>
	<form action="island.php" id="generate-form">
		<fieldset>
			<div>
				<label for="mapSizeBasic"> Tamaño del Mapa</label>
				<p id="mapSizeBasic">
					<input type="radio" id="smallSize" name="mapSize" value="1"><label for="smallSize">Pequeño</label>
					<input type="radio" id="mediumSize" name="mapSize" value="0" checked><label for="mediumSize">Mediano</label>
					<input type="radio" id="bigSize" name="mapSize" value="2"><label for="bigSize">Grande</label>
				</p>
			</div>
			<div>
				<label for="waterPercent">Porcentaje de Agua</label>
				<p><span>[20%]</span><input type="range" name="percent" min="-4" max="4" step="2"><span>[80%]</span></p>
			</div>
			<div>
				<input type="submit" value="Generar!">
			</div>
		</fieldset>
	</form>

	<script>
//Función que devuelve una letra del ABC
	function colName(n) {
	    var s = "";
	    while(n >= 0) {
	        s = String.fromCharCode(n % 26 + 97) + s;
	        n = Math.floor(n / 26) - 1;
	    }
	    return s;
	}
//Función que devuelve variables desde URL
	function getParameterByName(name) {
	    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	        results = regex.exec(location.search);
	    return results === null ? 0 : decodeURIComponent(results[1].replace(/\+/g, " "));
	}

	if (getParameterByName('mapSize')  == 2){var ejeX=40;var ejeY=40;}
	else if (getParameterByName('mapSize')  == 1){var ejeX=15;var ejeY=15;}
	else {var ejeX=20;var ejeY=20;}

	if(getParameterByName('percent') <0)
		var porcentajeAgua=ejeX*(ejeY/Math.abs(getParameterByName('percent')));
	else if (getParameterByName('percent') >0)
		var porcentajeAgua=ejeX*(ejeY*(getParameterByName('percent')/1.5)).toFixed();
	else
		var porcentajeAgua=ejeX*ejeY;
	
	var terrain= [];

	for (var j = 0; j < ejeY; j++) {
		var letter= colName(j);
		for (var i = 0; i < ejeX; i++) {
			if ((letter=="a") 	||
				(i==0) 			||
				(i==ejeX-1) 	||
				(j==ejeY-1))
				terrain.push("agua");
			else
				terrain.push("tierra");
		};
	};
	while (porcentajeAgua!=0){
		count=0;
		var random= Math.floor((Math.random() * (ejeX*ejeY)) + 1); 
		if ((terrain[random-1]=="agua") 	||
			(terrain[random+1]=="agua") 	||
			(terrain[random-ejeX]=="agua") 	||
			(terrain[random+ejeX]=="agua"))
				count++;
		if (count>=1){
			terrain[random]="agua";
			porcentajeAgua--;
		};
	};
	//Primer bucle que añade la playa
	for (index = 0; index < terrain.length-1; ++index) {
	   	if (terrain[index] == "tierra")
	   		if ((terrain[index+1]=="agua") 		||
	   			(terrain[index-1]=="agua") 		||
	   			(terrain[index-ejeX]=="agua") 	||
	   			(terrain[index+ejeX]=="agua"))
	   			terrain[index] = "arena";
	};
	//Segundo bucle que añade la costa entre el oceano y la playa
	//Aprovecho y pinto el mapa
	for (index = 0; index < terrain.length-1; ++index) {
		if (terrain[index] == "agua")
	   		if ((terrain[index+1]=="arena") 	||
	   			(terrain[index-1]=="arena") 	||
	   			(terrain[index-ejeX]=="arena") 	||
	   			(terrain[index+ejeX]=="arena"))
	   			terrain[index] = "costa";

	    if (index%ejeX == 0)
			$('.canvas').append('<span class="clear '+terrain[index]+'"></span>');
	    else
	     	$('.canvas').append('<span class="'+terrain[index]+'"></span>');
	};
	</script>
</body>
</html>

