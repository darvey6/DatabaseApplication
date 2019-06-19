<h1>Recruiter</h1>
<h4>Insert Recruiter ID, Name into tab below:</h4>
<p>
    Recruiter ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<form method="POST" action="Recruiter.php">
    <!-- refreshes page when submitted -->

    <p><input type="text" name="rid" size="12">
        <input type="text" name="rname" size="24">
        <!-- Define two variables to pass values. -->
        <input type="submit" value="insert" name="insertrecruiter"></p>
</form>


<!-- Create a form to pass the values.
     See below for how to get the values. -->

<h4>Add or update a Full-Time job posting below:</h4>
<p>
    Job ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Description&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Benefits&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Job Details
</p>

<form method="POST" action="Recruiter.php">
    <!-- refreshes page when submitted -->

    <p><input type="text" name="ftid" size="12">
        <input type="text" name="fttitle" size="18">
        <input type="text" name="ftdescription" size="30">
        <input type="text" name="ftbenefits" size="18">
        <input type="text" name="ftdeadline" size="18">
        <input type="submit" value="Post" name="postft">
        <input type="submit" value="Update" name="updateft">
        <!-- Define two variables to pass values. -->

    </p>
</form>


<h4>Add or update a Part-Time job posting below:</h4>
<p>
    Job ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Description&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Hours&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Job Details
</p>

<form method="POST" action="Recruiter.php">
    <!-- refreshes page when submitted -->

    <p><input type="text" name="ftid" size="12">
        <input type="text" name="fttitle" size="18">
        <input type="text" name="ftdescription" size="30">
        <input type="text" name="ftbenefits" size="18">
        <input type="text" name="ftdeadline" size="18">
        <input type="submit" value="Post" name="postpt">
        <input type="submit" value="Update" name="updatept">
        <!-- Define two variables to pass values. -->

    </p>
</form>


<h4>Send a Screening Test: </h4>
<p>
    Test ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Aid ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<form method="POST" action="Recruiter.php">
    <p>
        <input type="text" name="sid" size="12">
        <input type="text" name="said" size="12">
        <input type="submit" value="Send" name="sendtest">
    </p>
</form>


<h4> Delete offer below</h4>
<p>
    Offer ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<form method="POST" action="Recruiter.php">
    <!-- refreshes page when submitted -->

    <p><input type="text" name="Oid" size="12">
        <!-- Define two variables to pass values. -->

        <input type="submit" value="delete" name="deleteOffer"></p>
</form>

<h4> To delete Recruiter, Job Posting, or Screen test input ID below: </h4>
<p>
    ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<form method="POST" action="Recruiter.php">
    <!-- refreshes page when submitted -->

    <p> <input type="text" name="deleteid" size="12">
        <!-- Define two variables to pass values. -->
        <input type="submit" value="Delete Recruiter" name="deleterecruiter">
        <input type="submit" value="Delete Job" name="deletejob">
        <input type="submit" value="Delete Test" name="deletetest"></p>
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


function printTable($resultFromSQL, $namesOfColumnsArray)
{
    echo "<table>";
    echo "<tr>";
    // iterate through the array and print the string contents
    foreach ($namesOfColumnsArray as $name) {
        echo "<th>$name</th>";
    }
    echo "</tr>";

    while ($row = OCI_Fetch_Array($resultFromSQL, OCI_BOTH)) {
        echo "<tr>";
        $string = "";

        // iterates through the results returned from SQL query and
        // creates the contents of the table
        for ($i = 0; $i < sizeof($namesOfColumnsArray); $i++) {
            $string .= "<td>" . $row["$i"] . "</td>";
        }
        echo $string;
        echo "</tr>";
    }
    echo "</table>";
}


