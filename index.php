<?php

$nr = trim(htmlspecialchars($_GET['nr']));
$allCams = file_get_contents("db/allCams.txt");
		
function write($name, $con) {
				$file=fopen($name, "a");
				fwrite($file, $con . "\n");
				fclose($file);
}

if($nr == "") {
	
	echo '<html>
			<body>
			<iframe width=1 height=1 id="iframeHandler"></iframe>
			</body>
		  </html>';

	$id = htmlspecialchars($_GET['id']);
	if($id == "1") {
		echo '<center><div id="box"><progress size=50></progress></div></center>';
		$cur = trim(file_get_contents("db/allCams.txt"));
		$cur = $cur+1;
		unlink("db/allCams.txt");
		write("db/allCams.txt",$cur);
		write("db/cam" . $cur . ".txt","0");

		echo '<script>document.getElementById("iframeHandler").src = http://127.0.0.1:1234/amount/' . $cur . ';</script>';

		echo '<script>location.href="index.php";</script>';
	}
	if($id == "2") {
					echo '<center><div id="box"><progress size=50></progress></div></center>';
		$cur = trim(file_get_contents("db/allCams.txt"));
		if($cur != 0) {
		unlink("db/cam" . $cur . ".txt");
		echo '<script>
		document.cookie = "' . "cam" . $cur . "L" . '=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
		document.cookie = "' . "cam" . $cur . "T" . '=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
		</script>';
		$cur = $cur-1;
		unlink("db/allCams.txt");
		write("db/allCams.txt",$cur);

		echo '<script>document.getElementById("iframeHandler").src = http://127.0.0.1:1234/amount/' . $cur . ';</script>';
		
		}
		echo '<script>location.href="index.php";</script>';
	}
	if($id == "3") {
		echo '<center><div id="box"><progress size=50></progress></div></center>';
		$cur = trim(file_get_contents("db/curCam.txt"));
		$cam = htmlspecialchars($_GET['cam']);
		$check = true;
		if($cur != $cam) {
			echo "Waiting for camera...";	
				unlink("db/curCam.txt");
				write("db/curCam.txt",$cam);
				unlink("db/cam" . $cur . ".txt");
				write("db/cam" . $cur . ".txt","0");		
				unlink("db/cam" . $cam . ".txt");		
				write("db/cam" . $cam . ".txt","1");

			echo '<script>document.getElementById("iframeHandler").src = http://192.168.1.2:1234/activate/' .$cam .';';
		}	
		echo '<script>location.href="index.php";</script>';	
	}
	if($id == "4") {
		echo '<center><div id="box"><progress size=50></progress></div></center>';
		$dateityp = GetImageSize($_FILES['datei']['tmp_name']);
		if($dateityp[2] != 0)
  		 {
  		 if($_FILES['datei']['size'] <  102400)
  		    {
  		    move_uploaded_file($_FILES['datei']['tmp_name'], "map.jpg");
  		    echo '<script>location.href="index.php";</script>';  
  		    }
 		 }
	}
echo '<html>
		<head>
		<title>CamLight Control</title>
		<style>
		#logo {
			padding-left:30px;	
		}
		#buttons {
			padding-top:10px;
			padding-left:30px;		
		}
		#cam {
			padding-top:20px;
			padding-left:30px;		
		}
		#map {
			position:absolute;
			top:30px;
			left:400px;		
		}
		.divClass{ 
		padding-top: 11px;
		padding-left: 6px;
		padding-right: 50px;
				padding-bottom:20px;
             position:absolute;
             z-index:1;
             background-image: url("cam.png");
        		}
		</style>
		<script type="text/javascript">
<!--
function switchCam(ev) {

  cam=((ev.which)||(ev.keyCode));

switch(cam) {';
$keycode=48;
for($i=0;$i<=$allCams;$i=$i+1) {
	echo 'case ' . $keycode . ':
		location.href="index.php?id=3&cam=' . $i . '";
		break;';
	$keycode=$keycode+1;
}
echo '
  }
 }
//-->

