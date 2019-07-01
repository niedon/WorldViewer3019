

<?php 

    //$valCajatexto;

    if(isset($_GET['cajaBuscar'])){
        $valCajatexto = $_GET["cajaBuscar"];
    }else if(isset($_POST['enviaResultados'])){
        $valCajatexto = $_GET["cajaBuscar"];
    }else{
        $valCajatexto = "";
    }




?>




<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<link rel="stylesheet" href="cssjs/principal.css" type="text/css" />
 <script src="cssjs/principal.js"></script> 


<title>WorldViewer (TM)</title>

<?php 
    

    if (isset($_GET['enviaBusqueda'])){
        
        //$valCajaTexto = $_GET["cajaBuscar"];
        
        $b_nombre = "%" . $_GET["cajaBuscar"] . "%";
        
        $base = null;
        //$registro = null;
        
        
        
        try{
            
            $base = new PDO("mysql:host=localhost; dbname=world", "root", "");
            
            //echo "Conexión OK <br/><br/>";
            
            //Le dice a la base de datos que ante los errores (primer parámetro)
            //lance excepciones (segundo parámetro), así se pueden capturar en este humilde php
            $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $base->exec("SET CHARACTER SET utf8");
            
            //Sentencia SQL, usa un marcador (sintaxis de los 2 puntos obligatoria??)
            $sql="SELECT Name, Code FROM country WHERE Name LIKE :n_pais";
            
            //PDO Statement (preparada)
            $resultado = $base->prepare($sql);
            
            //ResultSet
            $resultado->execute(array(":n_pais"=>$b_nombre));
            
            //Bucle con cada fila (fetch da true si encuentra fila, luego se ejecuta el while para esa fila)
            //echo "Holaaaa";
            
            
            
            
        }catch(Exception $e){
            
            echo $e->getMessage() . "<br/>La has jodido, chavea.";
            
        }finally{
            
            $base = null;
            
        }
        
    }

?>


</head>
<body>

<!-- <?php echo date_default_timezone_get(); ?> -->

<header>WorldViewer Online API 3019</header>

<aside>
	
	<form action="index.php" method="get">
	<input type="text" name="cajaBuscar" autofocus required value="<?php echo $valCajatexto; ?>">
                            <!--TODO 	Añadir required -->
	<input type="submit" name="enviaBusqueda" value="Enviar">
	<br/>
<!-- 	<span>Resultados</span> -->
	<br/>
<!-- 	<span>Continent:</span> -->
<!-- 	<br/> -->
	<fieldset>
    	<legend align="left">Continent</legend>
	
    	<table>
        		<tr>
        			<td>
        				<input id="b_Asia" type="checkbox" name="b_Asia">
        				<label for="b_Asia">Asia</label>
        			</td>
        			<td>
        				<input id="b_Europe" type="checkbox" name="b_Europe">
        				<label for="b_Europe">Europe</label>
        			</td>
        		</tr>
        		<tr>
        			<td>
        				<input id="b_NAmerica" type="checkbox" name="b_NAmerica">
        				<label for="b_NAmerica">N. America</label>
        			</td>
        			<td>
        				<input id="b_Africa" type="checkbox" name="b_Africa">
        				<label for="b_Africa">Africa</label>
        			</td>
        		</tr>
        		<tr>
        			<td>
        				<input id="b_Oceania" type="checkbox" name="b_Oceania">
        				<label for="b_Oceania">Oceania</label>
        			</td>
        			<td>
        				<input id="b_SAmerica" type="checkbox" name="b_SAmerica">
        				<label for="b_SAmerica">S. America</label>
        			</td>
        		</tr>
        	</table>
	</fieldset>

<br/>

<fieldset>
<legend align="left">Population</legend>
	<input id="rbMore" type="radio" name="moreLess" value="more" checked>
	<label for="rbMore">More</label>
	<input id="rbLess" type="radio" name="moreLess" value="less">
	<label for="rbLess">Less</label>
	<br/>
	<input id="rango" type="range" min="0" max="100000000" value="0" oninput="cambiarRango()" />
