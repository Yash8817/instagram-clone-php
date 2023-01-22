<?php
require "Database.php";
include "function.php";

$message   = [];

if (isset($_POST['usersingup'])) {

    $database = new Database();
    $db = $database->getConnection();

    $full_name = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];

    $stmt = $db->prepare("select * from user where username = :data");
    $stmt->bindParam(':data', $username);
    $stmt->execute();
    $users = $stmt->fetchAll();

    if (count($users) > 0) {
        array_push($message, "Username already exists, try diffrent one");
    }
    



    $stmt = $db->prepare("select * from user where mobile = :data");
    $stmt->bindParam(':data', $mobile);
    $stmt->execute();
    $users = $stmt->fetchAll();

    if (count($users) > 0) {
        array_push($message, "mobile already registered, try diffrent one");
    }

    $stmt = $db->prepare("select * from user where email = :data");
    $stmt->bindParam(':data', $email);
    $stmt->execute();
    $users = $stmt->fetchAll();
    if (count($users) > 0) {
        array_push($message, "email already exists, try diffrent one");
    }


    if (!$message) {
        
        $stmt = $db->prepare("INSERT INTO `user` (`full_name`, `username`, `mobile`, `email`, `user_password`) 
        VALUES (:fname,:uname, :mbl, :emil, :pwd);");
        $stmt->bindParam(':fname', $full_name);
        $stmt->bindParam(':uname', $username);
        $stmt->bindParam(':mbl', $mobile);
        $stmt->bindParam(':emil', $email);
        $stmt->bindParam(':pwd', $password);
        $stmt->execute();


        if ($stmt->rowCount() == 1) {
            $letest_id = $db->lastInsertId();
        }

        

        $stmt2 = $db->prepare("INSERT INTO `social_media_app`.`profile` (`bio`, `profile_image`, `location`, `is_private`, `user_id`)
         VALUES ('', 'media/profile_image/', '', '0', :uid);");
        $stmt2->bindParam(':uid', $letest_id);
        $stmt2->execute();

        if ($stmt2->rowCount() == 1) {
            header("Location:signin");
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/images/favicon.png" rel="icon" type="image/png">
    <title>Sign Up - Social Book</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include_once "css.php"  ?>


</head>

<body class="bg-gray-100">


    <div id="wrapper" class="flex flex-col justify-between h-screen">

        <?php include_once "header.php"; ?>

        <!-- Content-->

        <div>
            <div class="lg:p-12 max-w-md max-w-xl lg:my-0 my-12 mx-auto p-6 space-y-">
                <h1 class="lg:text-3xl text-xl font-semibold mb-6"> Sign Up</h1>
                <!--<p class="mb-2 text-black text-lg"> Register to manage your account </p>-->


                <?php
                if ($message) {
                ?>

                    <div>
                        <style>
                            h5 {
                                color: red;
                            }
                        </style>
                        <?php
                        foreach ($message as $msg) { ?>
                            <h5> <?php echo $msg  ?></h5>
                        <?php
                        }
                        ?>
                    </div>

                <?php
                } ?>



                <form action="<?php echo (basename($_SERVER['SCRIPT_FILENAME'])); ?>" method="POST" id="singupform">

                    <input type="text" id="fullname" name="fullname" placeholder="full name" class="bg-gray-200 mb-2 shadow-none  dark:bg-gray-800" style="border: 1px solid #d3d5d8 !important;">
                    <input type="text" name="username" id="username" placeholder="Username" class="bg-gray-200 mb-2 shadow-none  dark:bg-gray-800" style="border: 1px solid #d3d5d8 !important;">
                    <input type="email" name="email" id="email" placeholder="Email" class="bg-gray-200 mb-2 shadow-none  dark:bg-gray-800" style="border: 1px solid #d3d5d8 !important;">
                    <input type="text" name="mobile" id="mobile" placeholder="mobile" class="bg-gray-200 mb-2 shadow-none  dark:bg-gray-800" style="border: 1px solid #d3d5d8 !important;">
                    <input type="password" id="password" name="password" placeholder="Password" class="bg-gray-200 mb-2 shadow-none  dark:bg-gray-800" style="border: 1px solid #d3d5d8 !important;">
                    <input type="password" id="password2" name="password2" placeholder="Confirm Password" class="bg-gray-200 mb-2 shadow-none  dark:bg-gray-800" style="border: 1px solid #d3d5d8 !important;">
                    <button type="submit" class="bg-gradient-to-br from-pink-500 py-3 rounded-md text-white text-xl to-red-400 w-full" name="usersingup">Sign Up</button>

                    <div class="text-center mt-5 space-x-2">
                        <p class="text-base"> Do you have an account? <a href="signin"> Login </a></p>

                    </div>
                </form>



            </div>
        </div>

        <!-- Footer -->
        <?php include_once "Footer.php"; ?>


    </div>


    <!-- Scripts
    ================================================== -->
    <?php include_once "script.php"; ?>



</body>

<script>
    $(document).ready(function() {

        $(singupform).submit(function() {

            var flagValidation = true;

            if (($("#fullname").val().length == 0) || ($("#username").val().length == 0) || ($("#email").val().length == 0) || ($("#password").val().length == 0) || ($("#password2").val().length == 0) || ($("#mobile").val().length == 0)) {
                alert("All fields are required for signup");
                flagValidation = false;
            }

            if (($("#mobile").val().length != 0)) {
                if ($("#mobile").val().length < 10 || $("#mobile").val().length > 10) {
                    alert("invalid mobile number");
                    flagValidation = false;
                }
            }


            if (($("#password").val().length != 0) && ($("#password2").val().length != 0)) {
                if ($("#password").val() != $("#password2").val()) {
                    alert("password and confirm password not matching");
                    flagValidation = false;
                }
            }


            return flagValidation;

        });

    });
</script>


</html>