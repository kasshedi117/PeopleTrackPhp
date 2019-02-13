<?php
header("Access-Control-Allow-Origin: *");
   // Define database co*/ection parameters
   $hn      = 'localhost';
   $un      = 'root';
   $pwd     = '*******';
   $db      = 'track';
   $cs      = 'utf8';
   // Set up the PDO parameters
   $dsn         = "mysql:host=" . $hn . ";port=3306;dbname=" . $db . ";charset=" . $cs;
   $opt         = array(
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                       );
   // Create a PDO instance (co*/ect to the database)
   $pdo         = new PDO($dsn, $un, $pwd, $opt);
   // Retrieve the posted data


$stmt     = $pdo->query('SELECT MAX(ID) FROM user');
              while($row  = $stmt->fetch(PDO::FETCH_OBJ))
              {          
/* 
                        $stmt     = $pdo->query('INSERT INTO localisation(idUser, x, y) VALUES('.$row[0].' ,0,0)'); 
*/ 

foreach($row as $field) {
        echo ($field) ;
    }

}

?>
