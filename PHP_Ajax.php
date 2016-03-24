<?php
  if($_REQUEST['type'] == "Hint")
  {
     // Fill up array with names
     $a[]="Anna";
     $a[]="Brittany";
     $a[]="Cinderella";
     $a[]="Diana";
     $a[]="Eva";
     $a[]="Fiona";
     $a[]="Gunda";
     $a[]="Hege";
     $a[]="Inga";
     $a[]="Johanna";
     $a[]="Kitty";
     $a[]="Linda";
     $a[]="Nina";
     $a[]="Ophelia";
     $a[]="Petunia";
     $a[]="Amanda";
     $a[]="Raquel";
     $a[]="Cindy";
     $a[]="Doris";
     $a[]="Eve";
     $a[]="Evita";
     $a[]="Sunniva";
     $a[]="Tove";
     $a[]="Unni";
     $a[]="Violet";
     $a[]="Liza";
     $a[]="Elizabeth";
     $a[]="Ellen";
     $a[]="Wenche";
     $a[]="Vicky";
     
     $q=$_GET["q"];
     
     if (strlen($q) > 0)
     {
        $hint="";
        for($i=0; $i<count($a); $i++)
        {
           if (strtolower($q) == strtolower(substr($a[$i], 0, strlen($q))))
           {
              if ($hint == "") {
                 $hint = $a[$i];
              }
              else {
                 $hint = $hint . " , " . $a[$i];
              }
           }
        }
     }
     
     if ($hint == "") {
        $response = "No Hint";
     }
     else {
        $response = $hint;
     }
     
     echo $response;
  }
  else if($_REQUEST['type'] == "CD")
  {
     $q=$_GET["q"];

     $xmlDoc = new DOMDocument();
     $xmlDoc->load("cd_catalog.xml");

     $x = $xmlDoc->getElementsByTagName('ARTIST');

     for($i=0; $i<=$x->length-1; $i++) {
        //Process only element nodes
        if ($x->item($i)->nodeType == 1) {
           if ($x->item($i)->childNodes->item(0)->nodeValue == $q) { 
              $y=($x->item($i)->parentNode);
           }
        }
     }

     $cd = ($y->childNodes);

     for($i=0; $i<$cd->length; $i++) { 
        //Process only element nodes
        if ($cd->item($i)->nodeType == 1) { 
           echo($cd->item($i)->nodeName);
           echo(": ");
           echo($cd->item($i)->childNodes->item(0)->nodeValue);
           echo("<br>");
        } 
     }
  }
  else if($_REQUEST['type'] == "user")
  {
     $q=$_GET["q"];

     $mysqli = new mysqli('localhost', 'root', '123456', 'my_db');
     if($mysqli->connect_error){
        echo "can not connect to mysql my_db : [" . $mysqli->connect_errno . "] " .  $mysqli->connect_error . "<br>";
     }

     $sql = "SELECT * FROM Persons WHERE CardID = $q";
     $result = $mysqli->query($sql);
     if($mysqli->error){
        echo "can not select data : " . $mysqli->error . "<br>";
     } else if($_GET["xml"] == "no"){
        echo "<br>
              <table border='1'>
                <tr>
                  <th>ID</th>
                  <th>CardID</th>
                  <th>FirstName</th>
                  <th>LastName</th>
                  <th>Age</th>
                  <th>Company</th>
                  <th>City</th>
                </tr>";
        
        while($row = $result->fetch_array()) {
           echo "<tr>";
           echo "<td>" . $row['ID'] . "</td>";
           echo "<td>" . $row['CardID'] . "</td>";
           echo "<td>" . $row['FirstName'] . "</td>";
           echo "<td>" . $row['LastName'] . "</td>";
           echo "<td>" . $row['Age'] . "</td>";
           echo "<td>" . $row['Company'] . "</td>";
           echo "<td>" . $row['City'] . "</td>";
           echo "</tr>";
        }
        echo "</table>";
     }
     else if($_GET["xml"] == "yes"){
        header('Content-Type: text/xml');
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
        echo '<person>';
        while($row = $result->fetch_array()) {
           echo "<ID>" . $row['ID'] . "</ID>";
           echo "<CardID>" . $row['CardID'] . "</CardID>";
           echo "<FirstName>" . $row['FirstName'] . "</FirstName>";
           echo "<LastName>" . $row['LastName'] . "</LastName>";
           echo "<Age>" . $row['Age'] . "</Age>";
           echo "<Company>" . $row['Company'] . "</Company>";
           echo "<City>" . $row['City'] . "</City>";
        }
        echo "</person>";
     }
  }
?>
