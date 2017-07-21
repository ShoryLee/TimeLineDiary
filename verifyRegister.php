<?php
    session_start();

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    //创建连接
    $mysqli = new mysqli("localhost", "root", "root", "timeline_diary");
    //判断是否出错
    if(mysqli_connect_errno()) {
        echo '数据库连接失败'.mysqli_connect_error();
        $mysqli = null;
        exit;
    }
    $created_at = time();
    //开始查询
    $query = "INSERT INTO diariers (username, email, password, created_at)
              VALUES ('$username', '$email', '$password', '$created_at')";
    $result = $mysqli->query($query);
    

    //结果判断
    if($result) {
        //验证成功，查询出用户名
        $queryUsername = "SELECT username FROM diariers WHERE email = '$email'";
        $username = $mysqli->query($queryUsername)->fetch_object()->username;
        //将验证成功的用户名存入session
        //返回首页
        $_SESSION['diarier'] = $username;
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = '';
        header("Location:http://$host$uri/$extra");
    } else {
        //验证失败，重新提交
        $_SESSION['diarier'] = '';
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'register.php';
        header("Location:http://$host$uri/$extra");
    }
    //关闭
    $mysqli->close();