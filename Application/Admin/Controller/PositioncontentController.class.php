<?php
/**
 * Created by PhpStorm.
 * User: liqm
 * Date: 2016/8/5
 * Time: 10:14
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

class PositioncontentController extends CommonController{
    public function index(){
        //获取推荐位标识
        $positions=D("Position")->getNormalPositions();
        //获取推荐位里面的内容
        $data['status']=array('neq','-1');
        if($_GET['title']){
            $data['title']=trim($_GET['title']);
            $this->assign('title',$data['title']);
        }
        //获取推荐位内容，有就返回，没有就获取当前第一条信息
        $data['position_id']=$_GET['position_id']?intval($_GET['position_id']):$position[0]['id'];
        $contents=D("PositionContent")->select($data);
        $this->assign('positions',$positions);
        $this->assign('contents',$contents);
        $this->assign('positionId',$data['position_id']);//使搜索固定的语句/**/
        $this->display();
    }

    public function add(){
        if($_POST){
            if(!isset($_POST['position_id']) || !$_POST['position_id']){
                return show(0,'推荐位id不能为空');
            }
            if(!isset($_POST['title']) || !$_POST['title']){
                return show(0,'推荐位标题不能为空');
            }
            if(!$_POST['url'] && !$_POST['news_id']){
                return show(0,'url和文章id不能同时为空');
            }
            if(!isset($_POST['thumb']) || !$_POST['thumb']){
                if($_POST['news_id']) {
                    $res = D("News")->find($_POST['news_id']);
                    if ($res && is_array($res)) {
                        $_POST['thumb'] = $res['thumb'];
                    }
                }else{
                    return show(0,'图片不能为空');
                }
            }
            if($_POST['id']){
                return $this->save($_POST);
            }
            try{
                $id=D("PositionContent")->insert($_POST);
                if($id){
                   return show(1,'新增成功');
                }
                return show(0,'新增失败');
            }catch(Exception $e){
                return show(0,$e->getMessage());
            }
        }else{
            $positions=D("Position")->getNormalPositions();
            $this->assign('positions',$positions);
            $this->display();
        }
    }
      public function edit(){
          //获取地址栏中的id
        $id=$_GET['id'];
        $position=D("PositionContent")->find($id);
        $positions=D("Position")->getNormalPositions();
        $this->assign('positions',$positions);
        $this->assign('vo',$position);
        $this->display();
    }


    public function save($data){
        $id=$data['id'];
        unset($data['id']);

        try{
            $resId=D("PositionContent")->updatePositionContentById($id,$data);
            if($resId===false){
                return show(0,'更新失败');
            }
            return show(1,'更新成功');
        }catch(Exception $e){
            return  show(0,$e->getMessage());
        }
    }

    /*public function setStatus(){

        $data=array(
            'id'=> intval($_POST['id']),
            'status'=>intval($_POST['status']),
        );
        return parent::setStatus($data,'PositionContent');*/

    public function  setStatus(){
        try{
            if($_POST){
                $id=$_POST['id'];
                $status=$_POST['status'];
                if(!$id){
                    return show(0,'ID不存在');
                }
                $res=D("PositionContent")->updateStatusById($id,$status);

                if($res){
                    return show(1,'操作成功');
                }else{
                    return show(0,'操作失败');
                }
            }
            return show(0,'没有提交的内容');
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
    }

    public function listorder(){
        $listorder = $_POST['listorder'];
        $jumpUrl = $_SERVER['HTTP_REFERER'];//跳转url相当于http中的referer
        $error = array();
        //print_r($listorder);exit;
        try {
            if ($listorder) {
                foreach ($listorder as $voId => $v) {
                    //执行更新
                    $id = D('PositionContent')->updateListorderById($voId, $v);
                    if ($id === false) {
                        $error[] = $voId;
                    }
                }
                if ($errors) {
                    //失败返回
                    return show(0, '排序失败-' . implode(',', $errors), array('jump_url' => $jumpUrl));
                }
                return show(1, '排序成功', array('jump_url' => $jumpUrl));
            }
        } catch (Exception $e) {
            return show(0, $e->getMessage());
        }
        return show(0, '排序数据失败', array('jump_url' => $jumpUrl));
    }

    /*public function listorder(){
        return parent::listorder("PositionContent");
    }*/
}



