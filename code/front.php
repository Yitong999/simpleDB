<html>
<head>

<link rel="stylesheet" href="style.css"/>
</head>
<body>

<!-- break -->

<div id= "welcom">
<h1> Welcome!</h1>
	
<h1>To begain please select an option below.</h1>
<form action="restaurant.php">
    <button >view restaurant</button>
</form>
<form action="promotion.php">
    <button >Per-platform information</button>
</form>
<form action="delivery.php">
    <button >Delivery imformation</button>
</form>
<form action="user.php">
    <button >User info</button>
</form>
</div>




<?php
		//this tells the system that it's no longer just parsing html; it's now parsing PHP
      
      $success = True; //keep track of errors so it redirects the page only if there are no errors
      $db_conn = NULL; // edit the login credentials in connectToDB()
      $show_debug_alert_messages = False;

      restaurantsFilter(2);
      
      function connectToDB() {
         global $db_conn;

         // Your username is ora_(CWL_ID) and the password is a(student number). For example,
         // ora_platypus is the username and a12345678 is the password.
         $db_conn = OCILogon("ora_yitongta", "a47999388", "dbhost.students.cs.ubc.ca:1522/stu");
         
         if ($db_conn) {
             debugAlertMessage("Database is Connected");
             return $db_conn;
         } else {
             debugAlertMessage("Cannot connect to Database");
             $e = OCI_Error(); // For OCILogon errors pass no handle
             echo htmlentities($e['message']);
             return 0;
         }
      }

      function disconnectFromDB() {
         global $db_conn;
         debugAlertMessage("Disconnect from Database");
         OCILogoff($db_conn);
      }

      function debugAlertMessage($message) {
         global $show_debug_alert_messages;

         if ($show_debug_alert_messages) {
             echo "<script type='text/javascript'>alert('" . $message . "');</script>";
         }
      }

      function handlePOSTRequest() {
         if (connectToDB()) {
            // TODO: reroute any requests accordingly
            if (array_key_exists('TODORequest', $_POST)) {
              // handleTODORequest();
            }
      
            disconnectFromDB();
          }
      }

      function handleGETRequest() {
         if (connectToDB()) {
            // TODO: reroute any requests accordingly
            if (array_key_exists('TODORequest', $_GET)) {
               // handleTODORequest();
            }
      
            disconnectFromDB();
         }
      }

      

   // function for printing table, part of the code is referenced by Test Oracle file for UBC CPSC304 2018 Winter Term 1
   function printResult($result,$parent,$table,$name) {
       //prints results from a select statement 
       //parent and table are string which represents the parent div and the given id for table div
       //name is the name of the table element
      echo "<div id ='".$table."'>";
      echo "<br>Retrieved data from table demoTable:<br>";
      echo "<table>";
      echo "<tr><th>".$name."</th></tr>";

      while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row[0] . "</td></tr>";
      }

      echo "</table>";
      echo "</div>";

      //place the table in the right div
      echo '<script type="text/javascript">',
              'document.getElementById("'.$parent.'").appendChild(document.getElementById("'.$table.'")); ',
            '</script>';
 }


      

     //select restaurants with rate greater than $rate
      function restaurantsFilter($rate){ 
         $sql = "SELECT restaurantID, name
               FROM Restaurant
               WHERE rating  > $rate
                  ";

         $db_conn = connectToDB();
         echo $db_conn;

         $result = $db_conn->query($sql);
         // echo $result->num_rows;
         if ($result->num_rows > 0){
            while($row = $result->fetch_assoc()) {
               echo "restaurantID: " . $row["restaurantID"]. " - Name: " . $row["name"]. "<br>";
            }
         }
      }

      function retrieveDiscountCodeFromRestaurants($restaurantID){
         $sql = "SELECT promotionID
               FROM RestaurantSpecificDiscount
               WHERE restaurantID = $restaurantID
                  ";
      }

      function retriveAllDiscountCode(){
         $sql = "SELECT promotionID
               FROM RestaurantSpecificDiscount
               ";
      }

      function retriveAllDiliveryPersonName(){
         $sql = "
            SELECT name
            FROM DeliveryPerson
         ";
      }

      function retriveCarNumberPlate($personID){
         $sql = "
            SELECT number_plate
            FROM UsesVehicle
            WHERE deliveryPersonID = $personID
         ";
      }

      function retriveCarModel($num_plate){
         $sql = "
            SELECT model
            FROM Vehicle
            WHERE number_plate = $num_plate
         ";
      }

      function changeAddress($ID, $newCountry, $newCity, $newStreet){
         $sql = "
            UPDATE UserAddress
            SET country = newCountry, city = newCity, streetAddress = newStreet
            WHERE userID  = $ID
         ";
      }


      if (isset($_POST['TODOInputName'])) {
         handlePOSTRequest();
      } else if (isset($_GET['TODOInputName'])) {
         handleGETRequest();
      }
      ?>

</body>	


</html>
