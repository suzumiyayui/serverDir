<?php

class Mydir extends CI_Model {

    public $BaseDirPath; //用户根目录
    public $ImagesList;  //目录下遍历图片数组
    public $ImageArray;  //图片地址
    public $ImageIconArray; //缩略图地址
    public $UsedCapacity;
    protected $FileManager;
    protected $BaseDirPathCache;
    protected $BaseDirPathIcon;   //缩略图根目录
    protected $UserId;

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('image_lib');
    }

    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //初始化目录系统资源
    public function initDirWithUid($UserId) {

        if ($UserId)
            $UserId = md5($UserId);

        if (!$this->setPathWithUserId($UserId)) {
            echo "初始化失败";
            return FALSE;
        };
        $this->getDiyImageArray();
    }

    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //初始目录
    protected function setPathWithUserId($UserId) {

        if ($UserId) {
            $this->BaseDirPath = ROOTPATH . "imagesBase/{$UserId}";
            $this->BaseDirPathCache = ROOTPATH . "imagesBase/{$UserId}@Cache";
            $this->BaseDirPathIcon = ROOTPATH . "imagesBase/{$UserId}@Icon";

            if (!is_dir($this->BaseDirPath)) {
                mkdir($this->BaseDirPath, 0777);
                mkdir($this->BaseDirPathCache, 0777);
                mkdir($this->BaseDirPathIcon, 0777);
            }
            $this->FileManager = opendir($this->BaseDirPath);
            $this->UserId = $UserId;

            return TRUE;
        }

        return FALSE;
    }

    //
    //
    //
    //    
    //      
    //
    //
    //
    //
    //
    //遍历资源
    protected function getDiyImageArray() {

        $FileDirSteam = dir($this->BaseDirPath);       

        while ($file = $FileDirSteam->read()) {

            $filename = $this->BaseDirPath . "/" . $file;

            if ($file != "." && $file != "..") {

                $files[filectime($filename)] = $file;
            }
        }
        

        foreach ($files as $key => $value) {

            $FlieKeys[] = $key;
        }
        //创建时间排序
        sort($FlieKeys);
    
        foreach ($FlieKeys as $key) {
            
         

            $SortFiles[] = $files[$key];
        }

        $this->ImagesList = $SortFiles;
        


        $totalsize = 0;

        foreach ($this->ImagesList as $singleImage) {   
            
            $this->ImageArray[] = "http://".$_SERVER['HTTP_HOST'] . "/imagesBase/{$this->UserId}/" . $singleImage;
            $this->ImageIconArray[] = "http://".$_SERVER['HTTP_HOST'] . "/imagesBase/{$this->UserId}@Icon/" . $singleImage;
            $totalsize += @filesize($this->BaseDirPath . "/" . $singleImage);
        }

        $this->UsedCapacity = $totalsize;
    }

    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //添加上传方法,制作省资源缩略图
    public function AddImageWithUpload($image) {
        

        

        $imageName = $this->rule("\?id=", "\&ext", $image["name"]);
//        
        $ext = $info = $this->rule("\&ext=", "\.", $image["name"]);

        if ($image['error'])
            return;

        $loaclimagePath = $this->BaseDirPath . "/" . $imageName[0] . "." . $ext[0];
        $loaclimagePathIcon = $this->BaseDirPathIcon . "/" . $imageName[0] . "." . $ext[0];

        move_uploaded_file($image["tmp_name"], $loaclimagePath);


        $this->CIResizeImageSize($loaclimagePath, $loaclimagePathIcon);
    }

    protected function CIResizeImageSize($loaclimagePath, $loaclimagePathIcon) {
        /*
          注意
          当$config['create_thumb']等于FALSE并且$config['new_image']没有指定时，会调整原图的大小
          当$config['create_thumb']等于TRUE并且$config['new_image']没有指定时，生成文件名为(原图名 _thumb.扩展名)
          当$config['create_thumb']等于FALSE并且$config['new_image']指定时，生成文件名为$config['new_image']的值
          当$config['create_thumb']等于TRUE并且$config['new_image']指定时，生成文件名为(原图名 _thumb.扩展名)
         */
        $config['image_library'] = 'gd2'; //(必须)设置图像库
        $config['source_image'] = $loaclimagePath; //(必须)设置原始图像的名字/路径
        $config['dynamic_output'] = FALSE; //决定新图像的生成是要写入硬盘还是动态的存在
        $config['quality'] = '30%'; //设置图像的品质。品质越高，图像文件越大
        $config['new_image'] = $loaclimagePathIcon; //设置图像的目标名/路径。
        $config['width'] = 150; //(必须)设置你想要得图像宽度。
        $config['height'] = 150; //(必须)设置你想要得图像高度
        $config['create_thumb'] = TRUE; //让图像处理函数产生一个预览图像(将_thumb插入文件扩展名之前)
        $config['thumb_marker'] = ''; //指定预览图像的标示。它将在被插入文件扩展名之前。例如，mypic.jpg 将会变成 mypic_thumb.jpg
        $config['maintain_ratio'] = TRUE; //维持比例
        $config['master_dim'] = 'auto'; //auto, width, height 指定主轴线
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
    }
  /*
   * 
   * 
   * 
   *
   * 
   * 
   * 
   *  
   * 
   * 
   * 
   * 
   *  删除相册图片  
   * removeImagesWithName  
   */
    
    
  public function removeImagesWithName($imageName) {
      
      
      if(!$this->BaseDirPath){
          
           $array = array("status"=>"0","info"=>"没设置必要参数");
           
           
           return $array;
         
          }
      
    // echo file_exists("test.txt");
          $RemoveImgPath = $this->BaseDirPath . "/" . $imageName;
          $RemoveImgPathIcon = $this->BaseDirPathIcon . "/" . $imageName;
          
      
          
         if(file_exists($RemoveImgPath)){
             
         $NewRemoveImgPath  = $this->BaseDirPathCache . "/" .  $imageName ;
             
             
             if(rename($RemoveImgPath,$NewRemoveImgPath)){
                 
                 unlink($RemoveImgPathIcon);
                 
                 
                 $array = array("status"=>"1","info"=>"删除成功");
               
             }else{
                 
                 $array = array("status"=>"0","info"=>"删除没能成功,请检查权限");
                 
             }
             
             
             
         }else{
             
            $array = array("status"=>"0","info"=>"文件不存在");
            
         }
         
         
         return $array;
      
      
      
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
