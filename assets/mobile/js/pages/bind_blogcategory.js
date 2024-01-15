$(function() {
    BindBlogCategory();
   // BlogNavSetSelected();
});



getRouteUrlParameter = function (sParam) {
    var value = window.location.pathname;

    var sub = value.split('/');
    if (sub.indexOf(sParam) > -1) {
        return sub[sub.indexOf(sParam) + 1];
    } else {
        return null;
    }
};



BindBlogCategory = function () {
    $.ajax(
    {
        url: "/handlers/GetBlogCategoryList.ashx",
        type: 'POST',
        cache: false,
        async: true,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (result) {
            var o = result;
            var code = o.code;
            switch (code) {
                case 0:
                    if (o.data) {
                        var json = JSON.parse(o.data);
                        $("#ddl_blog").append($("<option></option>").val('0').html('所有'));
                        //console.log(json);
                        $.each(json.response, function (key, item) {
                            var category = item.name;
                            var cid = item.category_id;
                            var pathname = item.path_name;

                            var href = "";
                            if (pathname != "")
                                href =  pathname.ToLower();
                            else
                                href =  cid;

                            //console.log(href);

                            $("#ddl_blog").append($("<option></option>").val(href).html(category));
                        });

                        var domain = window.location.pathname.split("/").pop();
                        console.log(domain);
                        var asd =  window.location.pathname;
                        //alert(asd);
                        if(asd == "/m/blog")
                            $('#ddl_blog').val("0");
                        else
                            $('#ddl_blog').val(domain);
   
                    }
                    break;
                case 592:
                    location.href = '/';
                    break;
            }
        },
        error: function (e) {
        }
    });
}

