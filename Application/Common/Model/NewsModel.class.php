<?php
namespace Common\Model;
use Think\Model;

/*
 * 文章内容model操作
 * @author liqm
 */

class NewsModel extends Model{
    private  $_db='';
    Public function __construct(){
        $this->_db=M('news');
    }
    //插入数据的操作
    public function insert($data=array()){
        if(!$data || !is_array($data)){
            return 0;
        }
        //相当于这里的老师说的一样，是吗？对头，明白，难怪说提交不到时间，而是我这个添加的问题，
        $data['create_time']=time();
        $data['username']=getLoginUsername();
        return $this->_db->add($data);
    }

    //分页的参数
    public function getNews($data,$page,$pageSize=10){
        //分别按标题以及栏目进行赋值，搜索
        $conditions=$data;
        if(isset($data['title']) && $data['title']){
            $conditions['title']=array('like','%'.$data['title'].'%');
        }
        if(isset($data['catid']) && $data['catid']){
            $conditions['catid']=intval($data['catid']);
        }
        $conditions['status']=array('neq','-1');
        $offset=( $page - 1)*$pageSize;//取得起始数据的位置
        $list=$this->_db->where($conditions)
            ->order('listorder desc,news_id desc')
            ->limit($offset,$pageSize)
            ->select();
        return $list;
    }

   public function  getNewsCount($data=array()){
       $conditions=$data;
       //$data['status']=array('neq',-1);
       if(isset($data['title']) && $data['title']){
           $conditions['title']=array('like','%'.$data['title'].'%');
       }
       if(isset($data['catid']) && $data['catid']){
           $conditions['catid']=intval($data['catid']);
        }
        //展示的数量
        return $this->_db->where($conditions)->count();
    }

   //编辑模式下获取主表文章内容
   public  function find($id){
       //先对数据进行判断在获取$data
       if(!$id || !is_numeric($id)){
           return array();
       }
       $data=$this->_db->where('news_id='.$id)->find();
       return $data;

   }
    //对主表的更新
    public function updateById($id,$data){
        if(!$id || !is_numeric($id)){
            throw_exception("ID不合法");
        }
        if(!$data || !is_array($data)){
            throw_exception("更新数据不合法");
        }
        return $this->_db->where('news_id='.$id)->save($data);
    }

    public function updateStatusById($id,$status){
        if(!is_numeric($status)){
            throw_exception('status不能为非数字');
        }
        if(!is_numeric($id)|| !$id){
            throw_exception('id不合法');
        }
        $data['status']=$status;
        return $this->_db->where('news_id='.$id)->save($data);
    }

    public function updateNewsListorderById($id,$listorder){
        if(!$id  || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        $data=array('listorder'=>intval($listorder));
        return $this->_db->where('news_id ='.$id)->save($data);
    }

    public function getNewsByNewsIdIn($newIds){
        if(!is_array($newIds)){
            throw_exception("参数不合法");
        }
        $data=array(
            'news_id'=>array('in',implode(',',$newIds)),
        );
        return $this->_db->where($data)->select();
    }

    /*
     * 获取排行的数据
     * @param array $data
     * @param int $limit
     * @return array
     */

    public function getRank($data=array(),$limit=100){
        $list=$this->_db->where($data)->order('count desc ,news_id desc')->limit($limit)->select();
        return $list;
    }

    public function select( $data=array() , $limit=0){

        $list=$this->_db->where($data)->order('count desc,news_id desc')->limit($limit)->select();
        return $list;
    }

    public function updateCount($id,$count){
        if(!$id || !is_numeric($id)){
            throw_exception("ID不合法");
        }
        if(!is_numeric($count)){
            throw_exception("count不能为非数字");
        }
        $data['count']=$count;
        return $this->_db->where('news_id='.$id)->save($data);
    }
    /*
     * 获取最大阅读数
     */
    public function maxcount(){
        $data=array(
            'status'=>1,
        );
       return $this->_db->where($data)->order('count desc')->limit(1)->find();
    }

}