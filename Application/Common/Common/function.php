<?php
/**
 *公用的方法
 */

function show($status,$message,$data=array()){
    $result=array(
        'status'=>$status,
        'message'=>$message,
        'data'=>$data,
    );
    exit(json_encode($result));
}

function getMd5Password($password){
    return md5($password.C('MD5_PRE'));
}

function getMenuType($type){
    return $type==1 ? '后端菜单' : '前端导航';
}

function status($status){
    if($status == 0){
        $str='关闭';
    }elseif($status == 1){
        $str='开启';
    }elseif($status == -1){
        $str='删除';
    }
    return $str;
}

function getAdminMenuUrl($nav){
    $url='/admin.php?c='.$nav['c'].'&a='.$nav['a'];
    if($nav['f']=='index'){
        $url='/admin.php?c='.$nav['c'];
    }
    return $url;
}

function getActive($navc){
    $c=strtolower(CONTROLLER_NAME);//得到控制器的名称
    if(strtolower($navc)==$c){
        return 'class="active"';
    }
    return '';
}

function showKind($status,$data){
    header('Content-type:application/json;charset=UTF-8');
    if($status==0){
        //kindeditor错误输出机制：http://kindeditor.net/docs/upload.html
        exit(json_encode(array('error'=>0,'url'=>$data)));
    }
    exit(json_encode(array('error'=>1,'message'=>'上传失败')));
}

function getLoginUsername(){
    return $_SESSION['adminUser']['username']?$_SESSION['adminUser']['username']:'';
}
//得到栏目的名字
function getCatName($navs,$id){
    foreach($navs as $nav){
        $navList[$nav['menu_id']]=$nav['name'];
    }
    return isset($navList[$id])?$navList[$id]:'';
}
//得到文章的来源
function getCopyFromById($id){
    $copyfrom=C("COPY_FROM");//从配置文件中获取文章来源
    return isset($copyfrom[$id])?$copyfrom[$id]:'';
}

//对缩略图属性进行展示
function isThumb($thumb){
    if($thumb){
        return '<span style="color:red">有</span>';
    }
    return '无';

}