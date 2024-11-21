<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


require '../src/vendor/autoload.php';

$app = new \Slim\App;

// USER REGISTRATION API
$app->post('/user/registration', function(Request $request, Response $response, array $args){
    error_reporting(E_ALL);
    $data = json_decode($request->getBody());

    $uname = $data -> username;
    $pass = $data -> password;
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO users(username, password)
        VALUES ('".$uname."', '".hash('SHA256',$pass)."')";
        // use exec() because no results are returned

        $conn->exec($sql);

        $response -> getBody() -> write(json_encode(array("status" => "success", "data" => null)));


    } catch(PDOException $e) {
        $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => $e->getMessage()))));
    }

    $conn = null;   



    return $response;

});


// LOGIN / AUTHENTICATE / GENERATE TOKEN 
$app->post('/user/authentication', function(Request $request, Response $response, array $args){

    error_reporting(E_ALL);
    $data = json_decode($request->getBody());

    $uname = $data -> username;
    $pass = $data -> password;
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM users where username='".$uname."' AND password='".hash('SHA256', $pass)."'";
        
       

        $stmt = $conn->prepare($sql);
        $stmt-> execute();

        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        

        if (count($result) == 1){

            $uid = $result[0]["userid"];

            $expire=time();
            $key = 'theSUSI';
            

            $payload = [
                'iss' => 'http://security.org',
                'aud' => 'http://security.com',
                'iat' => $expire,
                'exp' => $expire + 300,
                'data' => array(
                    "user_id" => $uid,
                )
            ];

            $jwt = JWT::encode($payload, $key, 'HS256');

            $sql_token_select = "SELECT * FROM token WHERE status = 'active'";
            $stmt = $conn -> prepare($sql_token_select);
            $stmt-> execute();
            $stmt -> setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();

            if (count($result) == 1){
                $sql_token_update = "UPDATE token SET status = 'inactive' WHERE status='active'";
                $stmt = $conn -> prepare($sql_token_update);
                $stmt -> execute();

                $sql_token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'active')";
                $stmt = $conn -> prepare($sql_token);
                $stmt -> execute();
            }else {
                $sql_token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'active')";
                $stmt = $conn -> prepare($sql_token);
                $stmt -> execute();
            }
            
            $response -> getBody() -> write(json_encode(array("status" => "success", "data" => array("token" => $jwt))));

        }else{
            $response -> getBody() -> write(json_encode(array("status" => "fail", "title" => "authentication failed")));
        }


    } catch(PDOException $e) {
        $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => $e->getMessage()))));
    }

    $conn = null;   

    return $response;

});


$app->post('/login', function(Request $request, Response $response, array $args){
    $data = json_decode($request->getBody());

    $username = $data -> username;
    $password = $data -> password;
    $expire = time();

    if($username == "admin" && $password == "opengate"){
        $key = 'thisIsTheKey';

        $payload = [
            'iss' => 'http://security.org',
            'aud' => 'http://security.com',
            'iat' => $expire,
            'exp' => $expire + 60,
            'data' => array(
                "name" => "ray mark bautista",
                "access_level" => 1,
            )
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');


        $response -> getBody() -> write(json_encode(array("status" => "success", "data" => array("token" => $jwt))));
        
    }else{
        $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => "login fail"))));
    }

    return $response;

});



$app->post('/viewEmployee', function(Request $request, Response $response, array $args){
    $data = json_decode($request->getBody());

    $jwt = $data -> token;

    $key = 'thisIsTheKey';

    try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    
        $response -> getBody() -> write(json_encode(array("status" => "success", "data" => array("lname" => "bautista", "fname" => "ray mark"))));

    } catch (\Excemption $e) {
        $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => $e->getMessage()))));
    }


    return $response;


});







// ------------------------------ACTIVITY-----------------------------



