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
// console.log($.cookie('username'));
    var contentId = '';
    //点击条目，弹出窗口，显示详情
    //初始化弹窗
    var modalBigContent = new tingle.modal({
        onOpen: function() {
            //点击发布新的评论  

            //将jQuery事件注入modal页面
            //否则会造成modal无法识别jQuery
            $('#write-comment').on('click', function() {
                var commentNew = $("input[name='write-comment']").val();
                if(commentNew != '') {
                    $.post('addNewComment.php', {id: contentId, content: commentNew}, function(result) {
                        if(result === 'true') {
                            //评论成功，将数据写入，假装已经存入数据库
                            var newComments = '<p><span class="comment-by">' + $.cookie('username') + ': </span>' + 
                            '<span class="comment-content">' + commentNew + '</span><br>' +
                            '<span class="comment-time">发布于: <span class="diary-time">' +
                            UnixToDate(new Date().getTime()) + '</span></span></p>';
                            //评论数量+1
                            var commentCount = parseInt($('.comment-num').html())+1;
                            $('.comment-num').html(commentCount);
                            //显示
                            $('.comments').prepend(newComments);
                            //清空输入框
                            $("input[name='write-comment']").val('');
                            //然后关闭modal
                            // modalBigContent.close();
                        } else {
                            $("input[name='write-comment']").val('Sorry, 失败啦！');
                            $("input[name='write-comment']").css('color', 'red');
                            return false;
                        }
                    });
                } else {
                    $("input[name='write-comment']").css('borderColor', 'red');
                    return false;
                } 
            });
        }
    });
    //点击条目，弹出详细
    //初始化评论数
    $('.content').on('click', function() {
        var commentNums = 0;
        $('.comments').html('');
        // console.log(this);
        //获取id进行数据库查询
        contentId = $(this).children(':first').text();
        // var contentId = $(this).prev().text();
        //执行ajax
        $.post('getDetails.php', {id: contentId}, function(data) {
            // alert(contentId);
            //解析返回的json数据，并排版
            //href="http://localhost/jianguoCloud/TimeLineDiary/category.php?category=' + item.category + '"
            var oneDiary = JSON.parse(data);
            $.each(oneDiary, function(index, item) {
                $('.modal-title').html(item.title);
                $('.under-line').html(item.category);
                $('.wi').addClass(item.weather);
                $('#diary-id').html(item.id);
                $('.modal-emotion').attr('src', 'images/emotion/' + item.emotion + '.png');
                $('.created_time').html(UnixToDate(item.created_at));
                $('.updated_time').html(UnixToDate(item.updated_at));
                // $('.wi').addClass(item.weather);
                $('.modal-content').html(item.content);
                //获取评论数据
                $.post('getComments.php', {id: contentId}, function(comments) {
                    //判断是否有评论
                    if(comments != 'null') {
                        //解析json文件
                        var oneComment = JSON.parse(comments);
                        $.each(oneComment, function(icommentIndex, commentItem) {
                            var oneComments = '<p><span class="comment-number"></span>' +
                            '<span class="comment-by">' + commentItem.comment_by + ': </span>' + 
                            '<span class="comment-content">' + commentItem.comment + '</span><br>' +
                            '<span class="comment-time">发布于: <span class="diary-time">' +
                            UnixToDate(commentItem.created_at) + '</span></span></p>';
                            $('.comments').append(oneComments);
                            commentNums++;
                            $('.comment-num').html(commentNums);
                        });
                    } else {
                        commentNums = 0;
                        $('.comment-num').html(commentNums);
                        $('.comments').html('');
                    }
                    //打开modal
                    //只有将数据全部准备好，再打开modal，这样才能让溢出的窗口正常上下滚动
                    //但是这样会导致modal打开缓慢，延迟严重
                    modalBigContent.setContent($('#modal').html());
                    //打开弹窗
                    modalBigContent.open();
                });
            });
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