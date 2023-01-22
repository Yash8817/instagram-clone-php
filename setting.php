<?php

session_start();
require "Database.php";

$database = new Database();
$db = $database->getConnection();

if (!isset($_SESSION['login_user']) || !isset($_SESSION['login_user_id'])) {
    header("location: signin.php");
}

$userid = $_SESSION['login_user_id'];

$stmt = $db->prepare("select p.* from user u inner join profile p on u.id = p.user_id where p.user_id = :data");
$stmt->bindParam(':data', $userid);
$stmt->execute();
$user_data = $stmt->fetchAll();

if (isset($_POST['save_profile_data'])) {

// echo "<pre>";
    // print_r($_FILES);
    // echo "</pre>";
    // die();


    $bio = $_POST['bio'];
    $location = $_POST['location'];

    $old_img  = $user_data[0]['profile_image'];


    

    // image upload
    if ($_FILES['userimage']['name'] != "") {

        #   Set a directory to save uploaded files
        $target_dir           = "media/profile_images";

        $path_of_file_to_save = $target_dir . '/' . $_SESSION['login_user'] . ".jpg";

        // path_of_file_to_save = media/profile_images/yash_8817.jpg

        $imageinfo = getimagesize($_FILES["userimage"]["tmp_name"]);

        $flag_safe_to_upload  = true;

        if ($imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/jpg') {
            echo ("<br>Only JPG is allowe <br><hr>");
            $flag_safe_to_upload = false;
            $updatestatus        = false;
        }

        if ($flag_safe_to_upload == true) {
            move_uploaded_file($_FILES["userimage"]["tmp_name"], $path_of_file_to_save);
        }
    } else {
        $path_of_file_to_save = $old_img;
    }


    if (isset($_POST['private_account']) && ($_POST['private_account'] == "on")) {
        $private = 1;
    } else {
        $private = 0;
    }










    $stmt = $db->prepare("UPDATE profile SET bio = :bio,profile_image = :p_image, location =:user_location , is_private =:is_private  WHERE user_id =:uid ;");
    $stmt->bindParam(':bio', $bio);
    $stmt->bindParam(':p_image', $path_of_file_to_save);
    $stmt->bindParam(':user_location', $location);
    $stmt->bindParam(':is_private', $private);
    $stmt->bindParam(':uid', $userid);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        header("Location:setting");
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/images/favicon.png" rel="icon" type="image/png">
    <title>Settings</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include_once "css.php"  ?>

</head>

<body>
    <div class="container m-auto">
        <h1 class="text-2xl leading-none text-gray-900 tracking-tight mt-3"><a href="/">Home</a> / Account Setting for
            <?php echo $_SESSION['login_user'] ?>
        </h1>
        <br>
        <hr>
        <div class="grid lg:grid-cols-3 mt-12 gap-8">
            <div>
                <h3 class="text-xl mb-2"> General</h3>
                <p></p>
            </div>
            <div class="bg-white rounded-md lg:shadow-lg shadow col-span-2">

                <form action="setting" method="POST" enctype="multipart/form-data">
                    <div class="grid grid-cols-2 gap-3 lg:p-6 p-4">
                        <div class="col-span-2">
                            <label for=""> Profile Image </label>
                            
                            <img width="100" height="100" src="<?php echo $user_data[0]['profile_image']; ?>" />
                            <input type="file" name="userimage" id="userimage" value="" class="shadow-none bg-gray-100">
                        </div>
                        <div class="col-span-2">
                            <label for="about"></label>
                            <textarea id="about" name="bio" rows="3" class="shadow-none bg-gray-100"><?php echo $user_data[0]['bio']; ?></textarea>
                        </div>
                        <div class="col-span-2">
                            <label for=""> Location</label>
                            <input type="text" name="location" value="<?php echo $user_data[0]['location']; ?>" class="shadow-none bg-gray-100">
                        </div>


                        <div class="bg-white rounded-md lg:shadow-lg shadow lg:p-6 p-4 col-span-2">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4> Private Account</h4>
                                        <div> </div>
                                    </div>

                                    <div class="switches-list -mt-8 is-large">
                                        <div class="switch-container">
                                            <?php
                                            $check = "";
                                            if ($user_data[0]['is_private']) {
                                                $check = "checked";
                                            }
                                            ?>
                                            <label class="switch"><input type="checkbox" <?php echo $check; ?> name="private_account"><span class="switch-button "></span> </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>






                    <div class="bg-gray-10 p-6 pt-0 flex justify-end space-x-3">
                        <button type="submit" class="button bg-blue-700" name="save_profile_data"> Save </button>
                    </div>



            </div>


            </form>


        </div>
    </div>





    <!-- Scripts
    ================================================== -->
    <script src="assets/js/tippy.all.min.js"></script>
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/js/uikit.js"></script>
    <script src="assets/js/simplebar.js"></script>
    <script src="assets/js/custom.js"></script>
</body>

</html>