<?php
      require_once("dbconnect.php"); // Assuming dbconnect.php contains database connection code
      
    $updateResult = FALSE; 
    $updateTotalResult = FALSE;
    $eventID = $_GET['event_id'];
    $sponsorEmail = $_GET['email'];
    
        // Fetch event details based on event_id and update content here...
    $eventSql = "SELECT * FROM event WHERE event_id = $eventID";
    $eventResult = mysqli_query($conn, $eventSql);

    if (mysqli_num_rows($eventResult) > 0) {
        $eventRow = mysqli_fetch_assoc($eventResult);
        $eventName = $eventRow['name'];
        $eventCost = $eventRow['cost'];
        $eventDate = $eventRow['date'];
        $eventLocation = $eventRow['vanue'];
        $clubName = $eventRow['club_name'];
        $moneyReceived = $eventRow['money_received'];
        

        } else {
          echo "<p>No event found.</p>";
        }
      

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve the form data
        $sponsorLevel = $_POST["sponsor_level"];
        $amount = $_POST["amount"];
        //header("Location: donate_submission.php?sponsorLevel=$sponsorLevel&amount=$amount");

        
        $checkQuery = "SELECT * FROM funding_request WHERE sponsor_email = '$sponsorEmail' and event = '$eventName'";
        $checkResult = $conn->query($checkQuery);

        $sql = "SELECT SUM(amount) AS total_amount FROM funding_request WHERE event = '$eventName'";
          $result = mysqli_query($conn, $sql);
          if(mysqli_num_rows($result) > 0){
              while($row = mysqli_fetch_array($result))
              $donation = $row[0];
          }

        if ($checkResult->num_rows > 0) {
          
          if ($moneyReceived+$amount<=$eventCost){
            $updateQuery = "UPDATE funding_request 
                            SET amount = amount + $amount
                            WHERE Sponsor_email = '$sponsorEmail' AND event = '$eventName'";
            $updateResult = $conn->query($updateQuery);
            $updateTotalFund = "UPDATE sponsor
                            SET funding = funding+$amount
                            WHERE email = '$sponsorEmail'";
        $updateTotalResult = $conn->query($updateTotalFund);
          }
          else{
            echo 'Donation exceeds event cost. Please choose a lesser amount.';
          }
        }
        else{
          if ($moneyReceived + $amount<=$eventCost){
            $insertQuery = "INSERT INTO funding_request (Sponsor_email, event, amount)
                            VALUES ('$sponsorEmail', '$eventName', $amount)";
            $updateResult = $conn->query($insertQuery); 
            $updateTotalFund = "UPDATE sponsor
                            SET funding = funding+$amount
                            WHERE email = '$sponsorEmail'";
        $updateTotalResult = $conn->query($updateTotalFund);
          }
          else{
            echo 'Donation exceeds event cost. Please choose a lesser amount.';
          }              
            }
        
        
        if ($updateResult === TRUE && $updateTotalResult === TRUE) {
            echo "Donation recorded successfully.";
        }
        else {
                echo "Error updating record: " . $conn->error;
            }
        }
        $sql = "SELECT SUM(amount) AS total_amount FROM funding_request WHERE event = '$eventName'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result))
            $donation = $row[0];
        }
    $ratio = $moneyReceived/$eventCost*100;

    $presiSql = "Select email,contact_no from member where designation= 'president' and club= '$clubName'";
    $presiResult = mysqli_query($conn,$presiSql);
    
    $row = mysqli_fetch_array($presiResult);
    $presiEmail = $row[0];
    $presiContact = $row[1];
    

    ?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sponsor View</title>
