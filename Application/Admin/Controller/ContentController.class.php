<?php
/**
 * Created by PhpStorm.
 * User: liqm
 * Date: 2016/8/2
 * Time: 12:45
 * 后台ndex相关
 */

namespace Admin\Controller;
use Think\Controller;
use Think\Exception;
/*
 * 文章内容管理
 */
class ContentController extends CommonController{
    /*
     * 查找操作
     */
    public function index(){
        //将文章内容使用分页效果输出到列表中
        $conds=array();
        if($_GET['status']){
            $conds['status']=$_GET['status'];

        }
        //对标题做判断
        if($_GET['title']){
            $conds['title']=$_GET['title'];
        }
        //对栏目做判断
        if($_GET['catid']){
            $conds['catid']=intval($_GET['catid']);
        }
        $page=$_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize=3; //每页显示的个数

        $news=D('News')->getNews($conds,$page,$pageSize);
        $count=D('News')->getNewsCount($conds);
       // $this->assign('menuId',$news['menu_id']);
        $res =new \Think\Page($count,$pageSize);
        $pageres=$res->show();
        $position=D('Position')->getNormalPositions();
        $this->assign('pageres',$pageres);
        $this->assign('news',$news);
        $this->assign('positions',$position);
        $this->assign('webSiteMenu',D('Menu')->getBarMenus());
        $this->display();

    }
    /*
     * 添加操作
     */
    public function add(){
        //提交修改时使用的是save_url的js
        //对所要提交的数据进行判断
        if($_POST){
            if(!isset($_POST['title']) || !$_POST['title']){
                return show(0,'标题不存在');
            }
            if(!isset($_POST['small_title']) || !$_POST['small_title']){
                return show(0,'短标题不存在');
            }
            if(!isset($_POST['catid']) || !$_POST['catid']){
                return show(0,'文章栏目不存在');
            }
            if(!isset($_POST['keywords']) || !$_POST['keywords']){
                return show(0,'关键字不存在');
            }
            if(!isset($_POST['content']) || !$_POST['content']){
                return show(0,'content不存在');
            }
            //提交编辑更新的数据
            if($_POST['news_id']){
                return $this->save($_POST);
            }
            //往主表插入数据并保存在数据库
            $newsId=D("News")->insert($_POST);
            //将主表中的文章内容形成副表并插入数据库
            if($newsId){
                $newsContentData['content']=$_POST['content'];
                $newsContentData['news_id']=$newsId;
                $cId=D("NewsContent")->insert($newsContentData);
                if($cId){
                    return show (1,'新建成功');
                }else{
                    //因为$newsId是插入成功的
                    return show (1,'主表插入成功，副表插入失败');
                }
            }else{
                return show (0,'新建失败');
            }
        }else{
             //将标题颜色/所属栏目/来源输出到页面
             $webSiteMenu=D('Menu')->getBarMenus();
             $titleFontColor= C('TITLE_FONT_COLOR');
             $copyFrom= C('COPY_FROM');
             //输出到模板
             $this->assign('webSiteMenu',$webSiteMenu);
             $this->assign('titleFontColor',$titleFontColor);
             $this->assign('copyfrom',$copyFrom);
             $this->display();
        }
    }
    /*
     * 修改操作
     */
    public function edit(){
        //先获取id来调用数据表中的内容
        $newId=$_GET['id'];
        //判断
        if(!$newId){
            //执行跳转
            $this->redirect('/admin.php?c=content');
        }
        //先使用D方法实例化News模型下的find方法获取主表的内容
        $news=D('News')->find($newId);
        if(!$news){
            //执行跳转
            $this->redirect('/admin.php?c=content');
        }
        //再获取副表的内容
        $newsContent=D('NewsContent')->find($newId);
        if($newsContent){
            $news['content']=$newsContent['content'];//将主表中的文字内容复制给副表
        }

        $webSiteMenu=D('Menu')->getBarMenus();
        $this->assign('webSiteMenu',$webSiteMenu);
        $this->assign('titleFontColor',C("TITLE_FONT_COLOR"));
        $this->assign('copyFrom',C("COPY_FROM"));

        $this->assign('news',$news);
        $this->display();
    }
    //将编辑的数据提交数据库保存
    public  function save($data){
        $newsId=$data['news_id'];
        unset($data['news_id']);
        try{
            //更改主表内容
            $id=D("News")->updateById($newsId,$data);
            //更改副表的内容
            $newsContentData['content']=$data['content'];
            $conId=D("NewsContent")->updateNewsById($newsId,$newsContentData);
            if($id===false || $conId===false){
                return show(0,'更新失败');
            }
            return show(1,'更新成功');
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
    }

    public function setStatus(){
        try{
            if($_POST){
                $id=$_POST['id'];
                $status=$_POST['status'];
                if(!$id){
                    return show(0,'ID不存在');
                 }
                $res=D("News")->updateStatusById($id,$status);

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
                foreach ($listorder as $newsId => $v) {
                    //执行更新
                    $id = D('News')->updateNewsListorderById($newsId, $v);
                    if ($id === false) {
                        $error[] = $newsId;
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

    public function push(){
        $jumpUrl=$_SERVER['HTTP_REFERER'];//跳转url相当于http中的referer
        $positionId=intval($_POST['position_id']);
        //获取推送按钮选择的值
        $newsId=$_POST['push'];

        if(!$newsId || !is_array($newsId)){
            return show(0,'请选择推荐的文章ID进行推荐');
        }
        if(!$positionId){
            return show(0,'没有选择推荐位');
        }
        //print_r($push);exit;

        try{
            $news=D("News")->getNewsByNewsIdIn($newsId);

            if(!$news){
                return show(0,'没有相关内容');
            }
            foreach($news as $new){
                $data=array(
                    'position_id'=>$positionId,
                    'title'=>$new['title'],
                    'thumb'=>$new['thumb'],
                    'news_id'=>$new['news_id'],
                    'status'=>1,
                    'create_time'=>$new['create_time'],
                );
                //print_r($new);
                $position=D("PositionContent")->insert($data);
            }
        }catch(exception $e){
            return show(0,$e->getMessage());
        }
        return show(1,'推荐成功',array('jump_url'=>$jumpUrl));
    }
}