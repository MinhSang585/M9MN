var _testMode = 0;

/*=======短信服务==========*/


$('#js-message-submit').on('click', smsServie);

$(function () {
    checkMessageBtnStatus();
});

//保存状态
function checkMessageBtnStatus() {
    //短信状态缓存
    var checkStatus = localStorage.getItem('check');
    if (checkStatus) {
        var $serviceNum = $('.service-num'),
            btnArr = checkStatus.split(',');

        $serviceNum.each(function () {
            for (var i = 0; i < btnArr.length; i++) {
                if ($(this).val() == btnArr[i]) {
                    $(this).prop("checked", true);
                }
            }
        })
    }

}

//短信更新
function smsServie() {
    var serviceStr = "",
        serviceNum = $('.service-num');

    serviceNum.each(function () {

        if ($(this).prop("checked")) {
            serviceStr += $(this).val() + ","
        }
    });

    //短信状态缓存
    localStorage.setItem('check', serviceStr);




    $.post("/asp/chooseservice.php", {
        "service": serviceStr
    }, function (returnedData, status) {
        if ("success" == status) {
            // closeProgressBar();
            _showLayer(returnedData, '关闭');
        }
    });

}







/* =========L1 站内信=========*/

// $('#tab-userLetter').on('click',getGuestbookCountNew);

//初始化
$(function () {
    letterService('1',"1");
});

//标签页切换
$('.js-letter-menu').on('click','li',function () {

    var indexNum = $(this).data('num');
    letterService('1',indexNum);
});



//邮件列表
function letterService(index,type) {

    // 获取未读信息 in mainheader.jsp
    // getLetterCount();

    var num = 10;         // 取得几笔
    var index = index;   // 第几页

    var dataArr = {
        "page": index,
        "count": num,
        "letterType":type
    };

    if (_testMode == 1) {
        var data =
            '{"count":26,"msgList":[{"content":"","createDate":"2017-08-09 14:43:04","id":5436467,"private":false,"read":false,"title":"iWinapp朋友圈“七夕”优秀话题彩金派发通知"},{"content":"","createDate":"2017-08-08 00:39:56","id":5267979,"private":false,"read":false,"title":"iWinAPP朋友圈新活动上线通知"},{"content":"","createDate":"2017-08-06 00:29:29","id":5036275,"private":false,"read":false,"title":"秒存账户 不定时更新提醒"},{"content":"","createDate":"2017-08-02 15:46:24","id":4484087,"private":false,"read":false,"title":"8月免费筹码派发通知"},{"content":"","createDate":"2017-08-02 15:46:24","id":4484965,"private":false,"read":false,"title":"8月免费筹码派发通知"}],"pageNo":0,"pageSize":0}';
        data = JSON.parse(data);
    } else {
        var data = ajaxPost("/asp/getMessageByUser.php", dataArr);
    }

    /*

     <li>iWin论坛商城开业啦~</li>
     <li><span class="s">2016-09-12 12:00:00</span></li>
     <li><a href="javascript:void(0);"data-letterid="1799243"data-read="false"data-private="false"class="letter">&nbsp;&nbsp;查看</a></li>
     */

    if (data) {

        var html = "";
        var total = data.count;
        var msgList = data.msgList;

        if (msgList.length > 0) {

            for (var i = 0; i < msgList.length; i++) {

                var id = msgList[i].id;
                var title = msgList[i].title;
                var ctime = msgList[i].createDate;
                var readStatus = msgList[i].read;

                if ( readStatus) { // 已读

                    html += "<tr class='read'>\
                               <td ><input type='checkbox' class='choose-input' name='choose' value='"+id+"' onclick='changeStatus()'></td>\
                               <td onclick='detailMessage("+id+")'>"+title + "</td>\
                              <td>" + ctime + "</td>\
                            </tr>";

                } else { // 未读
                    html += "<tr class='unread'>\
                               <td ><input type='checkbox' class='choose-input' name='choose' value='"+id+"' onclick='changeStatus()'></td>\
                               <td onclick='detailMessage("+id+")'>"+title + "</td>\
                              <td>" + ctime + "</td>\
                            </tr>";

                }


            }


            // 分页记录

            var paginationHtml = "";
            var page_max = Math.ceil(total / num);
            var page_next = parseInt(index) + 1;
            var page_past = parseInt(index) - 1;

            paginationHtml += '<tr><td class="pagination" colspan="4">';
            paginationHtml += '每页&nbsp;' + num + '&nbsp;条记录&nbsp;&nbsp;';

            paginationHtml += '第&nbsp;' + index + '/' + page_max;
            paginationHtml += '&nbsp;页&nbsp;&nbsp;&nbsp;';

            paginationHtml += '<a href="javascript:void(0);" onclick="letterService(1,'+type+');">首頁</a>&nbsp;';

            if (index != 1) {
                paginationHtml += '<a href="javascript:void(0);" onclick="letterService(' + page_past + ','+type+');">上一页</a>&nbsp;&nbsp;';
            }

            if (index != page_max) {
                paginationHtml += '<a href="javascript:void(0);" onclick="letterService(' + page_next + ','+type+');">下一页</a>&nbsp;&nbsp;';
            }

            paginationHtml += '<a href="javascript:void(0);" onclick="letterService(' + page_max + ','+type+');">尾页</a>&nbsp;';
            paginationHtml += '</td></tr>';

            // append 第一个：邮件列表, 第二个：分页记录
            $('#j-letterList'+type).empty().append(html).append(paginationHtml).show();

            var checked = $('#all-check').prop('checked');

            $('#j-letterList'+type).find(' input[type=checkbox]').prop("checked",checked);


        } else {
            $('#j-letterList'+type).empty().html("<tr><td colspan='3'>暂无数据</td></tr>");
        }

    } else {



    }
}

