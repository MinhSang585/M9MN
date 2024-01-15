/**
 * Created by 1040170 on 2018/7/11.
 */


$('.js-ui-coupon').on('click', 'li', changeCouponType);


$('li[href="#tab-userCoupons"]').on('click', function () {

    $('.js-ui-coupon').show();

    var action = getQueryString("action");

    if (action == 6) {

        setTimeout(function () {

            $('.js-ui-coupon').find('li[href="#tab-coupon"]').trigger('click')
        },200)

    }else{
        $('.js-ui-coupon li:nth-of-type(1)').trigger('click');
    }


});


var accountTrueName = $('#j-name').val();
var accountPhone = $('#j-phone').val();


//自助优惠切换标签
function changeCouponType() {

    var type = $(this).attr('href');

    switch (type) {
        case"#tab-return":
            getXimaEndTime('ptasiatiger');
            break;
        case"#tab-checkIn":
            signFun();
            break;
        case"#tab-chips":
            getVipMoney();
            break;
        case"#tab-coupon":
            getcoupon();
            break;
        case"#tab-birthday":
            getBirthdayMoney();
            break;
        case"#tab-friend":
            $('.recommendFriendContent').show().siblings('#rec-container').hide();
            queryRecFriendAllMoney();
            break;
        case"#tab-help":
            losePromoRecord();
            break;
        case"#tab-level":
            queryBets('month');
            break;
        case"#tab-point":
            queryPointBalance();
            break;
        case"#tab-ptbb":
            queryPTBigBang();
            break;
        case"#tab-persent":
            getSelfDepositData();
            break;
    }
}
//签到
function dosign() {
    openProgressBar();
    $.ajax({
        url: "${ctx}/asp/doSignRecord.php",
        type: "post",
        dataType: "text",
        success: function (msg) {
            closeProgressBar();
            alert(msg);
        },
        error: function () {
            window.loaction.href = "${ctx}/";
        }
    });
}

//自助优惠领取
$('#happyBirthday').on('click', drawBirthdayMoney);  //生日礼金

$('.js-checkUpgrade').on('click', checkUpgrade);  //晋级
$('#submitPointRemit').on('click', submitPointRemit); //积分
$('#checkSelfYouHuiSubmit').on('click', checkSelfYouHuiSubmit); //存送

//免费筹码
$('#drawVipMoney').on('click', drawVipMoney);
/**返水**/
//切换返水平台
$('#platForm').change(function () {

    getXimaEndTime($(this).val());
});

//返水等级
function backLevel(platform) {
    $.post('/asp/getUserXimaLelve.php', function (data) {


        if (data) {
            var level = '', levelPlatform = '';
            if (platform == "ea" || platform == "agin" || platform == "agqj") {
                level = data.LIVE;
                levelPlatform = '真人';
            } else if(platform == "agfish"){
                level = data.OTHER;
                levelPlatform = '其他类';
            }else {
                level = data.SLOT;
                levelPlatform = '老虎机';
            }
            $('#user-back-level').val(levelPlatform + '第' + level + '阶段');
        }
    });
}

//打开返水
function getXimaEndTime(platform) {

    backLevel(platform);

    if (platform != "slot") {
        $('#otherList').show();
        $('#slotList').hide();

        $("#validAmount").val("");
        $("#rate").val("");
        $("#ximaAmount").val("");
        $("#endTime").val("");

        $.post("/asp/getXimaEndTime.php", {
            'platform': platform
        }, function (returnedData, status) {
            if ("success" == status) {

                if (returnedData.indexOf("-") >= 0) {
                    $("#startTime").val(returnedData.split(",")[0]);
                    $("#endTime").val(returnedData.split(",")[1]);
                    getAutoXimaObject();
                } else {
                    _showLayer(returnedData, '')
                }

            }
        });
    } else {
        soltSelfGetEvent();
    }
}

$('#checkEaSubmit').on('click', checkEaSubmit);

//提交返水
function checkEaSubmit() {
    var startTime = $("#startTime").val();
    var endTime = $("#endTime").val();
    var validAmount = $("#validAmount").val();
    var rate = $("#rate").val();
    var ximaAmount = $("#ximaAmount").val();
    var platForm = $("#platForm").val();
    if (startTime == "" || endTime == "" || validAmount == "" || rate == "" || ximaAmount == "") {
        _showLayer("所有项都为必填项\n请重新选择[截止时间]，以让系统为您自动填写其他栏目", '');
        return;
    }
    openProgressBar();

    $('#checkEaSubmit').attr('disabled','disabled');

    $.post("/asp/execXima.php", {
        "startTime": startTime,
        "endTime": endTime,
        "validAmount": validAmount,
        "rate": rate,
        "ximaAmount": ximaAmount,
        "platform": platForm
    }, function (returnedData, status) {
        if ("success" == status) {

            $('#checkEaSubmit').removeAttr('disabled');

            //getXimaEndTime(platForm);
            closeProgressBar();
            alert(returnedData);
        }
    });

}

//返水
function getAutoXimaObject() {
    var startTime = $("#startTime").val();
    var endTime = $("#endTime").val();
    var platForm = $("#platForm").val();
    if (startTime == "" || endTime == "" || platForm == "") {
        return false;
    }

    // openProgressBar();
    $.post("/asp/getAutoXimaObjectData.php", {
        "startTime": startTime,
        "endTime": endTime,
        "platform": platForm
    }, function (returnedData, status) {
        if ("success" == status) {
            // closeProgressBar();
            $("#validAmount").val("");
            $("#ximaAmount").val("");
            $("#rate").val("");
            var ximaBean = returnedData;
            var validAmount = ximaBean.validAmount;
            var ximaAmount = ximaBean.ximaAmount;
            var rate = ximaBean.rate;
            var message = ximaBean.message;
            if (message == null || message == "") {
                $("#validAmount").val(validAmount);
                $("#ximaAmount").val(ximaAmount);
                $("#rate").val(rate);
            } else {
                _showLayer(message, '');
            }
        }
    });
    return false;
}

