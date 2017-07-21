<?php
    session_start();
    header("Contnt-type: txt/html; charset = utf-8");
    //接收超链接参数
    $commentId = $_POST['id'];
    $commentContent = $_POST['content'];
    //创建连接
    $mysqli = new mysqli("localhost", "root", "root", "timeline_diary");
    //判断是否出错
    if(mysqli_connect_errno()) {
        echo '数据库连接失败'.mysqli_connect_error();
        $mysqli = null;
        exit;
    }
    $timeNow = time();
    $commentBy = $_SESSION['diarier'];
    //开始插入新的评论
    $query = "INSERT INTO comments(diary_id, comment, comment_by, created_at, updated_at) VALUES
              ('$commentId', '$commentContent', '$commentBy', '$timeNow', '$timeNow')";
    //执行
    $result = $mysqli->query($query);
   
    //关闭
    $mysqli->close();
    //回传结果
    echo json_encode($result);