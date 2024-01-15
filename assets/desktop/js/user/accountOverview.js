/**
 * Created by 1040170 on 2018/7/9.
 */

$(document).ready(function () {
    checkQuickMenu();
    queryLoseOrWin();
});

$(window).load(function () {
    refreshBalance();
    privateQrcode();
    queryPoint();
});

//mainheader的快捷存取款判断

// 故增加秒数，让首頁图表加载一会儿，再往下走
function checkQuickMenu() {
    var action = getQueryString("action");

    if (action == 1) {
        $('.js-quick-deposit').trigger('click');

    } else if (action == 2) {

        setTimeout(function () {
            $('.js-quick-withdrawal').trigger('click');
        },2000)


    } else if (action == 3) {

        $('.js-menu-list:nth-child(2) .list-title').trigger('click');
        $('.js-menu-list:nth-child(2) .list-item').find('li[href="#tab-transfer"]').trigger('click');

    } else if (action == 4) {

        $('.js-menu-list:nth-child(5) .list-title').trigger('click');
        $('.js-menu-list:nth-child(5) .list-item').find('li[href="#tab-userLetter"]').trigger('click');

    } else if (action == 5) {
        $('.js-menu-list:nth-child(4) .list-title').trigger('click');
        $('.js-menu-list:nth-child(4) .list-item').find('li[href="#tab-personal"]').trigger('click');

    }
    else if (action == 6) {
        $('.js-menu-list:nth-child(2) .list-title').trigger('click');


        // $('.js-ui-coupon').find('li[href="#tab-coupon"]').trigger('click')
        setTimeout(function () {
            $('.js-menu-list:nth-child(2) .list-item').find('li[href="#tab-userCoupons"]').trigger('click');
        },1000)


    }
}


// 具体问题：先点击站内进，返回首頁看不到图表
$(document).on('click', 'li[href="#tab-home"]', function () {
    location.href = "/userManage.php";
});

//
$('.js-navbar li').removeClass('active');

$('.js-menu-list').on('click', '.list-title', function () {

    $(this).addClass('active').parents('.js-menu-list').siblings().children('.list-title').removeClass('active');

    $(this).next('.list-item').addClass('active').parents('.js-menu-list').siblings().children('.list-item').removeClass('active');


});

$('.js-menu-list').on('click', '.list-item li', function () {

    $('html,body').animate({scrollTop: 0}, 500);
});


/*首頁四图展示*/

//捕鱼/真人/体育/彩票/电竞
function liveFishCommon(list, num, titleName) {
    var platValue = [];
    var betTotal = [];

    for (i = 0; i < list.length; i++) {
        var platform = '';
        switch (list[i]['platform']) {
            case 'aginfish':
                platform = 'AG';
                break;
            case 'ig':
                platform = 'IG';
                break;
            case 'swfish':
                platform = 'SW';
                break;
            case 'hyg':
                platform = 'HYG';
                break;
            case 'agin':
                platform = 'AG';
                break;
            case 'blqp':
                platform = '博乐棋牌';
                break;
            case 'dtchess':
                platform = 'DT棋牌';
                break;
            case 'as':
                platform = 'AS真人棋牌';
                break;
            case 'hlqp':
                platform = '欢乐棋牌';
                break;
            case 'kyqp':
                platform = '开元棋牌';
                break;
            case 'ea':
                platform = 'EA';
                break;
            case 'ebet':
                platform = 'EABT';
                break;
            case 'bbinvid':
                platform = 'BBIN';
                break;
            case 'sb':
                platform = '沙巴体育';
                break;
            case 'xj':
                platform = 'iWin体育';
                break;
            case 'mglive':
                platform = 'MG真人';
                break;
            case 'sunbet':
                platform = '申博真人';
                break;
            case 'agqj':
                platform = 'AG旗舰';
                break;
        }
        platValue.push(platform);
        betTotal.push(list[i]['bettotal'])

    }

    barChart(num, platValue, betTotal, titleName);
}