//新老虎机返水
function soltSelfGetEvent() {
    $('#otherList').hide();
    $('#slotList').show();
    openProgressBar();
    $.post("/asp/getAutoXimaSlotObject.php", {
        "platform": 'slot'
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            var html, message = returnedData.message;
            if (message == 'success') {
                var jsonData = eval('(' + returnedData.jsonResult + ')');
                var totalCount = 0;
                for (var key in jsonData) {
                    if (key != 'message') {
                        html += '<tr>' + '<td>' + key + '</td>' + '<td>' + jsonData[key].startTimeStr + '</td>' + '<td>' + jsonData[key].endTimeStr + '</td>' + '<td>' + jsonData[key].validAmount + '</td>' + '<td>' + jsonData[key].rate + '</td>' + '<td>' + jsonData[key].ximaAmount + '</td>' + '</tr>'
                    }
                    totalCount += jsonData[key].ximaAmount
                }
                $('.totalCount').html(totalCount.toFixed(2));
                $('#slotList table tbody').html(html);
            } else {
                _showLayer(message, '')
            }
        }
    })

}

function newExecXimaSubmit() {
    $.post("/asp/execSlotXima.php", {
        "platform": 'slot'
    }, function (returnedData, status) {
        if ("success" == status) {

            _showLayer(returnedData.message, '');

            $("#submit_fanshui").attr("disabled", false);

            flag = true;
        }
    });
}

/**返水**/

/**自助存送**/

var preferentialConfig = {};
var _runUrl = {};


// 获取存送优惠数据
function getSelfDepositData() {
    var phoneNum = $('#j-phone').val();
    var accountName = $('#j-name').val();

    if(phoneNum != ''&& accountName != ''){
        $.ajax({
            type: "post",
            url: "/asp/getYouHuiConfig.php",
            data: {},
            success: function (data) {

                $.each(data, function (index, ele) {

                    preferentialConfig[ele.id] = ele;
                });
            }
        });
    }else{
        layer.open({
            skin: 'tips-layer',
            closeBtn: false,
            content: '  为了您的账户安全，请完善您的姓名和手机号再申请优惠！',
            btn: ['确定'],
            yes:function () {
                location.href='/userManage.php?action=5';
            }
        });
    }

}

$('#youhuiName').change(function () {

    var value = $(this).val();
    youHuiNameChange(value);



});



// 选择平台下拉事件
function youHuiNameChange(value) {

    $("#youhuiType").empty();
    $("#giftMoney").val('');
    $("#waterTimes").val('');

    if (undefined == value || null == value || '' == value) {
        return;
    }

    var data = [];

    for (var prop in preferentialConfig) {

        if (preferentialConfig.hasOwnProperty(prop)) {

            var p = preferentialConfig[prop];

            if (value == p.platformId) {

                data.push(p);
            }
        }
    }

    if (data.length == 0) {

        var tipData = '未找到' + $('#youhuiName').find('option:selected').text() + '类型数据！';

        _showLayer(tipData, '');

    } else {
        $.each(data, function (index, ele) {

            var id = ele.id;

            $("#youhuiType").append("<option value='" + id + "'>" + ele.aliasTitle + "</option>");
        });

        youHuiTypeChange(data[0].id);
    }


}

// 优惠类型下拉事件
function youHuiTypeChange(id) {

    if (undefined == id || null == id || '' == id) {
        return;
    }

    var data = preferentialConfig[id];
    // 流水倍数
    $("#waterTimes").val(data.betMultiples);
    // 转账金额
    var transferMoney = $("#transferMoney").val();

    getSelfYouhuiAmount(transferMoney);
}

$('#youhuiType').change(function () {
    youHuiTypeChange(this.value);
});

$('#transferMoney').blur(function () {
    getSelfYouhuiAmount(this.value);
});

// 红利金额计算方法
function getSelfYouhuiAmount(value) {

    var money = 0.00;

    if (!(null == value || '' == value || isNaN(value))) {

        var id = $("#youhuiType").val();
        var data = preferentialConfig[id];

        // 计算红利金额
        money = value * (data.percent) > (data.limitMoney) ? (data.limitMoney) : value * (data.percent);
    }

    $("#giftMoney").val(money.toFixed(2));
}


// 提交方法
function checkSelfYouHuiSubmit() {

    // 选择平台
    var name = $("#youhuiName").val();
    // 优惠类型
    var type = $("#youhuiType").val();
    // 转账金额
    var money = $("#transferMoney").val();
    // 执行的请求地址
    var url = "/asp/getSelfYouHuiObject.php";

    if (null == name || '' == name) {
        alert("请选择存送优惠平台！");
        return;
    }

    if (null == type || '' == type) {
        alert("请选择存送优惠类型！");
        return;
    }

    if (null == money || '' == money) {
        alert("请输入转账金额！");
        return;
    }

    if (isNaN(money)) {
        alert("转账金额只能为数字！");
        return;
    }

    var rex = /^[1-9][0-9]+$/;

    if (!rex.test(money)) {
        _showLayer("抱歉，存送金额只能是大于或等于10元的整数哦。", '');
        return;
    }

    if (_runUrl[url]) {
        _showLayer('目前正在执行，请稍候再尝试！', '');
        return;
    }

    var data = preferentialConfig[type];

    _runUrl[url] = true;

    $(this).attr('disabled','disabled');
    $.post(url, {
        "id": data.id,
        "platformId": data.platformId,
        "titleId": data.titleId,
        "remit": money
    }, function (respData) {

        $('#checkSelfYouHuiSubmit').removeAttr('disabled');

        _runUrl[url] = false;

        _showLayer(respData, '');
    });
}

