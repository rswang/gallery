<?php
error_reporting(0);
if(isset($_POST['submit']))
{
	$target = "../uploads/"; 
	$allowedExts = array("jpg", "jpeg");
	$extension = end(explode(".", $_FILES["file_upload"]["name"]));
	$target = $target . basename( $_FILES['file_upload']['name']);
	$date = date("Y-m-d H:i:s");

	//Function to generate image thumbnails
	function make_thumb($src, $dest, $desired_width) {

		/* read the source image */
		$source_image = imagecreatefromjpeg($src);
		$width = imagesx($source_image);
		$height = imagesy($source_image);
	
		/* find the "desired height" of this thumbnail, relative to the desired width  */
		$desired_height = floor($height * ($desired_width / $width));
	
		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	
		/* copy source image at a resized size */
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	
		/* create the physical thumbnail image to its destination with 100% quality*/
		imagejpeg($virtual_image, $dest,100);
	}

	//create a mysql connection
	$dbhost							= "127.0.0.1";
	$dbuser							= "rswang";
	$dbpass							= "rachel";
	$dbname							= "gallery.phptos";

	$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ("Error connecting to database");
	mysql_select_db($dbname) or die ("Error selecting the database");

	//check for allowed extensions
	if ((($_FILES["file_upload"]["type"] == "image/jpg")|| ($_FILES["file_upload"]["type"] == "image/jpeg"))&& in_array($extension, $allowedExts))
	{
		$photoname = $_FILES["file_upload"]["name"];
		if (file_exists("../uploads/" . $photoname))
		{
			die( '<div class="error">Sorry <b>'. $photoname . '</b> already exists</div>');
		}
	
		if(move_uploaded_file($_FILES['file_upload']['tmp_name'], $target)) 
		{
			$query = "INSERT INTO photos (photo_name,date_added) VALUES ('$photoname','$date')";
			mysql_query($query); 
			$sql = "SELECT MAX(id) FROM photos";
			$max = mysql_query($sql);
			$row = mysql_fetch_array($max);
			$maxId = $row['MAX(id)'];
		
			$type = $_FILES["file_upload"]["type"];
			switch($type)
			{
				case "image/jpeg":
				$ext = ".jpeg";
				break;
				case "image/jpg";
				$ext = ".jpg";
				break;			
			}
		
		    //define arguments for the make_thumb function
			$source = "../uploads/".$photoname;
			$destination = "../thumbnails/thumb_". $maxId . $ext ."";			
			//specify your desired width for your thumbnails
			$width = "282";
			//Finally call the make_thumb function
			make_thumb($source,$destination,$width);
		
			$msg = '<div class="success">
						<b>Upload: </b>' . basename($photoname) . '<br />
    					<b>Type: </b>' . $_FILES["file_upload"]["type"] . '<br />
    					<b>Size: </b>' . ceil(($_FILES["file_upload"]["size"] / 1024)) . 'Kb<br />
			  		</div>';
			}	 
			else
			{
				$msg = '<div class="error">Sorry, there was a problem uploading your file.</div>';
			}
		}
		else
		{
			$msg = '<div class="error">The file type you are trying to upload is not allowed!</div>';
		}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload Image</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<H3>Upload Images Here</H3>(<a href="../" target="_blank">View Gallery</a>)
<div id="upload">
<?php echo $msg; ?>
<form action="" method="post" enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file_upload" id="upload_file" />
<input type="submit" name="submit" value="Upload" />
</form>
</div>
</body>
</html>