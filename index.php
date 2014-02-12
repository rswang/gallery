<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Photo Gallery</title>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/thickbox.js"></script>
<link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body bgcolor="#FFFFFF" background="images/bg.gif"> 
<div id="wrapper">
<p align="center"><img src="images/photo-gallery.png" width="793" height="71" alt="simple php photo gallery" /></p>
<hr style="margin-bottom: 20px;" width="98%" />
<?php
$dbhost							= "127.0.0.1";
$dbuser							= "root";
$dbpass							= "";
$dbname							= "gallery";

$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ("Error connecting to database");
mysql_select_db($dbname) or die ("Error selecting the database");

function cleanString($caption="")
{
	$removeunderscore = str_replace("_"," ",$caption);
	$removedash = str_replace("-"," ",$removeunderscore);
	$removedimensions = str_replace("1366x768","",$removedash);
	$cleanstring = str_replace(".jpg","",$removedimensions);	
	return $cleanstring;
}
$sql = "SELECT  * FROM photos ORDER BY id ASC";
$query = mysql_query($sql);
while($row = mysql_fetch_array($query))
{
	$file = $row['photo_name'];
	echo '<div id="container">
  			<div id="thumbnail"><a href="uploads/'. $file .'"  title="'.cleanString($file).'" class="thickbox"><img src="thumbnails/thumb_'.$row['id'].'.jpeg" width="282" height="158" alt="image" /></a></div>
  		    <div id="info"><strong>' .cleanString($file).'</div>
		  </div>';
}
?>
<div class="clear"></div>
</div>
</body>
</html>