/**自助存送**/

/**签到**/
$(function () {
    setQianBonus();
});



function signFun() {
    var dateArray = [];

    var $dateBox = $("#js-qiandao-list"),
        $currentDate = $(".js-current-date"),
        $currentEnDate = $(".js-current-enDate"),
        $currentYear = $(".js-current-year"),
        $qiandaoBnt = $("#j-qdA"),
        _html = '',
        _handle = true,
        myDate = new Date(),
        enMonth = '',
        myMonth = parseInt(myDate.getMonth() + 1),
        myYear = myDate.getFullYear();

    switch (myMonth) {
        case 1:
            enMonth = 'January';
            break;
        case 2:
            enMonth = 'February';
            break;
        case 3:
            enMonth = 'March';
            break;
        case 4:
            enMonth = 'April';
            break;
        case 5:
            enMonth = 'May';
            break;
        case 6:
            enMonth = 'June';
            break;
        case 7:
            enMonth = 'July';
            break;
        case 8:
            enMonth = 'August';
            break;
        case 9:
            enMonth = 'September';
            break;
        case 10:
            enMonth = 'October';
            break;
        case 11:
            enMonth = 'November';
            break;
        case 12:
            enMonth = 'December';
            break;
    }


    $currentEnDate.text(enMonth);
    $currentYear.text(myYear);
    $currentDate.text(myMonth + '月');


    var monthFirst = new Date(myDate.getFullYear(), parseInt(myDate.getMonth()), 1).getDay();

    var d = new Date(myDate.getFullYear(), parseInt(myDate.getMonth() + 1), 0);
    var totalDay = d.getDate();
    var today = myDate.getDate();

    for (var i = 0; i < 40; i++) {
        _html += ' <li><div class="qiandao-icon"></div></li>';
    }

    $dateBox.html(_html);

    var $dateLi = $dateBox.find("li");

    $.ajax({
        type: "get",
        url: "/asp/findSignrecord.php",
        data: "",
        cache: false,
        async: true,
        success: function (data) {

            // var obj = eval(data);
            var obj = JSON.parse(data);

            var total = 0;
            for (var i = 0; i < obj.length; i++) {
                var day = new Date(obj[i].timeStr).getDate() - 1;
                dateArray.push(day);
                $('#todayGet').text(obj[i].amount);

                var remark = obj[i]['remark'];
                remark = remark.split("：");

                var amount = remark[remark.length - 1];
                total += parseFloat(amount);
            }

            $("#j-totalbonus").html(total + '元');

            for (var i = 0; i < totalDay; i++) {
                var str = "date" + parseInt(i + 1);
                $dateLi.eq(i + monthFirst).addClass(str);
                for (var j = 0; j < dateArray.length; j++) {
                    if (i == dateArray[j]) {
                        $dateLi.eq(i + monthFirst).addClass("qiandao");
                    }
                    if (dateArray.indexOf(today - 1) != -1) {
                        $qiandaoBnt.find('span').html('今日已签到');
                        $qiandaoBnt.addClass('done');
                        _handle = false;
                    }
                }
            }
        }
    });

    $(".date" + myDate.getDate()).addClass('able-qiandao');

    $dateBox.on("click", "li", function () {
        if ($(this).hasClass('able-qiandao') && _handle) {
            $(this).addClass('qiandao');
            qiandaoFun();
        }
    });

    $qiandaoBnt.on("click", function () {
        if (_handle == false) {
            qiandaoFun();


        }
    });

    function qiandaoFun() {

        openLayer("qiandao-active", qianDao);
        $qiandaoBnt.find('span').html('今日已签到');
        $qiandaoBnt.addClass('done');
        _handle = false;
    }

    function qianDao() {
        $(".date" + myDate.getDate()).addClass('qiandao');
    }

    function openLayer(a, Fun) {
        $('.' + a).fadeIn(Fun)
    }

    var closeLayer = function () {
        $("body").on("click", ".close-qiandao-layer", function () {
            $(this).parents(".qiandao-layer").fadeOut()
        })
    }();

    $("#js-qiandao-history").on("click", function () {
        openLayer("qiandao-history-layer", myFun);

        function myFun() {
            console.log(1)
        }
    });
}

function setQianBonus() {

    var USER_LEVEL = $('#j-myLevel').val();
    var result = "";

    switch (USER_LEVEL) {
        case "0":
            result = 4;
            break;
        case "1":
            result = 10;
            break;
        case "2":
            result = 12;
            break;
        case "3":
            result = 14;
            break;
        case "4":
            result = 16;
            break;
        case "5":
            result = 18;
            break;
        case "6":
            result = 20;
            break;
    }

    $("#j-qianbonus").html(result + '元');
}

