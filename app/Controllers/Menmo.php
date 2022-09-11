<?php 

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

use App\Models\MenmoM;

class Menmo extends BaseController{

    use ResponseTrait;

    protected $MenmoM;

    public function __construct()
    {
        $this->MenmoM = new MenmoM;

        helper(['auth']);
    }

    // ----------------------------------------------------------------
    //  API GROUP USERS
    // ----------------------------------------------------------------

    public function post_users(){
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

            if(!$this->MenmoM->postData($data)){
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
    
    public function get_users(){
        $apiToken = $this->request->header('Api-Key')->getValue();

        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            if($this->MenmoM->getData()){
                $respond = [
                    'message' => 'Success - Get All User Data',
                    'data' => $this->MenmoM->getData()
                ];
                return $this->respond($respond, 200);;
            } else {
                return $this->fail('Request Failed', 400);
            }
        }
    }

    public function get_user_id(){

        $apiToken = $this->request->header('Api-Key')->getValue();
        $uri = explode('/', $_SERVER['PHP_SELF']);
        $uid = end($uri);

        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            if($this->MenmoM->getDataId($uid)){
                $respond = [
                    'message' => 'Success - Get User Data',
                    'data' => $this->MenmoM->getDataId($uid)
                ];
                return $this->respond($respond, 200);
            } else {
                return $this->failNotFound('Request Failed - Data not found');
            }
        }
    }

    public function put_user(){
        $apiToken = $this->request->header('Api-Key')->getValue();
        $uri = explode('/', $_SERVER['PHP_SELF']);
        $uid = end($uri);
        
        $requestData = $this->request->getRawInput();
        
        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            if(!$this->MenmoM->putUser($uid, $requestData)){
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

    public function delete_user() {
        $apiToken = $this->request->header('Api-Key')->getValue();
        $uri = explode('/', $_SERVER['PHP_SELF']);
        $uid = end($uri);

        if(!checkToken($apiToken)){
            return $this->failForbidden('Access denied');
        } else {
            if($this->MenmoM->getDataId($uid)){

                $this->MenmoM->deleteUser($uid);

                $respond = [
                    'message' => 'Success - User Removed',
                ];
                return $this->respondDeleted($respond);
            } else {
                return $this->failNotFound('Request Failed - Data not found');
            }
        }
    }

    
    

}