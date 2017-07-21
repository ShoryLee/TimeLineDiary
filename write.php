<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>写日记 | 素隐日记</title>
    <link rel="stylesheet" href="editor/css/wangEditor.min.css">
    <link rel="stylesheet" href="css/tingle.min.css">
    <link rel="stylesheet" href="css/weather-icons.min.css">
    <style>
        #container, body {
            margin: 0 auto;
            background-color: #F8F8F8;
            width: 1200px;
        }
        a {
            text-decoration: none;
            color: #555;
        }
        .content-font {
            font-family: 方正清刻本悦宋简体;
        }
        #header {
            text-align: center;
            width: 100%;
            margin-bottom: 55px;
            font-family: 'Constantia';
        }
        #title {
            padding-bottom: 20px;
        }
        /*.labels {
            font-size: 1.2em;
        }*/
        #title input,#category input {
            font-size: 0.8em;
            width: 500px;
            height: 27px;
        }
        .select-item {
            appearance:none;
            -moz-appearance:none;
            -webkit-appearance:none;
            height: 32px;
            width: 50px;
            padding: 5px;
            padding-right: 15px;
            background: url("images/arrow.png") no-repeat scroll right center transparent;
        }
        .tags {
            color: #555;
            cursor: pointer;
        }
        #newDiary {
            height: 400px;
        }
        #submit {
            margin: 30px 0 0 535px;
        }
        #submit button {
            font-size: 1.3em;
            width: 100px;
            padding: 10px 0;
            border: none;
            color: #fff;
            background-color: #555;
            cursor: pointer;
        }
        #submit button:hover {
            background-color: #000;
        }
    </style>
</head>
<body>

<div id="container">
    <!-- 站名 -->
    <div id="header">
        <h1><a href="http://localhost/jianguoCloud/TimeLineDiary" class="content-font">素隐日记</a></h1>
    </div>
    <!-- 标题 -->
    <div id="title">
        <!-- 标题 -->
        <label for="title" class="content-font labels">标题: </label>
        <input id="diary-title" type="text" class="content-font labels" />&emsp;
        <!-- 天气 -->
        <label for="weather" class="content-font labels">天气: </label>
        <select name="weather" id="weather" class="select-item content-font">
            <option value="wi-day-sunny">晴天</option>
            <option value="wi-cloudy">阴天</option>
            <option value="wi-fog">雾天</option>
            <option value="wi-rain-mix">下雨</option>
            <option value="wi-storm-showers">雷电</option>
            <option value="wi-snow">下雪</option>
        </select>&emsp;
        <!-- 心情 -->
        <label for="emotion" class="content-font labels">心情: </label>
        <select name="emotion" id="emotion" class="select-item content-font">
            <option value="calm">平静</option>
            <option value="happy">开心</option>
            <option value="excite">激动</option>
            <option value="nervous">紧张</option>
            <option value="awkward">尴尬</option>
            <option value="lost">失落</option>
            <option value="sadness">伤心</option>
            <option value="depression">沮丧</option>
            <option value="pain">痛苦</option>
            <option value="indignant">气愤</option>
            <option value="anger">愤怒</option>
        </select>
    </div>
    <!-- 归档 -->
    <div id="category">
        <label for="category" class="content-font labels">归档: </label>
        <input id="diary-category" type="text" class="content-font labels" />&emsp;
        <span class="tags content-font">见闻</span>
        <span class="tags content-font">感想</span>
        <span class="tags content-font">心情</span>
        <span class="tags content-font">评论</span>
        <span class="tags content-font">批评</span>
        <span class="tags content-font">梦呓</span>
    </div>
    <!-- 正文 -->
    <p class="content-font labels">正文:</p>
    <div id="newDiary" class="content-font"></div>
    <!-- 提交按钮 -->
    <div id="submit">
        <button  class="content-font">提交</button>
    </div>
</div>
<script src="js/jquery-3.1.1.min.js"></script>
<script src="editor/js/wangEditor.min.js"></script>
<script src="js/tingle.min.js"></script>
<script>
    //创建并生成文本框
    var editor = new wangEditor('newDiary');
    editor.create();
    //点击标签直接添加到归档
    $('.tags').on('click', function() {
        $('#diary-category').val($(this).text());
    });
    //处理提交
    //获取数据
    $('button').on('click', function() {
        var title = $('#diary-title').val();
        var weather = $("#weather option:selected").val();
        var emotion = $("#emotion option:selected").val();
        var category = $('#diary-category').val();
        var newDiary = editor.$txt.html();
        var created_at = Math.round(new Date().getTime()/1000);
        //验证数据
        if(title == '' || category == '' || newDiary == '') {
            resultModal('填写不完整');
            return false;
        }
        //提交数据
        $.post('http://localhost/jianguoCloud/TimeLineDiary/addNewDiary.php', {
            title: title,
            weather: weather,
            emotion: emotion,
            category: category,
            content: newDiary,
            created_at: created_at
        }, function(result) {
            if(result) {
                resultModal('成功');

            } else {
                resultModal('失败');
            }
        });
    });
    //结果弹窗
    function resultModal(resultText) {
        var message = '<h1 class="content-font" style="text-align:center">' + resultText + '</h1>';
        //新建模态框
        var modalTinyBtn = new tingle.modal({
            footer: true
        });
        //添加内容
        modalTinyBtn.setContent(message);
        //添加按钮
        modalTinyBtn.addFooterBtn('关 闭', 'tingle-btn tingle-btn--default tingle-btn--center', function(){
            modalTinyBtn.close();
            resultText == '成功' ? location.href = 'http://localhost/jianguoCloud/TimeLineDiary/' : '';
        });
        //打开模态弹窗
        modalTinyBtn.open();
    }
    
</script>
</body>
</html>