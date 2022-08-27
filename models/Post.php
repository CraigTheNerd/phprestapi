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

    //  Get posts from the database
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
}

