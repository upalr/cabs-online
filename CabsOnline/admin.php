<!--
Name: Upal Roy
StudentID: 101918586
Admin page
-->
<html>
<head>
    <title>CabsOnline</title>
</head>
<body>
<h1>Admin page of CabsOnline</h1>
<p>1. Click below button to search for all unassigned booking requests with a pick-up time within 2 hours.</p>
<form action="admin.php" method="post">
    <input type="submit" name="submitListAllField" value="List all"/>
</form>

<?php
require_once "settings.php";	// Load MySQL log in credentials

//check submit button click
if (isset($_POST['submitListAllField'])) {
    $DBConnect = @mysqli_connect($host, $user, $pwd, $sql_db)
    Or die ("<p>Unable to connect to the database server.</p>" . "<p>Error code " . mysqli_connect_errno() . ": " . mysqli_connect_error()) . "</p>";

    $SQLstring = "SELECT b.booking_number, c.name, b.passenger_name, b.passenger_phone_number,b.pickup_unit_number, b.pickup_street_number,b.pickup_street_name,b.pickup_suburb, b.destination_suburb, b.pickup_time FROM booking b, customer c where b.email_address=c.email_address and b.status='unassigned' and pickup_time > NOW() and pickup_time < NOW() + INTERVAL 2 HOUR";
    $queryResult = @mysqli_query($DBConnect, $SQLstring)
    Or die ("<p>Unable to query into  booking table.</p>" . "<p>Error code " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect)) . "</p>";

    echo "<table width='100%' border='1'>";
    echo "<th>reference #</th><th>customer name</th><th>passenger name</th><th>passenger contact phone</th><th>pick-up address</th><th>destination suburb</th><th>pick-time</th>";
    $row = mysqli_fetch_row($queryResult);
    while ($row) {
        $pickupAddress = "";
        if ($row[4] != '')
            $pickupAddress = $pickupAddress . $row[4] . "/";

        $pickupAddress = $pickupAddress . $row[5] . " " . $row[6] . ", " . $row[7];
        $picupTime = date_format(date_create($row[9]), "d M H:i");
        echo "<tr><td>{$row[0]}</td>";
        echo "<td>{$row[1]}</td>";
        echo "<td>{$row[2]}</td>";
        echo "<td>{$row[3]}</td>";
        echo "<td>{$pickupAddress}</td>";
        echo "<td>{$row[8]}</td>";
        echo "<td>{$picupTime}</td></tr>";
        $row = mysqli_fetch_row($queryResult);
    }

    echo "</table>";
    mysqli_close($DBConnect);
}
?>

<p>2.Input a reference number below and click "update" button to assign a taxi to that request.</p>
<form action="admin.php" method="post">
    Reference number: <input type="text" name="referenceNumberField">
    <input type="submit" name="submitUpdateField" value="update"/>
</form>


<?php
//check submit button click
if (isset($_POST['submitUpdateField'])) {
    if (isset($_POST['referenceNumberField']) && $_POST['referenceNumberField'] != "") {
        $DBConnect = @mysqli_connect($host, $user, $pwd, $sql_db)
        Or die ("<p>Unable to connect to the database server.</p>" . "<p>Error code " . mysqli_connect_errno() . ": " . mysqli_connect_error()) . "</p>";

        $SQLstring = "UPDATE booking SET status = 'assigned' WHERE status='unassigned' and booking_number = " . $_POST['referenceNumberField'];
        $queryResult = @mysqli_query($DBConnect, $SQLstring)
        Or die ("<p>Unable to query into  booking table.</p>" . "<p>Error code " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect)) . "</p>";

        $num_rows = mysqli_affected_rows($DBConnect); // find the number of affected rows for the update

        if ($num_rows == 1) {
            echo "<p style='color:green;'>The booking request " . $_POST['referenceNumberField'] . " has been properly assigned</p>";
        } else {
            echo "<p style='color:red;'>No unassigned booking request is found for update</p>";
        }
    }
}
?>
</body>
</html>