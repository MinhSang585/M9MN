
$(function () {
    initHotGame();
});


function initHotGame() {

    $.getJSON( '/data/slot/pc/hotgame.json?v=1', function (data) {
        buildHtml(data);
    });

}



function buildHtml(data) {
    var _ret = [], animaClass = '';

    var  tpl = ['<div class="game-info box {{class}}" id="{{id}}" data-subtype="{{subType}}" data-tag="{{tag}}">',
        '       <div class="game-pic">',
        '           <img class="lazy" data-original="{{categoryPic}}{{pic}}" width="200" height="200" alt="">',
        '       </div>',
        '       <div class="name">',
        '           <h4>{{name}}<a class="category" href="/slotGame.jsp?showtype={{category}}">{{category}}</a><sub>{{eName}}</sub></h4>',
        '       </div>',
        '       <div class="game-brief">',
        '           <div class="btn-wp text-center">',
        '                {{linkPlay}} ',
        '                {{linkDemo}}',
        '                {{jackPot}}',
        '</div>',
        '       </div>',
        '  </div>'].join('');

    $.each(data, function (i, o) {

        var jackpot = "";
        if (o.category == 'DT' && o.jackpot == 1) {
            jackpot = "<i class='jackpot-flag'></i>";
        } else {
            jackpot = "";
        }

        var category1 = '';
        if(o.category == "MGS"){
            category1 ='MG'
        }else if(o.category == "PTAISA"){
            category1 = "PT国际"
        }else{
            category1 = o.category;
        }

        _ret.push(tpl.replace(/\{\{name\}\}/g, o.name)
            .replace(/\{\{pic\}\}/g, o.pic)
            .replace(/\{\{id\}\}/g, o.id)
            .replace(/\{\{class\}\}/g, animaClass)
            .replace(/\{\{categoryPic\}\}/g, SlotMg.ImgUrl[o.category])
            .replace(/\{\{key\}\}/g, '')
            .replace(/\{\{eName\}\}/g, o.eName || '')
            .replace(/\{\{category\}\}/g,  category1 || '')
            .replace(/\{\{tag\}\}/g, o.tag.join(','))
            .replace(/\{\{json\}\}/g, JSON.stringify(o))
            .replace(/\{\{subType\}\}/g, o.subType)
            .replace(/\{\{collectAction\}\}/g, o.isCollect ? '<i class="iconfont icon-heart2"></i>已收藏' : '<i class="iconfont icon-heart"></i>添加收藏')
            .replace(/\{\{isFavorite\}\}/g, o.isCollect ? ' data-favorite ' : '')
            .replace(/\{\{linkDemo\}\}/g, SlotMg.getLinkDemo(o))
            .replace(/\{\{linkPlay\}\}/g, SlotMg.getLinkPlay(o))
            .replace(/\{\{jackPot\}\}/g, jackpot));
    });

    $('#j-hotContainer').html(_ret);
    SlotMg.lazyload();
}

//搜索
$('.j-btnSearch').on('click',function () {

    var ipt = $('.j-ipt').val();

    if( ipt){
        var tab = $(this).parents('.search_container').prev().find('li.active a').data('tab');

        if(tab =="OTHER"){

            $('.game-info .name h4').each(function (index,item) {

                var str = $(item).html();
                if(str.indexOf(ipt)  !== -1){
                    $(item).parents('.game-info').show();
                }else{
                    $(item).parents('.game-info').hide();
                }
            })
        }
    }else{
        _showLayer('请输入需要查询的游戏','确定');
    }

});

function _showLayer(msg, btn) {

    if (btn == "") {
        btn = '关闭';
    }

    layer.open({
        skin: 'tips-layer',
        closeBtn: false,
        content: msg,
        btn: btn
    });

    $("body").addClass("layer-open");
}