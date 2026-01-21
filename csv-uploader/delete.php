<?php
    try{
        $db = new PDO('mysql:dbname=csv_uploader;host=127.0.0.1;charset=utf8', 'root', '');
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $db -> exec("delete from job_offers");
            header("Location: upload.html",true,301);
            exit();
        }
    } catch(PDOException $e) {
		print 'DB接続エラー：' . $e->getMessage();
    }

?>