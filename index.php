<?php
    session_start();
    if(! isset($_SESSION['diarier'])) {
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'login.php';
        header("Location:http://$host$uri/$extra");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>首页 | 素隐日记</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/tingle.min.css">
    <link rel="stylesheet" href="css/weather-icons.min.css">
</head>
<body>
<div id="container">
    <!-- 标题 -->
    <div id="header">
        <h1><a href="http://localhost/jianguoCloud/TimeLineDiary" class="content-font">素隐日记</a></h1>
        <img src="images/suyin_avator.jpg">
    </div>

    <!-- 右侧日期选择 -->
    <div id="chose-date">
        <?php require_once 'dateSort.php'; ?>
    </div>

    <?php
        //接收超链接参数
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y', $latest['latestDiary']);
        $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('n', $latest['latestDiary']);
        //避免12月的时候加到13月了，让年份+1月份回到1
        if($month != 12) {
            $nextMonth = $month + 1;
            $nextYear = $year;
        } else {
            $nextYear = $year + 1;
            $nextMonth = '1';
        }
        //将时间格式化未unix时间
        $formatMonth = $month < 10 ? strtotime($year.'0'.$month.'01') : strtotime($year.$month.'01');
        $formatNextMonth = $nextMonth < 10 ? strtotime($nextYear.'0'.$nextMonth.'01') : strtotime($nextYear.$nextMonth.'01');
        //创建连接
        $mysqli = new mysqli("localhost", "root", "root", "timeline_diary");
        //判断是否出错
        if(mysqli_connect_errno()) {
            echo '数据库连接失败'.mysqli_connect_error();
            $mysqli = null;
            exit;
        }
        //开始查询
        $query = "SELECT id, title, weather, emotion, content, category, created_at FROM diary 
                  WHERE created_at >= $formatMonth AND created_at < $formatNextMonth 
                  ORDER BY created_at DESC";
        $result = $mysqli->query($query);

        //计算数量
        $count = $result->num_rows;
        //生成结果集
        while($row = $result->fetch_assoc()) {
            $diaries[] = $row; 
        }
        //释放
        $result->free();
        //关闭
        $mysqli->close();
    ?>

    <!-- 左侧日期 -->
    <div id="years">
        <h1><?php echo $year.' . '.$month; ?></h1>
    </div>

    <!-- 时间轴和内容 -->
    <?php if($count): ?>
        <div id="circle_line">
            <ul>
                <?php for($i = 0; $i < $count; $i++) { ?>
                    <li class="circle"></li>
                    <li class="line"></li>
                <?php } ?>
            </ul>
        </div>
        <div id="text">
            <!-- 弹出框放在这里 -->
            <?php require_once 'modal.php'; ?>
            <!-- 正文 -->
            <ul>
                <?php foreach($diaries as $diary) {
                    //获取日期 [ 年 ]，并且去重
                    $diaryYear[] = date('Y', $diary['created_at']);
                    $diaryYearSort = array_unique($diaryYear);
                    $diaryYearReIndex = array_values($diaryYearSort);
                    //获取日期 [年 - 月]，并且去重
                    $date[] = date('Y', $diary['created_at']) . ' . ' . date('n', $diary['created_at']);
                    $dateSort = array_unique($date);
                    $dateSortReIndex = array_values($dateSort);
                ?>
                <li class="content">
                    <span class="content-id"><?php echo $diary['id']; ?></span>
                    <p class="content-intro content-font content-color">
                        <?php 
                            $content = strip_tags($diary['content']);
                            echo mb_strlen($content) > 500 ? mb_substr($content, 0, 250, 'utf-8').'...' : $content; 
                        ?>
                    </p>
                    <p class="diary-time" style="float: right">
                        <!--  href="http://localhost/jianguoCloud/TimeLineDiary/category.php?category=<?php echo $diary['category']; ?>" -->
                        <span class="diary-by-text content-font">归档于:&nbsp;
                            <a class="under-line">
                                <?php echo $diary['category']; ?>
                            </a>&emsp;&emsp;
                        </span>
                        <i class="wi <?php echo $diary['weather'];?>"></i>&emsp;&emsp;
                        <img class="diary-emotion" src="images/emotion/<?php echo $diary['emotion'];?>.png" alt="心情">&emsp;&emsp;
                        <span class="diary-by-text content-font">撰写于:&nbsp;</span>
                        <?php echo date('Y-m-d H:i:s' ,$diary['created_at']);?>
                    </p>
                </li>
            <?php } ?>
            </ul>
        </div>
    <?php else: ?>
        <div id="no-diary">
        </div>
    <?php endif; ?>
    <!-- 页脚 -->
    <div id="footer">
    </div>
</div>
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src="js/index.js"></script>
<script src="js/move.min.js"></script>
<script src="js/tingle.min.js"></script>
</body>
</html>