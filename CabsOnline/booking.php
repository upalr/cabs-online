<!--
Name: Upal Roy
StudentID: 101918586
Booking page
-->
<?php
session_start();
require_once "settings.php";	// Load MySQL log in credentials

if (isset($_GET['email']) && $_GET['email'] != "")
    $_SESSION['customerEmailAddressValue'] = $_GET['email'];
//check submit button click
if (isset($_POST['submitField'])) {
    if (isRequiredFieldsValid() && isPickupTimeIntervalMoreThanAnHour() && isCustomerEmailAddressValid()) {
        $DBConnect = @mysqli_connect($host, $user, $pwd, $sql_db)
        Or die ("<p>Unable to connect to the database server.</p>" . "<p>Error code " . mysqli_connect_errno() . ": " . mysqli_connect_error()) . "</p>";

        $pickupDate = $_POST['pickupDateField'];
        $pickupTime = $_POST['pickupTimeField'];
        $pickupDateTime = date('Y-m-d H:i:s', strtotime("$pickupDate $pickupTime"));
        $bookingDateTime = date("Y-m-d H:i:s");
        $SQLstring = "INSERT INTO booking (email_address, passenger_name, 	passenger_phone_number, pickup_unit_number, pickup_street_number, pickup_street_name, pickup_suburb, 	destination_suburb,	pickup_time, booking_time, 	status) 
        VALUES ('" . $_POST['customerEmailAddressField'] . "','" . $_POST['passengerNameField'] . "','" . $_POST['passengerPhoneNumberField'] . "','" . $_POST['unitNumberField'] . "','" . $_POST['streetNumberField'] . "','" . $_POST['streetNameField'] . "','" . $_POST['suburbField'] . "','" . $_POST['destinationSuburbField'] . "','" . $pickupDateTime . "','" . $bookingDateTime . "','unassigned')";

        $queryResult = @mysqli_query($DBConnect, $SQLstring)
        Or die ("<p>Unable to insert into  customer table.</p>" . "<p>Error code " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect)) . "</p>";
        $bookingReferenceNumber = mysqli_insert_id($DBConnect);

        $_SESSION['ConfirmationMessage'] = "Thank you! Your booking reference number is " . $bookingReferenceNumber . ". We will pick up the passengers in front of your provided address at " . $pickupTime . " on " . $pickupDate . ".";
        mysqli_close($DBConnect);

        sendEmailToCustomer($_POST['customerEmailAddressField']);
    } else {
        saveValidFields(); // Save data  so that user can access already submitted data in the form during resubmission of the form
    }
}

//Required fields' data validity checking
function isRequiredFieldsValid()
{
    $allFieldsValid = true;
    if (isset($_POST['passengerNameField']) && $_POST['passengerNameField'] == "") {
        $_SESSION['passengerNameFieldRequired'] = true;
        $allFieldsValid = false;
    }
    if (isset($_POST['passengerPhoneNumberField']) && $_POST['passengerPhoneNumberField'] == "") {
        $_SESSION['passengerPhoneNumberFieldRequired'] = true;
        $allFieldsValid = false;
    }
    if (isset($_POST['streetNumberField']) && $_POST['streetNumberField'] == "") {
        $_SESSION['streetNumberFieldRequired'] = true;
        $allFieldsValid = false;
    }
    if (isset($_POST['streetNameField']) && $_POST['streetNameField'] == "") {
        $_SESSION['streetNameFieldRequired'] = true;
        $allFieldsValid = false;
    }
    if (isset($_POST['suburbField']) && $_POST['suburbField'] == "") {
        $_SESSION['suburbFieldRequired'] = true;
        $allFieldsValid = false;
    }
    if (isset($_POST['destinationSuburbField']) && $_POST['destinationSuburbField'] == "") {
        $_SESSION['destinationSuburbFieldRequired'] = true;
        $allFieldsValid = false;
    }
    if (isset($_POST['pickupDateField']) && $_POST['pickupDateField'] == "") {
        $_SESSION['pickupDateFieldRequired'] = true;
        $allFieldsValid = false;
    }
    if (isset($_POST['pickupTimeField']) && $_POST['pickupTimeField'] == "") {
        $_SESSION['pickupTimeFieldRequired'] = true;
        $allFieldsValid = false;
    }
    return $allFieldsValid;
}

