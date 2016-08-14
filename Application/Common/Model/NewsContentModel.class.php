<?php
namespace Common\Model;
use Think\Model;
/*
 * 文章内容content model操作
 * @author liqm
 */

class NewsContentModel extends Model{
    private  $_db='';
    Public function __construct(){
        $this->_db=M('news_content');
    }
    //同步对主表中的数据插入到副表的函数
    public function insert($data=array()){
        if(!$data || !is_array($data)){
            return 0;
        }
        $data['create_time']=time();
        if(isset($data['content']) && $data['content']){
            //对编辑器中输入的html标签内容进行实体转换
            $data['content']=htmlspecialchars( $data['content']);
        }
        return $this->_db->add($data);
    }
    //编辑模式下获取副表文章内容
    public function find($id){
        if(!$id || !is_numeric($id)){
            return array();
        }
        return $this->_db->where('news_id='.$id)->find();
    }

    public  function updateNewsById($id,$data){
        if(!$id || !is_numeric($id)){
            throw_exception("ID不合法");
        }
        if(!$data || !is_array($data)){
            throw_exception("更新数据不合法");
        }
        if(isset($data['content']) && $data['content']){
            //对编辑器中输入的html标签内容进行实体转换
            $data['content']=htmlspecialchars( $data['content']);
        }
        return $this->_db->where('news_id='.$id)->save($data);
    }
}