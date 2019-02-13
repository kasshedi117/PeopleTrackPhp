<?php
header("Access-Control-Allow-Origin: *");
   // Define database connection parameters
   $hn      = 'localhost';
   $un      = 'root';
   $pwd     = '*********';
   $db      = 'track';
   $cs      = 'utf8';
   // Set up the PDO parameters
   $dsn 	= "mysql:host=" . $hn . ";port=3306;dbname=" . $db . ";charset=" . $cs;
   $opt 	= array(
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                       );
   // Create a PDO instance (connect to the database)
   $pdo 	= new PDO($dsn, $un, $pwd, $opt);
   // Retrieve the posted data
   $json    =  file_get_contents('php://input');
   $obj     =  json_decode($json);
   $key     =  strip_tags($obj->key);
   // variables 
   $data    = array();
   $existe = False;
   switch($key)
   {
      
      // Add a new record to the technologies table
      case "connect":
           $userName      = filter_var($obj->userName, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
           $password      = filter_var($obj->password, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
           // Attempt to query database table and retrieve data
           try {
              $stmt 	= $pdo->query('SELECT * FROM user where userName = "'.$userName.'" and password = "'.$password.'"' );
              while($row  = $stmt->fetch(PDO::FETCH_OBJ))
              {
                 // Assign each row of data to associative array
                 $data[] = $row;
              //    echo json_encode($data);
                  $existe = True;
              }
              if($existe == False){
                     $data[1] = (array('status' => '0')); 
              }
              else{
                     $data[1] = (array('status' => '1')); 
              }
                     echo json_encode($data);
           }
           catch(PDOException $e)
           {
              echo $e->getMessage();
           }
      break;
   }
?>
