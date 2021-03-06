<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        .tableStyle {
            border: solid 1px;
            border-color: black;
            text-align: center;
        }
        // 테이블 스타일
    </style>
</head>
<body>
<h1 align="center">세혁이의 게시판</h1>
<br>
<div align="center">
    <table>
        <tr>
            <td>
                <form method="get" action="find.php">
                    <input type="text" name="finder">
                    <input type="submit" value="검색">
                </form>
            </td>
            <!-- 검색태그 -->
            <?php
                if (isset($_SESSION['name'])) {
                    echo "<td>";
                    echo $_SESSION['name']."님이 로그인 하셨습니다.";
                    echo "</td>";
                    echo "<td>";
                    echo "<form action=\"logout.php\">";
                    echo "<input type=\"submit\" value=\"로그아웃\">";
                    echo "</form>";
                    echo "</td>";
                }
                else {
                    echo "<td>";
                    echo "<form action=\"login.html\">";
                    echo "<input type=\"submit\" value=\"로그인\">";
                    echo "</form>";
                    echo "</td>";
                }
                // 로그인 유무에 따라
            ?>
        </tr>
    </table>
</div>
<br>
<!-- 게시판 테이블 -->
<table class="tableStyle" style="height: 300px; width: 700px" align="center">
    <tr>
        <td class="tableStyle">글번호</td>
        <td class="tableStyle" style="width: 150px">제목</td>
        <td class="tableStyle">날짜</td>
        <td class="tableStyle">조회수</td>
    </tr>
    <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        if ($page == "no") {
            echo "<script>";
            echo "alert('검색결과가 없습니다.')";
            echo "</script>";

            $page = 1;
        }
        // 검색 결과 없을 경우

        if ($page == "noid") {
            echo "<script>";
            echo "alert('잘못된 입력입니다.')";
            echo "</script>";

            $page = 1;
        }
        // 잘못 입력 했을 경우

        $pageCount = ($page - 1) * 5;   // 페이지 계산

        @$db_con = mysql_connect("localhost", "root", "autoset");
        mysql_select_db("sehyuk_board");
        // DB 연결

        $board = mysql_query("select board_id, subject, reg_date, hits from board where board_pid = 0 order by board_id desc limit $pageCount, 5");
        // 페이지에 맞는 데이터 가져오기
    
        for ($i = 0; $i < mysql_num_rows($board); $i++) {
            $board_result = mysql_fetch_row($board);
            echo "<tr>";

            for ($j = 0; $j < count($board_result); $j++) {
                echo "<td class=\"tableStyle\">";

                if ($j == 1) {
                    echo "<a href='viewer.php?id=$board_result[0]&page=$page'>";
                    echo $board_result[$j];
                    echo "</a>";
                }
                else {
                    echo $board_result[$j];
                }

                echo "</td>";
            }

            echo "</tr>";
        }
        // 내용 출력
    
        echo "</table>";

        // 페이지네이션 기능
        echo "<br><div align='center'>";

        $id_count = mysql_query("select count(board_id) from board where board_pid = 0");
        $id_count = mysql_fetch_row($id_count);

        $lastPage = 0;
        $firstPage = 0;
        $stopPage = ceil($id_count[0] / 5);

        for ($i = $page; $i < $page + 5; $i++) {
            if ($i % 5 == 0) {
                $lastPage = $i;

                if ($lastPage > $stopPage) {
                    $lastPage = $stopPage;
                }

                break;
            }
        }

        for ($i = $page; $i > $page - 5; $i--) {
            if ($i % 5 == 1) {
                $firstPage = $i;
                break;
            }
        }

        if ($page > 5) {
            $returnPage = $firstPage - 5;

            echo "<a href='board_list.php?page=$returnPage'>";
            echo "<";
            echo "</a>";
            echo "&nbsp;";
        }

        for ($i = $firstPage; $i <= $lastPage; $i++) {
            echo "<a href='board_list.php?page=$i'>";
            echo $i;
            echo "</a>";
            echo "&nbsp;";
        }

        if ($id_count[0] > 25) {
            if ($lastPage % 5 == 0) {
                $nextPage = $lastPage + 1;

                echo "<a href='board_list.php?page=$nextPage'>";
                echo ">";
                echo "</a>";
            }
        }

        echo "</div>";
        // 페이지네이션 기능

        if (isset($_SESSION['name'])) {
            echo "<br>";
            echo "<div align=\"center\">";
            echo "<form action=\"viewWrite.php\">";
            echo "<input type=\"submit\" value=\"글쓰기\">";
            echo "</form>";
            echo "</div>";
        }
        // 로그인 했을 경우 다른 기능 사용 가능

        mysql_close();  // DB연결 종료
    ?>
</body>
</html>
