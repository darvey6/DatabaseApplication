
<h1>Reset Page</h1>
<h4>Reset Database:</h4>
<form method="POST" action="Reset.php">
    <p><input type="submit" value="Reset" name="reset"></p>
</form>


<html>
<style>
    table {
        width: 20%;
        border: 1px solid black;
    }

    th {
        font-family: Arial, Helvetica, sans-serif;
        font-size: .7em;
        background: #666;
        color: #FFF;
        padding: 2px 6px;
        border-collapse: separate;
        border: 1px solid #000;
    }

    td {
        font-family: Arial, Helvetica, sans-serif;
        font-size: .7em;
        border: 1px solid #DDD;
        color: black;
    }
</style>
</html>


<?php

// keep track of errors so it redirects the page only if
// there are no errors
$success = True;
$db_conn = OCILogon("ora_darvey6", "a16444144",
    "dbhost.students.cs.ubc.ca:1522/stu");

function executePlainSQL($cmdstr)
{
    // Take a plain (no bound variables) SQL command and execute it.
    //echo "<br>running ".$cmdstr."<br>";
    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);
    // There is a set of comments at the end of the file that
    // describes some of the OCI specific functions and how they work.

    if (!$statement) {
        echo "<br>Cannot parse this command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        // For OCIParse errors, pass the connection handle.
        echo htmlentities($e['message']);
        $success = False;
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
        echo "<br>Cannot execute this command: " . $cmdstr . "<br>";
        $e = oci_error($statement);
        // For OCIExecute errors, pass the statement handle.
        echo htmlentities($e['message']);
        $success = False;
    } else {

    }
    return $statement;

}

function executeBoundSQL($cmdstr, $list)
{
    /* Sometimes the same statement will be executed several times.
        Only the value of variables need to be changed.
       In this case, you don't need to create the statement several
        times.  Using bind variables can make the statement be shared
        and just parsed once.
        This is also very useful in protecting against SQL injection
        attacks.  See the sample code below for how this function is
        used. */

    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse this command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            //echo $val;
            //echo "<br>".$bind."<br>";
            OCIBindByName($statement, $bind, $val);
            unset ($val); // Make sure you do not remove this.
            // Otherwise, $val will remain in an
            // array object wrapper which will not
            // be recognized by Oracle as a proper
            // datatype.
        }
        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute this command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement);
            // For OCIExecute errors pass the statement handle
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
        }
    }

}

function printResult($result)
{ //prints results from a select statement
    echo "<br>Got data from table tab1:<br>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["NID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]"
    }
    echo "</table>";
}


// Connect Oracle...
if ($db_conn) {

    if (array_key_exists('reset', $_POST)) {
        // Drop old table...
        echo "<p> Database has been reset</p>";

        echo "<br> dropping table <br>";
        executePlainSQL("Drop table Offer");

        echo "<br> dropping table <br>";
        executePlainSQL("Drop table HR");

        echo "<br> dropping table <br>";
        executePlainSQL("Drop table HM");

        // Create new table...
        echo "<br> creating new table <br>";
        executePlainSQL("create table HR (HRid number,
                                                 HRname varchar2(30), 
                                                 primary key (HRid))");
        OCICommit($db_conn);

        // Create new table...
        echo "<br> creating new table <br>";
        executePlainSQL("create table HM (HMid number,
                                                 HMname varchar2(30),
                                                 department varchar2(30), 
                                                 primary key (HMid))");
        OCICommit($db_conn);

        echo "<br> creating new table <br>";
        executePlainSQL("create table Offer (HRid number, 
                                                    HMid number,
                                                    Aid number,
                                                    Oid number,
                                                    Jobdetails varchar2(80), 
                                                    primary key (HRid, Oid),
                                                    foreign key (HRid) references HR on delete cascade)");
        OCICommit($db_conn);

    }


    //Commit to save changes...
    OCILogoff($db_conn);
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}

?>
