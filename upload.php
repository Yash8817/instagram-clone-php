<?php

session_start();
require "Database.php";

$database = new Database();
$db = $database->getConnection();

if (!isset($_SESSION['login_user']) || !isset($_SESSION['login_user_id'])) {
    header("location: signin.php");
}

$userid = $_SESSION['login_user_id'];

if(isset($_POST['newpost']))
{
    $caption = $_POST['caption'];
    $cdate   = date("Y-m-d"); 
    
    if($_POST['radio2'] == "public")
    {
        $is_hidden = 0;
        $is_private = 0;
    }

    if($_POST['radio2'] == "Followers")
    {
        $is_hidden = 0;
        $is_private = 1;
    }
    
    if($_POST['radio2'] == "hidden")
    {
        $is_hidden = 1;
        $is_private = 0;
    }

    // image upload
    if ($_FILES['image_upload']['name'] != "") {
        #   Set a directory to save uploaded files
        $target_dir = "media/post_images";

        $path_of_file_to_save = $target_dir . '/' . $_SESSION['login_user'] ."-". date("Ymdhis") . ".jpg";

        $imageinfo = getimagesize($_FILES["image_upload"]["tmp_name"]);

        $flag_safe_to_upload  = true;

        if ($imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/jpg') {
            echo ("<br>Only JPG is allowe <br><hr>");
            $flag_safe_to_upload = false;
            $updatestatus        = false;
        }

        if ($flag_safe_to_upload == true) {
            if (!move_uploaded_file($_FILES["image_upload"]["tmp_name"], $path_of_file_to_save)) {
                $updatestatus = false;
            }
        }
    }

    $stmt = $db->prepare("INSERT INTO post(`userid`, `image`, `caption`, `create_date`, `no_of_like`, `is_hidden` , `is_followeronly`) 
    VALUES(:userid,:image_name, :caption, :create_date, 0 ,  :is_hidden  ,:is_followeronly); ");
    $stmt->bindParam(':userid', $userid);
    $stmt->bindParam(':image_name', $path_of_file_to_save);
    $stmt->bindParam(':caption', $caption);
    $stmt->bindParam(':create_date',$cdate );
    $stmt->bindParam(':is_hidden', $is_hidden);
    $stmt->bindParam(':is_followeronly', $is_private);
    $stmt->execute();


    if ($stmt->rowCount() == 1) {
        header("Location:home");
    }else
    {
        die("error in new post");
    }


    
}



