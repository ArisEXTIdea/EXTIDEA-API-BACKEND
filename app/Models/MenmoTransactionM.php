<?php 

namespace App\Models;

use CodeIgniter\Model;

class MenmoTransactionM extends Model{
    protected $table = 'transactions';
    protected $allowedFields  = ['trx', 'uid', 'trx_name', 'category', 'trx_category', 'created_at', 'updated_at', 'note'];


    public function postData($data){
        $this->insert($data);
    }

    public function getData(){
        return $this->findAll();
    }

    public function getDataBy($filter, $value){
        $this->where($filter, $value);
        return $this->findAll();
    }
    
    public function putData($trx, $requestData){
        $keys = array_keys($requestData);
        for($i = 0; $i < count($keys); $i++){
            $this->set($keys[$i], $requestData[$keys[$i]]);
        }
        $this->where('trx', $trx);
        $this->update();
    }

    public function deleteData($trx){
        $this->where('trx', $trx);
        $this->delete();
    }
}