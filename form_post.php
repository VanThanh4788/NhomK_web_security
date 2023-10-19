<?php
// Start the session
session_start();
require_once 'models/PostModel.php';
$postModel = new PostModel();

$post = NULL; //Add new post
$_id = NULL;

if (!empty($_GET['id'])) {
    $_id = $_GET['id'];
    $post = $postModel->findPostById($_id);//Update existing post
}


if (!empty($_POST['submit'])) {

    if (!empty($_id)) {
        $postModel->updatePost($_POST);
    } else {
        $postModel->insertPost($_POST);
    }
    header('location: list_posts.php');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>post form</title>
    <?php include 'views/meta.php' ?>
</head>
<body>
    <?php include 'views/header.php'?>
    <div class="container">
            <?php if ($post || !isset($_id)) { ?>
                <div class="alert alert-warning" role="alert">
                    post form
                </div>
                <form method="POST">
                <input type="hidden" name="version" value="<?php if (!empty($post[0]['version'])) echo $post[0]['version'] ?>">
                    <input type="hidden" name="id" value="<?php echo $_id ?>">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input class="form-control" name="name" placeholder="Name" value='<?php if (!empty($post[0]['name'])) echo $post[0]['name'] ?>'>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>

                    <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                </form>
            <?php } else { ?>
                <div class="alert alert-success" role="alert">
                    post not found!
                </div>
            <?php } ?>
    </div>
</body>
</html>