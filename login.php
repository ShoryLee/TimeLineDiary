<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录 | 素隐日记</title>
    <style>
        body {
            
        }
        .bg-blur {
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            z-index: -999;
            position: absolute;
            background: url('images/bg/bg2.jpg') no-repeat center;
        }
        .container {
            margin: 0 auto;
            padding-top: 100px;
            width: 450px;
            font-family: '方正清刻本悦宋简体';
        }
        a {
            text-decoration: none;
            color: #555;
        }
        #header {
            text-align: center;
            width: 100%;
        }
        .login-form {
            width: 100%;
        }
        .login {
            padding: 10px;
            line-height: 2;
        }
        .login label {
            font-size: 1.2em;
        }
        .login input {
            width: 100%;
            height: 30px;
            font-size: 1.2em;
            font-family: 'Constantia';
        }
        .login button {
            margin-left: 152px;
            font-size: 1.2em;
            width: 80px;
            padding: 10px 0;
            border: none;
            color: #fff;
            background-color: #777;
            cursor: pointer;
            font-family: '方正清刻本悦宋简体';
        }
        .login button:hover {
            background-color: #000;
        }
    </style>
</head>
<body>
    <div class="bg-blur"></div>
    <div class="container">
        <!-- 标题 -->
        <div id="header">
            <h1><a href="http://localhost/jianguoCloud/TimeLineDiary">素隐日记</a></h1>
        </div>
        <div class="login-form">
            <form action="http://localhost/jianguoCloud/TimeLineDiary/veryfyLogin.php" method="post">
                <div class="login">
                    <label for="email">邮箱:</label>
                    <input type="text" name="email">
                </div>
                <div class="login">
                    <label for="password">密码:</label>
                    <input type="password" name="password">
                </div>
                <div class="login">
                    <button type="submit">登录</button>
                    <span>&emsp;<small>没有账号？
                        <a href="http://localhost/jianguoCloud/TimeLineDiary/register.php">请注册</a>
                    </small></span>
                </div>
            </form>     
        </div>
    </div>
</body>
</html>