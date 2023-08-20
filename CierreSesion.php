<html>
<head>
</head>
<body>
<?php
	session_start();
	
	session_unset();
	
	header("location:index.php");
?>

</body>
</html>