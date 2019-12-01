<!--ID#:620101035 Jerome Henry-->
<?php
session_start();

if ( isset( $_SESSION['id'] ) ) {
    // Grab user data from the database using the user_id
    // Let them access the "logged in only" pages
} else {
    // Redirect them to the login page
    echo "<script> loadHome(); </script>";
}
?>
<html>
<head>
  <meta charset="utf-8">
<meta name="viewport"content="width=device-width, initial-scale=1">
  <link rel='stylesheet' type='text/css' href='styles.css'>
   <script src="dynamic.js" type="text/javascript"></script>
        <link href="https://fonts.googleapis.com/css?family=Rajdhani&display=swap" rel="stylesheet">
</head>
<body>
  <header>
        <h1>BugMe Issue Tracker</h1>
      </header>

<div class ="main">
  <div class="grid">
    <div class="box sideNav">
      <a  href= "javascript:void(0)" class ="navButton" onclick="navButton()"></a>
        <li id= home><a href="home.html">Home</a></li><br>
        <li id= newUser><a href="newuser.html">Add User</a></li><br>
        <li id= create><a href="create.html">New Issue</a></li><br>
        <li id= logout><a href="logout.html">Logout</a> </li>
         <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
      </ul>
    </div>
</body>
</html>







