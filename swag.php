<?php
		include 'settings/settings.php';

		if(!isset($_COOKIE['pinkman_lng']))
		{
    	setcookie("pinkman_lng", "hu", time() + (365 * 24 * 60 * 60 * 1000), "/");
    	$_COOKIE['pinkman_lng'] = 'hu';
		}

	  if(isset($_COOKIE['pinkman_lng']) && $_COOKIE['pinkman_lng'] == "hu")
		{
			 include'language/hu.php';
		}
		elseif(isset($_COOKIE['pinkman_lng']) && $_COOKIE['pinkman_lng'] == "en")
		{
			 include'language/en.php';
		}
		else
		{
			include'language/hu.php';
		}

		$conn = new mysqli($servername, $username, $password, $dbname);

		if(!$conn)
		{
			die("Csatlakozási hiba.");
		}

		if(mysqli_connect_errno())
		{
			printf("Csatlakozási hiba.");
			exit();
		}
		
		$conn->set_charset("utf8");
		
		function max_id_lekerdezes()
		{
			global $conn;

			$sqlselect = "SELECT MAX(id) FROM files";
			if($result = $conn->query($sqlselect))
			{
				while ($ertek=mysqli_fetch_row($result))
				{
					return $ertek[0];
				}
			}
			else
			{
				die('Hiba a lekérdezés során: [' . $conn->error . ']');
			}
		}

		function file_nev_lekerdezes($id)
		{
			global $conn;
			$sqlselect = "SELECT nev FROM files WHERE id=" . $id . " AND aktiv=1";
			if($result = $conn->query($sqlselect))
			{
				while ($ertek=mysqli_fetch_row($result))
				{
					return $ertek[0];
				}
			}
			else
			{
				die('Hiba a lekérdezés során: [' . $conn->error . ']');
			}
		}

		function getLines($file)
		{
			$f = fopen($file, 'rb');
			$lines = 0;
			while (!feof($f))
			{
				$lines += substr_count(fread($f, 8192), "\n");
			}
			fclose($f);
			return $lines;
		}

		function getNyelv($id)
		{
			global $conn;
			$sqlq = "SELECT tipus FROM files WHERE id=" . $id;
			if($result = $conn->query($sqlq))
			{
				while ($ertek=mysqli_fetch_row($result))
				{
					return $ertek[0];
				}
			}
			else
			{
				die('Hiba a lekérdezés során: [' . $conn->error . ']');
			}
		}

		function file_aktiv_q($id)
		{
			global $conn;
			$sqlll = "SELECT aktiv FROM files WHERE id=" . $id;
			if($result = $conn->query($sqlll))
			{
				while ($ertek=mysqli_fetch_row($result))
				{
					return $ertek[0];
				}
			}
			else
			{
				die('Hiba a lekérdezés során: [' . $conn->error . ']');
			}
		}

		function isHomePage()
		{
			$pageHelperF = $_SERVER['QUERY_STRING'];
			$pageF = substr($pageHelperF, 5);
			if(is_numeric($pageF) || !$pageHelperF)
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}

		function formatSizeUnits($bytes)
		{
			if ($bytes >= 1073741824)
			{
				$bytes = number_format($bytes / 1073741824, 2) . ' Gb';
			}
			elseif ($bytes >= 1048576)
			{
				$bytes = number_format($bytes / 1048576, 2) . ' Mb';
			}
			elseif ($bytes >= 1024)
			{
				$bytes = number_format($bytes / 1024, 2) . ' Kb';
			}
			elseif ($bytes > 1)
			{
				$bytes = $bytes . ' bytes';
			}
			elseif ($bytes == 1)
			{
				$bytes = $bytes . ' byte';
			}
			else
			{
				$bytes = '0 bytes';
			}
			return $bytes;
		}
?><!DOCTYPE html>
<html>

<head>

	<meta charset="UTF-8">
	<meta name="description" content="Forráskódok.">
	<meta name="keywords" content="forraskodok, kodok, codes, programs, programming, program, java, html, php">
	<meta name="author" content="Zsigmond Péter">
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href='https://fonts.googleapis.com/css?family=Roboto&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="css/pinkman.css">
	
	<link rel="icon" href="pics/ico.ico" type="image/x-icon">
	<title>Pinkman</title>
	
	<script src="//cdn.jsdelivr.net/cookie-bar/1/cookiebar-latest.min.js?theme=grey&remember=365"></script>
	
</head>

