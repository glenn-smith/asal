<?php


    /* Script generated for Network Solutions  
   	In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
	of $_FILES.
   */

$uploaddir = 'files/';  // The path that you would like to upload your files to.
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);  // File Upload stored into the variable

echo '<pre>';

			

	
		if (copy($_FILES['userfile']['tmp_name'], $uploadfile)){

			echo "<center>File was successfully uploaded.\n <Br> <a href=index.php>Go Back</a></center>";

			echo"<meta http-equiv='refresh' content='3; url=index.php'>";


		}else{
		   echo "File upload error \n";

		

		}




?> 