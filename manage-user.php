<?php
header("Access-Control-Allow-Origin: *");
   // Define database connection parameters
   $hn      = 'localhost';
   $un      = 'root';
   $pwd     = '*******';
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
   // Determine which mode is being requested
   switch($key)
   {
      // Add a new record to the technologies table
      case "create":
         // Sanitise URL supplied values
         $userName      = filter_var($obj->userName	, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $firstName     = filter_var($obj->firstName	, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $lastName      = filter_var($obj->lastName	, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $password      = filter_var($obj->password	, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);

         // Attempt to run PDO prepared statement
         try {

	      $stmt1     = $pdo->query('SELECT MAX(ID) FROM user');
              while($row  = $stmt1->fetch(PDO::FETCH_OBJ))
              {
			foreach($row as $field) {
				$stmt1     = $pdo->query('INSERT INTO localisation(idUser, x, y) VALUES('.++$field.' ,0,0)');
	    		}
              }

            $sql 	= "INSERT INTO user(userName, firstName, lastName, password, type ) VALUES(:userName, :firstName, :lastName, :password, 0)";
            $stmt 	= $pdo->prepare($sql);
            $stmt->bindParam(':userName',  $userName  , PDO::PARAM_STR);
            $stmt->bindParam(':firstName', $firstName , PDO::PARAM_STR);
            $stmt->bindParam(':lastName' , $lastName  , PDO::PARAM_STR);
            $stmt->bindParam(':password' , $password  , PDO::PARAM_STR);
            $stmt->execute();
            echo json_encode(array('message' => 'Congratulations the record ' . $firstName. ' was added to the database'));
         }
         // Catch any errors in running the prepared statement
         catch(PDOException $e)
         {
            echo $e->getMessage();
            $myfile = fopen("log.txt", "w") or die("Unable to open file!");
            $txt    = "Jane Doe\n";
            fwrite($myfile, $e->getMessage());
            fclose($myfile);
         }
      break;
      // Update an existing record in the technologies table
      case "update":
         // Sanitise URL supplied values
         $name 		     = filter_var($obj->name, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $recordID	     = filter_var($obj->recordID, FILTER_SANITIZE_NUMBER_INT);
         // Attempt to run PDO prepared statement
         try {
            $sql 	= "UPDATE user SET name = :name WHERE id = :recordID";
            $stmt 	=	$pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':recordID', $recordID, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode('Congratulations the record ' . $name . ' was updated');
         }
         // Catch any errors in running the prepared statement
         catch(PDOException $e)
         {
            echo $e->getMessage();
         }
      break;
      // Remove an existing record in the technologies table
      case "delete":
         // Sanitise supplied record ID for matching to table record
         $recordID	=	filter_var($obj->recordID, FILTER_SANITIZE_NUMBER_INT);
         // Attempt to run PDO prepared statement
         try {
            $pdo 	= new PDO($dsn, $un, $pwd);
            $sql 	= "DELETE FROM user WHERE id = :recordID";
            $stmt 	= $pdo->prepare($sql);
            $stmt->bindParam(':recordID', $recordID, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode('Congratulations the record ' . $name . ' was removed');
         }
         // Catch any errors in running the prepared statement
         catch(PDOException $e)
         {
            echo $e->getMessage();
         }
      break;
   }
?>
