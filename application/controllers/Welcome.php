<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Mydir');
        $this->Mydir->initDirWithUid('MEMBERDIR1');
    }

    public function index() {

//   print_r($this->Mydir->BaseDirPath);
////
//        print_r($this->Mydir->ImageArray);
////
//        print_r($this->Mydir->ImageIconArray);
////    
//        print_r($this->Mydir->UsedCapacity);
        
    }

    public function test() {
      
  $str = "asset.JPG?id=AFD17A98-9BDD-4EBE-AF07-C96CA5D12A22&ext=JPG";
        
        $info = $this->rule("\&ext", ".", $str);
        
        
        print_r($info);
        
    }

    public function testUpload() {
        
    }
    
    
      protected function rule($start, $end, $from, $type = NULL) {

        if ($type != NULL) {

            preg_match("|$start([^^]*?)$end|u", $from, $return_array);
        } else {

            preg_match_all("|$start([^^]*?)$end|u", $from, $return_array);
        }


        return $return_array[1];
    }
    
    

}