// ADD BOOKS AND AUTHOR API
$app->post('/addBookAuthor', function(Request $request, Response $response, array $args){
    $data = json_decode($request->getBody());

    $booktitle = $data -> title_of_book;
    $author = $data -> name_of_author;
    $jwt = $data -> token;

    $key = 'theSUSI';
    $expire = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";


    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql_token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($sql_token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "active"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $sql_token_update = "UPDATE token SET status='inactive' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($sql_token_update);
            $stmt -> execute();

            

                $sql_author = "INSERT INTO authors(name) VALUES ('".$author."')";
                $stmt = $conn -> prepare($sql_author);
                $stmt->execute();
                $author_id = $conn -> lastInsertId();

            
                $sql_author_id_select = "SELECT authorid FROM authors WHERE name = '".$author."'";
                $stmt = $conn -> prepare($sql_author_id_select);
                $stmt -> execute();
                $stmt -> setFetchMode(PDO::FETCH_ASSOC);
                $result_id = $stmt->fetchAll();
                $author_id = $result_id[0]["authorid"];
                


            $sql_book = "INSERT INTO books(title) VALUES ('".$booktitle."')";
            $stmt = $conn -> prepare($sql_book);
            $stmt -> execute();
            $book_id = $conn -> lastInsertId();


            $sql_collection = "INSERT INTO book_author(bookid, authorid) VALUES ('".$book_id."', '".$author_id."')";
            $stmt = $conn -> prepare($sql_collection);
            $stmt -> execute();

            $payload = [
                'iss' => 'http://security.org',
                'aud' => 'http://security.com',
                'iat' => $expire,
                'exp' => $expire + 300,
                'data' => array(
                    "status" => $token_status,
                )
            ];

            $jwt = JWT::encode($payload, $key, 'HS256');

            $sql_token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'active')";
            $stmt = $conn -> prepare($sql_token);
            $stmt -> execute();


            $response -> getBody() -> write(json_encode(array(
                "status" => "success", 
                "data" => array(
                    "message" => "very good",
                    "new_token" => $jwt
            ))));

        }else{
            $response -> getBody() -> write(json_encode(array(
                "status" => "fail", 
                "data" => array(
                    "message" => "Token already used."))));
        }


    }catch (\Excemption $e){
        $response -> getBody() -> write(json_encode(array(
            "status" => "fail", 
            "data" => array(
                "title" => $e->getMessage()
        ))));
    }

    $conn = null;

    return $response;

});

// DELETE BOOK API
$app->post('/deleteBook', function(Request $request, Response $response, array $args){
    $data = json_decode($request->getBody());

    $bookid = $data -> book_id;

    $jwt = $data -> token;

    $key = 'theSUSI';
    $expire = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try {

        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql_token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($sql_token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "active"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $sql_token_update = "UPDATE token SET status='inactive' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($sql_token_update);
            $stmt -> execute();

            $sql_author = "SELECT * FROM books";
            $stmt = $conn -> prepare($sql_author);
            $stmt -> execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result_books = $stmt->fetchAll();

            if ($result_books != null){
                $sql_delete_book = "DELETE FROM books WHERE bookid='".$bookid."'";
                $stmt = $conn -> prepare($sql_delete_book);
                $stmt -> execute();

                $payload = [
                    'iss' => 'http://security.org',
                    'aud' => 'http://security.com',
                    'iat' => $expire,
                    'exp' => $expire + 300,
                    'data' => array(
                        "status" => $token_status,
                        )
                ];

                $jwt = JWT::encode($payload, $key, 'HS256');

                $sql_token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'active')";
                $stmt = $conn -> prepare($sql_token);
                $stmt -> execute();


                $response -> getBody() -> write(json_encode(array(
                    "status" => "success", 
                    "data" => array(
                        "message" => "Successfully deleted a book from records.",
                        "new_token" => $jwt
                ))));


            }else{
                $response -> getBody() -> write(json_encode(array(
                    "status" => "success", 
                    "data" => array(
                        "message" => "No books found from records."
                ))));
            }
        
        }else{
            $response -> getBody() -> write(json_encode(array(
                "status" => "fail", 
                "data" => array(
                    "title" => "Token already used."
            ))));
        }
        


        
    } catch (\Excemption $e) {
        $response -> getBody() -> write(json_encode(array(
            "status" => "fail", 
            "data" => array(
                "title" => $e->getMessage()
        ))));
    }


    $conn = null;

    return $response;


});

