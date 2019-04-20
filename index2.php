<?php
    session_start();
    include "checkpass.php"; 
?>

<!DOCTYPE html>
<html id = "html">
    <head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> <!-- Open Sans Font -->
	<link rel="stylesheet" href="index2.css">
    </head>
    <body onload="scale();" onresize="scale();">
	<div id="navbar">
	    <div class="inlineblockformdiv">
		<img src="Assets/SVG/cloud.svg" width="140px;"/>
		<p style="margin-top:0px;">Modify:</p>
		<form action="selectall.php" method="post" enctype="multipart/form-data">
		    <input type="submit" value="Select All" id="selectall" name="selectallbtn" class="inputButton_navbar">
		</form>
		<form action="refresh.php" method="post" enctype="multipart/form-data">
		    <input type="submit" value="Deselect All" id="deselectall" name="deselectallbtn" class="inputButton_navbar">
		</form>
		<div>
		    <input type="button" value="Upload File" id="uploadfile" class="inputButton_navbar" onclick="openModify('uploadform', this, 0); scale();">
		</div>
		<div>
		    <input type="button" value="New Folder" id="newfolder" class="inputButton_navbar" onclick="openModify('newfolderform', this, 0);scale();">
		</div>
		<form action="moveto_file.php" method="post" enctype="multipart/form-data">
		    <input type="submit" value="Move File(s)" name="moveto" id="movefile" class="inputButton_navbar">
		</form>
		<form action="copyto_file.php" method="post" enctype="multipart/form-data">
		    <input type="submit" value="Copy File(s)" name="copyto" id="copyfile" class="inputButton_navbar">
		</form>
		<form action="logout.php" method="post" enctype="multipart/form-data">
		    <input type="submit" value="Log Out" id="logout" name="logout" class="inputButton_navbar">
		</form>
	    </div>
	    <div class="modify_dropdown">
		<form action="upload.php" method="post" enctype="multipart/form-data" class="forms2" id="uploadform">
		    Select file(s) to upload:
		    <input type="file" name="fileToUpload[]" id="fileToUpload" multiple="multiple" class="inputButton">
		    <input type="submit" value="Upload File" name="submit" class="inputButton dropdown_spacing"><br>
		    <p style="color:red; margin:0px;">WARNING: Files with same name will be replaced.</p>
		</form>
		<form action="newfolder.php" method="post" enctype="multipart/form-data" class="forms" id="newfolderform">
		    Add a folder:<br>
		    <input type="text" name="foldername" placeholder="Folder Name" class="textBox">
		    <input type="submit" value="Add New Folder" name="addnewfolder" class="inputButton dropdown_spacing">
		</form>
	    </div>
	    </div><!-- This comment removes the gap due from inline block style
	 --><div id="filemanager">
		<?php include "system_stats.php";?>
		<?php include "current_dir.php";?><!-- current_dir.php displays the current directory so that the user knows where to go. Show_dir.php shows the contents of that directory -->
		<div class="dir_nav_buttons">
		    <form action="back.php" method="post" enctype="multipart/form-data">
			<input type="submit" value="Back" name="goback" class="inputButton">
		    </form><!-- This comment removes the gap due from inline block style
	         --><form action="home.php" method="post" enctype="multipart/form-data">
			<input type="submit" value="Go Home" name="gohome" class="inputButton">
		    </form><!--
		 --><form action="delete.php" id="delete" method="post" enctype="multipart/form-data">
			<input type="submit" value="Delete" name="delete" class="inputButton_delete">
		    </form>
		</div>
		<?php include "show_dir.php";?>
	    </div>
	<script type="text/javascript" src="index2.js"></script>
    </body>
</html>