//领取签到彩金
$('#j-qdA').click(function () {




    openProgressBar();
    $(this).attr('disabled','disabled');
    $.ajax({
        url: "/asp/doSignRecord.php",
        type: "post",
        dataType: "text",
        data: "",

        success: function (msg) {
            $('#j-qdA').removeAttr('disabled');
            closeProgressBar();
            layer.alert(msg, {
                skin: 'layui-layer-molv',
                closeBtn: 0,
                yes: function () {
                    layer.closeAll();
                    signFun();
                }
            });
            if (msg.indexOf("不满足") > 0) {
                return;
            }
            querySignAmount();
        },
    });

    // } else {
    //     linkSet();
    // }

});


/**优惠劵**/
function getcoupon() {
    var phoneNum = $('#j-phone').val();
    var accountName = $('#j-name').val();

    findCouponNum();
    findUnUseCouponList();
    findUseCouponList();
    findExpireCouponPageList();

    if(phoneNum != ''&& accountName != ''){

    }else{
        layer.open({
            skin: 'tips-layer',
            closeBtn: false,
            content: '  为了您的账户安全，请完善您的姓名和手机号再申请优惠！',
            btn: ['确定'],
            yes:function () {
                location.href='/userManage.php?action=5';
            }
        });
    }

}

//优惠券数量及状态
function findCouponNum() {
    $.post('/asp/findCouponNum.php', function (backData) {
        var returnData = backData.data;
        if (returnData) {
            $('.js-coupon-cont li:nth-of-type(1) span').html(returnData.sum);
            $('.js-coupon-cont li:nth-of-type(2) span').html(returnData.unReceivedNum);
            $('.js-coupon-cont li:nth-of-type(3) span').html(returnData.receivedNum);
            $('.js-coupon-cont li:nth-of-type(4) span').html(returnData.expireNum);
        }

    });

}

//未使用优惠券
function findUnUseCouponList() {

    $.post('/asp/findUnUseCouponList.php', function (backData) {
        var returnData = backData.data.dataList;

        // console.log(returnData);

        showCouponStatus(returnData, '1')


    });
}

//已使用优惠券
function findUseCouponList() {
    $.post('/asp/findUseCouponList.php', function (backData) {
        var returnData = backData.data.dataList;

        // console.log(returnData);

        showCouponStatus(returnData, '2')


    });
}

//已过期优惠券
function findExpireCouponPageList() {
    $.post('/asp/findExpireCouponPageList.php', function (backData) {
        var returnData = backData.data.dataList;

        // console.log(returnData);

        showCouponStatus(returnData, '3')


    });
}

//显示各状态html
function showCouponStatus(returnData, num) {

    if (returnData) {

        if (returnData.length > 3) {

            $('.js-load-show' + num).show();
        } else {

            $('.js-load-show' + num).hide();
        }

        var couponHtml = '';
        var couponHtml1 = '';

        for (var i = 0; i < returnData.length; i++) {

            if (returnData[i].couponType == '419') {

                var platformType = "";

                switch (returnData[i].platformType) {
                    case '0':
                        platformType = '老虎机';
                        break;
                    case '1':
                        platformType = '真人';
                        break;
                    case '2':
                        platformType = '棋牌';
                        break;
                    default :
                        platformType = '';
                        break;
                }

                couponHtml += '<li class="js-coupon-item coupon-item" ' +
                    'data-listtype="' + returnData[i].platformType + '"' +
                    'data-code="' + returnData[i].couponCode + '" ' +
                    'data-type="' + returnData[i].couponType + '">' +
                    '<div> ' +
                    '<div class="type"> ' +
                    '<div>¥<span>' + returnData[i].giftAmount + '</span> <br>红包金额 </div><div>' + platformType + '红包优惠券</div> </div>' +
                    ' <div class="conditions-use">  <p>使用条件：满' + returnData[i].betMultiples + '倍流水</p> <p>有效时间：' + returnData[i].remark + '</p> </div> ' +
                    '</div>' +
                    '</li>';

            } else if (returnData[i].couponType == '319') {

                couponHtml1 += '<li class="js-coupon-item coupon-item" ' +
                    'data-code="' + returnData[i].couponCode + '"' +
                    'data-type="' + returnData[i].couponType + '" ' +
                    'data-platform="' + returnData[i].platformName + '">' +
                    '<div> ' +
                    '<div class="type"> ' +
                    '<div><span>' + (returnData[i].percent * 100).toFixed(0) + '%</span> <br>存送百分比 </div><div>存送优惠券</div> </div>' +
                    ' <div class="conditions-use">' +
                    '<p>最高赠送红利: ¥<span>' + returnData[i].limitMoney + '</span></p>';

                if (returnData[i].minAmount) {
                    couponHtml1 += '<p> 最低存入金额: ¥<span>' + returnData[i].minAmount + '</span></p> ';
                } else {
                    couponHtml1 += '<p> 最低存入金额: ¥<span>0</span></p> ';
                }

                couponHtml1 += ' <p>使用条件：满' + returnData[i].betMultiples + '倍流水</p>' +
                    ' <p>有效时间：' + returnData[i].remark + '</p>' +
                    ' </div> ' +
                    '</div>' +
                    '</li>';

            }


        }


        $('.js-coupon-lists' + num).html(couponHtml + couponHtml1);

    }
}

