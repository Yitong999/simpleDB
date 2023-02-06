
<html>
<head>
<link rel="stylesheet" href="style.css"/>
</head>

<div id="promotion">

<div>
	   <p> promotions:</p>
       <?php
                require_once( "helper.php");
                if (connectToDB()) {
                    $result = executePlainSQL("select * from Promotion");
                    printResult($result);
                    disconnectFromDB();
                }
            ?>  
</div>


<div class= "filter">
<form action="promotion.php" method="GET">
    <input type="hidden" id="promoMinMaxRequest" name="promoMinMaxRequest">
	<span> show </span>
        <input type="radio" name="aggregOp" value="min">min
		<input type="radio" name="aggregOp" value="max">max
    <span> discount per platform </span>
   <br />
    <input type="submit"  class="submit" value="submit" name="promotionSubmit"></p>
</form>

<?php
    if (isset($_GET['promoMinMaxRequest'])) {
        handlePromoMinMaxRequest();
    }
?>

<p>find maximum user balance, for each platform with more than one user:</p>
<form action="promotion.php" method="GET"> 
    <input type="hidden" id="maxBalanceRequest" name="maxBalanceRequest">
    <input type="submit"  class="submit" value="find" name="maxBalanceSubmit"></p>
</form>

<?php
    if (isset($_GET['maxBalanceRequest'])) {
        handleMaxBalanceRequest();
    }
?>

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

        function handlePromoMinMaxRequest() {
            global $db_conn, $success;
            if (connectToDB()) {
                if (array_key_exists('promotionSubmit', $_GET) && isset($_GET['aggregOp'])) {
                    $aggregateOp = $_GET['aggregOp'];

                    $sqlcmd = "
                        SELECT p.platform, " . $aggregateOp . "(p.discountPercentage)
                        FROM Promotion p
                        GROUP BY p.platform
                    ";

                    $result = executeAndPrintPlainSQL(
                        $sqlcmd,
                        $db_conn,
                        $success
                    );
                }
                disconnectFromDB();
            }
        }

        function handleMaxBalanceRequest() {
            global $db_conn, $success;
            if (connectToDB()) {
                if (array_key_exists('maxBalanceSubmit', $_GET)) {
                    $sqlcmd = "
                        SELECT u1.platformName, MAX(u1.account_balance) as maxAccountBalance
                        FROM User_1 u1
                        GROUP BY u1.platformName
                        HAVING 1 < (
                            SELECT COUNT(*)
                            FROM User_1 u2
                            WHERE u1.platformName = u2.platformName
                        )
                    ";

                    $result = executeAndPrintPlainSQL(
                        $sqlcmd,
                        $db_conn,
                        $success
                    );
                }
                disconnectFromDB();
            }
        }

    ?>
</div>
</html>