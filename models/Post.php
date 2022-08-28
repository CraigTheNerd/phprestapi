<?php

//  Create a Post class
class Post
{
    //  Database
    private $conn;
    private $table = 'posts';

    //  Properties
    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at;

    //  Constructor to connect to the database when the class is instantiated
    //  The constructor takes a database object as a parameter
    //  Since the constructor runs as soon as the class is instantiated, it will immediately and automatically connect to the database
    public function __construct($db)
    {
        //  Set the connection of this class to the database
        $this->conn = $db;
    }

    //  Get all posts from the database
    //  Read method
    public function read()
    {
        //  Query the database to fetch existing posts
        //  If you look at the properties of this class, there is a $category_name property.
        //  The posts table however does not have a category name field.
        //  We use a join to get this field from the categories table
        $query = 'SELECT
                    category.name as category_name,
                    post.id,
                    post.category_id,
                    post.title,
                    post.body,
                    post.author,
                    post.created_at
                FROM
                    ' . $this->table . ' post
                LEFT JOIN
                    categories category ON post.category_id = category.id
                ORDER BY
                    post.created_at DESC';

//        $query = 'SELECT
//                c.name as category_name,
//                p.id,
//                p.category_id,
//                p.title,
//                p.body,
//                p.author,
//                p.created_at
//            FROM ' . $this->table . ' p
//            LEFT JOIN
//                categories c ON p.category_id = c.id
//            ORDER BY
//                p.created_at DESC';

        //  PDO Prepared Statement
        //  Takes in the query as a parameter
        $statement = $this->conn->prepare($query);

        //  Now execute the query
        $statement->execute();

        //  Return the statement
        return $statement;
    }

    //  Get a single post from the database
    //  Read Single method
    public function readsingle()
    {
        //  The query is pretty much the same as the read method
        //  This time however we do not need to ORDER BY
        //  Instead we use a WHERE clause with a question mark '?' as a placeholder
        //  We will then use PDO Bind Param to BIND a query parameter in the URL to this placeholder
        //  We will look for the id in the URL query parameter
        //  We also use a LIMIT clause to limit the results to 1 post
        $query = 'SELECT
                    category.name as category_name,
                    post.id,
                    post.category_id,
                    post.title,
                    post.body,
                    post.author,
                    post.created_at
                FROM
                    ' . $this->table . ' post
                LEFT JOIN
                    categories category ON post.category_id = category.id
                WHERE
                    post.id = ?
                LIMIT 0,1';

        //  PDO Prepared Statement
        //  Takes in the query as a parameter
        $statement = $this->conn->prepare($query);

        //  BIND id
        //  Now we want to bind the id to the placeholder
        //  The question mark placeholder is a positional parameter as opposed to a named parameter
        //  Since there is only one parameter we can use a positional parameter
        //  We are binding the positional parameter at position 1 to the id
        $statement->bindParam(1, $this->id);

        //  Execute the statement
        $statement->execute();

        //  Instead of then just returning the statement
        //  We want to fetch the array with the single item/post
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        //  Set Properties on the fields
        $this->title = $row['title'];
        $this->body = $row['body'];
        $this->author = $row['author'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
    }

    //  Create a Post
    public function create()
    {
        //  Create the database query using named parameters
        $query = 'INSERT INTO ' . $this->table . ' 
            SET
                title = :title,
                body = :body,
                author = :author,
                category_id = :category_id';

        //  Prepared Statement
        $statement = $this->conn->prepare($query);

        //  Clean up the submitted data since this data will be user submitted
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        //  Since we're using named parameters we need to bind the data to the named parameters in our query
        $statement->bindParam(':title', $this->title);
        $statement->bindParam(':body', $this->body);
        $statement->bindParam(':author', $this->author);
        $statement->bindParam(':category_id', $this->category_id);

        //  Execute the query
        if ($statement->execute()) {
            //  If the execute passed
            return true;
        }

        //  If it does not execute
        //  Print error message
        printf("Error: %s.\n", $statement->error);
        return false;

    }
}