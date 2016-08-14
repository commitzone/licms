<?php
/**
 * Created by PhpStorm.
 * User: liqm
 * Date: 2016/8/5
 * Time: 10:26
 */
namespace Common\Model;
use Think\Model;

class PositionContentModel extends Model{

    private $_db = '';
    Public function __construct(){
        $this->_db = M('position_content');
    }

    public function select( $data=array() , $limit=0){
        //模糊搜索
        if($data['title']){
            $data['title']=array('like','%'.$data['title'].'%');
        }
        $this->_db->where($data)->order('listorder desc , id desc');
        if($limit){
            $this->_db->limit($limit);
        }
        $list=$this->_db->select();
        //echo $this->_db->getLqstSql();exit;
        return $list;
    }

    public function insert($data = array()){

        if (!$data || !is_array($data)) {
            return 0;
        }
        //exit(var_dump($data));
        //$data提交的时候就没有create字段  所以需要自己添加
        $data['create_time']=time();
        //print_r($data);exit;
        return  $this->_db->add($data);
    }
    public function find($id){

       if(!$id || !is_numeric($id)){
            return array();
        }
        $data=$this->_db->where('id='.$id)->find();
        return $data;
    }

    public function updatePositionContentById($id,$data){
        if(!$id || !is_numeric($id)){
            throw_exception("ID不合法");
        }
        if(!$data || !is_array($data)){
            throw_exception("更新的数据不合法");
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


    public function updateListorderById($id,$listorder){
        if(!$id  || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        $data=array('listorder'=>intval($listorder));
        return $this->_db->where('id ='.$id)->save($data);
    }
}