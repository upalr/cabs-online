<!--
 Name: Upal Roy
 StudentID: 101918586
 login page
-->

<html>
<head>
    <title></title>
</head>
<body>
<h1>Login to CabsOnline</h1>

<form action="login.php" method="post">
    <table>
        <tr>
            <td>Email:</td>
            <td><input type="email" name="emailField"></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="passwordField"></td>
        </tr>

        <tr>
            <td><input type="submit" name="submitField" value="Log in"/></td>
        </tr>
    </table>
</form>
<?php
require_once "settings.php";	// Load MySQL log in credentials

//Checking all data are accessible before executing the query
if (isset($_POST['emailField']) && $_POST['emailField'] != "" && isset($_POST['passwordField']) && $_POST['passwordField'] != "") {
    $DBConnect = @mysqli_connect($host, $user, $pwd, $sql_db)
    Or die ("<p>Unable to connect to the database server.</p>" . "<p>Error code " . mysqli_connect_errno() . ": " . mysqli_connect_error()) . "</p>";

    $email = $_POST['emailField'];
    $password = $_POST['passwordField'];
    $SQLstring = "SELECT * FROM customer where email_address='" . $email . "' and password='" . $password . "'";
    $queryResult = @mysqli_query($DBConnect, $SQLstring)
    Or die ("<p>Unable to query in customer table.</p>" . "<p>Error code " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect)) . "</p>";

    $num_rows = mysqli_num_rows($queryResult);  /*Checking the number of customer found in the customer table*/
    if ($num_rows == 1) {
        header("Location:booking.php?email=" . $email);
    } else {
        echo "<p style='color: red'>Either Email Address or Password is incorrect. Please enter information correctly.</p>";
    }

    mysqli_close($DBConnect);
}
?>

New member? <a href="./register.php">Register now</a>
</body>
</html>