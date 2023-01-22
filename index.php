    <?php
    session_start();

    require "Database.php";

    $database = new Database();
    $db = $database->getConnection();

    if (!isset($_SESSION['login_user'])) {
        header("location: signin.php");
    }

    $userid = $_SESSION['login_user_id'];

    $stmt = $db->prepare("select p.*,u.* from user u inner join profile p on u.id = p.user_id where p.user_id = :data");
    $stmt->bindParam(':data', $userid);
    $stmt->execute();
    $user_data = $stmt->fetchAll();


    // echo "<pre>";
    // print_r($user_data[0]['profile_image']);
    // echo "</pre>";
    // die();


    $stmt_post = $db->prepare("select p.*,u.*,profile.profile_image from post p inner join user u on u.id= p.userid inner join profile on u.id = profile.user_id;");
    $stmt_post->execute();
    $feed_post = $stmt_post->fetchAll();

    // echo "<pre>";
    //     print_r($feed_post[0]['profile_image']);
    //     echo "</pre>";
    //     die();

    $user_following_list = [];
    $feed = [];



    ?>

    <!DOCTYPE html>
    <html lang="en">


    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="favicon.png" rel="icon" type="image/png">
        <title>Home</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php include_once "css.php"  ?>
        <script src="https://cdn.jsdelivr.net/npm/uikit@3.15.22/dist/js/uikit.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/uikit@3.15.22/dist/js/uikit-icons.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.c{om/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>

    <body>
        <header>
            <div class="header_inner">
                <form action="/search" method="POST">
                    <div class="left-side">
                        <!-- Logo -->
                        <div id="logo" class=" uk-hidden@s">
                            <a href="home.html">
                                <b>
                                    <h1 style="text-transform: uppercase;">Social Book</h1>
                                </b>
                            </a>
                        </div>

                        <input type="text" name="username" placeholder="Search for username..">&nbsp;&nbsp;&nbsp;
                        <button type="submit"> <i class="fa fa-search fa-1x"></i> </button>
                        <div class="icon-search">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                    </div>
                </form>
                <div class="right-side lg:pr-4">

                    <!-- upload -->


                    <a class="uk-button uk-button-default" href="#header_dropdown" uk-toggle>Upload post</a>

                    <!-- upload dropdown box -->
                    <div uk-dropdown="pos: top-right;mode:click ; animation: uk-animation-slide-bottom-small" class="header_dropdown">

                        <!-- notivication header -->
                        <div class="px-4 py-3 -mx-5 -mt-4 mb-5 border-b">
                            <h4>Choose file</h4>
                        </div>

                        <!-- notification contents -->
                        <div class="flex justify-center flex-center text-center">
                            <div class="flex flex-col choose-upload text-center">


                                <form action="upload" method="POST" enctype="multipart/form-data">
                                    <div>
                                        <input type="file" name="image_upload">
                                        <div class="px-4 py-3 -mx-5 -mb-4 mt-5 border-t text-sm">
                                            Post type
                                        </div>
                                        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                                            <label><input class="uk-radio" type="radio" name="radio2" value="public" checked>Public</label>
                                            <label><input class="uk-radio" type="radio" name="radio2" value="Followers">Followers only</label>
                                            <label><input class="uk-radio" type="radio" name="radio2" value="hidden">Hidden</label>
                                        </div>
                                        <textarea name="caption" placeholder="caption" cols="30" rows="5"></textarea> <br>
                                        <button type="submit" class="bg-blue-700 button" name="newpost">Upload</button>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <div class="px-4 py-3 -mx-5 -mb-4 mt-5 border-t text-sm">
                            Only Images are allowed.
                        </div>
                    </div>

                    <!-- Notification -->

                    <a href="#header_dropdown" class="header-links-item">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                            <g fill="none">
                                <path d="M12 22a2.02 2.02 0 0 1-2.01-2h4a2.02 2.02 0 0 1-.15.78a2.042 2.042 0 0 1-1.44 1.18h-.047A1.922 1.922 0 0 1 12 22zm8-3H4v-2l2-1v-5.5a8.065 8.065 0 0 1 .924-4.06A4.654 4.654 0 0 1 10 4.18V2h4v2.18c2.579.614 4 2.858 4 6.32V16l2 1v2z" fill="currentColor" />
                            </g>
                        </svg>
                    </a>


                    <div uk-drop="mode: click;offset: 4" class="header_dropdown" id="header_dropdown">
                        <h4 class="-mt-5 -mx-5 bg-gradient-to-t from-gray-100 to-gray-50 border-b font-bold px-6 py-3">
                            Notification </h4>
                        <ul class="dropdown_scrollbar" data-simplebar>
                            <li>
                                <a href="#">
                                    <div class="drop_avatar"> <img src="assets/images/avatars/avatar-1.jpg" alt="">
                                    </div>
                                    <div class="drop_content">
                                        <p> <strong class="text-link">Taiye</strong>
                                            <span class="text-link"> is following you </span>
                                        </p>
                                        <span class="time-ago"> 2 hours ago </span>
                                    </div>
                                </a>
                            </li>


                        </ul>
                        <a href="#" class="see-all">See all</a>
                    </div>

                    <!-- Messages -->

                    <a href="#" class="header-links-item">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="0.5em" height="0.5em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 16 16">
                            <g fill="currentColor">
                                <path d="M2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                            </g>
                        </svg>
                    </a>


                    <div uk-drop="mode: click;offset: 4" class="header_dropdown">
                        <h4 class="-mt-5 -mx-5 bg-gradient-to-t from-gray-100 to-gray-50 border-b font-bold px-6 py-3">
                            Messages </h4>
                        <ul class="dropdown_scrollbar" data-simplebar>
                            <li>
                                <a href="#">
                                    <div class="drop_avatar"> <img src="assets/images/avatars/avatar-1.jpg" alt="">
                                    </div>
                                    <div class="drop_content">
                                        <strong> Taiye </strong> <time> 6:43 PM</time>
                                        <p> hi tomi </p>
                                    </div>
                                </a>
                            </li>



                        </ul>
                        <a href="#" class="see-all">See all</a>
                    </div>

                    <!-- profile -->

                    <a href="#">
                        <img src="<?php echo $user_data[0]['profile_image']; ?>" class="header-avatar" alt="">
                    </a>
                    <div uk-drop="mode: click;offset:9" class="header_dropdown profile_dropdown border-t">
                        <ul>
                            <li><a href="setting"> Account setting </a> </li>
                            <li><a href="logout"> Log Out</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </header>



        <div class="container m-auto">

            <h1 class="lg:text-2xl text-lg font-extrabold leading-none text-gray-900 tracking-tight mb-5"> Feed </h1>

            <div class="lg:flex justify-center lg:space-x-10 lg:space-y-0 space-y-5">

                <!-- left sidebar-->
                <div class="space-y-5 flex-shrink-0 lg:w-7/12">


                    <!-- post 1-->
                    <!-- {% for post in posts reversed %} -->

                    <?php
                    foreach ($feed_post as $post) {

                    ?>


                        <!-- post header-->
                        <div class="bg-white shadow rounded-md  -mx-2 lg:mx-0">

                            <div class="flex justify-between items-center px-4 py-3">
                                <div class="flex flex-1 items-center space-x-4">
                                    <a href="/profile/{{post.user}}">
                                        <div class="bg-gradient-to-tr from-yellow-600 to-pink-600 p-0.5 rounded-full">
                                            <img src="<?php echo $post['profile_image']; ?>" class="bg-gray-200 border border-white rounded-full w-8 h-8">
                                        </div>
                                    </a>
                                    <span class="block capitalize font-semibold "> <a href="/profile/{{post.user}}"> <strong>@<?php echo $post['username']; ?></strong></a> </span>
                                </div>
                                <div>

                                </div>
                            </div>

                            <div uk-lightbox>
                                <a href="<?php echo $post['image']; ?>">
                                    <video src="{{ post.image.url }}" type="video/mp4"></video>
                                    <img src="<?php echo $post['image']; ?>" alt="">
                                </a>
                            </div>


                            <div class="py-3 px-4 space-y-3">

                                <div class="flex space-x-4 lg:font-bold">
                                    <a href="like?pid=<?php echo $post['post_id']; ?>" class="flex items-center space-x-2">
                                        <div class="p-2 rounded-full text-black">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="25" height="25" class="">
                                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                                            </svg>
                                            <?php  if($post['no_of_like'] == 0)
                                            {
                                                echo "<p>No likes </p>";
                                            }
                                            else
                                            {
                                                echo "<p> Liked by " . $post['no_of_like'] . " person </p>";
                                            } ?>
                                        </div>
                                    </a>

                                    <!-- <a href="#" class="flex items-center space-x-2">
                                    <div class="p-2 rounded-full text-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="25" height="25" class="">
                                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>

                                </a> -->

                                    <!-- <a href="{{post.image.url}}" class="flex items-center space-x-2 flex-1 justify-end" download> -->
                                    <!-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="25" height="25" preserveAspectRatio="xMidYMid meet" viewBox="0 0 16 16">
                                    <g fill="currentColor">
                                        <path d="M8.5 1.5A1.5 1.5 0 0 1 10 0h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h6c-.314.418-.5.937-.5 1.5v6h-2a.5.5 0 0 0-.354.854l2.5 2.5a.5.5 0 0 0 .708 0l2.5-2.5A.5.5 0 0 0 10.5 7.5h-2v-6z" />
                                    </g>
                                </svg>

                                </a> -->


                                </div>

                                <!-- <div class="flex items-center space-x-3">
                                <div class="flex items-center">
                                    <img src="assets/images/avatars/avatar-1.jpg" alt="" class="w-6 h-6 rounded-full border-2 border-white ">
                                    <img src="assets/images/avatars/avatar-4.jpg" alt="" class="w-6 h-6 rounded-full border-2 border-white  -ml-2">
                                    <img src="assets/images/avatars/avatar-2.jpg" alt="" class="w-6 h-6 rounded-full border-2 border-white  -ml-2">
                                </div>
                                <div class="">
                                    Liked <strong> taiye</strong> and <strong> 209 Others </strong>
                                </div>
                            </div> -->

                                <p>
                                    <strong><a href="/profile/{{user.username}}"><?php echo $post['username']; ?> </a> </strong> <?php echo $post['caption']; ?>
                                </p>
                                <!-- <div class="border-t pt-4 space-y-4 ">
                                    <div class="flex">
                                        <div class="w-10 h-10 rounded-full relative flex-shrink-0">
                                            <img src="{% static 'assets/images/avatars/avatar-1.jpg' %}" alt="" class="absolute h-full rounded-full w-full">
                                        </div>
                                        <div class="text-gray-700 py-2 px-3 rounded-md bg-gray-100 h-full relative lg:ml-5 ml-2 lg:mr-20   ">
                                            <p class="leading-6">Y'all like billie eillish? <urna class="i uil-heart"></urna> <i class="uil-grin-tongue-wink"> </i> </p>
                                            <div class="absolute w-3 h-3 top-3 -left-1 bg-gray-100 transform rotate-45 "></div>
                                        </div>
                                    </div>
                                    <div class="flex">
                                        <div class="w-10 h-10 rounded-full relative flex-shrink-0">
                                            <img src="assets/images/avatars/avatar-1.jpg" alt="" class="absolute h-full rounded-full w-full">
                                        </div>
                                        <div class="text-gray-700 py-2 px-3 rounded-md bg-gray-100 h-full relative lg:ml-5 ml-2 lg:mr-20   ">
                                            <p class="leading-6">She's my favourite <i class="uil-grin-tongue-wink-alt"></i>
                                            </p>
                                            <div class="absolute w-3 h-3 top-3 -left-1 bg-gray-100 transform rotate-45 "></div>
                                        </div>
                                    </div>
                                </div> -->

                                <!-- <div class="bg-gray-100 bg-gray-100 rounded-full rounded-md relative ">
                                    <input type="text" placeholder="post a comment" class="bg-transparent max-h-10 shadow-none">
                                    <div class="absolute bottom-0 flex h-full items-center right-0 right-3 text-xl space-x-2">
                                        <a href="#"> <i class="uil-image"></i></a>
                                        <a href="#"> <i class="uil-video"></i></a>
                                    </div>
                                </div> -->

                            </div>

                        </div>


                    <?php
                    }
                    ?>


                </div>

                <!-- right sidebar-->
                <div class="lg:w-5/12">

                    <div class="bg-white  shadow-md rounded-md overflow-hidden">

                        <div class="bg-gray-50  border-b border-gray-100 flex items-baseline justify-between py-4 px-6 ">
                            <h2 class="font-semibold text-lg">Users You Can Follow</h2>
                            <a href="#"> Refresh</a>
                        </div>

                        <div class="divide-gray-300 divide-gray-50 divide-opacity-50 divide-y px-4 ">


                            <!-- {% for suggestion in  suggestion_username_profile_list %} -->


                            <div class="flex items-center justify-between py-3">
                                <div class="flex flex-1 items-center space-x-4">
                                    <a href="/profile/{{suggestion.user}}">
                                        <!-- <img src="{{suggestion.profileImage.url}}" class="bg-gray-200 rounded-full w-10 h-10"> -->
                                    </a>
                                    <div class="flex flex-col">
                                        <span class="block capitalize font-semibold">
                                            <!-- {{suggestion.user}} -->
                                        </span>
                                        <span class="block capitalize text-sm">
                                            <!-- {{suggestion.bio}}  -->
                                        </span>
                                    </div>
                                </div>

                                <a href="/profile/{{suggestion.user}}" class="border border-gray-200 font-semibold px-4 py-1 rounded-full hover:bg-pink-600 hover:text-white hover:border-pink-600 "> View </a>
                            </div>

                            <!-- {% endfor %} -->



                        </div>

                    </div>



                </div>

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