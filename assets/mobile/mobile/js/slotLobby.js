/**
 * Created by 1040170 on 2018/3/20.
 */

$(function () {
    gameShow();
    checkUrl();
    gameTab();
});

header.find('.header-title').text('游戏大厅');

/*类型切换*/
function gameTab() {
    $('.js-game-type').on('click', 'li', function () {

        $(this).addClass('active').siblings('.active').removeClass('active');

        $('.js-games-container .js-tab-panel').eq($(this).index()).css({'display': 'flex'}).siblings().hide();

    });
}

/*检查url*/
function checkUrl() {

    var str = window.location.href;
    var num = str.split('=')[1];

    $('.js-game-type>li').eq(num).addClass('active').siblings('.active').removeClass('active');

    $('.js-games-container .js-tab-panel').eq(num).css({'display': 'flex'}).siblings().hide();
}


/*配置平台*/
function gameShow() {

    var gamesData = [
        [
            {
                "id": "ptGame",
                "title": "PT老虎机",
                "imgUrl": "slot-pt.png",
                "subtype": "slot",
                "type": "PT",
                "rec": "/images/hot-icon.png"
            },
            {
                "id": "ptaisaGame",
                "title": "PT国际版老虎机",
                "imgUrl": "slot-ptaisa.jpg",
                "subtype": "slot",
                "type": "PTAISA",
                "rec": "/images/hot-icon.png"
            },
            {
                "id": "dtGame",
                "title": "DT老虎机",
                "imgUrl": "slot-dt.png",
                "subtype": "slot",
                "type": "DT",
                "rec": "/images/hot-icon.png"
            },
            {
                "id": "skyGame",
                "title": "SW老虎机",
                "imgUrl": "slot-sky.png",
                "subtype": "slot",
                "type": "PTSW"
            },
            {
                "id": "ppGame",
                "title": "PP老虎机",
                "imgUrl": "slot-pp1.jpg",
                "subtype": "slot",
                "type": "PP"
            },
            {
                "id": "mgGame",
                "title": "MG老虎机",
                "imgUrl": "slot-mg.png",
                "subtype": "slot",
                "type": "MGS"
            },
            {
                "id": "pgGame",
                "title": "PG老虎机",
                "imgUrl": "slot-pg.jpg",
                "subtype": "slot",
                "type": "PG",
                "rec": "/images/hot-icon.png"
            },
            {
                "id": "cq9Game",
                "title": "CQ9老虎机",
                "imgUrl": "slot-cq9.jpg",
                "subtype": "slot",
                "type": "CQ9"
            },
            {
                "id": "aeGame",
                "title": "AE老虎机",
                "imgUrl": "slot-ae.jpg",
                "subtype": "slot",
                "type": "AE",
                "rec": "/images/new-icon.png"
            },
            {
                "id": "pngGame",
                "title": "PNG老虎机",
                "imgUrl": "slot-png.png",
                "subtype": "slot",
                "type": "PNG"
            },
            {
                "id": "qtGame",
                "title": "QT老虎机",
                "imgUrl": "slot-qt.png",
                "subtype": "slot",
                "type": "QT"
            },
            {
                "id": "ntGame",
                "title": "NT老虎机",
                "imgUrl": "slot-nt.png",
                "subtype": "slot",
                "type": "NT"
            },
            {
                "id": "agGame",
                "title": "AG老虎机",
                "imgUrl": "slot-ag.png",
                "subtype": "slot",
                "type": "XIN"
            }
        ],
        [
            {
                "id": "agqjLive",
                "title": "AG旗舰真人游戏",
                "imgUrl": "live-agqj.jpg",
                "subtype": "live",
                "type": "AGQJLIVE"
            },
            {
                "id": "agLive",
                "title": "AG国际真人游戏",
                "imgUrl": "live-ag.png",
                "subtype": "live",
                "type": "AGLIVE"
            },
            {
                "id": "bbinLive",
                "title": "BBIN真人游戏",
                "imgUrl": "live-bbin.png",
                "subtype": "live",
                "type": "BBINLIVE"
            },
            {
                "id": "mgLive",
                "title": "MG真人游戏",
                "imgUrl": "live-mg.jpg",
                "subtype": "live",
                "type": "MGLIVE"
            },
            {
                "id": "sunLive",
                "title": "申博真人游戏",
                "imgUrl": "live-sunbet.jpg",
                "subtype": "live",
                "type": "SUNBETLIVE"
            },
            {
                "id": "eaLive",
                "title": "EA真人游戏",
                "imgUrl": "live-ea.png",
                "subtype": "live",
                "type": "EALIVE"
            },
            {
                "id": "ebetLive",
                "title": "EBET真人游戏",
                "imgUrl": "live-ebet.png",
                "subtype": "live",
                "type": "EBETLIVE"
            }
        ],
        [
            {
                "id": "xjSport",
                "title": "iWin体育",
                "imgUrl": "sport-xj1.png",
                "subtype": "sport",
                "type": "xjSport"
            },
            {
                "id": "sbSport",
                "title": "沙巴体育",
                "imgUrl": "sport-sb1.png",
                "subtype": "sport",
                "type": "sbSport"
            }
        ],
        [
            {
                "id": "hlSport",
                "title": "欢乐棋牌",
                "imgUrl": "sport-hl.jpg",
                "subtype": "sport",
                "type": "hlSport"
            },
            {
                "id": "asSport",
                "title": "AS真人棋牌",
                "imgUrl": "sport-as.jpg",
                "subtype": "sport",
                "type": "asSport"
            },
            {
                "id": "blSport",
                "title": "博乐棋牌",
                "imgUrl": "sport-bl1.jpg",
                "subtype": "sport",
                "type": "blSport"
            },
            {
                "id": "dtSport",
                "title": "DT棋牌",
                "imgUrl": "sport-dt.jpg",
                "subtype": "sport",
                "type": "dtSport"
            },
            {
                "id": "kyqpSport",
                "title": "开元棋牌",
                "imgUrl": "sport-kyqp.jpg",
                "subtype": "sport",
                "type": "kyqpSport"
            },
            {
                "id": "agFish",
                "title": "AG捕鱼",
                "imgUrl": "fish-ag1.png",
                "subtype": "fish",
                "type": "agFish"
            },
            {
                "id": "skyFish",
                "title": "SW捕鱼",
                "imgUrl": "fish-sky.png",
                "subtype": "fish",
                "type": "skyFish"
            }
        ],
        [
            {
                "id": "ptaisaDown",
                "title": "PT国际",
                "imgUrl": "down-pt.png",
                "subtype": "down",
                "type": "ptaisaDown"
            },
            {
                "id": "qyDown",
                "title": "iWinAPP客户端",
                "imgUrl": "down-qy.png",
                "subtype": "down",
                "type": "qyDown"
            },
            {
                "id": "dtDown",
                "title": "DT老虎机",
                "imgUrl": "down-dt.png",
                "subtype": "down",
                "type": "dtDown"
            },
            {
                "id": "agDown",
                "title": "AG百家乐",
                "imgUrl": "down-ag.png",
                "subtype": "down",
                "type": "agDown"
            },
            {
                "id": "ptDown",
                "title": "PT老虎机",
                "imgUrl": "down-pt.png",
                "subtype": "down",
                "type": "ptDown"
            },
            {
                "id": "ebetDown",
                "title": "EBET百家乐",
                "imgUrl": "down-ebet.png",
                "subtype": "down",
                "type": "ebetDown"
            }
            // {
            //     "id": "agfishDown",
            //     "title": "AG捕鱼",
            //     "imgUrl": "down-agfish.png",
            //     "subtype": "down",
            //     "type": "agDown"
            // }
        ]
    ];

    var platArr = [];
    for (var i = 0; i < gamesData.length; i++) {
        var gamesHtml = '';

        for (j = 0; j < gamesData[i].length; j++) {

            if (gamesData[i][j].rec) {
                gamesHtml += '<div class="game-box" id=' + gamesData[i][j].id + ' data-type="' + gamesData[i][j].type + '" data-subtype="' + gamesData[i][j].subtype + '"> '
                    + '<img src="/mobile/images/gameicon/' + gamesData[i][j].imgUrl + '">'
                    + ' <h3 class="text">' + gamesData[i][j].title + '</h3> '
                    + ' <img class="hot-icon" src="' + gamesData[i][j].rec + '">'
                    + '</div>';
            } else {
                gamesHtml += '<div class="game-box" id=' + gamesData[i][j].id + ' data-type="' + gamesData[i][j].type + '" data-subtype="' + gamesData[i][j].subtype + '"> '
                    + '<img src="/mobile/images/gameicon/' + gamesData[i][j].imgUrl + '">'
                    + ' <h3 class="text">' + gamesData[i][j].title + '</h3> '
                    + '</div>';
            }


        }

        var lists = ' <div class="js-tab-panel tab-panel"> ' + gamesHtml + ' </div>';

        platArr.push(lists);
    }

    $('.js-games-container').html(platArr.join(''));
    $('.js-games-container .js-tab-panel').eq(0).addClass('active');
    $('#qyDown').addClass('appdown');
}


/*老虎机*/
//$(document).on('click', '.game-box[data-subtype="slot"]', function () {
//    var type = $(this).data('type');
//    window.location.href = 'mobile/app/gameLobby.jsp?num=0&showType=' + type;
//});