//红包优惠
function submitRedCouponRemit() {

    var couponType = $("#couponType").val();
    var couponCode = $('#code').val();

    if (couponType == "") {
        alert("请选择平台！");
        return false;
    }
    if (couponCode == "") {
        alert("优惠代码不能为空！");
        return false;
    }

    $('.js-coupon-btn').attr('disabled','disabled');


    $.post("/asp/transferInforRedCoupon.php", {
        "couponType": couponType,
        "couponCode": couponCode
    }, function (returnedData, status) {
        if ("success" == status) {
            $('.js-coupon-btn').removeAttr('disabled');
            layer.alert(returnedData, {
                skin: 'layui-layer-molv',
                closeBtn: 0,
                yes: function () {
                    layer.closeAll();
                    findCouponNum();
                    findUnUseCouponList();
                    findUseCouponList();
                    findExpireCouponPageList();

                }
            });

        }
    });
    return false;
}

//存送优惠
function submitCouponRemit() {
    var couponType = $("#couponType").val();
    var couponRemit = $("#couponRemit").val();
    var couponCode = $("#code").val();

    if (couponType == "") {
        alert("请选择平台！");
        return false;
    }

    if (couponRemit != "") {
        if (isNaN(couponRemit)) {
            alert("存款金额非有效数字！");
            return false;
        }
    }

    if (couponCode == "") {
        alert("优惠代码不能为空！");
        return false;
    }

    $.post("/asp/transferInforCoupon.php", {
        "couponType": couponType,
        "couponRemit": couponRemit,
        "couponCode": couponCode
    }, function (returnedData, status) {
        if ("success" == status) {
            layer.alert(returnedData, {
                skin: 'layui-layer-molv',
                closeBtn: 0,
                yes: function () {
                    layer.closeAll();
                    findCouponNum();
                    findUnUseCouponList();
                    findUseCouponList();
                    findExpireCouponPageList();
                }
            });
        }
    });

    return false;
}

$(window).load(function () {

    $('.js-coupon-lists1').on('click', 'li', function () {

        var code = $(this).data('code');
        var type = $(this).data('type');
        var listType = $(this).data('listtype');
        var couponHtml = '';

        if (type == '419') {

            $('#myCouponModal').fadeIn();
            $('.js-coupon-remit-cont').hide();
            $('#code').val(code).attr('readonly', 'readonly').prev().removeClass('i1');
            $('.js-coupon-btn').attr('data-newtype', '419');

            $('#couponType').removeAttr('disabled').prev().addClass('i1');

            $('#couponType option').hide();

            if (listType === 1) { // 真人
                $('#couponType option[value="agqj"],#couponType option[value="agin"],#couponType option[value="ea"]').show();
            } else if (listType === 2) { // 棋牌
                $('#couponType option[value="hlqp"],#couponType option[value="as"],#couponType option[value="dtchess"],#couponType option[value="chess"],#couponType option[value="blqp"],#couponType option[value="nkyqp"]').show();
            } else { // 老虎机
                $('#couponType option[value="pp"],#couponType option[value="ptslot"],#couponType option[value="ptasia"],#couponType option[value="cq9"],#couponType option[value="pg"],#couponType option[value="slot"],#couponType option[value="ameba"]').show();
            }


        } else if (type == '319') {
            var platform = $(this).data('platform').toLowerCase();
            if (platform === 'ae') {
                platform = 'ameba';
            }

            $('#myCouponModal').fadeIn();
            $('.js-coupon-remit-cont').show();
            $('#code').prop('readonly', 'readonly').val(code).prev().removeClass('i1');
            $('.js-coupon-btn').attr('data-newtype', '319');
            $('#couponType option[value="' + platform + '"]').attr('selected', true);
            $('#couponType').attr('disabled', 'disabled').prev().removeClass('i1');
        }
    });
});

$('.js-load-more').on('click', function () {
    var show = $(this).parent().siblings('.main-cont').find('ul');

    if (show.hasClass('expansion')) {

        show.removeClass('expansion');
        $(this).html('更多');
    } else {
        show.addClass('expansion');
        $(this).html('收起');
    }

});


$(document).on('click', '.js-coupon-btn', function () {



    var newType = $('.js-coupon-btn').attr('data-newtype');

    if (newType == '419') {

        submitRedCouponRemit();

    } else if (newType == '319') {

        submitCouponRemit();
    }


});


$('.js-close-coupon-btn').on('click', function () {
    $('#myCouponModal').fadeOut();
});

$('.js-cancel-btn').on('click', function () {
    $('#myCouponModal').fadeOut();
});


//其他类手动输入优惠
$('.js-get-others-coupon').on('click', getOtherCoupons);

function getOtherCoupons() {
    $('#myCouponModal').fadeIn();
    $('#code').val('').prop('readonly', '').prev().addClass('i1');
    $('.js-coupon-remit-cont').hide();
    $('#couponType').prop('disabled', '').val('').prev().addClass('i1');
}

$('#code').on('change', checkcouponType);

function checkcouponType() {

    var code = $(this).val();
    var testLetter = /[a-zA-Z]/.test(code);

    if (testLetter) {
        var platform = code.replace(/[^a-zA-Z]/ig, '').toLowerCase();

        $('.js-coupon-remit-cont').show();
        $('#couponType option[value="' + platform + '"]').attr('selected', true);
        $('#couponType').attr('disabled', 'disabled').prev().removeClass('i1');
        $('.js-coupon-btn').attr('data-newtype', '319');
    } else {
        $('.js-coupon-remit-cont').hide();
        $('.js-coupon-btn').attr('data-newtype', '419');
    }
}

/**优惠劵**/

/**免费筹码**/


