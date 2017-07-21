<?php
    header("Contnt-type: txt/html; charset = utf-8");

    //接收超链接参数
    $title = $_POST['title'];
    $weather = $_POST['weather'];
    $emotion = $_POST['emotion'];
    $category = $_POST['category'];
    $content = $_POST['content'];
    $created_at = $_POST['created_at'];

    //创建连接
    $mysqli = new mysqli("localhost", "root", "root", "timeline_diary");
    //判断是否出错
    if(mysqli_connect_errno()) {
        echo '数据库连接失败'.mysqli_connect_error();
        $mysqli = null;
        exit;
    }
    //开始查询
    $query = "INSERT INTO diary (title, weather, emotion, category, content, created_at, updated_at) 
              VALUES('$title', '$weather', '$emotion', '$category', '$content', '$created_at', '$created_at')";
    $result = $mysqli->query($query);

    //关闭
    $mysqli->close();
    //回传结果
    echo json_encode($result);