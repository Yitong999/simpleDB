<html>
<head>
<link rel="stylesheet" href="style.css"/>
</head>

<div class = "user" id="update">

<div>

<p id = "title" >USER:</p>

<?php
                require_once( "helper.php");
                if (connectToDB()) {
                    $result = executePlainSQL("select * from User_1");
                    printResult($result);
                    disconnectFromDB();
                }
            ?>  
</div>

<div  class= "filter">

<form method="POST"> 
            
            enter user id: <input type="number" name="userid" placeholder="user id"> 
            <input type="submit" name="button1"
                class="button" value="get user address" />            
            <br />-----------------------<br />
            
		

			change an address type: 
            <br>

			Street: <input type="text" name="oldStreet" placeholder="Street"> 
            <br>
            City: <input type="text" name="oldCity" placeholder="City"> 
            <br>
            Country: <input type="text" name="oldCountry" placeholder="Country"> 
            <br>
            New Address Type: <input type="text" name="AddressType" placeholder="type"> 

            <input type="submit" name="button2" class="button" value="change"></p>
        
            <br />-----------------------<br />

            insert an address type: 
            <br>

			Street: <input type="text" name="newStreet" placeholder="Street"> 
            <br>
            City: <input type="text" name="newCity" placeholder="City"> 
            <br>
            Country: <input type="text" name="newCountry" placeholder="Country"> 
            <br>
            New Address Type: <input type="text" name="newAddressType" placeholder="type"> 

            <input type="submit" name="button3" class="button" value="insert"></p>



            <br />-----------------------<br />

            delete an address type: 
            <br>

			Street: <input type="text" name="delStreet" placeholder="Street"> 
            <br>
            City: <input type="text" name="delCity" placeholder="City"> 
            <br>
            Country: <input type="text" name="delCountry" placeholder="Country"> 

            <input type="submit" name="button4" class="button" value="delete"></p>
            
            
            <div class = "result">

            <?php
                if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['button1']))
                {
                    echo "get user address button clicked<br>";
                    echo "--------------------------------<br><br>";
                    $id = $_POST['userid'];

                    if ($id){
                        require_once( "helper.php");
                        require_once("data_retrive_helper.php");
                        $command = retriveUserAddress($id);
                        if (connectToDB()) {
                            $result = executePlainSQL($command);
                            printResult($result);
                            echo "GET SUCCESSFULLY.";
                            disconnectFromDB();
                        }
                    }else{
                        echo "no input for user id yet";
                    }
                }

                if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['button2']))
                {
                    echo "change user address type button clicked<br>";
                    echo "--------------------------------<br><br>";
                    $id = $_POST['userid'];
                    $oldStreet = $_POST['oldStreet'];
                    $oldCity = $_POST['oldCity'];
                    $oldCountry = $_POST['oldCountry'];
                    $AddressType = $_POST['AddressType'];
                    // $id = '"$_POST['userid']"';
                    // $oldStreet = '"$_POST['oldStreet']'".;
                    // $oldCity = '"$_POST['oldCity']"'".;
                    // $oldCountry = '"$_POST['oldCountry']"';
                    // $AddressType = '"$_POST['AddressType']"';
                                        
                    if ($id and $oldStreet and $oldCity and $oldCountry and $AddressType){
                        require_once( "helper.php");
                        require_once("data_retrive_helper.php");
                        $command = changeAddressType($id, $oldStreet, $oldCity, $oldCountry, $AddressType);                    
                        echo $command;
                        $db_conn = connectToDB();
                        if ($db_conn) {
                            $result = executePlainSQL($command);
                            OCICommit($db_conn);
                            printResult($result);
                            echo "CHANGE SUCCESSFULLY.";
                            disconnectFromDB();
                        }
                    }else{
                        echo "miss one of the input";
                    }
                }

                if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['button3']))
                {
                    echo "insert button clicked<br>";
                    echo "--------------------------------<br><br>";
                    $id = $_POST['userid'];
                    $newStreet = $_POST['newStreet'];
                    $newCity = $_POST['newCity'];
                    $newCountry = $_POST['newCountry'];
                    $newAddressType = $_POST['newAddressType'];

                    if ($id and $newStreet and $newCity and $newCountry and $newAddressType){
                        require_once( "helper.php");
                        require_once("data_retrive_helper.php");
                        $command = insertAddress($id, $newCountry, $newCity, $newStreet, $newAddressType);
                        echo $command;
                        $db_conn = connectToDB();
                        if ($db_conn) {
                            $result = executePlainSQL($command);
                            OCICommit($db_conn);
                            printResult($result);
                            echo "INSERT SUCCESSFULLY.";
                            disconnectFromDB();
                        }
                    }else{
                        echo "at least one input is missed";
                    }
                }

                if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['button4']))
                {
                    echo "delete button clicked<br>";
                    echo "--------------------------------<br><br>";
                    $id = $_POST['userid'];
                    $newStreet = $_POST['delStreet'];
                    $newCity = $_POST['delCity'];
                    $newCountry = $_POST['delCountry'];

                    if ($id and $newStreet and $newCity and $newCountry){
                        require_once( "helper.php");
                        require_once("data_retrive_helper.php");
                        $command = deleteAddress($id, $newStreet, $newCity, $newCountry);
                        echo $command;
                        $db_conn = connectToDB();
                        if ($db_conn) {
                            $result = executePlainSQL($command);
                            OCICommit($db_conn);
                            printResult($result);
                            echo "DELETE SUCCESSFULLY.";
                            disconnectFromDB();
                        }
                    }else{
                        echo "at least one input is missed";
                    }
                }
                

                
            ?>
           
            </div>
        </form>

        <form action="front.php">
    <button class ="back" >back to home</button>
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
            //$db_conn = OCILogon("ora_pchang18", "a69230761", "dbhost.students.cs.ubc.ca:1522/stu");
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
                if (array_key_exists('TODORequest', $_POST)) {
                    // handleTODORequest();
                }
         
               disconnectFromDB();
            }
        }

        function handleGETRequest() {
            if (connectToDB()) {
                // Reroute any requests accordingly
                if (array_key_exists('restPlatSubmit', $_GET)) {
                    handleRestPlatRequest();
                } else if (array_key_exists('onAllPlatSubmit', $_GET)) {
                    handleOnAllPlatRequest();
                }
         
                disconnectFromDB();
            }
        }

        function handleRestPlatRequest() {
            global $db_conn, $success;

            $platformName = $_GET['pName'];
            $sqlcmd = "SELECT name, restaurantID FROM Hosts NATURAL JOIN Restaurant WHERE platform='" . $platformName . "'";

            $result = executeAndPrintPlainSQL(
                $sqlcmd,
                $db_conn,
                $success
            );
        }

        function handleOnAllPlatRequest() {
            global $db_conn, $success;

            $sqlcmd = "
                SELECT r.name 
                FROM Restaurant r 
                WHERE NOT EXISTS
                (
                    SELECT p.platformName FROM Platform p
                    MINUS
                    SELECT h.platform FROM Hosts h WHERE h.restaurantID=r.restaurantID
                )";

            $result = executeAndPrintPlainSQL(
                $sqlcmd,
                $db_conn,
                $success
            );
        }

        // if (isset($_POST['todoRequest'])) {
        //     handlePOSTRequest();
        // } else if (isset($_GET['restPlatRequest'])) {
        //     handleGETRequest();
        // }
    ?>
</div>
</html>