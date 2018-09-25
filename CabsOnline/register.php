<!--
Name: Upal Roy
StudentID: 101918586
Registration page
-->

<?php
require_once "settings.php";	// Load MySQL log in credentials
session_start();

//check submit button click
if (isset($_POST['submitField'])) {
    if (isRequiredFieldsValid() && isPasswordsEqual() && isEmailAddressUnique()) {

        $DBConnect = @mysqli_connect($host, $user, $pwd, $sql_db)
        Or die ("<p>Unable to connect to the database server.</p>" . "<p>Error code " . mysqli_connect_errno() . ": " . mysqli_connect_error()) . "</p>";

        $SQLstring = "INSERT INTO customer (email_address, name, password, phone_number) VALUES ('" . $_POST['emailField'] . "','" . $_POST['nameField'] . "','" . $_POST['passwordField'] . "','" . $_POST['phoneNumberField'] . "')";
        $queryResult = @mysqli_query($DBConnect, $SQLstring)
        Or die ("<p>Unable to insert into  customer table.</p>" . "<p>Error code " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect)) . "</p>";
        $_SESSION['successMessage'] = true;
        mysqli_close($DBConnect);
    } else {
        saveValidFields(); // Save data  so that user can access already submitted data in the form during resubmission of the form
    }
}

//Required fields' data validity checking
function isRequiredFieldsValid()
{
    $allFieldsValid = true;
    if (isset($_POST['nameField']) && $_POST['nameField'] == "") {
        $_SESSION['nameFieldRequired'] = true;
        $allFieldsValid = false;
    }
    if (isset($_POST['passwordField']) && $_POST['passwordField'] == "") {
        $_SESSION['passwordFieldRequired'] = true;
        $allFieldsValid = false;
    }
    if (isset($_POST['confirmPasswordField']) && $_POST['confirmPasswordField'] == "") {
        $_SESSION['confirmPasswordFieldRequired'] = true;
        $allFieldsValid = false;
    }
    if (isset($_POST['emailField']) && $_POST['emailField'] == "") {
        $_SESSION['emailFieldRequired'] = true;
        $allFieldsValid = false;
    }
    if (isset($_POST['phoneNumberField']) && $_POST['phoneNumberField'] == "") {
        $_SESSION['phoneNumberFieldRequired'] = true;
        $allFieldsValid = false;
    }
    return $allFieldsValid;
}

//Password and confirmation password equality checking
function isPasswordsEqual()
{
    if (strcmp($_POST['passwordField'], $_POST['confirmPasswordField']) == 0) {
        $_SESSION['passWordInequalityErrorMessage'] = false;
        return true;
    } else {
        $_SESSION['passWordInequalityErrorMessage'] = true;
        return false;
    }
}

//Uniqueness of the entered email address checking
function isEmailAddressUnique()
{
    global $host, $user, $pwd, $sql_db;
    $DBConnect = @mysqli_connect($host, $user, $pwd, $sql_db)
    Or die ("<p>Unable to connect to the database server.</p>" . "<p>Error code " . mysqli_connect_errno() . ": " . mysqli_connect_error()) . "</p>";

    $email = $_POST['emailField'];
    if ($email != "") {
        $SQLstring = "SELECT * FROM customer where email_address='" . $email . "'";
        $queryResult = @mysqli_query($DBConnect, $SQLstring)
        Or die ("<p>Unable to query in customer table.</p>" . "<p>Error code " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect)) . "</p>";

        $num_rows = mysqli_num_rows($queryResult);
        if ($num_rows >= 1) {
            $_SESSION['eamilNotUniqueErrorMessage'] = true;
            return false;
        } else {
            $_SESSION['eamilNotUniqueErrorMessage'] = false;
            return true;
        }

        mysqli_close($DBConnect);
    }

}

//If any validation error occured during the submission of the form. user should not lost already submitted data.
// Therefore, already submitted data are saving in session variable
function saveValidFields()
{
    if (isset($_POST['nameField']) && $_POST['nameField'] != "")
        $_SESSION['nameFieldValue'] = $_POST['nameField'];
    if (isset($_POST['emailField']) && $_POST['emailField'] != "")
        $_SESSION['emailFieldValue'] = $_POST['emailField'];
    if (isset($_POST['phoneNumberField']) && $_POST['phoneNumberField'] != "")
        $_SESSION['phoneNumberFieldValue'] = $_POST['phoneNumberField'];
}

?>

<html>
<head>
    <title>CabsOnline</title>
</head>
<body>
<h1>Register to CabsOnline</h1>
<p>Please fill the fields below to complete your registration.</p>
<form action="register.php" method="post">
    <table>
        <tr>
            <td>Name:</td>
            <td><input type="text" name="nameField"
                       value="<?php if (isset($_SESSION['nameFieldValue'])) echo $_SESSION['nameFieldValue'] ?>"></td>
            <td><?php if (isset($_SESSION['nameFieldRequired'])) echo "<p style='color:red;'>Name is required</p>"; ?> </td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="passwordField"></td>
            <td><?php if (isset($_SESSION['passwordFieldRequired'])) echo "<p style='color:red;'>Password is required</p>"; ?> </td>
        </tr>
        <tr>
            <td>Confirm password:</td>
            <td><input type="password" name="confirmPasswordField"></td>
            <td><?php if (isset($_SESSION['confirmPasswordFieldRequired'])) echo "<p style='color:red;'>Confirm Password is required</p>"; ?> </td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><input type="email" name="emailField"
                       value="<?php if (isset($_SESSION['emailFieldValue'])) echo $_SESSION['emailFieldValue'] ?>"></td>
            <td><?php if (isset($_SESSION['emailFieldRequired'])) echo "<p style='color:red;'>Email is required</p>"; ?> </td>
        </tr>
        <tr>
            <td>Phone:</td>
            <td><input type="tel" name="phoneNumberField"
                       value="<?php if (isset($_SESSION['phoneNumberFieldValue'])) echo $_SESSION['phoneNumberFieldValue'] ?>">
            </td>
            <td><?php if (isset($_SESSION['phoneNumberFieldRequired'])) echo "<p style='color:red;'>Phone Number is required</p>"; ?> </td>
        </tr>
        <tr>
            <td><input type="submit" name="submitField" value="Register"/></td>
        </tr>
    </table>
</form>

<!--Showing error and success message-->
<?php if (isset($_SESSION['passWordInequalityErrorMessage']) && $_SESSION['passWordInequalityErrorMessage']) echo "<p style='color:red;'>Password and Confirm Password does not match.</p></br>"; ?>
<?php if (isset($_SESSION['eamilNotUniqueErrorMessage']) && $_SESSION['eamilNotUniqueErrorMessage']) echo "<p style='color:red;'>This email is already registered. Please register using another email address.</p></br>"; ?>
<?php if (isset($_SESSION['successMessage']) && $_SESSION['successMessage']) echo "<p style='color:green;'>Successfully registered on CabsOnline.</p></br>"; ?>

<?
//unsetting session
session_unset();
?>

Already registered? <a href="./login.php">Login here</a>
</body>
</html>