function getVipMoney() {

  var phoneNum = $('#j-phone').val();
    var accountName = $('#j-name').val();
    
    if(phoneNum != ''&& accountName != ''){

        $('#drawVipMoney').attr('disabled','disabled');

        $.post("/asp/getVipMoney.php", function (response, status) {
            if (status == "success") {
                var code = response.code;
                if (code === "10000") {
                    $('#drawVipMoney').attr('disabled', false).val('领取');

                } else {
                    $('#drawVipMoney').attr('disabled', 'disabled').val('无法领取');
                }

                $("#j-freechips").html(response.message);
            } else {
                alert(response);
            }

        }); 
    }else{
        layer.open({
            skin: 'tips-layer',
            closeBtn: false,
            content: '  为了您的账户安全，请完善您的姓名和手机号再申请优惠！',
            btn: ['确定'],
            yes:function () {
                location.href='/userManage.php?action=5';
            }
        });
    }




}

function drawVipMoney() {

    // if (accountTrueName && accountPhone) {

    $.post("/asp/drawVipMoney.php", function (response, status) {

        if (status == "success") {

            _showLayer(response, '');

            if (response == "转入成功") {
                getVipMoney();
            }
        } else {
            _showLayer(response, '');
        }

    });

    // } else {
    //     linkSet()
    // }
}

/**免费筹码**/

/**生日礼金**/
var hash = window.location.hash;

$(window).load(function () {
    if (hash) {
        if (hash == "#birthday") {
            $("#clickBirthday").trigger('click');
        }
    }
});

function getBirthdayMoney() {

    var phoneNum = $('#j-phone').val();
    var accountName = $('#j-name').val();

    if(phoneNum != ''&& accountName != ''){
        var birthDate = $("#playlunaBirth").val();

        if (birthDate) {

            var $parent = $('#tab-birthday');
            var vipMonenyMount = $parent.find('.vipMonenyMount');
            $.post("/asp/getBirthdayMoney.php", {},
                function (data, status) {
                    if ("success" == status) {
                        vipMonenyMount.val(data.message);

                        if (data.code == "10004") {
                            $('#happyBirthday').prop('disabled', true).html('已领取');
                            $('#birdthdayPlatform').attr('disabled', 'disabled').prev().removeClass('i1');
                        } else if (data.code == "10000") {
                            $('#happyBirthday').prop('disabled', false).html('立即领取');
                            $('#birdthdayPlatform').attr('disabled', false).prev().addClass('i1');
                        } else {
                            $('#happyBirthday').prop('disabled', true).html('无法领取');
                            $('#birdthdayPlatform').attr('disabled', 'disabled').prev().removeClass('i1');
                        }

                    } else {
                        customAlert('系统繁忙，请稍后再试');
                    }
                });


        } else {
            //未设置生日
            $("#ifHasBirthday").hide();
            $("#ifNoBirthday").show();

        }
    }else{
        layer.open({
            skin: 'tips-layer',
            closeBtn: false,
            content: '  为了您的账户安全，请完善您的姓名和手机号再申请优惠！',
            btn: ['确定'],
            yes:function () {
                location.href='/userManage.php?action=5';
            }
        });
    }


}

$('#ifNoBirthday').on('click', function () {

    $('.js-menu-list:nth-child(4) .list-title').trigger('click');
    $('.js-menu-list:nth-child(4) .list-item').find('li[href="#tab-personal"]').trigger('click');

});

function drawBirthdayMoney() {

        var platform = $("#birdthdayPlatform").val();
        $.post("/asp/drawBirthdayMoney.php", {"platform": platform},
            function (data, status) {
                if ("success" == status) {
                    _showLayer(data, '')
                } else {
                    _showLayer('系统繁忙，请稍后再试', '');
                }
            });

}

/**生日**/


/**好友推荐**/
$(window).load(function () {

    queryfriendMoney(); //好友推荐链接
});

function queryRecFriendAllMoney() {

    $.post('/asp/getLoginnameBonus.php',function (data) {
        $('.js-friend-total-money').html(data);
        $('.js-friend-total-money1').html(data);
        $('.js-friend-total-money2').html(data);
    });
}




//推荐记录查询
$('.js-friend-record').on('click', function () {
    queryfriendRecordTwo('1');
});



//复制
$('.js-copy-text').on('click', function () {
    copyText('1');
});

//领取弹窗
$('.js-friend-close').click(function () {
    $('#friend-modal').hide();
});

//余额查询
$('.js-friend-balance').on('click','li',function () {

    var code = $(this).data('code');

    $(this).addClass('active').siblings().removeClass('active').parent().siblings().children('li').removeClass('active');

    $.post('/asp/getGameMoney.php', {gameCode: code}, function (data) {
        $('.js-friend-balance li.active').find('span').html(data);
    });
});


//全部领取按钮
$(document).on('click','.js-one-get-button,.js-one-get-button1',function () {

    getFriendBonus("",1);

});

//领取按钮调用弹窗
function getFriendBonus(pno,type) {


    $('#friend-modal').show();

    if(type == 0){
        $.post('/asp/getLoginnameBonus.php',{type:type},function (data) {
            $('.js-friend-total-money2').html(data+'元');
        });
    }else{
        $.post('/asp/getLoginnameBonus.php',function (data) {
            $('.js-friend-total-money2').html(data+'元');
        });
    }


    $('.js-get-rec-prize').data({'pno':pno,'type':type});

}

//获得推荐金按钮
$('.js-get-rec-prize').on('click',function () {

    var pno = $(this).data('pno');
    var type = $(this).data('type');

    getBonus(pno,type);

});

