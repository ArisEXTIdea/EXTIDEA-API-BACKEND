<?php 

namespace App\Models;

use CodeIgniter\Model;

class FimonDebtsM extends Model{
    protected $table = 'debts';
    protected $allowedFields  = ['debt_id', 'uid', 'client_name', 'debt_type', 'nominal', 'note', 'debt_date', 'max_payment_date', 'debt_title'];


    public function postData($data){
        $this->insert($data);
    }

    public function getData(){
        return $this->findAll();
    }

    public function getDataAllId($uid){
        $this->where('uid', $uid);
        return $this->findAll();
    }

    public function getDataBy($filter, $value, $uid){
        $this->where($filter, $value);
        $this->where('uid', $uid);
        return $this->findAll();
    }
    
    public function putData($trx, $requestData){
        $keys = array_keys($requestData);
        for($i = 0; $i < count($keys); $i++){
            $this->set($keys[$i], $requestData[$keys[$i]]);
        }
        $this->where('debt_id', $trx);
        $this->update();
    }

    public function deleteData($trx){
        $this->where('trx', $trx);
        $this->delete();
    }
}