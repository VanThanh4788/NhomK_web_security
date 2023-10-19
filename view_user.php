<?php
require_once 'models/UserModel.php';
$userModel = new UserModel();

$user = NULL; //Add new user
$id_get = NULL;
$thong_bao=Null;

if (!empty($_GET['id'])) {
    $id_get = $_GET['id'];
    $user = $userModel->findUserById($id_get);//Update existing user
}     
    if (!empty($_POST['submit'])) {
        
        if (!empty($id_get)) {
        $thong_bao=$userModel->updateUserInfo($_POST);  
    } else {
        $userModel->insertUser($_POST);
    }        
    header("Refresh:0"); 
    // header('location: list_users.php');
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>User form</title>
    <?php include 'views/meta.php' ?>
</head>
<style>
    th{
        text-align: center;
    }
    table {
            width: 95%;
            border-collapse: collapse;
            margin: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        td.editable {
            cursor: pointer;
        }

        td.editable input {
            width: 100%;
        }
</style>
<body>
    
<?php include 'views/header.php';
?>
<?php
echo($id_get);
?>
<div class="container">

    <?php if ($user || empty($id_get)) {
        if($thong_bao){
            echo "<script type='text/javascript'>alert('$thong_bao');</script>";
        }
        ?>
        
        <div class="alert alert-warning" role="alert">
            User profile
        </div>
        <form method="POST">
            <!-- <div class="form-group">
                <label for="name">Name</label>
                <span><?php if (!empty($user[0]['name'])) echo $user[0]['name'] ?></span>
            </div>
            <div class="form-group">
                <label for="password">Fullname</label>
                <span><?php if (!empty($user[0]['fullname'])) echo $user[0]['fullname'] ?></span>
            </div>
            <div class="form-group">
                <label for="password">Email</label>
                <span><?php if (!empty($user[0]['email'])) echo $user[0]['email'] ?></span>
            </div> -->
    <table>
        <tr>
            <th>Name</th>
            <th>Fullname</th>
            <th>Email</th>
            <th>Type</th>
        </tr>
        <tr>
            <input type="hidden" name="version" value="<?php if (!empty($user[0]['version'])) echo base64_encode($user[0]['version']) ?>">
            <input type="hidden" name="id" value="<?php echo $id_get ?>">
            <td><input class="form-control" name="name"  value='<?php if (!empty($user[0]['name'])) echo $user[0]['name'] ?>'></td>
            <td><input class="form-control" name="fullname" value='<?php if (!empty($user[0]['fullname'])) echo $user[0]['fullname'] ?>'></td>
            <td><input class="form-control" name="email"  value='<?php if (!empty($user[0]['email'])) echo $user[0]['email'] ?>'></td>
            <td><input class="form-control" name="type"  value='<?php if (!empty($user[0]['type'])) echo $user[0]['type'] ?>'></td>
        </tr>   
    </table>
    <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
    </form>
    <?php } else { ?>
        <div class="alert alert-success" role="alert">
            User not found!
        </div>
    <?php } ?>
</div>

</body>
</html>