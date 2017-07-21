<?php
    header("Contnt-type: txt/html; charset = utf-8");
    //接收超链接参数
    $commentId = $_POST['id'];
    //创建连接
    $mysqli = new mysqli("localhost", "root", "root", "timeline_diary");
    //判断是否出错
    if(mysqli_connect_errno()) {
        echo '数据库连接失败'.mysqli_connect_error();
        $mysqli = null;
        exit;
    }
    //开始查询
    $query = "SELECT * FROM comments WHERE diary_id = '$commentId' ORDER BY created_at DESC";
    // $query = "SELECT * FROM comments WHERE diary_id = '$commentId'";
    $result = $mysqli->query($query);
    //计算个数
    $commentsCount = $result->num_rows;
    //生成结果集
    if($commentsCount) {
        while($row = $result->fetch_assoc()) {
            $comments[] = $row; 
        } 
    } else {
        $comments = null;
    }
    // var_dump($result);
    //释放
    $result->free();
    //关闭
    $mysqli->close();
    //回传结果
    echo json_encode($comments);