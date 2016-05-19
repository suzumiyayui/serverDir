<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class api extends CI_Controller {

    function __construct() {
        parent::__construct();
        header("content-type:application/json;charset-utf8;");
        $this->load->model('Mydir');
        $this->load->model('Member');
        $this->load->model('SearchManager');
        // $this->Mydir->initDirWithUid('VipMember001');
      
       
    }

    public function index() {

//   print_r($this->Mydir->BaseDirPath);
//
//        print_r($this->Mydir->ImageArray);
//
//        print_r($this->Mydir->ImageIconArray);
//    
//        print_r($this->Mydir->UsedCapacity);
//        
    }

    public function login() {


        $username = $this->input->get('username');
        $password = $this->input->get('password');

        $Member = $this->Member->login($username, $password);

        if ($Member->memberId) {

            $return = array("status" => "1", "msg" => "登入成功!", "UIDStr" => $Member->memberUIDStr);
        } else {

            $return = array("status" => "0", "msg" => "账号或密码错误!");
        };

        print_r(json_encode($return));
    }

    public function uploadImages() {

        $uidStr = $this->input->post('uid');

        if (!$uidStr) {

            print_r(json_encode(array("msg" => "missSomeThing")));

            exit;
        }

        $this->Mydir->initDirWithUid($uidStr);


        $this->Mydir->AddImageWithUpload($_FILES['UploadFile']);
        //  print_r(json_encode($array));
    }

    public function getDirInfo() {
        
        $uidStr = $this->input->get('uid');

        if (!$uidStr) {

            print_r(json_encode(array("msg" => "missSomeThing")));

            exit;
        }
        
        $this->Mydir->initDirWithUid($uidStr);
        
        $return = array (
            
            "BaseDirPath"=>$this->Mydir->BaseDirPath,
            
            "ImageName"  =>  $this->Mydir->ImagesList,
            
            "ImageArray"=>$this->Mydir->ImageArray,
            
            "ImageIconArray"=>$this->Mydir->ImageIconArray,
            
            "UsedCapacity" => $this->Mydir->UsedCapacity
            
        );

        print_r(json_encode($return));
    }
    
    
    
    public function SearchImg() {  //Pubilic
           
          
       $imageUrl = $this->input->get('ImgUrlEncode');
        
       if (!$imageUrl) {
            exit;
        }

        $info = $this->SearchManager->Request_Api($imageUrl);

        
        print_r(json_encode($info));
        
    }
    
    
    public function removeImg(){
        
        $ImgName = $this->input->get('removeImgName');
        $uidStr  = $this->input->get('uid');
        
        if($ImgName&&$uidStr){
            
         $this->Mydir->initDirWithUid($uidStr);
         
         $info = $this->Mydir->removeImagesWithName($ImgName);
    
         print_r(json_encode($info));
            
        }
        
        
    }
    
    
   

}
