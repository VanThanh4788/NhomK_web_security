<?php
session_start();
require_once 'models/UserModel.php';
$userModel = new UserModel();

$user = NULL; //Add new user
$id = NULL;
echo $_SESSION['tokenDel'];
if (!empty($_GET['id']) && $_GET['tokenDel'] == $_SESSION['tokenDel']) {
    $id = $_GET['id'];
    $userModel->deleteUserById($id);//Delete existing user
}
header('location: list_users.php');
?>