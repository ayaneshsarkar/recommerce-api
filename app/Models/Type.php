<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 20/02/2021
     */

    namespace App\Models;
    /**
     * Class Type
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */

    class Type extends Model {

        public function primaryKey()
        {
            return 'id';
        }

        public function tableName()
        {
            return 'book_types';
        }

        public function get()
        {
            return $this->select()->orderBy('created_at')->getAll();
        }

    }