//Checking pick up time interval validation
function isPickupTimeIntervalMoreThanAnHour()
{
    $pickupDate = $_POST['pickupDateField'];
    $pickupTime = $_POST['pickupTimeField'];
    $pickupDateTime = date('Y-m-d H:i:s', strtotime("$pickupDate $pickupTime"));
    $currentDateTime = date("Y-m-d H:i:s");
    $timeDiff = strtotime($pickupDateTime) - strtotime($currentDateTime);

    if ($timeDiff > 0 && abs($timeDiff) >= 3600) {
        $_SESSION['pickupTimeInterValErrorMessage'] = false;
        return true;
    } else {
        $_SESSION['pickupTimeInterValErrorMessage'] = true;
        return false;
    }
}

// Checking customer email address is accessible or not
function isCustomerEmailAddressValid()
{
    if (isset($_POST['customerEmailAddressField']) && $_POST['customerEmailAddressField'] == "") {
        $_SESSION['customerEmailValidationErrorMessage'] = true;
        return false;
    } else {
        $_SESSION['customerEmailValidationErrorMessage'] = false;
        return true;
    }
}


//If any validation error occured during the submission of the form. user should not lost already submitted data.
// Therefore, already submitted data are saving in session variable
function saveValidFields()
{
    if (isset($_POST['passengerNameField']) && $_POST['passengerNameField'] != "")
        $_SESSION['passengerNameFieldValue'] = $_POST['passengerNameField'];
    if (isset($_POST['passengerPhoneNumberField']) && $_POST['passengerPhoneNumberField'] != "")
        $_SESSION['passengerPhoneNumberFieldValue'] = $_POST['passengerPhoneNumberField'];
    if (isset($_POST['unitNumberField']) && $_POST['unitNumberField'] != "")
        $_SESSION['unitNumberFieldValue'] = $_POST['unitNumberField'];
    if (isset($_POST['streetNumberField']) && $_POST['streetNumberField'] != "")
        $_SESSION['streetNumberFieldValue'] = $_POST['streetNumberField'];
    if (isset($_POST['streetNameField']) && $_POST['streetNameField'] != "")
        $_SESSION['streetNameFieldValue'] = $_POST['streetNameField'];
    if (isset($_POST['suburbField']) && $_POST['suburbField'] != "")
        $_SESSION['suburbFieldValue'] = $_POST['suburbField'];
    if (isset($_POST['destinationSuburbField']) && $_POST['destinationSuburbField'] != "")
        $_SESSION['destinationSuburbFieldValue'] = $_POST['destinationSuburbField'];
    if (isset($_POST['pickupDateField']) && $_POST['pickupDateField'] != "")
        $_SESSION['pickupDateFieldValue'] = $_POST['pickupDateField'];
    if (isset($_POST['pickupTimeField']) && $_POST['pickupTimeField'] != "")
        $_SESSION['pickupTimeFieldValue'] = $_POST['pickupTimeField'];
    if (isset($_POST['customerEmailAddressField']) && $_POST['customerEmailAddressField'] != "")
        $_SESSION['customerEmailAddressValue'] = $_POST['customerEmailAddressField'];
}

//Sending email address to the customer
function sendEmailToCustomer($to)
{
    $subject = "Your booking request with CabsOnline!";
    $message = $_SESSION['ConfirmationMessage'];
    $headers = "From booking@cabsonline.com.au";
    mail($to, $subject, $message, $headers, "-r 1234567@student.swin.edu.au");
}

?>

<html>
<head>
    <title>CabsOnline</title>
