<?php
/**
 * Created by PhpStorm.
 * User: liqm
 * Date: 2016/8/5
 * Time: 10:26
 */
namespace Common\Model;
use Think\Model;
class PositionModel extends Model{

    private  $_db='';

    Public function __construct(){
        $this->_db=M('position');
    }

    public function insert($data=array()){
        if(!$data || !is_array($data)){
            return 0;
        }else{
            return $this->_db->add($data);
        }
    }

    public function getPosition($data,$page,$pageSize=10){
        //分别按标题以及栏目进行赋值，搜索
        $data['status']=array('neq',-1);
        $conditions=$data;
        if(isset($data['id']) && $data['id']){
            $conditions['id']=intval($data['id']);
        }
        //$data['status']=array('neq',-1);//定义当前状态是开启或关闭
        $offset=( $page - 1)*$pageSize;//取得起始数据的位置
        $list=$this->_db->where($conditions)
            ->order('id desc')
            ->limit($offset,$pageSize)
            ->select();
        return $list;
    }


    public function  getPosCount($data=array()){
        //$data['status']=array('neq',-1);
        $conditions=$data;
        if(isset($data['id']) && $data['id']){
            $conditions['id']=intval($data['id']);
            $conditions['status']=array('neq',-1);
        }
        //展示的数量
        return $this->_db->where($conditions)->count();
    }



    public function updatePositionById($id,$data){
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!$data || !is_array($data)){
            throw_exception('更新数据不合法');
        }
        return $this->_db->where('id='.$id)->save($data);
    }

    public function updateStatusById($id,$status){
        if(!is_numeric($status)){
            throw_exception('status不能为非数字');
        }
        if(!is_numeric($id)|| !$id){
            throw_exception('id不合法');
        }
        $data['status']=$status;

        return $this->_db->where('id='.$id)->save($data);
    }

    //编辑模式下获取主表文章内容
    public  function find($id){
        //先对数据进行判断在获取$data
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        $data=$this->_db->where('id='.$id)->find();
        return $data;
    }

    public function getNormalPositions(){
        $conditions=array('status'=>1);
        $list=$this->_db->where($conditions)
            ->order('id')
            ->select();
        return $list;
    }

    public function getCount($data=array()) {
        $conditions = $data;
        $list = $this->_db->where($conditions)->count();

        return $list;
    }

}