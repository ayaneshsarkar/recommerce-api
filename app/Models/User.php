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

        public function primaryKey()
        {
            return 'id';
        }

        public function tableName()
        {
            return 'users';
        }

        public function getUser(object $data)
        {
            $query = "SELECT * FROM users WHERE email = :email";
            $statement = $this->db->prepare($query);
            $statement->execute([ 'email' => $data->email ]);

            return $statement->fetch();
        }

        public function get() {
            return $this->select()->orderBy('created_at', true)->getAll();
        }

        public function first(?int $id) {
            if($id) {
                return $this->select()->where('id', $id)->orderBy('created_at', true)
                        ->getFirst();
            } else {
                return NULL;
            }
        }

        public function checkMail(string $email) {
            $query = "SELECT * FROM users WHERE email = :email";
            $statement = $this->db->prepare($query);
            $statement->execute([ 'email' => $email ]);
            return $statement->fetch();
        }

        public function create(object $data)
        {
            $query = 
                "INSERT INTO users(first_name, last_name, email, password, 
                    avatar, address, city, state, country, date_of_birth, token, type)
                VALUES(:first_name, :last_name, :email, :password, :avatar, :address, :city, 
                    :state, :country, :date_of_birth, :token, :type)";
            $this->db->prepare($query)->execute([
                'first_name' => $data->first_name,
                'last_name' => $data->last_name,
                'email' => $data->email,
                'password' => password_hash($data->password, PASSWORD_BCRYPT),
                'avatar' => $data->avatar ?? NULL,
                'address' => $data->address ?? NULL,
                'city' => $data->city,
                'state' => $data->state,
                'country' => $data->country,
                'date_of_birth' => $data->date_of_birth,
                'token' => $data->token ?? NULL,
                'type' => $data->type ?? NULL
            ]);
        }

        public function update(object $data, object $user)
        {
            $query = 
                "UPDATE users SET 
                    first_name = :first_name, last_name = :last_name, email = :email, 
                    avatar = :avatar, address = :address, city = :city, state = :state, 
                    country = :country, date_of_birth = :date_of_birth, token = :token, 
                    type = :type
                WHERE id = :id";
            $this->db->prepare($query)->execute([
                'id' => $data->id,
                'first_name' => $data->first_name,
                'last_name' => $data->last_name,
                'email' => $data->email,
                'avatar' => $data->avatar,
                'address' => $data->address,
                'city' => $data->city,
                'state' => $data->state,
                'country' => $data->country,
                'date_of_birth' => $data->date_of_birth,
                'token' => $user->token,
                'type' => $data->type ?? NULL
            ]);
        }

        public function delete(?int $id)
        {
            if($id) {
                $query = "DELETE FROM users WHERE id = :id";
                $this->db->prepare($query)->execute([ 'id' => $id ]);
            }
        }

        public function authenticate(object $data): object
        {
            $user = $this->getUser($data);

            if(empty($user)) {
                return (object)[ 'auth' => FALSE, 'user' => NULL ];
            }

            $verification = password_verify($data->password, $user->password);

            if($verification) {
                return (object)['auth' => TRUE, 'user' => $user];
            } else {
                return (object)['auth' => FALSE, 'user' => NULL];
            }
        }

    }