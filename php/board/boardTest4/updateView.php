<?php
    $board_id = isset($_GET['board_id']) ? $_GET['board_id'] : false;

    if ($board_id == false) {
        echo "board_id select fail";
        exit();
    }

    class constantValue {
        const IP_adress  = 'localhost';
        const user_name  = 'root';
        const user_pass  = 'autoset';
        const use_db     = 'sehyuk_board';
    }

    $selectView = new myDBMS();
    $selectView->showView($board_id);

    class myDBMS {
        function showView ($showBoard_id) {
            try {
                $db_con = new mysqli(constantValue::IP_adress, constantValue::user_name, constantValue::user_pass, constantValue::use_db);

                if ($db_con->errno != 0) {
                    throw new Exception();
                }
            } catch (Exception $e) {
                echo "DB connect fail<br>";
                echo "<script>
                            function backWriter() {
                            window.location = 'writerCreate.html';
                            }
                        </script>";
                echo "<input type='button' value='뒤로가기' onclick='backWriter()'>";
            }

            try {
                $sendQuery = $db_con->query("select subject, contents from board where board_id=$showBoard_id");

                if ($sendQuery == false) {
                    throw new Exception();
                }
                else {
                    $sendQueryResult = $sendQuery->fetch_array(MYSQLI_NUM);

                    include_once "writerCreate.html";

                    $sendQueryResult[1] = trim($sendQueryResult[1]);

                    echo "<script>
                            document.getElementById('subject').value = '$sendQueryResult[0]';
                            document.getElementById('content').value = '$sendQueryResult[1]';
                            document.getElementById('send').value = '수정하기';
                            document.getElementById('dataForm').action = 'update.php?board_id=$showBoard_id';
                        </script>";
                }
            } catch (Exception $e) {
                echo "Query send fail";
                echo "<script>
                            function backWriter() {
                                window.location = 'writerCreate.html';
                            }
                        </script>";
                echo "<input type='button' value='뒤로가기' onclick='backWriter()'>";
            }
        }
    }
?>