<?php require_once('DBConn.php');

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = "UPDATE testi SET
  				titolo='".mysqli_real_escape_string($DBConn, $_POST['titolo'])."',
				testo='".mysqli_real_escape_string($DBConn, $_POST['testo'])."',
				pos_par=".(int)$_POST['pos_par'].",
				page_id=".(int)$_POST['page_id']."
				WHERE id_par=" . (int)$_POST['id_par'];


  $Result1 = mysqli_query($DBConn, $updateSQL);

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_modifica = "-1";
if (isset($_GET['par'])) {
  $colname_modifica = $_GET['par'];
}

$query_modifica = "SELECT * FROM testi WHERE id_par = $colname_modifica";
$modifica = mysqli_query($DBConn, $query_modifica);
$row_modifica = mysqli_fetch_assoc($modifica);
$totalRows_modifica = mysqli_num_rows($modifica);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Modifica paragrafo</title>
<link href="admin.css" rel="stylesheet" type="text/css" />
<script language='JavaScript' src='../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="checkFileUpload(this,'JPG',false,'','','','','','','');showProgressWindow('showProgress.htm',300,100);return document.MM_returnValue">
	<table width="90%" border="0" align="center" cellpadding="2" cellspacing="0" class="editTable">

		<tr>
			<td nowrap="nowrap" align="center">Titolo:
				<br />
			<input type="text" name="titolo" value="<?php echo $row_modifica['titolo']; ?>" size="50"/></td>
			<td align="center">Immagine</td>
		</tr>
		<tr>
			<td align="center" valign="top" nowrap="nowrap">Testo:
				<br />
			<textarea name="testo" id="testo"><?php echo $row_modifica['testo']; ?></textarea>
			<script> CKEDITOR.replace( 'testo', {
					height : 600,
					allowedContent : true
				});</script></td>
			<td align="center" valign="top" nowrap="nowrap"><?php if($row_modifica['imm']!="") { ?><div class="img2"><img src="../images/<?php echo $row_modifica['imm']; ?>" /></div><?php } ?></td>

		</tr>

		<tr>
			<td colspan="2" align="center" nowrap="nowrap">Posizione:
				<input name="pos_par" type="text" value="<?php echo $row_modifica['pos_par']; ?>" size="3" maxlength="3" /></td>
		</tr>
		<tr>
			<td colspan="2" align="center" nowrap="nowrap"><input type="submit" value="salva &gt;&gt;" /></td>
		</tr>
	</table>
	<input type="hidden" name="id_par" value="<?php echo $row_modifica['id_par']; ?>" />
	<input type="hidden" name="page_id" value="<?php echo $row_modifica['page_id']; ?>" />
	<input type="hidden" name="MM_update" value="form1" />
</form>
<p>&nbsp;</p>
<form id="form2" name="form2" method="post" action="<?php echo $_SERVER['HTTP_REFERER']; ?>">
	<label>
	<div align="center">
		<input type="submit" name="button" id="button" value="&lt;&lt; annulla" />
	</div>
	</label>
</form>
</body>
</html>
<?php
mysqli_free_result($modifica);
?>
