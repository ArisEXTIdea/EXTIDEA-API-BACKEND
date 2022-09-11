<?php 

namespace App\Models;

use CodeIgniter\Model;

class MenmoM extends Model{
    protected $table = 'users';
    protected $allowedFields  = ['uid', 'email', 'name', 'given_name', 'profile_picture', 'created_at', 'updated_at', 'user_type'];


    public function postData($data){
        $this->insert($data);
    }

    public function getData(){
        return $this->findAll();
    }

    public function getDataId($uid){
        $this->where('uid', $uid);
        return $this->findAll();
    }

    public function getDataEmail($email){
        $this->where('email', $email);
        return $this->findAll();
    }
    
    public function putUser($uid, $requestData){
        $keys = array_keys($requestData);
        for($i = 0; $i < count($keys); $i++){
            $this->set($keys[$i], $requestData[$keys[$i]]);
        }
        $this->where('uid', $uid);
        $this->update();
    }

    public function deleteUser($uid){
        $this->where('uid', $uid);
        $this->delete();
    }
}