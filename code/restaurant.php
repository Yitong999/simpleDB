
<html>

<head>
<link rel="stylesheet" href="style.css"/>
</head>

<div id= "viewrestaurant">
		<div class="list">
			<h2>here are the restaurants:</h2>

            <?php
                require_once( "helper.php");
                if (connectToDB()) {
                    $result = executePlainSQL("select * from Restaurant");
                    printResult($result);
                    disconnectFromDB();
                }
            ?>  

		</div>

		<div class="filter">
			<h2> Apply filter:</h2>
         <!-- list of restaurant here -->

			<form  action="restaurant.php" method="POST">
			I want restaurant that has a rating no lower than:
         <br />
			<input type="radio" name="rating" value="1">1
			<input type="radio" name="rating" value="2">2
			<input type="radio" name="rating" value="3">3
			<input type="radio" name="rating" value="4">4
			<input type="radio" name="rating" value="5">5
			<hr />

         Show discount:
         <br />
         <input type="radio" name="discount" value="yes">ON
			<input type="radio" name="discount" value="no">OFF
			<br />
            <input type="submit" value="apply" name="Submit" class="submit"></p>
			</form>

            <?php
            if (isset($_POST['rating'])) {
               
                handlePOSTRequest();
            }
        ?>

            <hr/>
            find all restaurant on a certan platform:<br/>

        <form action="restaurant.php" method="GET"> 
            <input type="hidden" id="restPlatRequest" name="restPlatRequest">
            enter platform name: <input type="text" name="pName"> <br /><br />
            <input type="submit"  class="submit" value="submit" name="restPlatSubmit"></p>
        </form>

        <?php
            if (isset($_GET['restPlatRequest'])) {
                handleGETRequest();
            }
        ?>
		
        <hr/>
            find maximum restaurant rating,<br/>
            for each restaurant type having an average rating greater than:<br/>

        <form action="restaurant.php" method="GET">     
            <input type="hidden" id="maxRatingGroupedRequest" name="maxRatingGroupedRequest">
            <input type="text" name="avgRatingThreshold"> <br /><br />
            <input type="submit"  class="submit" value="submit" name="maxRatingGroupedSubmit"></p>
        </form>

        <?php
            if (isset($_GET['maxRatingGroupedRequest'])) {
                handleGETRequest();
            }
        ?>
		
        <hr/>
            find restaurant registered on all platform:<br/>

        <form action="restaurant.php" method="GET"> 
            <input type="hidden" id="onAllPlatRequest" name="onAllPlatRequest">
            <input type="submit"  class="submit" value="find" name="onAllPlatSubmit"></p>
        </form>

        <?php
            if (isset($_GET['onAllPlatRequest'])) {
                handleGETRequest();
            }
        ?>


        <!-- back to main page -->
 <form action="front.php">
    <button class ="back">back to home</button>
</form>


    <!-- ============================================== -->
    <!-- Restaurant-specific PHP functions and handlers -->
    <!-- ============================================== -->

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
                if(array_key_exists('Submit', $_POST)){

                    restaurantsFilter();
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
                } else if (array_key_exists('maxRatingGroupedSubmit', $_GET)) {
                    handleMaxRatingGroupedRequest();
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

        function handleMaxRatingGroupedRequest() {
            global $db_conn, $success;

            $ratingThreshold = 1000;  // dummy value if no value was entered; ratings should never be greater than this
            if (isset($_GET['avgRatingThreshold'])) {
                $ratingThreshold = $_GET['avgRatingThreshold'];
            }

            $sqlcmd = "
                SELECT R.type, CAST(AVG(R.rating) AS DECIMAL(10,2)) AS avg_rating, MAX(R.rating)
                FROM Restaurant R
                GROUP BY R.type
                HAVING AVG(R.rating) > " . $ratingThreshold . "
            ";

            $result = executeAndPrintPlainSQL(
                $sqlcmd,
                $db_conn,
                $success
            );
        }

       //for filtering the restaurant
       function restaurantsFilter(){ 

        $rate = $_POST['rating'];
       $discount =$_POST['discount'];


        $sql = "SELECT restaurantID, name, rating
              FROM Restaurant
              WHERE rating  >". $rate
                ." ";

        $sql2="SELECT Restaurant.restaurantID, Restaurant.name, Restaurant.rating, RestaurantSpecificDiscount.promotionID
        FROM Restaurant,RestaurantSpecificDiscount

        WHERE RestaurantSpecificDiscount.restaurantID =Restaurant.restaurantID
        AND rating  >". $rate;
        

        if($discount=="yes"){
            $result = executePlainSQL($sql2);
                printResult($result);

        }else{$result = executePlainSQL($sql);
            printResult($result);}
        
                

                
    }
    ?>

	</div>
</html>