// Connect Oracle...
if ($db_conn) {
    if (array_key_exists('insertrecruiter', $_POST)) {
        // Get values from the user and insert data into
        // the table.
        $tuple = array(
            ":bind1" => $_POST['rid'],
            ":bind2" => $_POST['rname'],
        );
        $alltuples = array(
            $tuple
        );
        executeBoundSQL("insert into Recruiter values (:bind1, :bind2)", $alltuples);
        OCICommit($db_conn);
        

    } else
        if (array_key_exists('postft', $_POST)) {
            // Update tuple using data from user
            $tuple = array(
                ":bind1" => $_POST['ftid'],
                ":bind2" => $_POST['fttitle'],
                ":bind3" => $_POST['ftdescription'],
                ":bind4" => $_POST['ftbenefits'],
                ":bind5" => $_POST['ftdeadline'],
            );

            $alltuples = array(
                $tuple
            );
            executeBoundSQL("insert into Job values (:bind1, :bind2, :bind3, :bind5)", $alltuples);
            executeBoundSQL("insert into Job_Fulltime values (:bind1, :bind4)", $alltuples);

            OCICommit($db_conn);

        } else
            if (array_key_exists('postpt', $_POST)) {
                // Update tuple using data from user
                $tuple = array(
                    ":bind1" => $_POST['ptid'],
                    ":bind2" => $_POST['pttitle'],
                    ":bind3" => $_POST['ptdescription'],
                    ":bind4" => $_POST['pthours'],
                    ":bind5" => $_POST['ptdeadline'],
                );
                $alltuples = array(
                    $tuple
                );
                executeBoundSQL("insert into Job values (:bind1, :bind2, :bind3, :bind5)", $alltuples);
                executeBoundSQL("insert into Job_Parttime values (:bind1, :bind4)", $alltuples);

                OCICommit($db_conn);
        } else
                if (array_key_exists('updateft', $_POST)) {
                    // Update tuple using data from user
                    $tuple = array (
                        ":bind1" => $_POST['ftid'],
                        ":bind2" => $_POST['fttitle'],
                        ":bind3" => $_POST['ftdescription'],
                        ":bind4" => $_POST['ftbenefits'],
                        ":bind5" => $_POST['ftdeadline'],
                    );
                    $alltuples = array (
                        $tuple
                    );
                    executeBoundSQL("update Job set Title=:bind2, 
                                                Description=:bind3, 
                                                Deadline=:bind4,   
                                            where Jid=:bind1", $alltuples);
                    OCICommit($db_conn);

                    executeBoundSQL("update Job_Fulltime set Benefits=:bind4   
                                            where Jid=:bind1", $alltuples);
                    OCICommit($db_conn);
            } else
                    if (array_key_exists('updatept', $_POST)) {
                        // Update tuple using data from user
                        $tuple = array (
                            ":bind1" => $_POST['ptid'],
                            ":bind2" => $_POST['pttitle'],
                            ":bind3" => $_POST['ptdescription'],
                            ":bind4" => $_POST['pthours'],
                            ":bind5" => $_POST['ptdeadline'],
                        );
                        $alltuples = array (
                            $tuple
                        );
                        executeBoundSQL("update Job set Title=:bind2, 
                                                Description=:bind3, 
                                                Deadline=:bind4  
                                            where Jid=:bind1", $alltuples);
                        OCICommit($db_conn);

                        executeBoundSQL("update Job_Fulltime set Hours=:bind4   
                                                where Jid=:bind1", $alltuples);
                        OCICommit($db_conn);

                } else
                        if (array_key_exists('deleterecruiter', $_POST)) {
                            // Update tuple using data from user
                            $tuple = array (
                                ":bind1" => $_POST['deleteid'],
                            );
                            $alltuples = array (
                                $tuple
                            );
                            executeBoundSQL("delete from Recruiter where Rid=:bind1", $alltuples);

                            OCICommit($db_conn);
                    } else
                            if (array_key_exists('deletejob', $_POST)) {
                                // Update tuple using data from user
                                $tuple = array (
                                    ":bind1" => $_POST['deleteid'],
                                );
                                $alltuples = array (
                                    $tuple
                                );
                                executeBoundSQL("delete from Job where Jid=:bind1", $alltuples);

                                OCICommit($db_conn);

                            } else
                                if (array_key_exists('sendtest', $_POST)) {
                                    // Update tuple using data from user
                                    $tuple = array(
                                        ":bind1" => $_POST['sid'],
                                        ":bind2" => $_POST['said'],

                                    );
                                    $alltuples = array(
                                        $tuple
                                    );
                                    executeBoundSQL("insert into Screentest values (:bind1, :bind2)", $alltuples);

                                    OCICommit($db_conn);
                                }


    if ($_POST && $success) {
        //POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
        header("location: Recruiter.php");
    } else {
        // Select data...
        echo "<h5>Recruiter:<br>";
        $result = executePlainSQL("select * from Recruiter");
        $columnNames = array("Recruiter ID", "Name");
        printTable($result, $columnNames);
        echo "<h5>Jobs (part-time and full-time):<br>";
        $result = executePlainSQL("select * from Job");
        $columnNames = array("Job ID", "Title", "Description", "Deadline");
        printTable($result, $columnNames);

        echo "<h5>Full-Time Jobs:<br>";
        $result = executePlainSQL("select * from Job_Fulltime");
        $columnNames = array("Job ID", "Benefits");
        printTable($result, $columnNames);

        echo "<h5>Part-Time Jobs:<br>";
        $result = executePlainSQL("select * from Job_Parttime");
        $columnNames = array("Job ID", "Hours");
        printTable($result, $columnNames);

        echo "<h5>Screening Tests:<br>";
        $result = executePlainSQL("select * from Interview");
        $columnNames = array("Test ID", "Applicant ID");
        printTable($result, $columnNames);
    }


    //Commit to save changes...
    OCILogoff($db_conn);
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
?>