<body>
<div id="wrapper">
		<div id="fejlec">

				<div class="fejleclogo"><img src="pics/logo.jpg" alt="Pinkman Logo" onclick="location.href='http://pinkman.pe.hu'"></div>

				<div class="fejleclng"><img src="pics/eng_ico.png" alt="ENG icon" width="45" height="30" class="lngSelector" onClick="setCookie('pinkman_lng', 'en'), reloadPage();"></div>
				<div class="fejleclng"><img src="pics/hun_ico.png" alt="HUN icon" width="45" height="30" class="lngSelector" onClick="setCookie('pinkman_lng', 'hu'), reloadPage();"></div>

				<ul>
					<?php
						$pageHelper = $_SERVER['QUERY_STRING'];
						$page = substr($pageHelper, 5);

						$melyikAktiv = array("","","","");

						if(isHomePage())
						{
							$melyikAktiv[0] = " class='aktiv'";
						}
						elseif($page == "feltoltes" || $page == "upload")
						{
							$melyikAktiv[1] = " class='aktiv'";
						}
						elseif($page == "torles" || $page == "delete")
						{
							$melyikAktiv[2] = " class='aktiv'";
						}
						elseif($page == "programok" || $page == "programs")
						{
							$melyikAktiv[3] = " class='aktiv'";
						}

						echo'
						<li' . $melyikAktiv[0] . '><a href="http://pinkman.pe.hu">'. $nyelv[0] .'</a></li>
						<li' . $melyikAktiv[1] . '><a href="http://pinkman.pe.hu/'. $nyelv[1] .'">'. $nyelv[2] .'</a></li>
						<li' . $melyikAktiv[2] . '><a href="http://pinkman.pe.hu/'. $nyelv[3] .'">'. $nyelv[4] .'</a></li>
						<li' . $melyikAktiv[3] . '><a href="http://pinkman.pe.hu/'. $nyelv[22] .'">'. $nyelv[23] .'</a></li>
				</ul>
		</div>


		<div id="tartalom">';
		
		if(isHomePage())
		{

		echo"<div id='leftpanel'>
		<ul>";

			for($i = max_id_lekerdezes(); $i >= 0; $i--)
			{
				$fileneve = file_nev_lekerdezes($i);
				$ftype = "files/" . $i . "." . $filetype;
				if($fileneve)
				{
					echo"<li>";

						echo "<a href='http://pinkman.pe.hu/" . $i . "'>" . $fileneve . "<br><br>";
						if(file_exists($ftype))
						{
							echo"<div class='gombDate'>";
							echo date("Y.m.d.", filemtime($ftype));
							echo"</div></a>";
						}

					echo"</li>";
				}
			}
			echo"</ul></div>";

			echo"<div id='rightpanel'>";
				if($page)
				{
					$filename = "files/" . $page . "." . $filetype;
					if(file_exists($filename))
					{
						$fileaktiv = file_aktiv_q($page);
						if($fileaktiv)
						{
							$filenevee = file_nev_lekerdezes($page);
							$meret = filesize($filename);
							$merett = formatSizeUnits($meret);

							$nyelvek = array($nyelv[6], "PHP", "Java", "C", "C++", "C#", "Ruby", "Python", "JavaScript", "SQL", "Objective-C");
							$nyelvlekerdezes = getNyelv($page);
							$nyelvtipus = $nyelvek[$nyelvlekerdezes];

							echo"<div class='rightheader'>";
								echo"<p style='font-size:18px;'>" . $filenevee . "</p>";
								echo"<br><a class='rightheadersz'>" . date("Y.m.d. H:i:s", filemtime($filename)) . "</a>";
								echo"<a class='rightheadersz'>". $nyelv[5] .": <span style='color:#52ba71';>" . $nyelvtipus . "</span></a>";
								echo"<a class='rightheadersz'>". $nyelv[7] .": " . $merett . "</a>";
							echo"</div>";

							echo "<div class='szamok'><pre class='pre_szamok'>";
							for($k=1; $k <= getLines($filename)+1; $k++)
							{
								echo $k . ".<br>";
							}
							echo "</pre></div>";

							$file = fopen($filename, "r");
							echo "<div class='szoveg'><pre class='pre_szoveg'><ul>";
							while(!feof($file))
							{
								$goz = fgets($file);
								$goy = htmlspecialchars($goz, ENT_QUOTES);
								echo "<li>" . $goy . "</li>";
							}
							echo "</ul></pre></div>";

							fclose($file);
						}
						else
						{
							echo "<p style='padding-top:15px'>".$nyelv[21]."</p>";
						}
					}
					else
					{
						echo "<p style='padding-top:15px'>".$nyelv[20]."</p>";
					}
				}
				else
				{
					echo "<p style='padding-top:15px'>".$nyelv[19]."</p>";
				}

			echo"</div>";

			}
			elseif($page=="feltoltes" || $page=="upload")
			{
				echo'<div id="felttartalom">
				<div id="feltt">';

				if(isset($_POST['keszF']) && !empty($_POST['neveF']) && !empty($_POST['tartalmaF']))
				{
					if(strlen($_POST['neveF'])>100)
					{
						echo $nyelv[15];
					}
					else
					{
						$sqlF = "INSERT INTO `files` (`id`, `nev`, `tipus`) VALUES (NULL, '" . $_POST['neveF'] . "'," . $_POST['nyelvF'] . ")";

						if ($conn->query($sqlF) === TRUE)
						{
							$last_id = $conn->insert_id;

							$tartalomF = explode("\n", str_replace("\r", "", $_POST['tartalmaF']));
							$arrayyF = array_map('rtrim', $tartalomF);
							$tartalommF = implode("\n", $arrayyF);

							$mentettF = fopen("files/" . $last_id . "." . $filetype, 'w');
							fwrite($mentettF, $tartalommF);
							fclose($mentettF);

							header("Location: " . $last_id);
						}
						else
						{
							echo "Hiba: " . $sqlF . "<br>" . $conn->error;
						}
						$conn->close();
					}
				}


					echo '<form action="" method="POST">
					<input type="text" name="neveF" maxlength="100" autofocus="autofocus" required="required" placeholder="'.$nyelv[16].'">

					<br>

					<p>'.$nyelv[5].':

					<select name="nyelvF">';


					$nyelvekF = array($nyelv[6], "PHP", "Java", "C", "C++", "C#", "Ruby", "Python", "JavaScript", "SQL", "Objective-C");

					for ($iF = 0; $iF < count($nyelvekF); $iF++)
					{
						echo "<option value='" . $iF . "'>" . $nyelvekF[$iF] . "</option>";
					}
				echo'</select>
				</p>
				<br>
			</div>

			<div id="felta">
				<textarea name="tartalmaF" rows="10" cols="10" required="required" maxlength="100000" placeholder="'.$nyelv[17].'"></textarea>
				<br>
				<input type="submit" name="keszF" value="'.$nyelv[18].'">
			</form>
			</div>
			</div>';
			}
			elseif($page=="torles" || $page=="delete")
			{
				echo'
					<form action="" method="POST">
					<input type="text" name="szamm" maxlength="10" autofocus="autofocus" required="required" placeholder="'.$nyelv[12].'">
					<input type="password" name="pww" maxlength="30" required="required" placeholder="'.$nyelv[13].'">
					<input type="submit" name="kessz" value="'.$nyelv[14].'">
					</form>';

				if(isset($_POST['kessz']) && !empty($_POST['szamm']) && !empty($_POST['pww']))
				{
					$szamid = $_POST['szamm'];
					if(is_numeric($szamid) && $szamid > 0)
					{
						$postpw = $_POST['pww'];
						if(hash('sha512', $postpw."salt") == $deletepw) // replace salt with real salt.
						{
							$sqql = "UPDATE `files` SET `aktiv`=0 WHERE `id`=" . $_POST['szamm'];
							if ($conn->query($sqql) === TRUE)
							{
								echo $nyelv[11] . $_POST['szamm'];
							}
						}
						else
						{
							echo "<p>".$nyelv[8]."</p>";
						}
					}
					else
					{
						echo "<p>".$nyelv[9]."</p>";
					}
				}
			}
			elseif($page=="programok" || $page=="programs")
			{					
					echo $nyelv[24]."<br>";
					$pStyleT = " style='border:1px solid black; border-collapse: collapse; margin: 10px 10px 10px 50px;'";
					$pStyle = " style='border:1px solid black; border-collapse: collapse; padding: 10px;'";
					echo "<table".$pStyleT.">";

					foreach(glob("progs/*.*") as $pNeveH)
					{
					  $pNeve = substr($pNeveH, 6);
					  $pMereteH = filesize($pNeveH);
					  $pMerete = formatSizeUnits($pMereteH);
					  $pTime = date("Y.m.d. H:i", filemtime($pNeveH));
					  echo "<tr" . $pStyle . ">";
					  echo "<td" . $pStyle . ">" . $pTime . "</td><td" . $pStyle . "><a href='" . $pNeveH . "'>" . $pNeve . "</a></td><td" . $pStyle . ">" . $pMerete . "</td>";
					  echo"</tr>";
					}
					echo "</table>";
			}
			else
			{
				echo"<h1>".$nyelv[10]."</h1>";
			}
			?>

	</div>

	<div id="lablec">
		<p><a href="https://www.facebook.com/rajpeterv2.0" target="_blank">Zsigmond Péter</a>, 2016</p>
	</div>
	
	<script src="javascript/jquery.min.js"></script>
	<script src="javascript/jquery.transit.min.js"></script>
	<script src="javascript/pinkman.js"></script>
	
</div>	
</body>

</html>