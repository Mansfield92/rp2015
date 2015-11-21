<?php
error_reporting(E_ALL ^ E_NOTICE);
#################################################################################################
#	IMAGE FUNCTIONS FILE  - Adjust directory as required									   	#
#	Please also adjust the directory to this file in the "index.php" page						#
include("image_functions.php");                                    #
#################################################################################################

########################################################
#	UPLOAD THE IMAGE								   #
########################################################
if ($_POST["upload"] == "Upload") {
    //Get the file information
    $userfile_name = $_FILES['image']['name'];
    $userfile_tmp = $_FILES['image']['tmp_name'];
    $userfile_size = $_FILES['image']['size'];
    $userfile_type = $_FILES['image']['type'];
    $large_image_location.= $_POST['filename'];
    $filename = basename($_FILES['image']['name']);
    $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));

    if ((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {

        foreach ($allowed_image_types as $mime_type => $ext) {
            if ($file_ext == $ext && $userfile_type == $mime_type) {
                $error = "";
                break;
            } else {
                $error = "Only <strong>" . $image_ext . "</strong> images accepted for upload<br />";
            }
        }
        if ($userfile_size > ($max_file * 1048576)) {
            $error .= "Images must be under " . $max_file . "MB in size";
        }

    } else {
        $error = "Please select an image for upload";
    }
    if (strlen($error) == 0) {
        if (isset($_FILES['image']['name'])) {
            $large_image_location = $large_image_location . "." . $file_ext;
            $thumb_image_location = $thumb_image_location . "." . $file_ext;

            if ($_SESSION['user_file_ext'] != $file_ext) {
                $_SESSION['user_file_ext'] = "";
                $_SESSION['user_file_ext'] = "." . $file_ext;
            }

            move_uploaded_file($userfile_tmp, $large_image_location);
            chmod($large_image_location, 0777);

            $width = getWidth($large_image_location);
            $height = getHeight($large_image_location);
            //Scale the image if it is greater than the width set above
            if ($width > $max_width) {
                $scale = $max_width / $width;
                $uploaded = resizeImage($large_image_location, $width, $height, $scale);
            } else {
                $scale = 1;
                $uploaded = resizeImage($large_image_location, $width, $height, $scale);
            }
            //Delete the thumbnail file so the user can create a new one
            if (file_exists($thumb_image_location)) {
                unlink($thumb_image_location);
            }
            echo "success|" . $large_image_location . "|" . getWidth($large_image_location) . "|" . getHeight($large_image_location);
        }
    } else {
        echo "error|" . $error;
    }
}
//
//########################################################
//#	CREATE THE THUMBNAIL							   #
//########################################################
//if ($_POST["save_thumb"]=="Save Thumbnail") {
//	//Get the new coordinates to crop the image.
//	$x1 = $_POST["x1"];
//	$y1 = $_POST["y1"];
//	$x2 = $_POST["x2"];
//	$y2 = $_POST["y2"];
//	$w = $_POST["w"];
//	$h = $_POST["h"];
//	//Scale the image to the thumb_width set above
//	$large_image_location = $large_image_location.$_SESSION['user_file_ext'];
//	$thumb_image_location = $thumb_image_location.$_SESSION['user_file_ext'];
//	$scale = $thumb_width/$w;
//	$cropped = resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);
//	echo "success|".$large_image_location."|".$thumb_image_location;
//	$_SESSION['random_key']= "";
//	$_SESSION['user_file_ext']= "";
//}
//
#####################################################
#	DELETE BOTH IMAGES								#
#####################################################
if ($_POST['a']=="delete" && strlen($_POST['img'])>0){
//get the file locations
    if(strpos($_POST['img'], '../') !== FALSE) {
        $loc = $_POST['img'];
    }else{
        $loc = '../' . $_POST['img'];
    }
	if (file_exists($loc)) {
		unlink($loc);
        echo "success|Files have been deleted";
	}else{
        echo "error|Hups, neco se posralo :)".file_exists($loc).'-'.$loc;
    }
}
?>