//领取体验金 1全部 0 单个
function getBonus(pno,type){

    var signType = $('.js-friend-balance li.active').data('code');

    if(signType){
        if(signType =="ptasia"){

            signType ="ptasiatiger";

        }else if(signType =="ptslot"){

            signType ="ptslottiger";

        }

        $.post("/asp/newTransferInforFriend.php", {
            signType: signType,
            type:type,
            pno: pno
        }, function (returnedData, status) {
            if ("success" === status && returnedData === "转账成功") {


                layer.open({
                    skin: 'top-class tips-layer',
                    closeBtn: false,
                    content: returnedData,
                    btn: ['关闭'],
                    yes:function () {
                        $('.js-friend-balance li.active').trigger('click');
                        layer.closeAll();
                        $('#friend-modal').hide();
                        recommendDeposit(1);
                        queryRecFriendAllMoney();
                    }
                });
                


            } else {

                _showLayer(returnedData, '')
            }

        });
    }else{
        _showLayer('请选择需要转入的平台！');
    }
}




$('.js-friend-link-detail').on('click',function () {
     $('#rec-container').show().siblings().hide();
     $('.js-ui-coupon').hide();
     recommendDeposit(1);

});

$('.js-back-friend-rec').on('click',function () {
    $('#rec-container').hide().siblings().show();
    $('.js-ui-coupon').show();
});



//领取记录
function recommendDeposit(pageIndex) {


    var username = $('#j-isLogin').val();

    var today = new Date();
    var ty = today.getFullYear();
    var tm = today.getMonth() + 1; // 记得当前月是要+1的
    var td = today.getDate();
    var nowDate = ty + "-" + tm + "-" + td;



    var twoMonthAgoDate = new Date(today - 60 * 24 * 3600 * 1000);
    var y = twoMonthAgoDate.getFullYear();
    var m = twoMonthAgoDate.getMonth() + 1;
    var d = twoMonthAgoDate.getDate();
    var formatDate = y + '-' + m + '-' + d;


    openProgressBar();
    $.post("/asp/getLoginnameReceiveDetails.php", {
            "pageIndex": pageIndex,
            "size": 10,
            "starttime": formatDate,
            "endtime": nowDate
        },
        function (returnedData, status) {
            if ("success" == status) {
                closeProgressBar();

                $("#recommendDeposit").html("");
                $("#recommendDeposit").html(returnedData);
            }
        });
    return false;
}


//推荐记录
function queryfriendRecordTwo(pageIndex) {


    if (pageIndex < 1) {
        pageIndex = 1;
    }
    var username = $('#j-loginname').val();

    var today = new Date();
    var ty = today.getFullYear();
    var tm = today.getMonth() + 1; // 记得当前月是要+1的
    var td = today.getDate();
    var nowDate = ty + "-" + tm + "-" + td;


    var twoMonthAgoDate = new Date(today - 60 * 24 * 3600 * 1000);
    var y = twoMonthAgoDate.getFullYear();
    var m = twoMonthAgoDate.getMonth() + 1;
    var d = twoMonthAgoDate.getDate();
    var formatDate = y + '-' + m + '-' + d;

    $.post("/asp/getLoginnameDetails.php", {
        "pageIndex": pageIndex,
        "size": 10,
        "loginname": username,
        "starttime": formatDate,
        "endtime": nowDate,
        "friendtype": 0
    }, function (returnedData, status) {

        if ("success" == status) {
            closeProgressBar();

            var returnedDataHtml = '<div id="recommendRecord">' + returnedData + '</div>';

            layer.confirm(returnedDataHtml, {
                skin: 'coupon-layer',
                btn: 0,
                title: false,
                area: ['700px', '730px'],
            });
        }
    });
}

/**好友推荐**/




/***救援金(负盈利)***/
function getLosePromo(pno) {

    var losePromoHtml = '<div id="getLosePromo"> ' +
        '<p class="red">请选择您喜欢的老虎机平台,确定后我们不接受任何重新转至其他老虎机平台的申请</p> ' +
        '<div class="ipt-group"> ' +
        '&nbsp;&nbsp;&nbsp; <input type="radio" name="platform" value="ptasiatiger" checked="checked"> PT国际版 ' +
        '&nbsp;&nbsp;&nbsp; <input type="radio" name="platform" value="ptslottiger"> PT老虎机 ' +
        '&nbsp;&nbsp;&nbsp; <input type="radio" name="platform" value="cq9"> CQ9老虎机 ' +
        '&nbsp;&nbsp;&nbsp; <input type="radio" name="platform" value="pg"> PG老虎机' +
        '&nbsp;&nbsp;&nbsp; <input type="radio" name="platform" value="pp"> PP老虎机' +
        '&nbsp;&nbsp;&nbsp; <input type="radio" name="platform" value="ameba"> AE老虎机' +
        '<br>&nbsp;&nbsp;&nbsp; <input type="radio" name="platform" value="slot"> 中心钱包(DT/MG/NT/QT/SW/PNG)' +
        '</div> ' +
        '</div>';


    layer.confirm(losePromoHtml, {
        skin: 'coupon-layer',
        title: false,
        area: ['650px', '450px'],
        btn: ['确定']
    }, function () {

        var targetPlatform = $('#getLosePromo').find("input[name='platform']:checked").val();
        $('.btn-linqu').attr('disabled','disabled');
        $.ajax({
            type: "post",
            url: "/asp/optLosePromo.php",
            cache: false,
            data: {
                "jobPno": pno,
                "proposalFlag": 2,
                "platform": targetPlatform
            },
            success: function (data) {
                $('.btn-linqu').removeAttr('disabled');
                _showLayer(data, '');
                losePromoRecordTwo("1");
            },
            error: function () {
                _showLayer("系统错误", '');
            }
        });
    });

    // $('.layui-layer-content').css('height','auto');
}

