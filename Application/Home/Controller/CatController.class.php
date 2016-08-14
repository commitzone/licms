<?php
namespace Home\Controller;
use Think\Controller;
class CatController extends CommonController {
    public function index(){
        $id=intval($_GET['id']);
        if(!$id){
            return $this->error('Id不存在');
        }

        $nav=D("Menu")->find($id);
        if(!$nav || $nav['status']!=1){
            return $this->error('栏目id不存在或者状态不正常');
        }
        $rankNews=$this->getRank();
        $advNews=D("PositionContent")->select(array('status'=>1,'position_id'=>4),2);

        $page=$_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize=20; //每页显示的个数
        $conds=array(
            'stauts'=>1,
            'thumb'=>array('neq',''),
            'catid'=>$id,
        );
        $news=D('News')->getNews($conds,$page,$pageSize);
        $count=D('News')->getNewsCount($conds);

        $res =new \Think\Page($count,$pageSize);
        $pageres=$res->show();
        $this->assign('result',array(
            'advNews'=>$advNews,
            'rankNews'=>$rankNews,
            'catId'=>$id,
            'listNews'=>$news,
            'pageres'=>$pageres,
        ));
        $this->display();
    }
}