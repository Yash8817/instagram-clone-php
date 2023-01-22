<?php
session_start();

require "Database.php";

$message   = [];

if (isset($_POST['loginbtn'])) {

    $database = new Database();
    $db = $database->getConnection();

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $db->prepare("select * from user where username = :data");
    $stmt->bindParam(':data', $username);
    $stmt->execute();
    $user = $stmt->fetchAll();

    if (count($user) > 0) {
        
        $user_pass =  $user[0]['user_password'];

        // echo "<pre>";
        // print_r($user);
        // echo "</pre>";

        if ($password != $user_pass) {
            array_push($message, "Invalid password");
        } else {
            $_SESSION['login_user'] = $username;
            $_SESSION['login_user_id'] = $user[0]['id'];
            header("location: index.php");
        }
        // die();

    } else {
        array_push($message, "Username does not exist!, create account first");
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/images/favicon.png" rel="icon" type="image/png">
    <title>Sign In - Social Book</title>
    <?php include_once "css.php"  ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body class="bg-gray-100">


    <div id="wrapper" class="flex flex-col justify-between h-screen">

        <?php include_once "header.php"; ?>

        <!-- Content-->
        <div>
            <div class="lg:p-12 max-w-md max-w-xl lg:my-0 my-12 mx-auto p-6 space-y-">
                <h1 class="lg:text-3xl text-xl font-semibold  mb-6"> Log in</h1>


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



                <form action="" method="POST" id="signinform">

                    <input type="text" name="username" id="username" placeholder="Username" class="bg-gray-200 mb-2 shadow-none dark:bg-gray-800" style="border: 1px solid #d3d5d8 !important;">
                    <input type="password" name="password" id="password" placeholder="Password" class="bg-gray-200 mb-2 shadow-none dark:bg-gray-800" style="border: 1px solid #d3d5d8 !important;">
                    <!-- <input type="checkbox">check -->
                    <button type="submit" class="bg-gradient-to-br from-pink-500 py-3 rounded-md text-white text-xl to-red-400 w-full" name="loginbtn">Login</button>
                    <div class="text-center mt-5 space-x-2">
                        <p class="text-base"> Not registered? <a href="signup" class=""> Create a account </a></p>
                    </div>

                </form>
            </div>
        </div>
        <?php include_once "Footer.php"; ?>
        <!-- Scripts
        ================================================== -->

        <?php include_once "script.php"; ?>

</body>

<script>
    $(document).ready(function() {

        $(signinform).submit(function() {

            var flagValidation = true;

            if (($("#username").val().length == 0) || ($("#password").val().length == 0)) {
                alert("All fields are required for signup");
                flagValidation = false;
            }
            return flagValidation;

        });

    });
</script>


</html>