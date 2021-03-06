<?php
/**
 * Created by PhpStorm.
 * User: liqm
 * Date: 2016/7/30
 * Time: 13:05
 */
namespace Common\Model;
use Think\Model;

class  MenuModel extends Model{

    private $_db='';
    public function __construct(){
        $this->_db= M('menu');
    }

    public function insert($data=array()){
        if(!$data || !is_array($data)){
            return 0;
        }else{
            return $this->_db->add($data);
        }
    }

    public function getMenus($data,$page,$pageSize=10){
        //分页的参数
        $data['status']=array('neq',-1);
        $offset=($page-1)* $pageSize;//取得起始数据的位置
        $list=$this->_db->where($data)->order('listorder desc,menu_id desc')->limit($offset,$pageSize)->select();
        return $list;
    }

    public function  getMenuCount($data=array()){
        //展示的数量
        $data['status']=array('neq',-1);//定义不为删除的数据标识为-1
        return $this->_db->where($data)->count();
    }

    public function find($id){
        if(!$id || !is_numeric($id)){
             return array();
         }
        return $this->_db->where('menu_id='.$id)->find();
     }

    public function updateMenuById($id,$data){
         if(!$id || !is_numeric($id)){
             throw_exception('ID不合法');
         }

         if(!$data || !is_array($data)){
             throw_exception('更新的数据不合法');
         }

         return $this->_db->where('menu_id='.$id)->save($data);
     }

    public function updateStatusById($id,$status){
         if(!$id || !is_numeric($id)){
             throw_exception('ID不合法');
         }
        if(!$status || !is_numeric($status)){
            throw_exception('状态不合法');
        }

        $data['status']=$status;
        return $this->_db->where('menu_id='.$id)->save($data);
    }


    public function updateMenuListorderById($id,$listorder){
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        $data=array(
            'listorder'=>intval($listorder),
        );
        return $this->_db->where('menu_id='.$id)->save($data);
    }

    public function getAdminMenus(){
        $data=array(
            'status'=>array('neq',-1),
            'type'=>1,
        );
        return $this->_db->where($data)->order('listorder desc,menu_id desc')->select();
    }

    //此方法用于获取菜单管理中的类型为前端导航的菜单名
    public  function getBarMenus(){
        $data=array(
            'status'=>1,
            'type'=>0,
            );
        $res=$this->_db->where($data)
            ->order('listorder desc,menu_id desc')
            ->select();
        return $res;
    }
}