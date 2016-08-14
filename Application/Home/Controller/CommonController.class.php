<?php
/**
 * Created by PhpStorm.
 * User: liqm
 * Date: 2016/8/8
 * Time: 20:16
 */
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller{
    public function __construct(){
        header("Content-type:text/html;charset=utf-8");
        parent::__construct();
    }

    /*
     * @return 获取排行的数据
     */
    public function getRank(){
        $conds['status']=1;
        $news=D("News")->getRank($conds,10);
        return $news;
    }

    public function  error($message=''){
        $message=$message ? $message :'系统发生错误';
        $this->assign('message',$message);
        $this->display("Index/error");
    }
}