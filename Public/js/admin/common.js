/**
 * Created by liqm on 2016/7/30.
 * 添加按钮功能类操作
 */
  $("#button-add").click(function(){
      var url = SCOPE.add_url;//获取url
      window.location.href=url;

  });

/**
 * Created by liqm on 2016/7/30.
 * 提交form表单操作
 */
$("#singcms-button-submit").click(function(){//找到相应表单提交按钮的id
    var data =$("#singcms-form").serializeArray(); //获取表单数据,根据表单的id进行获取

    postData={};
    $(data).each(function(i){
        postData[this.name]=this.value;

    })
    url = SCOPE.save_url;//重用的用于添加的url
    jump_url=SCOPE.jump_url;
    $.post(url,postData,function(result){
        if(result.status==1){
            //success
            return dialog.success(result.message,jump_url);
        }else if(result.status==0){
            //failue
            return dialog.error(result.message);
        }
    },'JSON');
});

/*
*编辑模型
*
 */
    $('.singcms-table #singcms-edit').on('click',function(){
     var id=$(this).attr('attr-id');
    //如果地址如此配置可以提高代码复用率
     var url=SCOPE.edit_url+'&id='+id; //将id带过来+'&id='+id
     window.location.href=url;
});

/*
 *删除模型js
 *
 */
$('.singcms-table #singcms-delete').on('click',function(){
    var id=$(this).attr('attr-id');
    var a=$(this).attr('attr-a');
    var message=$(this).attr('attr-message');
    var url=SCOPE.set_status_url;

    data= {};
    data['id']=id;
    data['status']=-1;

    layer.open({
        type: 0,
        title: '是否提交？',
        btn: ['yes', 'no'],
        icon: 3,
        closeBtn: 2,
        content: "是否确定" + message,
        scrollbars: true,
        yes: function () {
            //执行相应的跳转
            todelete(url, data);
        },
    });
});

function todelete(url,data){
   $.post(
        url,
        data,
        function(s){
           if(s.status==1){
                return dialog.success(s.message,'');
           }else{
                return dialog.error(s.message);
           }
        }
   ,"JSON");
}
/*
 *排序操作
 *
 */
$("#button-listorder").click(function(){
   //获取listorder内容
    var data =$("#singcms-listorder").serializeArray(); //获取表单数据,根据表单的id进行获取
    postData={};
    $(data).each(function(){
        postData[this.name]=this.value;
    });
    var url = SCOPE.listorder_url;//用于获取listorder的url
    $.post(url,postData,function(result){
        if(result.status==1){
            //success
            return dialog.success(result.message,result['data']['jump_url']);
        }else if(result.status==0){
            //failue
            return dialog.error(result.message,result['data']['jump_url']);
        }
    },'JSON');
});
/*
 *修改操作js
 *
 */
$('.singcms-table #singcms-on-off').on('click',function(){
    var id=$(this).attr('attr-id');
    var status=$(this).attr('attr-status');
   // var message=$(this).attr('attr-message');
    var url=SCOPE.set_status_url;

    data= {};
    data['id']=id;
    data['status']=status;

    layer.open({
        type: 0,
        title: '是否提交？',
        btn: ['yes', 'no'],
        icon: 3,
        closeBtn: 2,
        content: "是否确定更改状态",
        scrollbars: true,
        yes: function () {
            //执行相应的跳转
            todelete(url, data);
        },
    });
});

/*
*推送js相关
*
*
*/
$("#singcms-push").click(function(){
    //获取提交的推荐位id
    var id=$("#select-push").val();
    if(id==0){
        return dialog.error("请选择推荐位");
    }
    push={};
    postData={};
    //获取推送位的选择框
    $("input[name='pushcheck']:checked").each(function(i){
        push[i]=$(this).val();
    });
    postData['push']=push;
    postData['position_id']=id;
    //console.log(postData);断点测试
    var url= SCOPE.push_url;
    $.post(url,postData,function(result){
        if(result.status == 1){
            //success
            return dialog.success(result.message,result['data']['jump_url']);
        }else if(result.status == 0){
            //failue
            return dialog.error(result.message,result['data']['jump_url']);
        }
    },"JSON");
});
