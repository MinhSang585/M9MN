
lay('.date-item').each(function(){
    laydate.render({
        elem: this,
        trigger: 'click'
    });
});



$('.js-record-search').on('click',searchRecord);

$('.js-ui-record').on('click','li',changeRecordType);

$('li[href="#tab-userRecords"]').on('click',recordInfo);

//初始化
function recordInfo() {
    $('.js-ui-record').find('li[href="#tab-payRecord"]').trigger('click');
}

//查询功能
function searchRecord() {

    var type = $('.js-ui-record li.active').attr('href');
    var startTime = $(type).find('.date-item[data-datenum="1"]').val();
    var endTime = $(type).find('.date-item[data-datenum="2"]').val();
    var startDay = new Date(startTime);
    var endDay = new Date(endTime);
    var duration = getD(startDay, endDay);

    // console.log(startTime,endTime);
    if(duration){
        alert(' 时间范围只能为一个月之内！');
    }else{
        if(endDay < startDay){
            alert('结束日期不能小于开始日期！');
        }else{

            switch (type){
                case"#tab-payRecord":payRecord(startTime,endTime);
                    break;
                case"#tab-depositRecord":depositRecord(startTime,endTime);
                    break;
                case"#tab-withdrawalRecord":withdrawalRecord(startTime,endTime);
                    break;
                case"#tab-depositOrderRecord":depositOrderRecord(startTime,endTime);
                    break;
                case"#tab-transferRecord":transferRecord(startTime,endTime);
                    break;
                case"#tab-selfDefectionRecord":selfDefectionRecord(startTime,endTime);
                    break;
                case"#tab-offersferRecord":offersferRecord(startTime,endTime);
                    break;
                case"#tab-couponRecord":couponRecord(startTime,endTime);
                    break;
                case"#tab-querypointRecord":querypointRecord(startTime,endTime);
                    break;

            }
        }
    }

}

function getD(sDate, endDate) {
    var sDate = new Date(sDate);
    var eDate = new Date(endDate);

    if (eDate.getFullYear() - sDate.getFullYear() > 1) {//先比较年
        return true;
    } else if (eDate.getMonth() - sDate.getMonth() > 1) {//再比较月
        return true;
    } else if (eDate.getMonth() - sDate.getMonth() == 1) {
        if (eDate.getDate() - sDate.getDate() >= 1) {
            return true;
        }
    }
    else if (eDate.getFullYear() - sDate.getFullYear() == 1) {
        if (eDate.getMonth()+12 - sDate.getMonth() > 1) {
            return true;
        }
        else if (eDate.getDate() - sDate.getDate() >= 1) {
            return true;
        }
    }
    return false;
}

//切换标签
function changeRecordType() {

    //获取三十天前日期
    var myDate = new Date();
    var lw = new Date(myDate - 1000 * 60 * 60 * 24 * 30);
    var lastY = lw.getFullYear();
    var lastM = lw.getMonth()+1;
    var lastD = lw.getDate();
    var startTime = lastY+"-"+(lastM<10 ? "0" + lastM : lastM)+"-"+(lastD<10 ? "0"+ lastD : lastD);

    //获取当前日期
    var nowY = myDate.getFullYear();
    var nowM = myDate.getMonth()+1;
    var nowD = myDate.getDate();
    var endTime = nowY+"-"+(nowM<10 ? "0" + nowM : nowM)+"-"+(nowD<10 ? "0"+ nowD : nowD);

    // console.log(startTime,endTime)

    var type = $(this).attr('href');

    $(type).find('.date-item[data-datenum="1"]').val(startTime);
    $(type).find('.date-item[data-datenum="2"]').val(endTime);


    switch (type){
        case"#tab-payRecord":payRecord(startTime,endTime);
            break;
        case"#tab-depositRecord":depositRecord(startTime,endTime);
            break;
        case"#tab-withdrawalRecord":withdrawalRecord(startTime,endTime);
            break;
        case"#tab-depositOrderRecord":depositOrderRecord(startTime,endTime);
            break;
        case"#tab-transferRecord":transferRecord(startTime,endTime);
            break;
        case"#tab-selfDefectionRecord":selfDefectionRecord(startTime,endTime);
            break;
        case"#tab-offersferRecord":offersferRecord(startTime,endTime);
            break;
        case"#tab-couponRecord":couponRecord(startTime,endTime);
            break;
        case"#tab-querypointRecord":querypointRecord(startTime,endTime);
            break;

    }
}

