<?php

require_once 'BaseModel.php';

class UserModel extends BaseModel {

    public function findUserById($id) {
        $sql = 'SELECT * FROM users WHERE id = '.$id;
        $user = $this->select($sql);

        return $user;
    }

    public function findUser($keyword) {
        $sql = 'SELECT * FROM users WHERE user_name LIKE %'.$keyword.'%'. ' OR user_email LIKE %'.$keyword.'%';
        $user = $this->select($sql);
        
        return $user;
    }

    /**
     * Authentication user
     * @param $userName
     * @param $password
     * @return array
     */
    public function auth($userName, $password) {
        $md5Password = md5($password);
        $sql = 'SELECT * FROM users WHERE name = "' . htmlspecialchars($userName) . '" AND password = "'.$md5Password.'"';  
        $user = $this->select($sql);
        echo $sql;
        return $user;
    }
        // $connection = BaseModel::$_connection;
        // $stmt= $_connection->prepare('SELECT * FROM users WHERE name = "? AND password = ?"');
        // $stmt->bind_param("ss",$userName,$md5Password);
        // $stmt->execute;
        // $sql = $stmt->get_result();
    /**
     * Delete user by id
     * @param $id
     * @return mixed
     */
    public function deleteUserById($id) {
        $iv = $_SESSION['iv'];
        $giaiId = openssl_decrypt($id,'AES-256-CBC','NhomK',0,$iv);
        $sql = 'DELETE FROM users WHERE id = '.$giaiId;
        return $this->delete($sql);

    }

    /**
     * Update user
     * @param $input
     * @return mixed
     */
    public function updateUser($input) {
        $iv = $_SESSION['iv'];
        $giaiId = openssl_decrypt($_GET['id'],'AES-256-CBC','NhomK',0,$iv);

        $sql = 'UPDATE users SET 
                 name = "' . mysqli_real_escape_string
                 (self::$_connection,($input['name'])) .'", 
                 password="'. md5($input['password']) .'"
                WHERE id = ' . $giaiId;

        $user = $this->update($sql);
        return $user;
    }

    /**
     * Update user 
     * Dùng để demo kỹ thuật optimistic looking
     * @param $input
     * @return mixed
     */
    public function updateUserInfo($input){
        $sql = "SELECT * FROM users WHERE id = ". $input['id'] ;
        $result = $this->query($sql); 
        $row = $result->fetch_assoc();
        $currentVersion = $row["version"];
        $thongBao="";
        //giải mã dữ liệu version đã mã hóa
        $inputVersion = intval(base64_decode($input['version'],'base64'));
        echo $inputVersion;
        if($currentVersion==$inputVersion)
        {
            $inputVersion+=7;
            $sql = 'UPDATE users SET 
                 name = "' . mysqli_real_escape_string(self::$_connection,htmlspecialchars($input['name'])) .'", 
                 fullname="'. $input['fullname'].'",
                 email="'. $input['email'] . '",
                 type="'. $input['type'] . '",
                 version = "'. $inputVersion . '"
                WHERE id = ' . $input['id'];
            $user = $this->update($sql);
            $thongBao='Đổi dữ liệu thành công';
        }
        else
        {
            $thongBao="Đổi dữ liệu thất bại";
        }   

        return ($thongBao);
    }
    /**
     * Insert user
     * @param $input
     * @return mixed
     */
    public function insertUser($input) {
        $sql = "INSERT INTO `app_web1`.`users` (`id`, `name`, `fullname`,`email`, `type`, `password`)
        VALUES (Null,'" . ($input['name']) . "','','','', '".md5($input['password'])."')";
        echo($sql);
        $user = $this->insert($sql);

        return $user;
    }

    /**
     * Search users
     * @param array $params
     * @return array
     */
    public function getUsers($params = []) {
        //Keyword
        if (!empty($params['keyword'])) {
            $sql = 'SELECT * FROM users WHERE name LIKE "%' . $params['keyword'] .'%"';

            //Keep this line to use Sql Injection
            //Don't change
            //Example keyword: abcef%";TRUNCATE banks;##
            $users = self::$_connection->multi_query($sql);
        } else {
            $sql = 'SELECT * FROM users';
            $users = $this->select($sql);
        }
        return $users;
    }
}