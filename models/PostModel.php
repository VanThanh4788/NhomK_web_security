<?php

require_once 'BaseModel.php';

class PostModel extends BaseModel {

    public function findPostById($id) {
        $sql = 'SELECT * FROM posts WHERE id = '.$id;
        $post = $this->select($sql);

        return $post;
    }

    public function findPost($keyword) {
        $sql = 'SELECT * FROM posts WHERE post_name LIKE %'.$keyword.'%'. ' OR post_email LIKE %'.$keyword.'%';
        $post = $this->select($sql);

        return $post;
    }

    /**
     * Authentication post
     * @param $postName
     * @param $password
     * @return array
     */
    public function auth($postName, $password) {
        $md5Password = md5($password);
        $sql = 'SELECT * FROM posts WHERE name = "' . $postName . '" AND password = "'.$md5Password.'"';

        $post = $this->select($sql);
        return $post;
    }

    /**
     * Delete post by id
     * @param $id
     * @return mixed
     */
    public function deletePostById($id) {
        $sql = 'DELETE FROM posts WHERE id = '.$id;
        return $this->delete($sql);

    }

    /**
     * Update post
     * @param $input
     * @return mixed
     */
    public function updatePost($input) {
        $sql = 'UPDATE posts SET 
                 name = "' . mysqli_real_escape_string(self::$_connection, $input['name']) .'", 
                 password="'. md5($input['password']) .'"
                WHERE id = ' . $input['id'];

        $post = $this->update($sql);

        return $post;
    }

    /**
     * Insert post
     * @param $input
     * @return mixed
     */
    public function insertPost($input) {
        $sql = "INSERT INTO `app_web1`.`posts` (`id`, `name`, `fullname`, `email`, `type`, `password`) VALUES (Null,'" . $input['name'] . "','','','', '".md5($input['password'])."')";
        echo($sql);
        $post = $this->insert($sql);

        return $post;
    }

    /**
     * Search posts
     * @param array $params
     * @return array
     */
    public function getPosts($params = []) {
        //Keyword
        if (!empty($params['keyword'])) {
            $sql = 'SELECT * FROM posts WHERE name LIKE "%' . $params['keyword'] .'%"';

            //Keep this line to use Sql Injection
            //Don't change
            //Example keyword: abcef%";TRUNCATE banks;##
            $posts = self::$_connection->multi_query($sql);
        } else {
            $sql = 'SELECT * FROM posts';
            $posts = $this->select($sql);
        }

        return $posts;
    }
}