//在线支付记录
function payRecord(startTime,endTime) {
    openProgressBar();
    $.post("/asp/depositRecords.php", {
        "pageIndex": 1,
        "size": 8,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#payRecordDiv").html("");
            $("#payRecordDiv").html(returnedData);
        }
    });
    return false;
}

//存款记录
function depositRecord(startTime,endTime) {

    openProgressBar();
    $.post("/asp/cashinRecords.php", {
        "pageIndex": 1,
        "size": 8,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#depositRecordDiv").html("");
            $("#depositRecordDiv").html(returnedData);
        }
    });
    return false;
}

//提款记录
function withdrawalRecord(startTime,endTime) {

    openProgressBar();
    $.post("/asp/withdrawRecords.php", {
        "pageIndex": 1,
        "size": 8,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#withdrawalRecordDiv").html("");
            $("#withdrawalRecordDiv").html(returnedData);
        }
    });
    return false;
}

//秒存附言记录
function depositOrderRecord(startTime,endTime) {

    openProgressBar();
    $.post("/asp/depositOrderRecord.php", {
        "pageIndex": 1,
        "size": 8,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#depositOrderRecordDiv").html("");
            $("#depositOrderRecordDiv").html(returnedData);
        }
    });
    return false;
}

//转账记录
function transferRecord(startTime,endTime) {

    openProgressBar();
    $.post("/asp/transferRecords.php", {
        "pageIndex": 1,
        "size": 8,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#transferRecordDiv").html("");
            $("#transferRecordDiv").html(returnedData);
        }
    });
    return false;
}

//自助返水优惠记录
function selfDefectionRecord(startTime,endTime) {
    openProgressBar();
    $.post("/asp/searchXima.php", {
        "pageno": 1,
        "maxRowsno": 4,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#selfDefectionRecordDiv").html("");
            $("#selfDefectionRecordDiv").html(returnedData);
        }
    });
    return false;
}

//优惠记录
function offersferRecord(startTime,endTime) {
    openProgressBar();
    $.post("/asp/consRecords.php", {
        "pageIndex": 1,
        "size": 8,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#offersRecordDiv").html("");
            $("#offersRecordDiv").html(returnedData);
        }
    });
    return false;
}

//优惠券记录
function couponRecord(startTime,endTime) {
    openProgressBar();
    $.post("/asp/couponRecords.php", {
        "pageIndex": 1,
        "size": 5,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#couponRecordDiv").html("");
            $("#couponRecordDiv").html(returnedData);
        }
    });
    return false;
}

//玩家积分记录
function querypointRecord(startTime,endTime) {
    openProgressBar();
    $.post("/asp/querypointRecord.php", {
        "pageIndex": 1,
        "size": 8,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#pointRecordDiv").html("");
            $("#pointRecordDiv").html(returnedData);
        }
    });
    return false;
}




/**后台分页调用方法**/

//在线支付记录
function payRecordTwo(pageIndex) {

    var type = $('.js-ui-record li.active').attr('href');
    var startTime = $(type).find('.date-item[data-datenum="1"]').val();
    var endTime = $(type).find('.date-item[data-datenum="2"]').val();

    if (pageIndex <= 1) {
        pageIndex = 1;
    }
    openProgressBar();
    $.post("/asp/depositRecords.php", {
        "pageIndex": pageIndex,
        "size": 8,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#payRecordDiv").html("");
            $("#payRecordDiv").html(returnedData);
        }
    });
    return false;
}

//存款记录
function depositRecordTwo(pageIndex) {

    var type = $('.js-ui-record li.active').attr('href');
    var startTime = $(type).find('.date-item[data-datenum="1"]').val();
    var endTime = $(type).find('.date-item[data-datenum="2"]').val();

    if (pageIndex <= 1) {
        pageIndex = 1;
    }
    openProgressBar();
    $.post("/asp/cashinRecords.php", {
        "pageIndex": pageIndex,
        "size": 8,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#depositRecordDiv").html("");
            $("#depositRecordDiv").html(returnedData);
        }
    });
    return false;
}


