<?php
if (isset($_GET['message'])) {
    $message = urldecode($_GET['message']);
    echo '<h2 style="text-align: center; background-color: rgba(0, 0, 0, 0.7); color: white; padding: 10px;">' . $message . '</h2>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">

</head>
<body background="img/bracubackground.jpg">
	<div class="body-content">
		<div class="module">
			<form name="fm" name ="myForm" action="signin.php" method="POST">
			<h2>BRAC University <br> Club Management System</h2>
			<center><img src="img/bracu_logo.png"></center>

			<div class="sgn">Sign In with User Type <br> Designation, Email & Password</div>
            <br>

            <label for="userType">User Type:</label>
            <select id="userType" name="userType">
				<option value="">Select Type</option selected>
                <option value="member">Member</option>
                <option value="advisor">Advisor</option>
                <option value="department">Department</option>
                <option value="oca">OCA Admin</option>
				<option value="sponsor">Sponsor</option>
            </select>

			<input type="text" name="designation" id ="designation" placeholder="Designation" required>

			<input type="text" name="email" id ="email" placeholder="Email" required>

			<input type="password" name="pin" id="pin" placeholder="Password" required>

			<input type="submit" name="login_user" value="Sign in" class="btn btn-block btn-primary">

			<div class="join">
			<span id="c">or, Create Account</span>
			<a href="register.php">Sign up</a>
			
			
			</form>
		</div>
	</div>
</body>
</html>