$app->post('/updateBook', function(Request $request, Response $response, array $args){
    $data = json_decode($request->getBody());

    $book_id = $data -> book_id;
    $new_book = $data -> new_book_title;
    $jwt = $data -> token;
    
    $key = 'theSUSI';
    $expire = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql_token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($sql_token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "active"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $sql_token_update = "UPDATE token SET status='inactive' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($sql_token_update);
            $stmt -> execute();

            $sql_books = "SELECT * FROM books";
            $stmt = $conn -> prepare($sql_books);
            $stmt -> execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result_books = $stmt->fetchAll();

            if($result_books != null){
                $sql_update_book = "UPDATE books 
                SET title = '".$new_book."' 
                WHERE bookid ='".$book_id."'";
                $stmt = $conn -> prepare($sql_update_book);
                $stmt -> execute();

                $payload = [
                    'iss' => 'http://security.org',
                    'aud' => 'http://security.com',
                    'iat' => $expire,
                    'exp' => $expire + 300,
                    'data' => array(
                        "status" => $token_status,
                        )
                ];

                $jwt = JWT::encode($payload, $key, 'HS256');

                $sql_token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'active')";
                $stmt = $conn -> prepare($sql_token);
                $stmt -> execute();


                $response -> getBody() -> write(json_encode(array(
                    "status" => "success", 
                    "data" => array(
                        "message" => "Successfully updated an author from records.",
                        "new_token" => $jwt
                ))));

            }else{
                $response -> getBody() -> write(json_encode(array(
                    "status" => "success", 
                    "data" => array(
                        "message" => "No authors found from records."
                ))));
            }

        }else{
            $response -> getBody() -> write(json_encode(array(
                "status" => "fail", 
                "data" => array(
                    "title" => "Token already used."
            ))));
        }


    } catch (\Excemption $e) {
        $response -> getBody() -> write(json_encode(array(
            "status" => "fail", 
            "data" => array(
                "title" => $e->getMessage()
        ))));
    }

    $conn = null;

    return $response;

});

// DELETE AUTHOR API
$app->post('/deleteAuthor', function(Request $request, Response $response, array $args){
    $data = json_decode($request->getBody());

    $author_id = $data -> author_id;
    $jwt = $data -> token;

    $key = 'theSUSI';
    $expire = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try {

        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql_token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($sql_token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "active"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $sql_token_update = "UPDATE token SET status='inactive' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($sql_token_update);
            $stmt -> execute();

            $sql_author = "SELECT * FROM authors";
            $stmt = $conn -> prepare($sql_author);
            $stmt -> execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result_authors = $stmt->fetchAll();

            if ($result_authors != null){
                $sql_delete_author = "DELETE FROM authors WHERE authorid='".$author_id."'";
                $stmt = $conn -> prepare($sql_delete_author);
                $stmt -> execute();



                $payload = [
                    'iss' => 'http://security.org',
                    'aud' => 'http://security.com',
                    'iat' => $expire,
                    'exp' => $expire + 300,
                    'data' => array(
                        "status" => $token_status,
                        )
                ];

                $jwt = JWT::encode($payload, $key, 'HS256');

                $sql_token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'active')";
                $stmt = $conn -> prepare($sql_token);
                $stmt -> execute();


                $response -> getBody() -> write(json_encode(array(
                    "status" => "success", 
                    "data" => array(
                        "message" => "Successfully deleted an author from records.",
                        "new_token" => $jwt
                ))));

            
            }else{
                $response -> getBody() -> write(json_encode(array(
                    "status" => "success", 
                    "data" => array(
                        "message" => "No authors found from records."
                ))));
            }

        }else{
            $response -> getBody() -> write(json_encode(array(
                "status" => "fail", 
                "data" => array(
                    "title" => "Token already used."
            ))));
        }


        
    } catch (\Excemption $e) {
        $response -> getBody() -> write(json_encode(array(
            "status" => "fail", 
            "data" => array(
                "title" => $e->getMessage()
        ))));
    }



    $conn = null;

    return $response;


});

