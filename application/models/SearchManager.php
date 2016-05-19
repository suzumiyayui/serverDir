<?php

class SearchManager extends CI_Model {

    protected $BASE_REQUEST_URL;

    public function __construct() {
        parent::__construct();

        //     http://stu.baidu.com/n/similar?queryImageUrl=$FileName&querySign=2395150167%2C1690228435&t=1461143665864&pn=10&rn=100&sort=
    }

    public function Request_Api($image) {

        if ($image == NULL)
            exit;


        $Url = "http://image.baidu.com/n/pc_search?queryImageUrl=$image&querySign=&simid=&fm=searchresult&pos=&uptype=paste";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_URL, $Url);

        $output = curl_exec($ch);        
        
        curl_close($ch);

        $HttpCode = $this->rule("<li class=\"source-card-topic source-card-same-data\">", "</li>", $output); 
        
        
        $Allobj = array();
        
        for($i = 0 ; $i < count($HttpCode) ; $i++){
           
            
            
                    
            $objSingle['img']   = str_replace("amp;", "", $this->rule("style=\"background:url\('", "'\)", $HttpCode[$i],TRUE));
            
            $objSingle['title'] = $this->rule("<div class=\"source-card-topic-content\">", "</div>", $HttpCode[$i],TRUE);
                  
            $objSingle['source'] = $this->rule("<a class=\"source-card-topic-title-link\" href=\"", "\"", $HttpCode[$i],TRUE);
            
            array_push($Allobj,$objSingle);
        
            
        }
      

        return $Allobj;      
                
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



