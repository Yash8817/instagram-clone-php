<?php

session_start();
require "Database.php";

$database = new Database();
$db = $database->getConnection();

if (!isset($_SESSION['login_user']) || !isset($_SESSION['login_user_id'])) {
    header("location: signin.php");
}

if (!isset($_GET['pid'])) {
    header("location: home");
}

$userid = $_SESSION['login_user_id'];
$post_id = $_GET['pid'];

$stmt = $db->prepare("select  * from postlike  where user_id = :uid and post_id = :pid");
$stmt->bindParam(':uid', $userid);
$stmt->bindParam(':pid', $post_id);
$stmt->execute();
$user_data = $stmt->fetchAll();

if (count($user_data) > 0) {
    $stmt_update_post_like = $db->prepare("delete from postlike where user_id = :uid and post_id =:pid");
    $stmt_update_post_like->bindParam(':pid', $post_id);
    $stmt_update_post_like->bindParam(':uid', $userid);
    $stmt_update_post_like->execute();


    $stmt_update_post = $db->prepare("update post set no_of_like =no_of_like - 1 where post_id = :pid");
    $stmt_update_post->bindParam(':pid', $post_id);
    $stmt_update_post->execute();


} else {
    $stmt_update_post_like = $db->prepare("insert into postlike(`post_id`,`user_id`) values (:pid,:uid);");
    $stmt_update_post_like->bindParam(':pid', $post_id);
    $stmt_update_post_like->bindParam(':uid', $userid);
    $stmt_update_post_like->execute();

    if (($stmt_update_post_like->rowCount()) > 0) {
        $stmt_update_post = $db->prepare("update post set no_of_like =no_of_like+ 1 where post_id = :pid");
        $stmt_update_post->bindParam(':pid', $post_id);
        $stmt_update_post->execute();
    }
}

header("location: home");
