<html>
<head>
<link rel="stylesheet" href="style.css"/>
</head>

<div id="delivery">
   <div>
	   <p> delivery imformations:</p>
      <?php
                require_once( "helper.php");
                if (connectToDB()) {
                    $result = executePlainSQL("select * from DeliveryPerson");
                    printResult($result);
                    disconnectFromDB();
                }
            ?>  

   </div>
   <div class= filter> 

   <form action="delivery.php" method ="POST">
   <input type="submit"  class="submit" value="show names of delivery person" name="namesofdeliv"></p>
   <?php
            if (isset($_POST['namesofdeliv'])) {
                handlePOSTRequest();
            }
        ?>
   </form>

         
   <hr/>
        Get car number for a delivery person:<br/>

        <form action="delivery.php" method="POST"> 
        
            enter delivery person id: <input type="text" name="deliveryid"> <br /><br />
            <input type="submit"  class="submit" value="submit" name="deliveryidSubmit"></p>

            <?php
            if (isset($_POST['deliveryidSubmit'])) {
                handlePOSTRequest();
            }
        ?>
        </form>

        <hr/>
        Get model for a specific car:<br/>

        <form action="delivery.php" method="POST"> 
            enter car number: <input type="text" name="numplate"> <br /><br />
            <input type="submit"  class="submit" value="submit" name="carnumSubmit"></p>


            <?php
            if (isset($_POST['carnumSubmit'])) {
                handlePOSTRequest();
            }
        ?>
        </form>
        

         <form action="front.php">
    <button class ="back">back to home</button>
</form>
   </div>
   <?php
        define('__ROOT__', dirname(__FILE__));
        require_once(__ROOT__.'/helper.php');

        $success = True;
        $db_conn = NULL;

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


        function handlePOSTRequest() {
            if (connectToDB()) {
                // TODO: reroute any requests accordingly
                if (array_key_exists('namesofdeliv', $_POST)) {
                    retriveAllDiliveryPersonName();
                }else if(array_key_exists('deliveryidSubmit', $_POST)){

                    retriveCarNumberPlate();
                }else if(array_key_exists('carnumSubmit', $_POST)){
                    retriveCarModel();

                }
         
               disconnectFromDB();
            }
        }


        // get name of delivery person

        function retriveAllDiliveryPersonName(){
            $sql = "select name from DeliveryPerson";
             $result = executePlainSQL($sql);
             printResult($result);
            
         }


         //get car num of delivery person

         function retriveCarNumberPlate(){
            $personID = $_POST['deliveryid'];
            $sql = "SELECT number_plate FROM UsesVehicle WHERE deliveryPersonID = '".$personID."'";
             $result = executePlainSQL($sql);
              printResult($result);
         }


         //get car model for specific car

         function retriveCarModel(){

            $num_plate=$_POST["numplate"];

            $sql = "SELECT model FROM Vehicle1 WHERE number_plate = '".$num_plate."'";
            $result = executePlainSQL($sql);
              printResult($result);
         }
    ?>
	
</div>
</html>