//input选中按钮变亮
function changeStatus() {

    var checkStatus = 0;
    var readCount = 0;


    $(".choose-input[name='choose']:checked").each(function () {
        if($(this).prop('checked')){
            if(!$(this).parent().parent().hasClass('read')){
                readCount += 1;
            }
            checkStatus += 1;
        }
    });





    if (checkStatus > 0) {

        $('.js-want-delete').addClass('active');

        if(readCount > 0){
            $('.js-already-read').addClass('active');
        }else{
            $('.js-already-read').removeClass('active');
        }

    }else{
        $('.js-already-read').removeClass('active');
        $('.js-want-delete').removeClass('active');
    }

}

//全选处理
$('#all-check').on('click',function () {

    var num = $('.js-letter-menu li.active').data('num');
    var itemLength = $('#j-letterList'+num).find('td').html();

    var checked = $(this).prop('checked');

    if(checked&&itemLength != '暂无数据'){

            $('.js-already-read').addClass('active');


        $('.js-want-delete').addClass('active');

    }else{
        $('.js-already-read').removeClass('active');
        $('.js-want-delete').removeClass('active');
    }

    $('#j-letterList'+num).find(' input[type=checkbox]').prop("checked",checked);


});


//已读处理
$('.js-already-read').on('click',function () {

    var unreadId = [];
    var num = $('.js-letter-menu li.active').data('num');
    var unMessage =$('#j-letterList'+num).find('.unread input[type=checkbox]:checked');



    unMessage.each(function(){
        unreadId.push( $(this).val());

    });


    if(unreadId.length > 0){

        $(this).addClass('active');

        $.post('/asp/batchReadTopic.php',{topicIds:unreadId.toString()},function (data) {

            if(data ==="操作成功"){
                letterService('1',num);
                getGuestbookCountNew();

            }else{
                $(this).removeClass('active');
                _showLayer(data,'确定');
            }


        });
    }else{

        alert('请选择未读项目！');
    }


});


//删除成功处理
$('.js-want-delete').on('click',function () {

    var allId = [];
    var num = $('.js-letter-menu li.active').data('num');
    var allMessage =$('#j-letterList'+num).find(' input[type=checkbox]:checked');




    allMessage.each(function(){
        allId.push( $(this).val());

    });

    if(allId.length > 0){
        $(this).addClass('active');
        var allId1 = allId.toString();
        deleteMsg(allId1,num);

    }else{
        $(this).removeClass('active');
        alert('请选择需要删除的项目！');
    }



});


/*==============L2站内信详情================*/

//单个站内信
function detailMessage(id) {

    $('.get-email-cont').hide().next().show();

    var num = $('.js-letter-menu li.active').data('num');

    var data = ajaxPost("/asp/readMsg.php?msgID=" + id);

    if (data) {
        var title = data.title;
        var ctime = data.createDate;
        var content = data.content;

        if (content) {
            content = content.replace(/\r\n/g, "<br />").replace(/\n/g, "<br />");
        }

        $('.js-email-title').html(title);
        $('.js-email-time').html(ctime);
        $('.js-mian-context').html(content);
        $('.js-once-delete').data({'id':id,'num':num});


    } else {
        alert("无内容返回！")
    }
}

//返回列表
$('.js-back-btn').on('click',function () {
    $('.get-email-cont').show().next().hide();
    var num = $('.js-once-delete').data('num');
    letterService('1',num);
    getGuestbookCountNew();

});

//删除详情内容
$(document).on('click','.js-once-delete',function () {
    var ids = $(this).data("id");
    var num = $(this).data('num');
    deleteMsg(ids,num);
});


/*站内信删除======= */
function deleteMsg(ids,num) {
    $.post(' /asp/deleteMsg.php ',{topicIds:ids},function (data) {

        if(data ==="站内信已删除"){
            $('.get-email-cont').show().next().hide();
            letterService('1',num);
            getGuestbookCountNew();
            changeStatus();
        }
    });
}






/* L4 站内信 发信
 ========================= */
function saveLetter() {
    var title = $("#letter-title").val();
    var content = $("#letter-content").val();

    if (title == "") {
        alert("标题不能为空！");
        return false;
    }

    if (title != "" && title.length > 25) {
        alert("标题过长！");
        return false;
    }

    if (content == "") {
        alert("回复信息不能为空！");
        return false;
    }

    if (content != "" && content.length > 255) {
        alert("回复信息过长！");
        return false;
    }

    $.ajax({
        url: "/asp/saveBookDate.php",
        type: "POST",
        cache: false,
        data: "guestbook.title=" + title + "&guestbook.content=" + content,
        complete: function (data) {
            if ("200" == data.status) {
                $("#letter-title, #letter-content").val("");
                alert("站内信已发送！");
                letterService(1);
            } else {
                alert("无内容返回！")
            }
        }
    });
}

//站内信数量
function getGuestbookCountNew() {
    $.post("/asp/getGuestbookCountNew.php", function (response) {
        if (response > 0) {
            $(".js-email-count").html(response);
        } else {
            $(".js-email-count").html("0");
        }
    });
}