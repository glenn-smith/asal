<!-- The data encoding type, enctype, MUST be specified as below -->
<br><br><br><br><br>
<form enctype="multipart/form-data" action="upload.php" method="POST">

<center>
<table border="1" cellpadding="0"  width=300>

<tr>
				<td valign="top">
			<center>
				
	<br>
	<br>
				

    <!-- MAX_FILE_SIZE must precede the file input field -->

    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />

    <!-- Name of input element determines name in $_FILES array -->

   <b><font face=arial color=Black>Files</font></b><br><input name="userfile" type="file" /><br>

    <input type="submit" name= submit value="Send File" />

</form>
<font size=2 face=arial color=Black><b>MAX FILE SIZE IS 5MB</b></font>
<br><font size=1 face=arial color=darkred><b>2MB upload time appx: 55 seconds</b></font></b>
</table>
</center>

