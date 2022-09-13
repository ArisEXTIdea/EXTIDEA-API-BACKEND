<?php 

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

use App\Models\MenmoUsersM;
use App\Models\MenmoTransactionM;
use CodeIgniter\RESTful\ResourceController;

class Menmo extends ResourceController{

    use ResponseTrait;

    protected $MenmoUsersM;
    protected $MenmoTransactionM;

    public function __construct()
    {
        $this->MenmoUsersM = new MenmoUsersM;
        $this->MenmoTransactionM = new MenmoTransactionM;

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

            if(!$this->MenmoUsersM->postData($data)){
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
            if($this->MenmoUsersM->getData()){
                $respond = [
                    'message' => 'Success - Get All User Data',
                    'data' => $this->MenmoUsersM->getData()
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
            if($this->MenmoUsersM->getDataId($uid)){
                $respond = [
                    'message' => 'Success - Get User Data',
                    'data' => $this->MenmoUsersM->getDataId($uid)
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
            if($this->MenmoUsersM->getDataEmail($email)){
                $respond = [
                    'message' => 'Success - Get User Data',
                    'data' => $this->MenmoUsersM->getDataEmail($email)
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
            if(!$this->MenmoUsersM->putUser($uid, $requestData)){
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
            if($this->MenmoUsersM->getDataId($uid)){

                $this->MenmoUsersM->deleteUser($uid);

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
                'trx_category' => $this->request->getPost('trx_category'),
                'created_at' => $this->request->getPost('created_at'),
                'updated_at' => $this->request->getPost('updated_at'),
                'note' => $this->request->getPost('note'),
            ];

            if(!$this->MenmoTransactionM->postData($data)){
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
            if($this->MenmoTransactionM->getData()){
                $respond = [
                    'message' => 'Success - Get All Transaction Data',
                    'data' => $this->MenmoTransactionM->getData()
                ];
                return $this->respond($respond, 200);;
            } else {
                return $this->fail('Request Failed', 400);
            }
        }
    }

    public function getTransactionBy(){
        $apiToken = $this->request->header('Api-Key')->getValue();
        $uri = explode('/', $_SERVER['PHP_SELF']);
        $value = end($uri);
        $filter = $uri[count($uri) - 2];

        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            if($this->MenmoTransactionM->getDataBy($filter, $value)){
                $respond = [
                    'message' => 'Success - Get Transaction Data',
                    'data' => $this->MenmoTransactionM->getDataBy($filter, $value)
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
            if(!$this->MenmoTransactionM->putData($trx, $requestData)){
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
        $uri = explode('/', $_SERVER['PHP_SELF']);
        $trx = end($uri);

        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            if($this->MenmoTransactionM->getDataBy('trx',$trx)){

                $this->MenmoTransactionM->deleteData($trx);

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