//柱状图公用方法
function barChart(num, platform, bettotal, titleName) {

    var myChart = echarts.init(document.getElementById('main' + num));
    myChart.setOption({
        title: {
            text: titleName,
            left: '3%',
            top: '3%'
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'line'
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: {
            data: platform,
            axisLabel: {
                show: true,
                interval: 0,
                // rotate:-15,
                textStyle: {
                    // color: '#000',
                    // fontSize:'10'
                }
            }
        },

        yAxis: {},
        series: [{
            barGap: '1%',
            barCategoryGap: '50%',
            name: '金额',
            type: 'bar',
            itemStyle: {
                normal: {
                    color: '#dcb66f'
                }
            },
            data: bettotal
        }]
    });
}


//饼状图方法
function pieChart(pid, platform, data, title) {

    var myChart = echarts.init(document.getElementById("main" + pid));


    myChart.setOption({

        title: {
            text: title,
            left: '3%',
            top: '3%'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        color: ['#2491e5', '#6c7eea', '#c288e5', '#e678c0', '#ff738a ', '#ff998b', '#fdb79b', '#f4bf4a', '#f2d563', '#dece82'],
        legend: {
            type: 'scroll',
            orient: 'vertical',
            show: true,
            right: 20,
            top: 20,
            bottom: 20,
            data: platform
        },
        series: [
            {
                name: '',
                type: 'pie',
                radius: ['50%', '70%'],
                avoidLabelOverlap: false,
                label: {
                    normal: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        show: true,
                        textStyle: {
                            fontSize: '30',
                            fontWeight: 'bold'
                        }
                    }
                },
                labelLine: {
                    normal: {
                        show: false
                    }
                },
                data: data
            }
        ]
    });
}

//异步加载数据
function queryLoseOrWin() {
    $.post('/asp/queryLoseOrWin.php', function (data) {
        if (data) {
            var loseOrWinlist = data.loseOrWinlist;
            var tigerPlatformList = data.tigerPlatformList;
            var liveAndSportsList = data.liveAndSportsList;
            var chessFishLotteryList = data.chessFishLotteryList;
            var currentMonth = data.date;

            //iWin四月份数据
            if (loseOrWinlist.length > 0) {

                var title = 'iWin' + currentMonth + '月数据',
                    loseOrWinPlatForm = [],
                    loseOrWinBetTotal = [];

                for (var key in loseOrWinlist[0]) {

                    loseOrWinBetTotal.push(parseFloat(loseOrWinlist[0][key]));
                    var keyName = '';

                    switch (key) {
                        case 'deposit':
                            keyName = '存款';
                            break;
                        case 'withdrawal':
                            keyName = '提款';
                            break;
                        case 'bettotal':
                            keyName = '投注';
                            break;

                    }

                    loseOrWinPlatForm.push(keyName);
                }

                console.log(loseOrWinPlatForm, loseOrWinBetTotal);
                barChart('0', loseOrWinPlatForm, loseOrWinBetTotal, title)
            } else {
                barChart('0', ['存款', '提款', '投注'], [0, 0, 0], 'iWin' + currentMonth + '月数据');
            }

            //老虎机投注额
            if (tigerPlatformList) {

                var tigerPlatformListData = [
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''}
                ];
                var platform = [];

                for (i = 0; i < tigerPlatformList.length; i++) {
                    var slotPlatform = '';
                    switch (tigerPlatformList[i]['platform']) {
                        case 'dt':
                            slotPlatform = 'DT';
                            break;
                        case 'ptsky':
                            slotPlatform = 'SW';
                            break;
                        case 'nt':
                            slotPlatform = 'NT';
                            break;
                        case 'mg':
                            slotPlatform = 'MG';
                            break;
                        case 'ptslot':
                            slotPlatform = 'PT';
                            break;
                        case 'qt':
                            slotPlatform = 'QT';
                            break;
                        case 'png':
                            slotPlatform = 'PNG';
                            break;
                        case 'aginslot':
                            slotPlatform = 'AG';
                            break;
                        case 'pg':
                            slotPlatform = 'PG';
                            break;
                        case 'cq9':
                            slotPlatform = 'CQ9';
                            break;
                        case 'ptasia':
                            slotPlatform = 'PT国际';
                            break;
                        case 'ameba':
                            slotPlatform = 'AE';
                            break;
                        default:
                            break;
                    }
                    platform.push(slotPlatform);

                    tigerPlatformListData[i]['name'] = slotPlatform;
                    tigerPlatformListData[i]['value'] = tigerPlatformList[i]['bettotal'];
                }

                var slotTitleName = '老虎机' + currentMonth + '月投注金额';

                pieChart("1", platform, tigerPlatformListData, slotTitleName);

            }


            if (liveAndSportsList) {

                var sportsList = [
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''},
                    {name: '', value: ''}
                ];
                var sportPlatform = [];

                for (i = 0; i < liveAndSportsList.length; i++) {
                    var livePlatform = '';
                    switch (liveAndSportsList[i]['platform']) {
                        case 'agin':
                            livePlatform = 'AG真人';
                            break;
                        case 'ea':
                            livePlatform = 'EA真人';
                            break;
                        case 'ebet':
                            livePlatform = 'EBET真人';
                            break;
                        case 'bbinvid':
                            livePlatform = 'BBIN真人';
                            break;
                        case 'sb':
                            livePlatform = '沙巴体育';
                            break;
                        case 'xj':
                            livePlatform = 'iWin体育';
                            break;
                        case 'mglive':
                            livePlatform = 'MG真人';
                            break;
                        case 'sunbet':
                            livePlatform = '申博真人';
                            break;
                        case 'agqj':
                            livePlatform = 'AG旗舰';
                            break;
                    }
                    sportPlatform.push(livePlatform);

                    sportsList[i]['name'] = livePlatform;
                    sportsList[i]['value'] = liveAndSportsList[i]['bettotal'];
                }

                var sportTitleName = '真人/体育' + currentMonth + '月投注金额';

                pieChart("2", sportPlatform, sportsList, sportTitleName);
            }

            //捕鱼/彩票/棋牌
            if (chessFishLotteryList) {

                var chessTitleName = '捕鱼/棋牌' + currentMonth + '月投注额';

                liveFishCommon(chessFishLotteryList, '3', chessTitleName);
            }


        } else {
            alert('系统异常!')
        }

    });
}