<br/>
<p id="numeroRango"	>0</p>
	</form>
</fieldset>


<br/>
<span>Results:</span>

<div class="scrollResultados">


 <?php 

    if (isset($_GET['enviaBusqueda'])){
        
        $numResultados = 0;
        
        echo "<form action='index.php' method='post'>";
        while($registro = $resultado->fetch(PDO::FETCH_ASSOC)){
            
            //Registro es el array que contiene la fila del resultset $resultado
            //echo "<span id='" . $registro["Name"] . "' class='eligePais'>" . $registro["Name"] . "</span><br/>";
            //<input type="submit" name="enviaBusqueda" value="Enviar">
            echo "<input type='submit' class='eligePais' name='enviaResultados' formmethod='post' value='" . $registro["Name"] . "' id='" . $registro["Code"] . "'><br/>";
            $numResultados++;
        }
        echo "</form>";
        
        
        $resultado->closeCursor();
        
        
        
    }


?>


</div>

<p id="resultados">
<?php
    if (isset($_GET['enviaBusqueda'])){
        echo $numResultados . " result" . (($numResultados==1) ? "" : "s");
    }else{
        echo "-";
    }
?>
</p>

</aside>

<main>

<?php 

    //pasar estas variables al if
    $bdContinente ="img/mundo.png";
    $bdRegion = "-";
    $bdNombrePais = "-";
    $bdCapital = "-";
    $bdNombreLocal = "-";
    $bdCodigo = "-";
    $bdArea = "-";
    $bdPoblacion = "-";
    $bdGobiernoJefe = "-";
    $bdGNP = "GNP: ";
    $bdIndepe = "";
    $bdVida = "";

    if (isset($_POST['enviaResultados'])){
        
        $paisElegido = $_POST["enviaResultados"];
        

        try{
            
            $base = new PDO("mysql:host=localhost; dbname=world", "root", "");
            
            //echo "Conexión OK <br/><br/>";
            
            //Le dice a la base de datos que ante los errores (primer parámetro)
            //lance excepciones (segundo parámetro), así se pueden capturar en este humilde php
            $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $base->exec("SET CHARACTER SET utf8");
            
            //Sentencia SQL, usa un marcador (sintaxis de los 2 puntos obligatoria??)
            $sql="SELECT * FROM country WHERE Name = :n_pais";
            
            //PDO Statement (preparada)
            $resultado = $base->prepare($sql);
            
            //ResultSet
            $resultado->execute(array(":n_pais"=>$paisElegido));
            
            //Bucle con cada fila (fetch da true si encuentra fila, luego se ejecuta el while para esa fila)
            
            $registro = $resultado->fetch(PDO::FETCH_ASSOC);
            
            $bdRegion = $registro["Region"];
            
            $bdNombrePais = $registro["Name"];
            
            $bdCapital = $registro["Capital"]==NULL ? "-" : $registro["Capital"];
            
            $bdNombreLocal = $registro["LocalName"];
            
            $bdCodigo = $registro["Code"];
            
            $bdArea = $registro["SurfaceArea"];
            
            $bdPoblacion = $registro["Population"]==0 ? "Uninhabited" : $registro["Population"];
            
            $bdGobiernoJefe = $registro["GovernmentForm"] . ($registro["HeadOfState"]==NULL ? "" : " - " . $registro["HeadOfState"]);
            
            if($registro["GNP"] != NULL && $registro["GNP"] != 0){
                
                $bdGNP = $bdGNP . $registro["GNP"];
                
                if($registro["GNPOld"] != NULL){
                    
                    $bdGNP = $bdGNP . " - Old GNP: " . $registro["GNPOld"];
                    
                }
                
            }else{
                
                $bdGNP = $bdGNP . "No info.";
            }
            
            $bdIndepe = $registro["IndepYear"] == NULL ? "?" : abs($registro["IndepYear"]) . ($registro["IndepYear"] > 0 ? " CE" : " BCE");
            
            $bdVida = $registro["LifeExpectancy"] == NULL ? "No info." : $registro["LifeExpectancy"] . " years";
            
            if($registro["Code"]=="ESP"){
                
                $bdContinente = "img/ESPANA.png";
                
            }else{
                
                switch($registro["Continent"]){
                    case "Asia":
                        $bdContinente = "img/asia.png";
                        break;
                    case "Europe":
                        $bdContinente = "img/euro.png";
                        break;
                    case "North America":
                        $bdContinente = "img/namer.png";
                        break;
                    case "Africa":
                        $bdContinente = "img/afri.png";
                        break;
                    case "Oceania":
                        $bdContinente = "img/ocea.png";
                        break;
                    case "Antarctica":
                        $bdContinente = "img/anta.png";
                        break;
                    case "South America":
                        $bdContinente = "img/samer.png";
                        break;
                    default:
                        $bdContinente = "img/mundo.png";
                }
                
            }
            
       }catch(Exception $e){
            
            echo $e->getMessage() . "<br/>La has liado, chavea.";
            
        }
    }

