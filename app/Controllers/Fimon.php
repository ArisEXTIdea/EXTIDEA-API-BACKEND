<?php 

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

use App\Models\FimonUsersM;
use App\Models\FimonTransactionM;
use CodeIgniter\RESTful\ResourceController;

class Fimon extends ResourceController{

    use ResponseTrait;

    protected $FimonUsersM;
    protected $FimonTransactionM;

    public function __construct()
    {
        $this->FimonUsersM = new FimonUsersM;
        $this->FimonTransactionM = new FimonTransactionM;

        helper(['auth']);

    }

    // ----------------------------------------------------------------
    //  API GROUP USERS
    // ----------------------------------------------------------------

    public function postUser(){
        $apiToken = $this->request->header('Api-Key')->getValue();

        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            $data = [
                'uid' => 'uid-' . uniqid() . date('Y') . uniqid(),
                'email' => $this->request->getPost('email'),
                'name' => $this->request->getPost('name'),
                'given_name' => $this->request->getPost('given_name'),
                'profile_picture' => $this->request->getPost('profile_picture'),
                'created_at' => $this->request->getPost('created_at'),
                'updated_at' => $this->request->getPost('updated_at'),
                'user_type' => $this->request->getPost('user_type'),
            ];

            if(!$this->FimonUsersM->postData($data)){
                $respond = [
                    'message' => 'Success - User Created',
                    'data' => $data
                ];
                return $this->respondCreated($respond);
            } else {
                return $this->fail('Request Failed', 400);
            }
        }
    }
    
    public function getUsers(){
        $apiToken = $this->request->header('Api-Key')->getValue();

        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            if($this->FimonUsersM->getData()){
                $respond = [
                    'message' => 'Success - Get All User Data',
                    'data' => $this->FimonUsersM->getData()
                ];
                return $this->respond($respond, 200);;
            } else {
                return $this->fail('Request Failed', 400);
            }
        }
    }

    public function getUserId(){

        $apiToken = $this->request->header('Api-Key')->getValue();
        $uri = explode('/', $_SERVER['PHP_SELF']);
        $uid = end($uri);

        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            if($this->FimonUsersM->getDataId($uid)){
                $respond = [
                    'message' => 'Success - Get User Data',
                    'data' => $this->FimonUsersM->getDataId($uid)
                ];
                return $this->respond($respond, 200);
            } else {
                return $this->failNotFound('Request Failed - Data not found');
            }
        }
    }

    public function getUserEmail(){

        $apiToken = $this->request->header('Api-Key')->getValue();
        $uri = explode('/', $_SERVER['PHP_SELF']);
        $email = end($uri);

        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            if($this->FimonUsersM->getDataEmail($email)){
                $respond = [
                    'message' => 'Success - Get User Data',
                    'data' => $this->FimonUsersM->getDataEmail($email)
                ];
                return $this->respond($respond, 200);
            } else {
                return $this->failNotFound('Request Failed - Data not found');
            }
        }
    }

    public function putUser(){
        $apiToken = $this->request->header('Api-Key')->getValue();
        $uri = explode('/', $_SERVER['PHP_SELF']);
        $uid = end($uri);
        
        $requestData = $this->request->getRawInput();
        
        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            if(!$this->FimonUsersM->putUser($uid, $requestData)){
                $respond = [
                    'message' => 'Success - User data Updated',
                    'data' => $requestData
                ];

                return $this->respondUpdated($respond);
            } else {
                return $this->failNotFound('Request Failed - Data not found', 400);
            }
        }

    }

    public function deleteUser() {
        $apiToken = $this->request->header('Api-Key')->getValue();
        $uri = explode('/', $_SERVER['PHP_SELF']);
        $uid = end($uri);

        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            if($this->FimonUsersM->getDataId($uid)){

                $this->FimonUsersM->deleteUser($uid);

                $respond = [
                    'message' => 'Success - User Removed',
                ];
                return $this->respondDeleted($respond);
            } else {
                return $this->failNotFound('Request Failed - Data not found');
            }
        }
    }

    // ----------------------------------------------------------------
    //  API GROUP TRANSACTIONS
    // ----------------------------------------------------------------

    public function postTransaction(){
        $apiToken = $this->request->header('Api-Key')->getValue();

        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            $data = [
                'trx' => 'trx-' . uniqid() . date('Y') . uniqid(),
                'uid' => $this->request->getPost('uid'),
                'trx_name' => $this->request->getPost('trx_name'),
                'category' => $this->request->getPost('category'),
                'nominal' => $this->request->getPost('nominal'),
                'trx_date' => $this->request->getPost('trx_date'),
                'note' => $this->request->getPost('note'),
            ];

            if(!$this->FimonTransactionM->postData($data)){
                $respond = [
                    'message' => 'Success - Transaction Created',
                    'data' => $data
                ];
                return $this->respondCreated($respond);
            } else {
                return $this->fail('Request Failed', 400);
            }
        }
    }

    public function getTransactions(){
        $apiToken = $this->request->header('Api-Key')->getValue();

        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            if($this->FimonTransactionM->getData()){
                $respond = [
                    'message' => 'Success - Get All Transaction Data',
                    'data' => $this->FimonTransactionM->getData()
                ];
                return $this->respond($respond, 200);;
            } else {
                return $this->fail('Request Failed', 400);
            }
        }
    }

    public function getTransactionBy(){
        $apiToken = $this->request->header('Api-Key')->getValue();
        $uid = $this->request->header('User-Id')->getValue();
        $uri = explode('/', $_SERVER['PHP_SELF']);
        $value = end($uri);
        $filter = $uri[count($uri) - 2];

        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            if($this->FimonTransactionM->getDataBy($filter, $value, $uid)){
                $respond = [
                    'message' => 'Success - Get Transaction Data',
                    'data' => $this->FimonTransactionM->getDataBy($filter, $value, $uid)
                ];
                return $this->respond($respond, 200);
            } else {
                return $this->failNotFound('Request Failed - Data not found');
            }
        }
    }

    public function putTransaction(){
        $apiToken = $this->request->header('Api-Key')->getValue();
        $uri = explode('/', $_SERVER['PHP_SELF']);
        $trx = end($uri);
        
        $requestData = $this->request->getRawInput();
        
        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            if(!$this->FimonTransactionM->putData($trx, $requestData)){
                $respond = [
                    'message' => 'Success - Transaction data Updated',
                    'data' => $requestData
                ];

                return $this->respondUpdated($respond);
            } else {
                return $this->failNotFound('Request Failed - Data not found', 400);
            }
        }

    }

    public function deleteTransaction() {
        $apiToken = $this->request->header('Api-Key')->getValue();
        $uid = $this->request->header('User-Id')->getValue();

        $uri = explode('/', $_SERVER['PHP_SELF']);
        $trx = end($uri);

        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            if($this->FimonTransactionM->getDataBy('trx',$trx, $uid)){

                $this->FimonTransactionM->deleteData($trx);

                $respond = [
                    'message' => 'Success - Data Removed',
                ];
                
                return $this->respondDeleted($respond);
            } else {
                return $this->failNotFound('Request Failed - Data not found');
            }
        }
    }
    
}