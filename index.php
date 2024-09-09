<?php require_once('DBConn.php');


if(isset($_GET['action'])) {
	switch($_GET['action']) {
		// case "deletePage":
		// $sql = "DELETE FROM pagine WHERE id_page=" . $_GET['p'];

		// $res = mysqli_query($DBConn, $sql);
		// $goto = "";
		// break;

		case "deletePar":
		$sql = "DELETE FROM testi WHERE id_par=" . $_GET['par'];
		$res = mysqli_query($DBConn, $sql);
		$goto = "?p=" . $_GET['p'];
		break;

		// case "deleteContact":
		// $sql = "DELETE FROM contatti WHERE id=" . $_GET['id'];

		// $res = mysqli_query($DBConn, $sql);
		// $goto = "?p=" . $_GET['p'];
		// break;

		// case "deleteMsg":
		// $sql = "DELETE FROM messaggi WHERE id=" . $_GET['id'];

		// $res = mysqli_query($DBConn, $sql);
		// $goto = "?p=" . $_GET['p'];
		// break;
	}
	header("location:index.php" . $goto);
}


$query_pagine = "SELECT id_page, nome, pos FROM pagine ORDER BY pos ASC";
$pagine = mysqli_query($DBConn, $query_pagine);
$row_pagine = mysqli_fetch_assoc($pagine);
$totalRows_pagine = mysqli_num_rows($pagine);

$colname_pagina = "-1";
if (isset($_GET['p'])) {
  $colname_pagina = $_GET['p'];
}

$query_pagina = "SELECT * FROM pagine WHERE id_page = $colname_pagina";
$pagina = mysqli_query($DBConn, $query_pagina);
$row_pagina = mysqli_fetch_assoc($pagina);
$totalRows_pagina = mysqli_num_rows($pagina);

$colname_par = "-1";
if (isset($_GET['p'])) {
  $colname_par = $_GET['p'];
}

$query_par = "SELECT * FROM testi WHERE page_id = $colname_par ORDER BY pos_par ASC";
$par = mysqli_query($DBConn, $query_par);
$row_par = mysqli_fetch_assoc($par);
$totalRows_par = mysqli_num_rows($par);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sito test</title>
	<link href='https://fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>
	<link href="admin.css" rel="stylesheet" type="text/css" />
	<script>
		function tmt_confirm(msg){
			document.MM_returnValue=(confirm(unescape(msg)));
		}
	</script>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="menu">
			<ul>
				<?php if ($totalRows_pagine > 0) { ?>
					<?php do { ?>
					<li>
						<a href="index.php?p=<?php echo $row_pagine['id_page']; ?>"<?php if($_GET['p']==$row_pagine['id_page']) { ?> class="sel"<?php } ?>>
							<?php echo $row_pagine['pos']; ?>. <?php echo $row_pagine['nome']; ?>
						</a>
					</li>
					<?php } while ($row_pagine = mysqli_fetch_assoc($pagine)); ?>
			<?php } ?>
			</ul>
		</td>
		<td valign="top" class="contents">
			<h3><?php echo $row_pagina['nome']; ?></h3>
			<div class="clear"></div>
			<?php if ($totalRows_par > 0) { ?>
				<?php do { ?>
				<div class="par">
					<?php if($row_par['imm']!="") { ?>
					<div class="img"><img src="images/<?php echo $row_par['imm']; ?>" /></div>
					<?php } ?>
					<p><strong><?php echo $row_par['titolo']; ?></strong></p>
					<div class="p"><?php echo $row_par['testo']; ?></div>
					<div class="clear"></div>
					<p>
						<a href="edit_par.php?p=<?php echo $row_pagina['id_page']; ?>&par=<?php echo $row_par['id_par']; ?>" class="edit">correggi</a>
						<a href="index.php?p=<?php echo $row_pagina['id_page']; ?>&par=<?php echo $row_par['id_par']; ?>&action=deletePar" class="delete" onclick="tmt_confirm('Attenzione,%20questa%20azione%20&egrave;%20definitiva!');return document.MM_returnValue">cancella</a>
					</p>
					<div class="clear"></div>
				</div>
				<?php } while ($row_par = mysqli_fetch_assoc($par)); ?>
			<?php } ?>
		</td>
	</tr>
</table>
</body>
</html>