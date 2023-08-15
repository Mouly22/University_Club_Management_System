<?php
// Retrieve the variables from URL parameters
$userType = $_GET['userType'];
$designation = $_GET['designation'];
$email = $_GET['email'];
$pin = $_GET['pin'];

// Connect to the database
require_once('dbconnect.php');

// Retrieve the club name based on user's designation, email, and pin
$sql = "SELECT club,student_id FROM member WHERE designation = '$designation' AND email = '$email' AND pin = $pin";
$result = mysqli_query($conn, $sql);
if ($row = mysqli_fetch_assoc($result)) {
    $clubname = $row['club'];
    $student_id = $row['student_id'];
} else {
    // Handle error or default club name if necessary
    $clubname = 'Default Club Name';
}

// Close the database connection
//mysqli_close($conn);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('bracubackground.jpg'); /* Replace with the path to your background image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .navbar {
            position: fixed;
            top: 0;
            right: 0;
            text-align: right;
            border-bottom: 2px solid #007BFF; /* Perfect Blue border */
            background-color: #007BFF; /* Perfect Blue background */
            padding: 10px;
            width: 30%; /* Adjust the width as needed */
            box-sizing: border-box;
            z-index: 100;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        .club-info {
            text-align: center;
            padding: 50px;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .club-logo {
            width: 100px; /* Adjust the width as needed */
        }

        .club-name {
            color: white;
            font-size: 24px;
            margin-top: 10px;
        }

        .events-section {
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .ongoing-events-dev {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            color: white;
        }

        .event {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #007BFF; /* Blue background */
        }

        .join-event {
            background-color: #F8F9FA; /* Off-white */
            color: #007BFF; /* Perfect Blue text color */
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }

        .panel-messages {
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="logout.php">Logout</a>
    </div>
    
    <div class="club-info">
        <img src="bracu_logo1.png" alt="Club Logo" class="club-logo">
        <h1 class="club-name"><?php echo $clubname; ?></h1>
    </div>

    <div class="events-section">
        <h2>On Going Club Events</h2>
        <div class="ongoing-events-dev">
            <?php 
                require_once("dbconnect.php");
                $sql = "SELECT * FROM event";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_array($result)){
            ?>
            <div class="event">
                <h3><?php echo $row[1]; ?></h3>
                <p> Venue: <?php echo $row[5]; ?></p>
                <p> Date: <?php echo $row[3]; ?></p>
                <form action="joinevent.php" method="post">
                    <input type="hidden" name="eventID" value="<?php echo $row[0]; ?>">
                    <input type="hidden" name="studentID" value="<?php echo $student_id; ?>">
                    <button type="submit" class="join-event-button">Join Event</button>
                </form>
            </div>
            <?php 
                    }                    
                }
            ?>
        </div>
    </div>

    <div class="member-requests">
    <h2 class="section-heading">Incoming Member Requests</h2>
    <div class="incoming-requests">
        <?php
            // Fetch and display incoming member requests
            // Replace this with your database query and loop
            for ($i = 1; $i <= 5; $i++) {
        ?>
        <div class="request">
            <p>Request <?php echo $i; ?></p>
            <button class="accept-request">Accept</button>
            <button class="reject-request">Reject</button>
        </div>
        <?php
            }
        ?>
    </div>
</div>

<style>
    .section-heading {
        border: 1px solid rgba(0, 0, 0, 0.5);
        padding: 5px;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        margin-bottom: 10px;
    }

    .incoming-requests {
        background-color: rgba(0, 0, 0, 0.7); /* Transparent black background */
        padding: 20px;
        color: white;
    }

    .request {
        border: 1px solid rgba(0, 0, 0, 0.5); /* Transparent black border */
        padding: 10px;
        margin-bottom: 10px;
        background-color: #007BFF; /* Blue background */
    }

    .accept-request,
    .reject-request {
        background-color: #F8F9FA; /* Off-white */
        color: #007BFF; /* Perfect Blue text color */
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 3px;
        margin-right: 5px;
    }



        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            /* Add styles for table cells */
            border: 1px solid rgba(0, 0, 0, 0.5); /* Transparent black border */
            padding: 10px;
            background-color: #007BFF; /* Blue background */
            color: white;
        }

        th {
            /* Add styles for table header cells */
            background-color: rgba(0, 0, 0, 0.5); /* Transparent black background */
            color: white;
        }

        .edit-member {
            /* Add styles for the edit button */
            background-color: #F8F9FA; /* Off-white */
            color: #007BFF; /* Perfect Blue text color */
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }




</style>



<div class="search-member">
        <h2 class="section-heading">Search Member</h2>
        <div class="member-table">
            <table>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Email</th>
                    <th>Date of Birth</th>
                    <th>Department</th>
                    <th>Gender</th>
                    <th>PIN</th>
                    <th>Contact No</th>
                    <th>Edit</th>
                </tr>
                <?php 
                require_once("dbconnect.php");
                $sql = "SELECT * FROM member";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_array($result)){
            ?>
                <tr>
                    <td><?php echo $row[0]; ?></td>
                    <td><?php echo $row[1]; ?></td>
                    <td><?php echo $row[2]; ?></td>
                    <td><?php echo $row[3]; ?></td>
                    <td><?php echo $row[4]; ?></td>
                    <td><?php echo $row[5]; ?></td>
                    <td><?php echo $row[6]; ?></td>
                    <td><?php echo $row[8]; ?></td>
                    <td><?php echo $row[9]; ?></td>
                    <td><button class="edit-member">Edit</button></td>
                </tr>
                <?php
}
                    }
                ?>
            </table>
        </div>
    </div>

<div class="post-message">
    <h2>Post a Message</h2>
    <div class="message-form">
        <form action="postmessage.php" method="post">
            <textarea name="message" rows="4" cols="50" placeholder="Type your message here"></textarea>
            <button type="submit" class="post-message-button">Post Message</button>
        </form>
    </div>
</div>
</body>
</html>