// UPDATE AUTHOR API
$app->post('/updateAuthor', function(Request $request, Response $response, array $args){
    $data = json_decode($request->getBody());

    $author_id = $data -> author_id;
    $new_author = $data -> new_author_name;
    $jwt = $data -> token;
    
    $key = 'theSUSI';
    $expire = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql_token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($sql_token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "active"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $sql_token_update = "UPDATE token SET status='inactive' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($sql_token_update);
            $stmt -> execute();

            $sql_author = "SELECT * FROM authors";
            $stmt = $conn -> prepare($sql_author);
            $stmt -> execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result_authors = $stmt->fetchAll();

            if($result_authors != null){
                $sql_update_author = "UPDATE authors 
                SET name = '".$new_author."' 
                WHERE authorid ='".$author_id."'";
                $stmt = $conn -> prepare($sql_update_author);
                $stmt -> execute();

                $payload = [
                    'iss' => 'http://security.org',
                    'aud' => 'http://security.com',
                    'iat' => $expire,
                    'exp' => $expire + 300,
                    'data' => array(
                        "status" => $token_status,
                        )
                ];

                $jwt = JWT::encode($payload, $key, 'HS256');

                $sql_token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'active')";
                $stmt = $conn -> prepare($sql_token);
                $stmt -> execute();


                $response -> getBody() -> write(json_encode(array(
                    "status" => "success", 
                    "data" => array(
                        "message" => "Successfully updated an author from records.",
                        "new_token" => $jwt
                ))));

            }else{
                $response -> getBody() -> write(json_encode(array(
                    "status" => "success", 
                    "data" => array(
                        "message" => "No authors found from records."
                ))));
            }

        }else{
            $response -> getBody() -> write(json_encode(array(
                "status" => "fail", 
                "data" => array(
                    "title" => "Token already used."
            ))));
        }


    } catch (\Excemption $e) {
        $response -> getBody() -> write(json_encode(array(
            "status" => "fail", 
            "data" => array(
                "title" => $e->getMessage()
        ))));
    }

    $conn = null;

    return $response;

});

// DISPLAY ALL BOOKS API
$app->post('/displayBooks', function(Request $request, Response $response, array $args){

    $data = json_decode($request->getBody());

    $jwt = $data -> token;

    $key = 'theSUSI';
    $expire = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql_token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($sql_token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "active"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $sql_token_update = "UPDATE token SET status='inactive' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($sql_token_update);
            $stmt -> execute();

            $sql_books = "SELECT * FROM books";
            $stmt = $conn -> prepare($sql_books);
            $stmt -> execute();
            $stmt -> setFetchMode(PDO::FETCH_ASSOC);
            $result_books = $stmt->fetchAll();

            if ($result_books !== null) {
                
                $books_data = array();

    
                foreach ($result_books as $book) {
                    $books_data[] = array(
                        "book_id" => $book['bookid'],  
                        "book_title" => $book['title'] 
                    );
                }

                $payload = [
                'iss' => 'http://security.org',
                'aud' => 'http://security.com',
                'iat' => $expire,
                'exp' => $expire + 300,
                'data' => array(
                    "status" => $token_status,
                )
                ];

                $jwt = JWT::encode($payload, $key, 'HS256');

                $sql_token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'active')";
                $stmt = $conn -> prepare($sql_token);
                $stmt -> execute();

    
                $response->getBody()->write(json_encode(array(
                    "status" => "success",
                    "data" => array(
                        "books" => $books_data, 
                        "new_token" => $jwt
                ))));

            } else{
                $response -> getBody() -> write(json_encode(array("status" => "success", "data" => array("message" => "No books found from records."))));
            }


        }else{
            $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => "Token already used."))));
        }

    } catch (\Excemption $e) {
        $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => $e->getMessage()))));
    }

    
    $conn = null;

    return $response;
    

});

