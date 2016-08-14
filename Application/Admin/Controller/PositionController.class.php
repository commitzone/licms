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

class PositionController extends Controller{

    public function index(){
        $pos=array();
        if($_GET['id']){
            $pos['id']=$_GET['id'];
        }
        //$pos['status']=array('neq',-1);
        $page=$_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize=2; //每页显示的个数

        $position=D('Position')->getPosition($pos,$page,$pageSize);
        $count=D('Position')->getPosCount($pos);

        $res =new \Think\Page($count,$pageSize);
        $pageres=$res->show();

        $this->assign('positions',$position);
        $this->assign('pageres',$pageres);

        $this->display();
    }

    public function add(){

        //对所要提交的数据进行判断
        if($_POST){
            if(!isset($_POST['id']) || !$_POST['id']){
                return show(0,'id不能为空');
            }
            if(!isset($_POST['name']) || !$_POST['name']){
                return show(0,'推荐位名称不能为空');
            }
            if(!isset($_POST['description']) || !$_POST['description']){
                return show(0,'描述不能为空');
            }
            //print_r($_POST);exit;
            if($_POST['id']) {
                return $this->save($_POST);
            }
        $posId=D("Position")->insert($_POST);

            if(isset($posId)){
                return show(1,'新增成功');
            }
            return show(1,'新增失败');
        }else{
            $this->display();
        }
    }

    public function edit(){
        //获取id所在的值
        $posId=$_GET['id'];
        //调用方法找到记录
        $position=D("Position")->find($posId);
        //模板上显示
        $this->assign('position',$position);
        $this->display();
    }

    public function save($data){
        //获取id所在的值
        $posId=$data['id'];
        unset($data['id']);
        //调用方法找到记录
        try{
            $position=D("Position")->updatePositionById($posId,$data);
            if($position===false){
                   return show(0,'更新失败');
            }
            return show(1,'更新成功');
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
    }

    public function  setStatus(){
        try{
            if($_POST){
                $id=$_POST['id'];
                $status=$_POST['status'];
                if(!$id){
                    return show(0,'ID不存在');
                }
                $res=D("Position")->updateStatusById($id,$status);

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


}