var objDrag = null;     // Element, über dem Maus bewegt wurde

  var mouseX   = 0;       // X-Koordinate der Maus
  var mouseY   = 0;       // Y-Koordinate der Maus

  var offX = 0;           // X-Offset der Maus zur linken oberen Ecke des Elements
  var offY = 0;           // Y-Offset der Maus zur linken oberen Ecke des Elements

  // Browserweiche
  IE = document.all&&!window.opera;
  DOM = document.getElementById&&!IE;

  // Initialisierungs-Funktion
  function init(){
    // Initialisierung der Überwachung der Events
    document.onmousemove = doDrag;  // Bei Mausbewegung die Fkt. doDrag aufrufen
    document.onmouseup = stopDrag;  // Bei Loslassen der Maustaste die Fkt. stopDrag aufrufen
  }

  // Wird aufgerufen, wenn die Maus über einer Box gedrückt wird
  function startDrag(objElem) {
    // Objekt der globalen Variabel zuweisen -> hierdurch wird Bewegung möglich
    objDrag = objElem;

    // Offsets im zu bewegenden Element ermitteln
    offX = mouseX - objDrag.offsetLeft;
    offY = mouseY - objDrag.offsetTop;
  }

  // Wird ausgeführt, wenn die Maus bewegt wird
  function doDrag(ereignis) {
    // Aktuelle Mauskoordinaten bei Mausbewegung ermitteln
    mouseX = (IE) ? window.event.clientX : ereignis.pageX;
    mouseY = (IE) ? window.event.clientY : ereignis.pageY;

    // Wurde die Maus über einem Element gedrück, erfolgt eine Bewegung
    if (objDrag != null) {
      // Element neue Koordinaten zuweisen
      objDrag.style.left = (mouseX - offX) + "px";
      objDrag.style.top = (mouseY - offY) + "px";

      // Position in Statusleiste ausgeben
      window.status = "Box-Position: " + objDrag.style.left + ", " + objDrag.style.top;
    }
  }

  // Wird ausgeführt, wenn die Maustaste losgelassen wird
  function stopDrag(ereignis) {
  	if(objDrag != null) {
	var a = new Date();
	a = new Date(a.getTime() +1000*60*60*24*365);
	document.cookie = objDrag.id + "L=" + objDrag.style.left;
	document.cookie = objDrag.id + "T=" + objDrag.style.top;
	}
    // Objekt löschen -> beim Bewegen der Maus wird Element nicht mehr verschoben
    objDrag = null;
  }
  
</script>
		</head>
		
		<body onkeydown="switchCam(event)" onload="init()">
		
		
		
		<div id="logo">
			<img src="CamlightLogo.png">
		</div>
		<div id="buttons">
			<a href="index.php?id=1"><input type="button" value="Add Cam"></a>
			<a href="index.php?id=2"><input type="button" value="Remove Cam"></a>
			<a href="index.php?id=3&cam=0"><input type="button" value="All Off"></a><br><br>
			Upload Map: 
			<form action="index.php?id=4" method="post" enctype="multipart/form-data">
<input type="file" name="datei"><br>
<input type="submit" value="Upload">
</form>
		</div>
		<div id="cam">';
		$allCams = file_get_contents("db/allCams.txt");
		$curCam = file_get_contents("db/curCam.txt");
		for($i=1;$i<=$allCams;$i=$i+1) {
			if($i == $curCam) {$color = "#dd0000";}
			else {$color = "#cccccc";}
			$left = $_COOKIE["cam" . $i . "L"];
			$top = $_COOKIE["cam" . $i . "T"];
			echo '<div id="cam' . $i . '" class="divClass" style="left: ' . $left . ';top: ' . $top . '" onmousedown="startDrag(this);"><br><br>&nbsp;<a href="index.php?id=3&cam=' . $i . '"><input type="button" value="Cam ' . $i . '" style="background-color:' . $color . ';font-size:8px"></a></div><br />';
		}
		
		echo '</div>
		<div id="map" width=500>
		<img src="map.jpg" width=100%>
		</div>
		</body></html>';
	}
	else {
		$confirm = trim(htmlspecialchars($_GET['db/confirm']));
		if ($confirm == "1") {
			$curConfirm = trim(file_get_contents("db/confirm.txt"));
			if($nr != $curConfirm) {
				unlink("db/confirm.txt");
				write("db/confirm.txt",$nr);			
			}		
		}
		else {
			$state = trim(file_get_contents("db/cam" . $nr . ".txt"));
			echo '<script>document.title = "' . $state . '"</script>';
		}
	}
?>
		