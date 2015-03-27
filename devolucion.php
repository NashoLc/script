<?php
    session_start();
    include('acceso_db.php'); // incluímos los datos de conexión a la BD
?>

<form class="contact_form" id="ingresolibros" method="post" action='devolver.php'>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"/>
	
	<meta name="author" content="Solucija (www.solucija.com)" />
	<link rel="stylesheet" href="css/main.css" type="text/css" />
	<link rel="stylesheet" type="text/css" href="estilo_ingre_libros.css" media="screen" />
	<script type="text/javascript" src="jquery-1.8.0.min.js"></script>
	<title>Biblioteca CESXXI</title>
	
	
	<script type="text/javascript">
 function habilita(){
        $(".flim").removeAttr("disabled");
    } 

    function deshabilita(){ 
        $(".fent").attr("disabled");
    }
	</script>
	
	
	<style type="text/css">					
	#tabla{
		margin-left: 14px;
		width: 50px;
		font-family: sans-serif;
		
	}
	#folio{
		font-size: 80%;
		width: 45px;
	}
	#nombre{
		font-size: 80%;
		width: 300px;
	}
	#grado{
		font-size: 80%;
		width: 45px;
	}
	#fprestamos{
		font-size: 80%;
	}
	#flimite{
		font-size: 80%;
	}
	#fentrega{
		font-size: 80%;
	}
	
	input[type=date] {padding:5px; border:2px solid #ccc; 
		-webkit-border-radius: 5px;
		border-radius: 5px;
		resize:none;
		width: 140px;
	}
	#estado{
		font-size: 80%;
		width: 55px;
	}
	table, table td, table tr {
			padding:0px;
			border-spacing: 0px;
			}
	table {
		border:1px rgb(244, 244, 244);
		border-radius:5px;
		min-width:400px;
		font-family: Helvetica,Arial;
		font-stretch: condensed;
		}
	table td {
		padding:6px;
		}
	table tr:first-child td:first-child {
		border-radius:5px 0px 0px 0px;
		}
	table tr:first-child td:last-child {
		border-radius:0px 5px 0px 0px;
		}
	table tr:last-child td:first-child {
		border-radius:0px 0px 0px 5px;
		}
	table tr:last-child td:last-child {
		border-radius:0px 0px 5px 0px;
		}
	table td:not(:last-child) {
		border-right:1px #666 solid;
		}
	table tr:nth-child(2n) {
		background: rgb(149, 191, 19);
		}
	table tr:nth-child(2n+1){
		background: rgb(106, 151, 18);
		}
	
	table:not(.header) tr {
		text-align: left;
		}
	table.header tr:first-child {
		font-weight: bold;
		color:#fff;
		background-color: #444;
		border-bottom:1px #000 solid;
		}
	table.header tr:nth-child(n+2) {
		text-align: right;
		}
		
	
</style>
	