function losePromoRecordTwo(pageIndex) {

    if (pageIndex <= 1) {
        pageIndex = 1;
    }
    openProgressBar();
    $.post("/asp/queryPTLosePromoReccords.php", {
        "pageIndex": pageIndex,
        "size": 8
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#losepromoRecordDiv");
            $("#losepromoRecordDiv").html(returnedData);
        }
    });
    return false;
}

/***救援金(负盈利)***/

/***自助晋级***/
//游戏平台投注额
function queryBets(type) {
    openProgressBar();

    $.post("/asp/queryBetOfPlatform.php", {
        "type": 'month'
    }, function (returnedData, status) {
        closeProgressBar();
        if ("success" == status) {
            $("#monthbetsDivContent").html("");
            $("#monthbetsDivContent").html(returnedData);
            var betofplatformErrMsg = $('#betofplatformErrMsg').val();
            if (betofplatformErrMsg != '')
                alert(betofplatformErrMsg);
        }
    });

    return false;
}

function checkUpgrade() {
    var upgradeHtml = '<div class="upgrade-cont"><p class="upgrade-title"><i class="iconfont icon-zizhujinji2"></i></p><p>每月只能晋级一次，确认晋级？</p></div>';

    layer.confirm(upgradeHtml, {
        skin: 'coupon-layer',
        title: false,
        area: ['570px', '390px'],
        btn: ['升级', '取消']
    }, function () {
        $.ajax({
            type: "post",
            url: "/asp/checkUpgrade.php",
            cache: false,
            data: {
                "type": 'month'
            },
            success: function (data) {

                _showLayer(data, '')
            },
            error: function () {

                _showLayer("系统错误", '')
            }
        });
    });
}

/***自助晋级***/


/***积分中心***/
function queryPointBalance() {
    var phoneNum = $('#j-phone').val();
    var accountName = $('#j-name').val();

    if(phoneNum != ''&& accountName != ''){

        $.post("/asp/queryPoints.php", function (returnedData, status) {
            if ("success" == status) {

                var level = $('#j-myLevel').val();
                var result = 0;

                var oldPoint = returnedData.oldPoint;
                var nowPoint = returnedData.nowPoint;
                var ratio = returnedData.ratio;
                var bonus = returnedData.bonus;

                $('#friendPoint2').val(nowPoint);
                $('#totalfriendPoint2').val(oldPoint);

                switch (level) {
                    case "6":
                        result = 300;
                        break;
                    case "5":
                        result = 350;
                        break;
                    case "4":
                        result = 400;
                        break;
                    case "3":
                        result = 470;
                        break;
                    case "2":
                        result = 520;
                        break;
                    case "1":
                        result = 560;
                        break;
                    case "0":
                        result = 600;
                        break;
                }

                var message = ratio + '积分兑换1元,可兑换奖金为：' + bonus + '元';

                $('#point-prize').val(message)
            }
        }).fail(function () {
            window.location.reload(true);
        });
    }else{
        layer.open({
            skin: 'tips-layer',
            closeBtn: false,
            content: '  为了您的账户安全，请完善您的姓名和手机号再申请优惠！',
            btn: ['确定'],
            yes:function () {
                location.href='/userManage.php?action=5';
            }
        });
    }




}

function submitPointRemit() {

    var signRemit = $("#pointRemit").val();
    if (signRemit != "") {
        if (isNaN(signRemit)) {
            alert("存款金额非有效数字！");
            return false;
        }
        if (signRemit < 1) {
            alert("兑换奖金金额必须大于等于1！");
            return false;
        }
    }
    openProgressBar();

    $('#submitPointRemit').attr('disabled','disabled');

    $.post("/asp/transferInforPoint.php", {
        "signRemit": signRemit,
    }, function (returnedData, status) {
        if ("success" == status) {

            $('#submitPointRemit').removeAttr('disabled');

            closeProgressBar();
            queryPointBalance();
            _showLayer(returnedData, '');

        }
    });
    return false;


}

/***积分中心***/


/**iWin保险**/

function queryPTBigBang() {
    openProgressBar();
    $.post("/asp/queryPTBigBang.php", function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#ptBigBangDiv").html("");
            $("#ptBigBangDiv").html(returnedData);
        }
    });
    return false;
}

function getPTBigBangBonus(id, platform) {
    $.ajax({
        type: "post",
        url: "/asp/getPTBigBangBonus.php",
        cache: false,
        data: {
            "ptBigBangId": id
            /* ,"platform":platform */
        },
        success: function (data) {

            _showLayer(data, '');
            queryPTBigBang();
        },
        error: function () {
            _showLayer("系统错误", '');
        }
    });
}

//催账
function urgeOrder() {
    urgeOrderRecord(1);
}


//签到转账
function submitSignRemit() {
    var signType = $("#signType").val();
    var signRemit = $("#signRemit").val();
    if (signType == "") {
        alert("请选择平台！");
        return false;
    }
    if (signRemit != "") {
        if (isNaN(signRemit)) {
            alert("存款金额非有效数字！");
            return false;
        }
        if (signRemit < 10) {
            alert("存款金额必须大于等于10！");
            return false;
        }
    }
    openProgressBar();
    $.post("/asp/transferSign.php", {
        "signType": signType,
        "signRemit": signRemit,
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            alert(returnedData);
            querySignAmount();
        }
    });
    return false;
}

function getQueryString(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}









