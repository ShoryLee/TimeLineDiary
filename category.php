<?php
    header("Contnt-type: txt/html; charset = utf-8");

    //获取
    $category = $_GET['category'];
    //创建连接
    $mysqli = new mysqli("localhost", "root", "root", "timeline_diary");
    //判断是否出错
    if(mysqli_connect_errno()) {
        echo '数据库连接失败'.mysqli_connect_error();
        $mysqli = null;
        exit;
    }
    //开始查询
    $query = "SELECT id, title, content, category, created_at FROM diary 
              WHERE category = '$category' 
              ORDER BY created_at DESC";
    $result = $mysqli->query($query);
    //生成结果集
    while($row = $result->fetch_object()) {
        $detailCategory[] = $row; 
    }
    //释放
    $result->free();
    //关闭
    $mysqli->close();
