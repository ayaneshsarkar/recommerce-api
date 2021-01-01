<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 01/01/2021
     */

    namespace App\Models;

    use App\Models\Model;

    /**
     * Class User
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    class User extends Model {

        public function get() {
            $query = "SELECT * FROM users ORDER BY created_at DESC";
            $users = $this->db->query($query)->fetchAll();

            return $users;
        }

    }