</head>
<body>
<h1>Register to CabsOnline</h1>
<p>Please fill the fields below to book a taxi.</p>
<form action="booking.php" method="post">
    <table>
        <tr>
            <td>Passenger name:</td>
            <td><input type="text" name="passengerNameField"
                       value="<?php if (isset($_SESSION['passengerNameFieldValue'])) echo $_SESSION['passengerNameFieldValue'] ?>">
            </td>
            <td><?php if (isset($_SESSION['passengerNameFieldRequired'])) echo "<p style='color:red;'>Passenger name is required</p>"; ?> </td>
        </tr>
        <tr>
            <td>Contact phone of the passenger:</td>
            <td><input type="text" name="passengerPhoneNumberField"
                       value="<?php if (isset($_SESSION['passengerPhoneNumberFieldValue'])) echo $_SESSION['passengerPhoneNumberFieldValue'] ?>">
            </td>
            <td><?php if (isset($_SESSION['passengerPhoneNumberFieldRequired'])) echo "<p style='color:red;'>Contact phone of the passenger is required</p>"; ?> </td>
        </tr>
        <tr>
            <td>Pick up address:</td>
            <td>
                Unit number: <input type="text" name="unitNumberField"
                                    value="<?php if (isset($_SESSION['unitNumberFieldValue'])) echo $_SESSION['unitNumberFieldValue'] ?>">


                <br/>Street number: <input type="text" name="streetNumberField"
                                           value="<?php if (isset($_SESSION['streetNumberFieldValue'])) echo $_SESSION['streetNumberFieldValue'] ?>">
                <?php if (isset($_SESSION['streetNumberFieldRequired'])) echo "<p style='color:red;'>Street number is required</p>"; ?>

                <br/> Street name: <input type="text" name="streetNameField"
                                          value="<?php if (isset($_SESSION['streetNameFieldValue'])) echo $_SESSION['streetNameFieldValue'] ?>">
                <?php if (isset($_SESSION['streetNameFieldRequired'])) echo "<p style='color:red;'>Street name is required</p>"; ?>

                <br/> Suburb: <input type="text" name="suburbField"
                                     value="<?php if (isset($_SESSION['suburbFieldValue'])) echo $_SESSION['suburbFieldValue'] ?>">
                <?php if (isset($_SESSION['suburbFieldRequired'])) echo "<p style='color:red;'>Suburb is required</p>"; ?>
            </td>
        </tr>
        <tr>
            <td>Destination suburb:</td>
            <td><input type="text" name="destinationSuburbField"
                       value="<?php if (isset($_SESSION['destinationSuburbFieldValue'])) echo $_SESSION['destinationSuburbFieldValue'] ?>">
            </td>
            <td><?php if (isset($_SESSION['destinationSuburbFieldRequired'])) echo "<p style='color:red;'>Destination suburb is required</p>"; ?> </td>
        </tr>
        <tr>
            <td>Pickup date: (Y-m-d)</td>
            <td><input type="text" name="pickupDateField"
                       value="<?php if (isset($_SESSION['pickupDateFieldValue'])) echo $_SESSION['pickupDateFieldValue'] ?>">
            </td>
            <td><?php if (isset($_SESSION['pickupDateFieldRequired'])) echo "<p style='color:red;'>Pickup date is required</p>"; ?> </td>
        </tr>
        <tr>
            <td>Pickup time: (H:i:s)</td>
            <td><input type="text" name="pickupTimeField"
                       value="<?php if (isset($_SESSION['pickupTimeFieldValue'])) echo $_SESSION['pickupTimeFieldValue'] ?>">
            </td>
            <td><?php if (isset($_SESSION['pickupTimeFieldRequired'])) echo "<p style='color:red;'>Pickup time is Required</p>"; ?> </td>
        </tr>
        <tr>
            <td><input name="customerEmailAddressField" type="hidden"
                       value="<?php if (isset($_SESSION['customerEmailAddressValue'])) echo $_SESSION['customerEmailAddressValue'] ?>">
            </td>
        </tr>
        <tr>
            <td><input type="submit" name="submitField" value="Book"/></td>
        </tr>
    </table>
</form>

<!--Showing error and success message-->
<?php if (isset($_SESSION['pickupTimeInterValErrorMessage']) && $_SESSION['pickupTimeInterValErrorMessage']) echo "<p style='color:red;'>Expand the Pick-up date/time. Pick-up date/time must be at least 1 hour after the current date/time.</p></br>"; ?>
<?php if (isset($_SESSION['customerEmailValidationErrorMessage']) && $_SESSION['customerEmailValidationErrorMessage']) echo "<p style='color:red;'>Customer email address not found.</p></br>"; ?>
<?php if (isset($_SESSION['ConfirmationMessage'])) echo "<p style='color:green;'>" . $_SESSION['ConfirmationMessage'] . "</p></br>"; ?>


<?php
//unsetting session
session_unset();
?>
</body>
</html>