// DISPLAY ALL AUTHORS API
$app->post('/displayAuthors', function(Request $request, Response $response, array $args){

    $data = json_decode($request->getBody());

    $jwt = $data -> token;

    $key = 'theSUSI';
    $expire = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql_token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($sql_token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "active"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $sql_token_update = "UPDATE token SET status='inactive' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($sql_token_update);
            $stmt -> execute();

            $sql_authors = "SELECT * FROM authors";
            $stmt = $conn -> prepare($sql_authors);
            $stmt -> execute();
            $stmt -> setFetchMode(PDO::FETCH_ASSOC);
            $result_authors = $stmt->fetchAll();

            if ($result_authors != null) {
                
                $authors_data = array();

    
                foreach ($result_authors as $author) {
                    $authors_data[] = array(
                        "author_id" => $author['authorid'],  
                        "author_name" => $author['name'] 
                    );
                }

                $payload = [
                'iss' => 'http://security.org',
                'aud' => 'http://security.com',
                'iat' => $expire,
                'exp' => $expire + 300,
                'data' => array(
                    "status" => $token_status,
                    )
                ];

                $jwt = JWT::encode($payload, $key, 'HS256');

                $sql_token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'active')";
                $stmt = $conn -> prepare($sql_token);
                $stmt -> execute();

    
                $response->getBody()->write(json_encode(array("status" => "success","data" => array("authors" => $authors_data, "new_token" => $jwt))));

            } else{
                $response -> getBody() -> write(json_encode(array("status" => "success", "data" => array("message" => "No authors found from records."))));
            }



        }else{
            $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => "Token already used."))));
        }

    } catch (\Excemption $e) {
        $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => $e->getMessage()))));
    }

    
    $conn = null;

    return $response;
    

});

// DISPLAY COLLECTIONS (BOOKS WITH AUTHORS) API
$app->post('/displayCollections', function(Request $request, Response $response, array $args){

    $data = json_decode($request->getBody());

    $jwt = $data -> token;

    $key = 'theSUSI';
    $expire = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql_token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($sql_token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "active"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $sql_token_update = "UPDATE token SET status='inactive' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($sql_token_update);
            $stmt -> execute();

            $sql_collection = "SELECT collectionid,b.title, a.name FROM book_author ba 
            JOIN books b ON ba.bookid = b.bookid 
            JOIN authors a ON ba.authorid = a.authorid";
            $stmt = $conn->prepare($sql_collection);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result_collection = $stmt->fetchAll();

            if ($result_collection != null) {
                $collection_data = array();

                foreach ($result_collection as $collection) {
                    $collection_data[] = array(
                        "collection_id" => $collection['collectionid'],
                        "book_title" => $collection['title'],      
                        "author_name" => $collection['name']     
                    );
                }

                $response->getBody()->write(json_encode(array(
                    "status" => "success", 
                    "data" => array(
                        "collections" => $collection_data,
                        "token"=> $jwt
                ))));
            }


        }else{
            $response -> getBody() -> write(json_encode(array(
                "status" => "fail", 
                "data" => array(
                    "title" => "Token already used."
            ))));
        }


        
    } catch (\Excemption $e) {
        $response -> getBody() -> write(json_encode(array(
            "status" => "fail", 
            "data" => array(
                "title" => $e->getMessage()
        ))));
    }

    $conn = null;

    return $response;


});



$app->run();

?>