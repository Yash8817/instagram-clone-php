<?php

session_start();
require "Database.php";

$database = new Database();
$db = $database->getConnection();

if (!isset($_SESSION['login_user']) || !isset($_SESSION['login_user_id'])) {
    header("location: signin.php");
}

$userid = $_SESSION['login_user_id'];

$stmt = $db->prepare("select p.*,u.* from post p inner join user u on u.id= p.userid where u.id = :data ");
$stmt->bindParam(':data', $userid);
$stmt->execute();
$user_data = $stmt->fetchAll();

//   echo "<pre>";
// echo count($user_data);
//     print_r($user_data);
//     echo "</pre>";
//     die();


// fetch post

$stmt = $db->prepare("select u.* , p.* from user u inner join profile p on u.id = p.user_id  where u.id = :data ");
$stmt->bindParam(':data', $userid);
$stmt->execute();
$user_data = $stmt->fetchAll();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="" />

    <title>Profile - <?php echo $user_data[0]['username']; ?> </title>
    <link rel="icon" href="images/fav.png" type="image/png" sizes="16x16">

    <link rel="stylesheet" href="css/main.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/color.css">
    <link rel="stylesheet" href="css/responsive.css">

</head>

<body>
    <div class="se-pre-con"></div>
    <div class="theme-layout">

    </div>

    <section>
        <div class="feature-photo">
            <figure><img src="images/resources/timeline-1.jpg" alt=""></figure>
            <form action="/follow" method="POST">
                <div class="add-btn">
                    <span style="color: white; font-size: 27px; margin-right: 520px;"><b><u><a href="home">Home</a></u></b></span>

                    <?php
                    $total_post = count($user_data);
                    if ($total_post == 0) {
                        echo "<span style='color: white; font-size: 27px;'><b>No Post</b></span>";
                    } elseif ($total_post == 1) {
                        echo "<span style='color: white; font-size: 27px;'><b> " . $total_post . " Post</b></span>";
                    } else {
                        echo "<span style='color: white; font-size: 27px;'><b> " . $total_post . "  Posts</b></span>";
                    }
                    ?>

                    <!-- {% if user_followers == 0 or user_followers == 1 %}
					<span style="color: white; font-size: 27px;"><b>{{user_followers}} follower</b></span>
					{% else %}
					<span style="color: white; font-size: 27px;"><b>{{user_followers}} followers</b></span>
					{% endif %} -->


                    <!-- <span style="color: white; font-size: 27px;"><b>{{user_following}} following</b></span> -->
                    <!-- 
					<input type="hidden" value="{{user.username}}" name="follower" />
					<input type="hidden" value="{{user_object.username}}" name="user" /> -->

                    <!-- {% if user_object.username == user.username %}
					<a href="/settings" data-ripple="">Account Settings</a>
					{% else %}
					<a data-ripple=""><button type="submit" style="background-color: #ffc0cb; border: #ffc0cb;">{{button_text}}</button></a>
					{% endif %} -->

                </div>
            </form>

            <div class="container-fluid">
                <div class="row merged">
                    <div class="col-lg-2 col-sm-3">
                        <div class="user-avatar">
                            <figure>
                                <img src="<?php echo $user_data[0]['profile_image'] ?>" style="height: 250px; width: 100%;" alt="">
                                <form class="edit-phto">
                                    <i class="fa fa-camera-retro"></i>
                                    <label class="fileContainer">
                                        <a href="setting">Upload Profile Photo</a>
                                    </label>
                                </form>
                            </figure>
                        </div>
                    </div>
                    <div class="col-lg-10 col-sm-9">
                        <div class="timeline-info">
                            <h5 style="color: black;white-space: nowrap; width: 110px; font-size: 27px;"><b>@<?php echo $user_data[0]['username']; ?></b><!--<i class="fa fa-check-circle" style="color: #48dbfb;" aria-hidden="true"></i>--></h5>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- top area -->
    <section>
        <div class="bio">
            <?php echo $user_data[0]['bio']; ?>
        </div>
    </section>

    <section>
        <div class="gap gray-bg">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row" id="page-contents">
                            <div class="col-lg-3">
                                <aside class="sidebar static">



                                </aside>
                            </div><!-- sidebar -->










                            <div class="col-lg-6">
                                <div class="central-meta">
                                    <ul class="photos">


                                        <?php
                                        $stmt_post = $db->prepare("select * from post where userid = :data ");
                                        $stmt_post->bindParam(':data', $userid);
                                        $stmt_post->execute();
                                        $user_post_data = $stmt_post->fetchAll();
                                        foreach ($user_post_data as $userpost) {
                                        ?>
                                            <li>
                                                <a class="strip" href="<?php echo $userpost['image'] ?>" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
                                                    <img src="<?php echo $userpost['image'] ?>" style="height: 250px; width: 300px;" alt=""></a>
                                            </li>
                                        <?php
                                        }
                                        ?>




                                    </ul>
                                    <!-- <div class="lodmore"><button class="btn-view btn-load-more"></button></div> -->
                                </div><!-- photos -->
                            </div><!-- centerl meta -->
                            <div class="col-lg-3">
                                <aside class="sidebar static">

                                </aside>
                            </div><!-- sidebar -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="js/main.min.js"></script>
    <script src="js/script.js"></script>

</body>


</html>