//存款附言记录
function depositOrderRecordTwo(pageIndex) {
    var type = $('.js-ui-record li.active').attr('href');
    var startTime = $(type).find('.date-item[data-datenum="1"]').val();
    var endTime = $(type).find('.date-item[data-datenum="2"]').val();

    if (pageIndex <= 1) {
        pageIndex = 1;
    }
    openProgressBar();
    $.post("/asp/depositOrderRecord.php", {
        "pageIndex": pageIndex,
        "size": 8,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#depositOrderRecordDiv").html("");
            $("#depositOrderRecordDiv").html(returnedData);
        }
    });
    return false;
}

//提款记录
function withdrawalRecordTwo(pageIndex) {
    var type = $('.js-ui-record li.active').attr('href');
    var startTime = $(type).find('.date-item[data-datenum="1"]').val();
    var endTime = $(type).find('.date-item[data-datenum="2"]').val();

    if (pageIndex <= 1) {
        pageIndex = 1;
    }
    openProgressBar();
    $.post("/asp/withdrawRecords.php", {
        "pageIndex": pageIndex,
        "size": 8,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#withdrawalRecordDiv").html("");
            $("#withdrawalRecordDiv").html(returnedData);
        }
    });
    return false;
}

//转账记录
function transferRecordTwo(pageIndex) {
    var type = $('.js-ui-record li.active').attr('href');
    var startTime = $(type).find('.date-item[data-datenum="1"]').val();
    var endTime = $(type).find('.date-item[data-datenum="2"]').val();

    if (pageIndex <= 1) {
        pageIndex = 1;
    }
    openProgressBar();
    $.post("/asp/transferRecords.php", {
        "pageIndex": pageIndex,
        "size": 8,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#transferRecordDiv").html("");
            $("#transferRecordDiv").html(returnedData);
        }
    });
    return false;
}

//优惠记录
function offersferRecordTwo(pageIndex) {

    var type = $('.js-ui-record li.active').attr('href');
    var startTime = $(type).find('.date-item[data-datenum="1"]').val();
    var endTime = $(type).find('.date-item[data-datenum="2"]').val();

    if (pageIndex <= 1) {
        pageIndex = 1;
    }
    openProgressBar();
    $.post("/asp/consRecords.php", {
        "pageIndex": pageIndex,
        "size": 8,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#offersRecordDiv").html("");
            $("#offersRecordDiv").html(returnedData);
        }
    });
    return false;
}


//优惠券记录
function couponRecordTwo(pageIndex) {
    var type = $('.js-ui-record li.active').attr('href');
    var startTime = $(type).find('.date-item[data-datenum="1"]').val();
    var endTime = $(type).find('.date-item[data-datenum="2"]').val();

    if (pageIndex <= 1) {
        pageIndex = 1;
    }
    openProgressBar();
    $.post("/asp/couponRecords.php", {
        "pageIndex": pageIndex,
        "size": 5,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#couponRecordDiv").html("");
            $("#couponRecordDiv").html(returnedData);
        }
    });
    return false;
}

//自助返水优惠记录
function selfDefectionRecordTwo(pageIndex) {
    var type = $('.js-ui-record li.active').attr('href');
    var startTime = $(type).find('.date-item[data-datenum="1"]').val();
    var endTime = $(type).find('.date-item[data-datenum="2"]').val();

    if (pageIndex <= 1) {
        pageIndex = 1;
    }
    openProgressBar();
    $.post("/asp/searchXima.php", {
        "pageno": pageIndex,
        "maxRowsno": 4,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#selfDefectionRecordDiv").html("");
            $("#selfDefectionRecordDiv").html(returnedData);
        }
    });
    return false;
}

//玩家积分记录
function querypointRecordTwo(pageIndex) {

    var type = $('.js-ui-record li.active').attr('href');
    var startTime = $(type).find('.date-item[data-datenum="1"]').val();
    var endTime = $(type).find('.date-item[data-datenum="2"]').val();

    openProgressBar();
    if (pageIndex < 1) {
        pageIndex = 1;
    }
    $.post("/asp/querypointRecord.php", {
        "pageIndex": pageIndex,
        "size": 8,
        "starttime":startTime,
        "endtime":endTime
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            $("#pointRecordDiv").html("");
            $("#pointRecordDiv").html(returnedData);
        }
    });
    return false;
}



function chkUndefined(str) {

    if (typeof str == 'undefined' || str == "") {
        str = "";
    }

    return str;
}