?>

<div class="divV1">
	<div class="divV1H1">
	<img id="imagenMundo" alt="Mundo" src="<?php echo $bdContinente; ?>">
	<p><?php echo $bdRegion ?></p>
	</div>
	<div class="divV1H2">
	<p><?php echo $bdNombrePais; ?></p>
	<p><?php echo $bdCapital; ?></p>
	<p><?php echo $bdNombreLocal; ?></p>
	<p><?php echo $bdCodigo; ?></p>
	</div>
</div>

<div class="divV2">

	<p>Area: <?php echo $bdArea; ?> km²</p>
	<p>Population: <?php echo $bdPoblacion; ?></p>
	<p><?php echo $bdGobiernoJefe; ?></p>
	<p><?php echo $bdGNP; ?></p>
	<p>Independence year: <?php echo $bdIndepe; ?></p>
	<p>Life Expectancy: <?php echo $bdVida; ?></p>
	
</div>

<div class="divV3">

    <div class="divV3H1">
    	<?php
    	
    	if (isset($_POST['enviaResultados'])){
    	    
    	    $sql = "SELECT Name, Population FROM city WHERE CountryCode = '" . $bdCodigo . "' ORDER BY Population DESC";
    	    
    	    $resultado = $base->prepare($sql);
    	    
    	    $resultado->execute(array());
    	    
    	    echo "<table><th>Cities</th>";
    	    
    	    while($registro = $resultado->fetch(PDO::FETCH_ASSOC)){
    	        
    	        echo "<tr><td>" . $registro["Name"] . "</td><td class='aladerecha'>" . $registro["Population"] . "</td></tr>";
    	        
    	    }
    	    
    	    echo "</table>";
    	    
    	}
    	
    	
    	
    	
    	
    	?>
    </div>
	
	<div class="divV3H2">
		
		<?php
		
		if (isset($_POST['enviaResultados'])){
		    
		    $sql = "SELECT Language, Percentage FROM countrylanguage WHERE CountryCode = '" . $bdCodigo . "' ORDER BY Percentage DESC";
		    
		    $resultado = $base->prepare($sql);
		    
		    $resultado->execute(array());
		    
		    echo "<table><th>Languages</th>";
		    
		    while($registro = $resultado->fetch(PDO::FETCH_ASSOC)){
		        
		        echo "<tr><td>" . $registro["Language"] . "</td><td class='aladerecha'>" . $registro["Percentage"] . "</td></tr>";
		        
		    }
		    
		    echo "</table>";
		    
		}
    	
    	
    	
    	
    	
    	?>	


    </div>
	
</div>


</main>
</body>
</html>