</head>
<style>
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-image: url('img/bracubackground.jpg'); /* Replace with the path to your background image */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
  }
  .navbar {
    position: fixed;
    top: 0;
    right: 0;
    text-align: right;
    border-bottom: 2px solid #4169E1; 
    background-color: #808080;
    padding: 10px;
    width: 5%;
    box-sizing: border-box;
    z-index: 100;
}
  header {
    background-color: rgb(50, 92, 112);
    color: white;
    text-align: center;
    padding: 10px 0;
  }
  .container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    border-radius: 9px;
    background-color: rgba(255, 255, 255, 0.9);
  }
  .event {
    padding: 20px;
    border: 1px solid #ddd;
    margin-bottom: 20px;
    background-color: rgb(50, 92, 112);
    color: white;
  }
  .event h2 {
    margin-top: 0;
  }
  .sponsor-container {
      display: flex;
      flex-direction: row;
      justify-content: space-around;
      align-items: flex-start;
    }

    .sponsor-box {
      border: 1px solid #ccc;
      width: 350px; 
      height: 250px; 
      margin-bottom: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .benefits {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: 10px;
    }

    .tick {
      margin-right: 5px;
    }

    .donate-button {
      margin-top: 20px;
    }

    .sponsor-level {
      font-weight: bold;
    }
  .sponsor-logo {
    max-width: 100px;
    height: auto;
    margin: 10px auto;
  }
  .donation-progress {
    margin-top: 20px;
    color: white;
  }
  .contact-info {
    margin-top: 20px;
    color: white;
  }
  .donate-button {
            background-color: #F8F9FA; /* Off-white */
            color: #007BFF; /* Perfect Blue text color */
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
            margin-right: 5px;
  }
  .footer {
    text-align: center;
    margin-top: 20px;
    padding: 10px;
    background-color: rgb(50, 92, 112);
    color: white;
  }
</style>

<body>
<div class="navbar">
        
        <a href="logout.php">Log out</a>
  </div>
  <header>
    <h1>University Management Platform</h1>
    <p>Sponsor View</p>
  </header>
  <div class="container">
    <div class="event">
      <h2>Upcoming Event: <?php echo $eventName; ?></h2>
      <p>Date: <?php echo $eventDate; ?></p>
      <p>Location: <?php echo $eventLocation; ?></p>
      <p>Hosted by: <?php echo $clubName; ?></p>
      <div class="sponsor-container">
      <div class="sponsor-box">
      <div class="sponsor-level">Gold Sponsor:</div>
      
      <div class="benefits">
        <div class="tick">&#10003; Logo placement</div>
        <div class="tick">&#10003; Promotion advertisements in the MM screen</div>
        <div class="tick">&#10003; Company booth in the venue</div>
        <div class="tick">&#10003; Special Guest in the main programmes</div>
      </div>
      <br>
      <div>10000 BDT</div>
      <form method="post" action="">
    <input type="hidden" name="sponsor_level" value="Gold Sponsor">
    <input type="hidden" name="amount" value="10000">
    <div class="donate-button">
        <button type="submit">Donate</button>
    </div>
</form>
    </div>

    <div class="sponsor-box">
      <div class="sponsor-level">Silver Sponsor:</div>
      
      <div class="benefits">
        <div class="tick">&#10003; Logo placement</div>
        <div class="tick">&#10003; Speaking opportunity</div>
      </div>
      
      <br>
      <br>
      <br>
      <div>4000 BDT</div>
      <form method="post" action="">
    <input type="hidden" name="sponsor_level" value="Silver Sponsor">
    <input type="hidden" name="amount" value="4000">
    <div class="donate-button">
        <button type="submit">Donate</button>
    </div>
</form>
    </div>

    <div class="sponsor-box">
      <div class="sponsor-level">Bronze Sponsor:</div>
      
      <div class="benefits">
        <div class="tick">&#10003; Logo placement</div>
      </div>
      
      <br>
      <br>
      <br>
      <br>
      <div>1000 BDT</div>
      
      <form method="post" action="">
    <input type="hidden" name="sponsor_level" value="Bronze Sponsor">
    <input type="hidden" name="amount" value="1000">
    <div class="donate-button">
        <button type="submit">Donate</button>
    </div>
</form>
    </div>
</div>
      <div class="donation-progress">
        <p>Donation Progress: <?php echo $moneyReceived; ?> BDT / <?php echo $eventCost; ?> BDT</p>
        <div style="border: 1px solid #ddd; height: 10px; width: 75%; margin: 10px auto;">
          <div style="background-color: #3498db; height: 100%; width: <?php echo $ratio; ?>%;"></div>
        </div>
      </div>
      <div class="contact-info">
        <p>Contact: <?php echo $presiEmail;?></p>
        <p>Phone: <?php echo $presiContact;?></p>
      </div>
    </div>
  </div>
  <div class="footer">
    <p>&copy; 2023 University Management Platform. All rights reserved.</p>
  </div>
</body>
</html>