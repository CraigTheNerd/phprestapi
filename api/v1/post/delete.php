<?php
//  Rest api Headers
//  Allow access from anywhere - no auth
header('Access-Control-Allow-Origin: *');

//  Set content type to json
header('Content-Type: application/json');

//  Allowed Methods
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

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

//  Get the raw posted data which will just be the id
$data = json_decode(file_get_contents("php://input"));

//  Set ID to be deleted
$post->id = $data->id;

//  Delete post
//  This goes in an if statement to catch any errors
if ($post->delete()) {
    echo json_encode(
        array('message' => 'Post Deleted')
    );
} else {
    //  If there was an error
    echo json_encode(
        array('message' => 'Post Not Deleted')
    );
}