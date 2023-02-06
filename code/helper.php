<?php
//code referenced by Test Oracle file for UBC CPSC304 2018 Winter Term 1
//   Created by Jiemin Zhang
//   Modified by Simona Radu
//   Modified by Jessica Wong (2018-06-22)

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executeAndPrintPlainSQL($cmdstr, $db_conn_ext, $success_ext) {
            global $db_conn, $success;
            $db_conn = $db_conn_ext;
            $success = $success_ext;
            $result = executePlainSQL($cmdstr);
            printResult($result);
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr);
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

			return $statement;
		}

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

			global $db_conn, $success;
			$statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    //echo $val;
                    //echo "<br>".$bind."<br>";
                    OCIBindByName($statement, $bind, $val);
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
				}

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }

        function printResult($result) { //prints results from a select statement
            // echo "<div class = "result">";
            echo "<div>";
            echo "<table>";

            $numfields = oci_num_fields($result);
            $columnNamesPrinted = False;

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                // Print column names
                if (!$columnNamesPrinted) {
                    $arr_keys = array_keys($row);
                    echo "<tr>\n";
                    for ($ci = 1; $ci < count($arr_keys); $ci+=2) {
                        echo "<td>" . $arr_keys[$ci] . "</td>";
                    }
                    echo "</tr>\n";
                    $columnNamesPrinted = True;
                }

                echo "<tr>\n";
                for ($i = 0; $i < $numfields; $i++) {
                    echo "<td>" . $row[$i] . "</td>";
                }
                echo "</tr>\n";
            }

            echo "</table>";
            if (!$columnNamesPrinted) {
                echo "<p>No results found.</p>";
            }
            echo "</div>";
          
        }



	?>