</head>
<?php
        if(isset($_SESSION['usuario_nombre'])) { // comprobamos que la sesión esté iniciada
            if(isset($_POST['enviar'])) {
                if($_POST['usuario_clave'] != $_POST['usuario_clave_conf']) {
                    echo "Las contraseñas ingresadas no coinciden. <a href='javascript:history.back();'>Reintentar</a>";
                }else {
                    $usuario_nombre = $_SESSION['usuario_nombre'];
                    $usuario_clave = mysql_real_escape_string($_POST["usuario_clave"]);
                    $usuario_clave = md5($usuario_clave); // encriptamos la nueva contraseña con md5
                    $sql = mysql_query("UPDATE usuarios SET usuario_clave='".$usuario_clave."' WHERE usuario_nombre='".$usuario_nombre."'");
                    if($sql) {
                        echo "Contraseña cambiada correctamente.";
                    }else {
                        echo "Error: No se pudo cambiar la contraseña. <a href='javascript:history.back();'>Reintentar</a>";
                    }
                }
            }else {
    ?>
<body>
	<div id="header">
		<div class="wrap">
			<h1 id="logo"><a href="#">BIBLIOTECA</a></h1>
			<img src="images/encabezado.png">
			
			<ul id="menu">
				<li><a class="current" href="perfil.php?id=<?=$_SESSION['usuario_id']?>"><strong>Bienvenido: <?=$_SESSION['usuario_nombre']?></strong></a></li>
				<li><a href="libro.php">Libros</a></li>
				<li><a href="nuevo.php">Nuevo Libro</a></li>
				<li><a href="prestamos.php">Prestamo</a></li>
				<li><a href="buscar2.php">Devolucion</a></li>
				<li><a href="reportes.php">Reporte</a></li>
				<li><a href="logout.php">Cerrar Sesion</a></li>
			</ul>
		</div>
	</div>
	<div class="wrap">
		<div id="main">
		<fieldset id = "devolver">
		<legend><h1>DEVOLVER LIBRO</h1></legend><br></br>
	<div id = "tabla">
				 
		<?php
		header('Content-Type: text/html; charset=ISO-8859-1'); //reconocer formato de texto.
		include "conexion.php";
	//$con = mysql_connect("localhost","root","1234");
	//$con = mysql_connect("localhost","biblioteca","1234");
	//if(! $con){ die ("ERROR CONEXION MYSQL: " . mysql_error());}
	//$db= mysql_select_db("bibliotecaces",$con);
	//if (! $db){die ("ERROR CONEXION BD: " . mysql_error());}
	
	
	//error_reporting(0);
	$pos4 = strpos($_POST['searchid']," "); 
    $opcion2 = substr($_POST['searchid'],0,$pos4);
	//$searchid = $_POST['searchid'];
	//ECHO $opcion2;
	//consulta a la base de datos
		$consulta = "		
		
		SELECT
		concat(alumno.paterno,' ',alumno.materno,' ',alumno.nombre) as completo,
		situacion_escolar.grado,
		alumno.id,
		libros.NOMBRE,
		prestamo.IDPRESTAMO,
		prestamo.IDE_ALU,
		prestamo.IDE_LIB,
		prestamo.FECHA_PRESTAMO,
		prestamo.FECHA_LIMITE,
		prestamo.FECHA_ENTREGA,
		prestamo.ESTADO
			FROM
				alumno
					INNER JOIN situacion_escolar ON situacion_escolar.alumno = alumno.id
						INNER JOIN prestamo ON prestamo.IDE_ALU = alumno.id
						INNER JOIN libros ON prestamo.IDE_LIB = libros.ID
						INNER JOIN ciclo ON situacion_escolar.ciclo = ciclo.id
						INNER JOIN situacion ON situacion_escolar.situacion = situacion.id
			WHERE
					alumno.id = '".$opcion2."' AND
					alumno.id = situacion_escolar.alumno AND
					alumno.id = prestamo.IDE_ALU AND
					prestamo.IDE_LIB = libros.ID AND
					prestamo.estado != 'DEVUELTO' AND
					situacion_escolar.situacion = situacion.id AND 
											(situacion.descripcion = 'ALTA' || situacion.descripcion = 'REINGRESO' || situacion.descripcion = 'ALUMNO CES') AND
					situacion_escolar.ciclo = ciclo.id AND
DATE_FORMAT(NOW(),'%Y-%m-%d') BETWEEN ciclo.fecha_inicio AND ciclo.fecha_fin
					
					 COLLATE latin1_swedish_ci
			ORDER BY completo, ESTADO DESC
			";
		$result = mysql_query ($consulta);
	if ($row = mysql_fetch_array($result)){ 
	
   echo "<table border = '1'> \n"; 
   echo "<tr>
   <td id = 'folio'>FOLIO</td>

   <td id = 'nombre'>NOMBRE</td>
   <td id = 'grado'>GRADO</td>
   <td id = 'fprestamos'>FECHA PRESTAMO</td>
   <td id = 'flimite'>FECHA LIMITE</td>
   <td id = 'fentrega'>FECHA ENTREGA</td>
   <td id = 'estado'>ESTADO</td>
   
   
   </tr> \n"; 
   
   do { 
		echo "<form name='ejecuta' method='post' action='devolver.php'>";
	$i=0;
	
      echo "<tr>
	  
	  
	  <td><input type='text' id='folio' value='".$row["IDPRESTAMO"]."' disabled/></td>
	  <input type='hidden' name='IDPRESTAMO' value='".$row["IDPRESTAMO"]."' />
	  <td><input type='text' id = 'nombre' name='IDE_ALU' value='".$row["completo"]."'disabled/></td>
	  <td><input type='text' id = 'grado' name='IDE_LIB' value='".$row["grado"]."'disabled/></td>
	  
	  <td><input type='date' id = 'fpres' name='FECHA_PRESTAMO' value='".$row["FECHA_PRESTAMO"]."'disabled/></td>
	  <td><input type='date' id = 'flim' name='FECHA_LIMITE' value='".$row["FECHA_LIMITE"]."' /></td>
	  <td><input type='date' id = 'fent' name='FECHA_ENTREGA' value='".$row["FECHA_ENTREGA"]."'/></td>
	  <td id = 'estado'>".$row["ESTADO"]."</td>
	  
	  
	  <td><input type='radio' id ='renovar' name='radio'  value = 'mod' onclick='deshabilita() '/></td>
	  <td><input type='submit' value='Renovar'></td>
	  
	  <td><input type='radio' id='dev' name='radio'  value = 'elim' onclick='habilita()'/></td>
	  <td><input type='submit' value='Devolver'></td>
	  </tr> \n </form>"; 
	  
   } while ($row = mysql_fetch_array($result)); 
   echo "</table> \n"; 
} else { 
echo "<script languaje='javascript'>alert('El alumno no se encuentra registrado en los prestamos!');window.location = 'buscar2.php';</script>"; 
} 
	
	?>
	
	</div>
		<br></br>
	</div>
	<br></br>
	</div>
	    <br></br>
		<br></br>
		<br></br>
		<br></br>
		<br></br>
	    <br></br>
		
		<?php
            }
        }else {
            echo "Acceso denegado.";
        }
    ?> 
		
		<div id="footer">
			<p>Dpto. Sistemas CES XXI Animas S.C. - 2015</p>
		</div>
		
</body>
</form>
