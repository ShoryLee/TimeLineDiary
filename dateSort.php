<?php
    //创建连接
    $mysqli = new mysqli("localhost", "root", "root", "timeline_diary");
    //判断是否出错
    if(mysqli_connect_errno()) {
        echo '数据库连接失败'.mysqli_connect_error();
        $mysqli = null;
        exit;
    }
    //开始查询           
    // $date = strtotime(date('Ym01', time()));WHERE created_at >= $date
    $query = "SELECT created_at FROM diary 
              ORDER BY created_at DESC";
    $result = $mysqli->query($query);
    //先进行判断，如果数据库中有数据，才进行其他的操作。以及，显示右侧时间列表
    if($result->num_rows) {
        //最新的一篇，用以在首页显示当月
        $latest = $mysqli->query("SELECT MAX(created_at) as latestDiary FROM diary")->fetch_assoc();
        //生成结果集
        while($row = $result->fetch_object()) {
            $diaryDate[] = $row; 
        }

        //释放
        $result->free();
        //关闭
        $mysqli->close(); 
?>
    <h4 class="write-new">
        <a href="http://localhost/jianguoCloud/TimeLineDiary/write.php" class="content-font">写日记<img src="images/add.png"></a>
    </h4>
    <ul class="rightDateSort" style="margin-top: 60px;">
        <?php
        foreach ($diaryDate as $diary) {
            //获取日期 [ 年 ]，并且去重，使用array_values可以重建因为array_unique去重后的索引
            $diaryYear[] = date('Y', $diary->created_at);
            $diaryYearSort = array_unique($diaryYear);
            $diaryYearReIndex = array_values($diaryYearSort);
            //获取日期 [年 - 月]，并且去重
            $date[] = date('Y', $diary->created_at) . ' . ' . date('m', $diary->created_at);
            $dateSort = array_unique($date);
            $dateSortReIndex = array_values($dateSort);
        }
        //循环出已有的日记日期
        for($i = 0; $i < count($diaryYearReIndex); $i++) { ?>
            <li>
                <a class="saa"><?php echo $diaryYearReIndex[$i]; ?></a>
                <ul class="aas">
                    <?php
                    //循环出已有的日记日期
                    for($j = 0; $j < count($dateSortReIndex); $j++) {
                        if(substr($dateSortReIndex[$j], 0, 4) == $diaryYearReIndex[$i]) {
                            echo '<li class="diaryDateList">';
                            echo '<a href="http://localhost/jianguoCloud/TimeLineDiary/index.php?year=' . $diaryYearReIndex[$i];
                            echo '&month=' . substr($dateSortReIndex[$j], -2) .'">';
                            echo $dateSortReIndex[$j];
                            echo '</a></li>';
                        }
                    }
                    ?>
                </ul>
            </li>
        <?php } ?>
    </ul>
<?php } else {
    //如果数据库中没有数据，则让首页显示当前时间
    $latest = ['latestDiary' => time()];
    //释放
    $result->free();
    //关闭
    $mysqli->close();
}?>


