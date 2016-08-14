<?php
/**
 * Created by PhpStorm.
 * User: liqm
 * Date: 2016/8/9
 * Time: 16:16
 */
namespace Home\Controller;
use Think\Controller;

class DetailController extends CommonController{
    //文章最终页功能
    public function index(){
        $id=intval($_GET['id']);
        if(!$id || $id<0){
            $this->error('Id不合法');
        }

        $news=D("News")->find($id);

        if(!$news || $news['status']!=1){
            return $this->error('id不存在或者资讯被关闭');
        }

        $count=intval($news['count'])+1;
        D('News')->updateCount($id,$count);

        $content=D('NewsContent')->find($id);
        $news['content']=htmlspecialchars_decode($content['content']);//转换html字符

        $rankNews=$this->getRank();
        $advNews=D("PositionContent")->select(array('status'=>1,'position_id'=>4),2);

        $this->assign('result',array(
            'rankNews'=>$rankNews,
            'advNews'=>$advNews,
            'catId'=>$news['catId'],
            'news'=>$news,
        ));
        //加路径是为了固定访问模板
        $this->display("Detail/index");
    }

    public function view(){
        if(!getLoginUsername()){
            $this->error("您没有权限访问该页面");
        }
        $this->index();
    }

}