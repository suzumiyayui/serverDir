<?php

class Math extends CI_Model {

    protected $Allorders; //数组类型 存放所有的资料
    protected $WinNumber; //中奖号码
    protected $SmallNumber; //小数判定数  如果小于这个数 即判断小数中奖.
    //中奖属性判断群
    public $bingoSmallNumber;    //小数
    public $bingoSingleNumber;   //单数
    public $bingoSmallAndSingle; //小单
    public $bingoBigAndSingle;   //大单
    public $bingoMinimal;        //极小
    public $bingoSmash;

    public function __construct() {
        parent::__construct();

        $this->initBaseData();  //初始化判定数据
    }

    protected function initBaseData() {

        $this->SmallNumber = 14;
    }

    public function initWithOrdersAndWinNumber($Allorders, $WinNumber) {

        //初始化数据
        $this->Allorders = $Allorders;
        $this->WinNumber = $WinNumber;




        //分析中奖结果
        if (!$this->WinNumber == NULL)
            $this->judgeWinTypeWithWinNumber($this->WinNumber);



        //输出中奖结果


        $msg = "中奖号码是 '{$WinNumber}' "
                . ", 小数:'{$this->bingoSmallNumber}'"
                . ", 单数:'{$this->bingoSingleNumber}'"
                . ", 小单:'{$this->bingoSmallAndSingle}'"
                . ", 大单:'{$this->bingoBigAndSingle}'"
                . ", 极小:'{$this->bingoMinimal}'"
                . ", 大数:'" . !$this->bingoSmallNumber . ""
                . ", 双数:'" . !$this->bingoSingleNumber . ""
                . ", 小双:'" . !$this->bingoBigAndSingle . ""
                . ", 大双:'" . !$this->bingoSmallAndSingle . ""
                . ", 极大:'" . !$this->bingoSmash . "";




        print_r(json_encode(array("msg" => $msg)));

        
        exit;



        //判断中奖结果

        $this->CheckBingoWithOrders($this->Allorders);
    }

    protected function judgeWinTypeWithWinNumber($winNumber) {


        //小数判定

        if ($winNumber < $this->SmallNumber) {

            $this->bingoSmallNumber = 1;
        } else {

            $this->bingoSmallNumber = 0;
        }

        //单数判定

        if ($winNumber % 2) {

            $this->bingoSingleNumber = 1;
        } else {

            $this->bingoSingleNumber = 0;
        }


        //小单判定

        if ($this->bingoSingleNumber && $this->bingoSmallNumber) {


            $this->bingoSmallAndSingle = 1;
        } else {

            $this->bingoSmallAndSingle = 0;
        }

        //大单判定


        if (!$this->bingoSingleNumber && $this->bingoSmallNumber) {


            $this->bingoBigAndSingle = 1;
        } else {

            $this->bingoBigAndSingle = 0;
        }


        //极小判定


        $this->bingoMinimal = 0;

        //极大判定
        $this->bingoSmash = 0;
    }

}
