<?php
//  Rest api Headers
//  Allow access from anywhere - no auth
header('Access-Control-Allow-Origin: *');

//  Set content type to json
header('Content-Type: application/json');

//  Include Database
include_once '../../../config/Database.php';
//  Include Post Model
include_once '../../../models/Post.php';

//  Instantiate and Connect to Database
$database = new Database();

//  This variable is for the connection to the database
//  The connect method is the connect method we created in the Database class
$db = $database->connect();

//  Instantiate blog post object
//  This takes $db as a parameter
//  When we created our constructor in the Post class, we passed a $db parameter to the constructor
//  We then added that $db to the connection
//  Now we need to pass in that $db object
$post = new Post($db);

//  Using the ternary operator, check
//  Get the id from the URL
//  phprestapi.com?id=3
//  Get the ID passed into the URL
//  If there is no id in the URL then use the die function to stop everything
$post->id = isset($_GET['id']) ? $_GET['id'] : die();

//  Now we want to call the readsingle method in the Post class
//  Get a single post
$post->readsingle();

//  We need to return json, so we put the post into an array
$post_array = array(
    'id' => $post->id,
    'title' => $post->title,
    'body' => $post->body,
    'author' => $post->author,
    'category_id' => $post->category_id,
    'category_name' => $post->category_name
);

//  Turn the PHP array into json
print_r(json_encode($post_array));