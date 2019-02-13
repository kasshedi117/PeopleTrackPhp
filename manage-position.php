<?php
header("Access-Control-Allow-Origin: *");
   // Define database connection parameters
   $hn      = 'localhost';
   $un      = 'root';
   $pwd     = '********';
   $db      = 'track';
   $cs      = 'utf8';
   $log     = '';
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
   // Determine which mode is being requested
   switch($key)
   {
      // Add a new record to the technologies table
      case "updatePosition":
         // Sanitise URL supplied values
         $idUser        = filter_var($obj->idUser   , FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $x		= filter_var($obj->x        , FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $y		= filter_var($obj->y        , FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $log		= filter_var($obj->y        , FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);

         // Attempt to run PDO prepared statement
         try {
            $sql 	= "UPDATE localisation SET x = :x , y = :y WHERE idUser = :idUser";
            $stmt 	= $pdo->prepare($sql);
            $stmt->bindParam(':idUser'	, $idUser     	, PDO::PARAM_STR);
            $stmt->bindParam(':x'	, $x     	, PDO::PARAM_STR);
            $stmt->bindParam(':y'	, $y     	, PDO::PARAM_STR);
            $stmt->execute();
            echo json_encode(array('message' => 'Congratulations '));
         }
         // Catch any errors in running the prepared statement
         catch(PDOException $e)
         {
            echo $e->getMessage();
            $myfile = fopen("log.txt", "w") or die("Unable to open file!");
            $txt    = "Jane Doe\n";
            fwrite($myfile, $log);
            fclose($myfile);
         }
      break;

      case "getPosition":
         // Sanitise URL supplied values
         $idUser        = filter_var($obj->idUser   , FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);

         // Attempt to run PDO prepared statement
         try {
            $sql 	= "select * from localisation WHERE idUser = :idUser";
            $stmt 	= $pdo->prepare($sql);
            $stmt->bindParam(':idUser'	, $idUser     	, PDO::PARAM_STR);
            $stmt->execute();

            while($row  = $stmt->fetch(PDO::FETCH_OBJ))
              {
                 $data[] = $row;
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
         // Catch any errors in running the prepared statement
         catch(PDOException $e)
         {
            echo $e->getMessage();
            $myfile = fopen("log.txt", "w") or die("Unable to open file!");
            $txt    = "Jane Doe\n";
            fwrite($myfile, $log);
            fclose($myfile);
         }
      break;
   }
?>
