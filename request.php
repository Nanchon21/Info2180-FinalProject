<?php

session_start();
if($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Location: ../index.php");
}

$host = getenv('IP');
$username = 'user';
$password = 'Pword';
$dbname = 'schema';

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['email'];
    $user = filter_var($user, FILTER_SANITIZE_EMAIL);
    $pass = $_POST['password'];
    $pass = strip_tags($pass);
    
    if (filter_var($user, FILTER_VALIDATE_EMAIL) && $pass != "") {
        $stmt = $conn->query("SELECT id, email, password, firstname FROM Users WHERE email = '$user';");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pass = hash("sha256", $pass);
        if(hash_equals($pass, $result[0]['password'])) {
            $_SESSION['user_id'] = $result[0]['id'];
            $_SESSION['name'] = $result[0]['firstname'];
        }
    }
}


$temp = $_GET['context'];
$context = strip_tags($temp);


if($context == "Open") {
    $stmt = $conn->query("SELECT id, title, type, status, assigned_to, created FROM Issues WHERE status = '$context';");
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if($context == "mine") {
    if (isset($_SESSION['user_id'])) {
        $sid = $_SESSION['user_id'];
        $stmt = $conn->query("SELECT id, title, type, status, assigned_to, created FROM Issues WHERE created_by = '$sid';");
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $context = 'nul';
    }
}

if($context == "nul") {
    $stmt = $conn->query("SELECT id, title, type, status, assigned_to, created FROM Issues;");
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if($context != "") {
    $stmt = $conn->query("SELECT id, firstname, lastname FROM Users;");
    $list2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
}

if(is_numeric($context)) {
    $stmt = $conn->query("SELECT * FROM Issues WHERE id = '$context';");
    $list3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if($context == "des"){
    session_unset();
    session_destroy();
    echo "Logged out";
}

if($context == "mClosed"){
    $ident = $_GET['num'];
    $ident = strip_tags($ident);
    $date2 = date("Y-m-d H:i:s");
    $stmt = $conn->query("UPDATE Issues SET Status = 'Closed', updated = '$date2' WHERE id = '$ident';");
    
}

if($context == "mInProg"){
    $ident2 = $_GET['num'];
    $ident2 = strip_tags($ident2);
    $date3 = date("Y-m-d H:i:s");
    $stmt = $conn->query("UPDATE Issues SET Status = 'In Progress', updated = '$date3' WHERE id = '$ident2';");
}



if($_SERVER["REQUEST_METHOD"] == "POST") {
    $fn = $_POST['fname'];
    $fn = strip_tags($fn);
    $ln = $_POST['lname'];
    $ln = strip_tags($ln);
    $pass = $_POST['password'];
    $pass = strip_tags($pass);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    if($fn != '' && filter_var($email, FILTER_VALIDATE_EMAIL) && $pass !='') {
        $stmt = $conn->query("SELECT * FROM Users ORDER BY id DESC LIMIT 1;");
        $last = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $last = $last[0]['id'] + 1;
        $date = date('Y-m-d');
        if ($_SESSION['user_id'] == '1') {
            $stmt = $conn->query("INSERT INTO Users VALUES ($last,'$fn','$ln', SHA2('$pass', 256),'$email', $date);");
        }
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $tit = $_POST['title'];
    $tit = strip_tags($tit);
    $description = $_POST['description'];
    $description = strip_tags($description);
    $assigned = $_POST['assigned'];
    $assigned = strip_tags($assigned);
    $type = $_POST['type'];
    $type = strip_tags($type);
    $priority = $_POST['priority'];
    $priority = strip_tags($priority);
    $default = "Open";
    $date1 = date("m-D-y H:i:s");
    
    if (isset($_SESSION['user_id'])  && $tit != "") {
        $stmt = $conn->query("SELECT * FROM Issues ORDER BY id DESC LIMIT 1;");
        $lastq = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $lastq = $lastq[0]['id'] + 1;
        $tempid = $_SESSION['user_id'];
        $stmt = $conn->query("INSERT INTO Issues VALUES ('$lastq','$tit','$description','$type','$priority','$default','$assigned','$tempid','$date1','$date1');");
    }
    
}


?>

<?php if($context == "nul" || $context == "Open" || $context == "mine") : ?>
    <h1>Issues</h1>
    <h4>Filter By:    <button type="button" id="all" onclick='loadHome()'>All</button><button type="button" id="open" onclick='loadO()'>Open</button><button type="button" id="mine" onclick='loadM()'>My Tickets</button></h4>
    <table style="width:100%">
      <tr>
        <th>Title</th>
        <th>Type</th>
        <th>Status</th>
        <th>Assigned To</th>
         <th>Created</th>
      </tr>
      <?php foreach ($list as $row): ?>
      <tr>
        <td>#<?= $row['id']; ?> <a href="javascript:linkPress(<?= $row['id'] ?>)"><?= $row['title']; ?></a></td>
        <td><?= $row['type']; ?></td>
        <td><?= $row['status']; ?></td>
        <?php foreach ($list2 as $row2): ?>
            <?php if($row['assigned_to'] == $row2['id']) : ?>
                <td><?= $row2['firstname']." ".$row2['lastname']; ?></td>
            <?php endif; ?>
        <?php endforeach; ?>
        <td><?= $row['created']; ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php if(is_numeric($context)) : ?>
    <?php foreach ($list3 as $row3): ?>
    <div class="details">
            <div class="mainStuff">
                <h1><?= $row3['title']; ?></h1>
                <h6>Issue #<?= $row3['id']; ?></h6>
                <p><?= $row3['description']; ?></p><br>
                <?php foreach ($list2 as $row2): ?>
                        <?php if($row3['created_by'] == $row2['id']) : ?>
                            <p>Issue created on <?= $row3['created']; ?> by <?= $row2['firstname']." ".$row2['lastname']; ?></p>
                        <?php endif; ?>
                <?php endforeach; ?>
                <p>Issue updated on <?= $row3['updated']; ?> </p>
            </div>
            <div class="synopsis">
                <?php foreach ($list2 as $row2): ?>
                        <?php if($row3['assigned_to'] == $row2['id']) : ?>
                            <p>Assigned To:<br> <?= $row2['firstname']." ".$row2['lastname']; ?></p>
                        <?php endif; ?>
                <?php endforeach; ?>
                <p>Type:<br> <?= $row3['type']; ?> </p>
                <p>Priority:<br> <?= $row3['priority']; ?> </p>
                <p>Status:<br> <?= $row3['status']; ?> </p>
                <button type="button" onclick="markClosed(<?= $row3['id'] ?>)">Mark as Closed</button>
                <button type="button" onclick="markInProg(<?= $row3['id'] ?>)">Mark In Progress</button>
            </div>
        </div>
    <?php endforeach; ?>    
<?php endif; ?>

<?php if($context == "users") : ?>
    <?php foreach ($list2 as $row2): ?>
        <?php $temp = $row2['firstname']." ".$row2['lastname']; ?>
        <option value= "<?= $row2['id'] ?>"><?= $temp; ?></option>
    <?php endforeach; ?>    
<?php endif; ?>
