<?php

namespace Libs\Database;

use PDOException;

class UsersTable
{
    private $db = null;

    // "MySQL" is predefining the parameter that's parameter must be MySQL class
    //Type Hinting procedure writing
    public function __construct(MySQL $mysql)
    {
        $this->db = $mysql->connect();
    }

    public function insert($data)
    {
        try {
            $query = "INSERT INTO users(name, email, phone, address,
            password, created_at) 
            VALUE 
            (:name, :email, :phone, :address,
            :password, NOW())";

            //preparing the query for security reasons and performance
            $statement = $this->db->prepare($query);

            //execute to run the query
            $statement->execute($data);

            //in case to see last insert id
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }

    public function getAll()
    {

        //select the wanted data
        $statement = $this->db->query("SELECT users.*, roles.name as role, roles.value FROM users LEFT JOIN roles ON users.role_id = roles.value");

        //to run the query
        return $statement->fetchAll();
    }

    public function findByEmailAndPassword($email, $password)
    {
        $statement = $this->db->prepare("
        SELECT users.*, roles.name AS role, roles.value
        FROM users LEFT JOIN roles
        ON users.role_id = roles.value
        WHERE users.email = :email");

        $statement->execute([':email' => $email]);

        $user = $statement->fetch();
        if($user){
            if(password_verify($password, $user->password)){
                return $user;
            }
        }
        return false;
    }

    public function updatePhoto($id, $name)
    {
        $statement = $this->db->prepare("UPDATE users SET photo=:name WHERE id = :id");
        $statement->execute([':name' => $name, ':id' => $id]);
        return $statement->rowCount();
    }

    public function suspend($id)
    {
        $statement = $this->db->prepare("UPDATE users SET suspended=1 WHERE id = :id");
        $statement->execute([':id' => $id]);
        return $statement->rowCount();
    }
    public function unsuspend($id)
    {
        $statement = $this->db->prepare("UPDATE users SET suspended=0 WHERE id = :id");
        $statement->execute([':id' => $id]);
        return $statement->rowCount();
    }
    public function changeRole($id, $role)
    {
        $statement = $this->db->prepare("UPDATE users SET role_id = :role WHERE id = :id");
        $statement->execute([':id' => $id, ':role' => $role]);
        return $statement->rowCount();
    }

    public function delete($id)
    {
        $statement = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $statement->execute([':id' => $id]);
        return $statement->rowCount();
    }
}
