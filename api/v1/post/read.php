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

//  Blog post query
//  Call the read method from the Post class to get results from the database
$result = $post->read();
//  Get row count
//  We use the row count to see if there are any posts in the database
//  In our loop we check to see whether the row count has any values
//  We can get the number of results from the database using the rowCount function
$number_of_results = $result->rowCount();

//  Check if there are any posts
//  If number of results are greater than 0, so at least one(1)
if ($number_of_results > 0) {
    //  Initialise an empty array
    //  We're going to fetch an associative array from the database
    //  So we first create an empty array which we will fill if there are posts in the database
    $posts_array = array();

    //  We then set the array to have an array value/key called data
    //  This way we don't just get back an array of json data
    //  That json data sits inside an array key called data
    //  This means we could add other items to the json array and store it in other keys
    //  The posts returned from the database sits in the data key
    $posts_array['data'] = array();

    //  Since we are getting back posts - We are within the if statement that checked that there is at least one(1) post returned from the database
    //  We then use a while loop to get each of the posts
    //  As mentioned, we're using PDO and fetching an associate array
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

        //  We could use array syntax
        //  If we want the title we could call $row['title']
        //  However we want to put the database fields into variables
        //  So we use the extract function
        //  We'll now be able to just use $title for the post title and $body for the post body instead of array syntax $row['body']
        extract($row);

        //  Create a post item for each post
        //  Here we're using the 'extracted' variable names eg. $id instead of $row['id']
        $post_item = array(
            'id' => $id,
            //  Use html_entity_decode to get back the 'FULL' html code.
            //  This function does the opposite of the htmlentities function
            //  The htmlentities function turns htmml code into entities instead of having the raw markup stored in the database
            //  So the html_entity_decode function turns those entities back into raw html markup so that it's parsed by the browser as raw html markup for proper output
            'title' => html_entity_decode($body),
            'author' => $author,
            'category_id' => $category_id,
            'category_name' => $category_name
        );

        //  Now we want to take each post item, which are the variables/individual fields on a post, to the data key in the json array
        //  We have at this point not just yet turned our php array into json data, but we will shortly
        //  Push each post_item to the data key of the posts array
        array_push($posts_array['data'], $post_item);
    }

    //  Turn it into json from the php array and output the json
    echo json_encode($posts_array);

} else {
    //  If there are no posts
    echo json_encode(
        array('message' => 'No Posts Found')
    );
}