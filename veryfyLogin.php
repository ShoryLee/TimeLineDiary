<?php
    session_start();

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
    //开始查询
    $query = "SELECT email, password FROM diariers 
              WHERE email = '$email' AND password = '$password'";
    $result = $mysqli->query($query)->num_rows;
    

    //结果判断
    if($result) {
        //验证成功，查询出用户名
        $queryUsername = "SELECT username FROM diariers WHERE email = '$email'";
        $username = $mysqli->query($queryUsername)->fetch_object()->username;
        //将验证成功的用户名存入session
        //返回首页
        $_SESSION['diarier'] = $username;
        setcookie('username', $_SESSION['diarier']);
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = '';
        header("Location:http://$host$uri/$extra");
    } else {
        //验证失败，重新提交
        $_SESSION['diarier'] = '';
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'login.php';
        header("Location:http://$host$uri/$extra");
    }
    //关闭
    $mysqli->close();