$(function () {
    move('#circle_line').delay('2s').end();
    //将content块的高度保存
    var contentHeight = [];
    //获取
    $('.content').each(function () {
        contentHeight.push($(this).height());
    });
    //设置偏移，只需设置第一个
    $('.circle:first').css('margin-top', contentHeight[0]/2 + 33);
    //设置a标签的高度
    $('.line').each(function(i) {
        $(this).height(contentHeight[i+1] / 2 + contentHeight[i] / 2 + 34);
    });
    //点击条目，弹出窗口，显示详情
    //初始化弹窗
    var modalBigContent = new tingle.modal();
    //点击条目，弹出详细
    var num = 0;
    $('.content').on('click', function() {
        //获取id进行数据库查询
        var contentId = $(this).children(':first').text();
        // var contentId = $(this).prev().text();
        //执行ajax
        $.post('getDetails.php', {id: contentId}, function(data) {
            //解析返回的json数据，并排版
            //href="http://localhost/jianguoCloud/TimeLineDiary/category.php?category=' + item.category + '"
            var oneDiary = JSON.parse(data);
            $.each(oneDiary, function(index, item) {
                $('.modal-title').html(item.title);
                $('.under-line').html(item.category);
                $('.wi').addClass(item.weather);
                $('.modal-emotion').attr('src', 'images/emotion/' + item.emotion + '.png');
                $('.created_time').html(UnixToDate(item.created_at));
                $('.updated_time').html(UnixToDate(item.updated_at));
                // $('.wi').addClass(item.weather);
                $('.modal-content').html(item.content);
                // var details ='<div><h3 style="text-align:center" class="content-font">' + item.title + '</h3>' +
                //              '<div style="float:left"><p class="diary-time modal-by-text">' +
                //              '<span class="diary-by-text content-font">归档于:&nbsp;' +
                //              '<a class="under-line">' +
                //               item.category + '</a></span>&emsp;&emsp;' +
                //              '<span class="content-font diary-by-text">撰写于:&nbsp;</span>' +
                //               UnixToDate(item.created_at) +
                //              '&emsp;<span class="content-font diary-by-text">最后修改于:&nbsp;</span>' +
                //               UnixToDate(item.updated_at) + '</p></div>' +
                //              '<div class="content-font content-color content-line modal-content"><p>' +
                //               item.content + '</p></div></div>'; 
                //填充数据
                // modalBigContent.setContent(details);
                modalBigContent.setContent($('#modal').html());
                //打开弹窗
                modalBigContent.open();
            });
            
            if($('.comment-content').html() == '') {
                $.post('getComments.php', {id: contentId}, function(comments) {
                    var oneComment = JSON.parse(comments);
                    $.each(oneComment, function(icommentIndex, commentItem) {
                        var oneComments = commentItem.comment_by + ' :' + commentItem.comment + '<br>' +
                                          UnixToDate(commentItem.created_at) + '<br><hr class="comment-line">';
                        $('.comment-content').append(oneComments);
                        num++;
                    });
                    $('#comment-num').html('( ' + num + ' )');
                });
            } else {
                $('#comment-num').html('( ' + num + ' )');
            }
        });
    });


    //unix时间转换成日期
    function UnixToDate(unixTime) {
    // function UnixToDate(unixTime, isFull, timeZone) {
        // if (typeof (timeZone) == 'number') {
            // unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
            unixTime = parseInt(unixTime) + parseInt(8) * 60 * 60;
        // }
        var time = new Date(unixTime * 1000);
        var ymdhis = "";
        ymdhis += time.getUTCFullYear() + "-";
        ymdhis += (time.getUTCMonth()+1) + "-";
        ymdhis += time.getUTCDate();
        // if (isFull === true) {
            ymdhis += " " + time.getUTCHours() + ":";
            ymdhis += time.getUTCMinutes() + ":";
            ymdhis += time.getUTCSeconds();
        // }
        return ymdhis;
    }
    //右侧时间展开与收起
    $('.saa').on('click', function() {
        var showOrNot = $(this).next();
        if(showOrNot.css('display') == 'block') {
            // $(this).children().html('∨');
            showOrNot.css('display', 'none');
        } else {
            showOrNot.css('display', 'block');
            // $(this).children().html('∧');
        }
    });
    

});