/*首頁四图展示*/


//快捷入口

$('.js-search-balance').on('click', listsBalanceHtml);
$('.js-quick-deposit').on('click', quickDeposit);
$('.js-quick-withdrawal').on('click', quickDeposit);
$('.js-info-link').on('click', 'li', quickDeposit);
$('.js-refresh-balance').on('click', refreshBalance);

$(document).on('click', '.js-show-balance', checkBalance);


function getQueryString(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

//查询余额
function checkBalance(e) {

    $(e.target).css({'font-size': '14px'}).html('加载中...');

    var gameCode = $(e.target).data('val');

    $.post('/asp/getGameMoney.php', {gameCode: gameCode}, function (data) {

        $(e.target).parent().html(data);
    });
}

//专属二维码
function privateQrcode() {

    $.post('/asp/queryQrcode.php', function (data) {
        if (data != "[]") {
            var returnData = JSON.parse(data);
            var timeDetail = returnData[0].timeDetail;
            var codeType = returnData[0].codeChannel === 0 ? 'QQ' : '微信';
            var qycodeHtml = '<div><img  class="code-img" src=' + returnData[0].address + '></div><div  class="tips"><p>' + codeType + '专属客服二维码<br>工作时间:' + timeDetail + '</p></div>';

            $('.js-private-qrcode').html(qycodeHtml);
        } else {
            $('.js-private-qrcode').html('');
        }
    })
}

//刷新余额
function refreshBalance() {
    $.ajax({
        type: "post",
        url: "/asp/refreshUserBalance.php",
        cache: false,
        beforeSend: function () {
            $(".j-balance").html("正在刷新..");
        },
        success: function (data) {
            if ($.isNumeric(data)) {
                $('.j-balance').html(data);
            }
        },
        error: function () {
            alert("服务繁忙");
        }
    });
}

//快捷入口
function quickDeposit() {

    var type = $(this).data('type'),
        num = $(this).data('num');

    $('.js-menu-list:nth-child(' + num + ') .list-title').trigger('click');

    if (type == '1') {

        $('.js-menu-list:nth-child(2) .list-item').find('li[href="#tab-deposit"]').trigger('click');

    } else if (type == '2') {

        $('.js-menu-list:nth-child(2) .list-item').find('li[href="#tab-withdraw"]').trigger('click');

    } else if (type == '3') {

        $('.js-menu-list:nth-child(4) .list-item').find('li[href="#tab-personal"]').trigger('click');
    } else if (type == '4') {

        $('.js-menu-list:nth-child(4) .list-item').find('li[href="#tab-card-binding"]').trigger('click');

    } else if (type == '5') {

        $('.js-menu-list:nth-child(5) .list-item').find('li[href="#tab-userLetter"]').trigger('click');
    }


}

//明细
function listsBalanceHtml() {

    var platformArr = [
        {
            "ptasia": "PT国际版账户",
            "ptslot": "PT账户",
            "slot": "中心钱包",
            "ameba": "AE账户",
            "cq9": "CQ9账户",
            "pg": "PG账户"
        },
        {
            "agin": "AG账户",
            "agqj": "AG旗舰真人账户",
            "ea": "EA账户",
            "ebetapp": "EBET账户",
            "bbin": "BBIN账户",
            "live": "MG账户",
            "sunbet": "申博账户"
        },
        {"sba": "沙巴账户","xj": "iWin体育"},
        {"mwg": "MWG账户", "kyqp": "开元棋牌账户", "blqp": "博乐棋牌账户", "as": "AS真人棋牌账户"}
    ];


    var slotArr = ['老虎机账户', '真人账户', '体育账户', '捕鱼/棋牌账户'];

    var platformHtmlArr = [];
    var totalHtml = '';

    for (i = 0; i < slotArr.length; i++) {

        var item = '';

        for (var key in platformArr[i]) {

            item += '<li> <p>' + platformArr[i][key] + '</p> <p><i data-val="' + key + '" class="js-show-balance show-balance iconfont icon-shuaxin2"></i></p></li>';
        }

        platformHtmlArr.push(item);

        totalHtml += '<h3 class="firstpage-balance-title">' + slotArr[i] + '</h3><ul class="firstpage-balance-lists">' + platformHtmlArr[i] + '</ul> ';
    }


    layer.open({
        btn: ['确定'],
        title: false,
        skin: 'coupon-layer balance-detail-layer',
        area: ['800px', '650px'],

        content: '<div id="ui-balance">' + totalHtml + '</div>'
    });
}

