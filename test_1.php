<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>PHP Test</title>
</head>

<body>
<h2>Please enter some text including the ampersand and click &quot;submit;&quot;</h2>
<form id="form1" name="form1" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <label for="TEST"></label>
  <input name="TEST" type="text" id="TEST" size="40" />
  <label for="button"></label>
  <input type="submit" name="button" id="button" value="Submit" />
</form>
<?php
	if (isset($_GET['TEST'])) {
		$s = $_GET['TEST'];
		echo "<hr />\n";
		echo "Sumitted text: $s<br /><br />";
		$s = htmlentities($s);
		
		echo "Literal:<br /><pre>$s</pre>\n";
	}
?>
</body>
</html>
