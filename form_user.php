<?php
// Start the session
session_start();
require_once 'models/UserModel.php';
$userModel = new UserModel();

// $kiemTraUser =  $_SESSION['id'];
// echo $kiemTraUser;
// if($kiemTraUser!=$_GET['id']){
//     echo "<script type='text/javascript'>alert('Bạn không có quyền sử dụng form này');</script>";
//     // quay lại trang trước đó
//     $previous_page = $_SERVER['HTTP_REFERER'];
//     header("refresh:0;url=$previous_page");
// }
$user = NULL; //Add new user
$_id = NULL;

if (!empty($_GET['id'])) {
    $iv = $_SESSION['iv'];
    $giaiId = openssl_decrypt($_GET['id'],'AES-256-CBC','NhomK',0,$iv);
    $user = $userModel->findUserById($giaiId);
}



if (!empty($_POST['submit'])) {
    // Kiểm tra token được gửi tới có bằng với $_SESSION['token'] hay không
    if( $_SESSION['token'] == $_POST['token'] ){
        if (!empty($giaiId)) {
            $userModel->updateUser($_POST);
        } else {
            $userModel->insertUser($_POST);
        }
        header('location: list_users.php');
    }
    else{
        // thông báo
        echo "<script type='text/javascript'>alert('Không thể thực hiện hành vi');</script>";
        // quay lại trang trước đó
        $previous_page = $_SERVER['HTTP_REFERER'];
        header("refresh:0;url=$previous_page");
    }     
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>User form</title>
    <?php include 'views/meta.php' ?>
</head>
<body>
    <?php include 'views/header.php';
    $token = md5(uniqid());
    // echo $token?>
     
    <div class="container">

            <?php if ($user || !isset($_id)) { ?>
                <div class="alert alert-warning" role="alert">
                    User form
                </div>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php if(!empty($_GET['id'])){echo $_GET['id'];}; ?>">
                    <input type="hidden" name="token" value="<?php echo $token ?>">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input class="form-control" name="name" placeholder="Name" value='<?php if (!empty($user[0]['name'])) echo $user[0]['name'] ?>'>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <?php $_SESSION['token']=$token;
                    // echo $_SESSION['token']?>
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