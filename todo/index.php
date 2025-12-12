<DOCTYPE! html>
<html lang="ja">

    <meta charset="UTF-8">
    <body>
        <h1>TODO管理</h1>
        <h2>★登録</h2>
        <form method="post" action="index.php">
            <div>
                <label>登録番号</label>
                <input type="number" name="id">
            </div>
            <div>
                <label>作業内容</label>
                <input type="text" name="workdetail">
            </div>
            <div>
                <label>作業期限</label>
                <input type="date" name="worklimit">
            </div>
            <br>
			<input type="hidden" name="process" value="1" />
            <input type="submit" value="登録"/>
        </form>
        <h2>★削除</h2>
        <form method="post" action="index.php">
            <div>
                <label>削除したいTODO</label>
                <br>
                <input type="number" name="id">
            </div>
            <br>
			<input type="hidden" name="process" value="2" />
            <input type="submit" value="削除"/>
        </form>
		<script>
			const checkbox = document.getElementById('flag');
			checkbox.addEventListener('change', function(){
				console.log
			});
		</script>
<?php
    try{
        $db = new PDO('mysql:dbname=todo;host=127.0.0.1;charset=utf8', 'root', '');
		if($_SERVER["REQUEST_METHOD"] == "POST") {
			$get = $_POST['process'];
			if($get == "1"){
			$id = $_POST['id'];
			$workdetail = $_POST['workdetail'];
			$worklimit = $_POST['worklimit'] . " 00:00:00";
			$flag = 0;
			$db -> exec("insert into profile values({$id} ,'{$workdetail}','{$worklimit}',{$flag}, current_timestamp)");
			}
			if($get == "2"){
				$id = $_POST['id'];
				$db ->exec("delete from profile where id = '$id'");
			}
			if($get == "3"){
				$id = $_POST['getid'];
				$db ->exec("delete from profile where id = '$id'");
			}
		}
		
    	$records = $db->query('select * from profile');
		print <<< EOT
			<table border="5" width="80%" height="20%" cellpadding="10">
			<tr>
				<td>ID</td>
				<td>作業内容</td>
				<td>作業期限</td>
				<td>作業済フラグ</td>
				<td>登録日時</td>
			</tr>
		EOT;
        // print '<table border="5" width="80%" height="20%" cellpadding="10">';
		// print '<tr>';
		// print '<td>' . "ID" . '</td>';
		// print '<td>' . "作業内容" . '</td>';
		// print '<td>' . "作業期限" . '</td>';
		// print '<td>' . "作業済フラグ" . '</td>';
		// print '<td>' . "登録日時" . '</td>';
		while($record = $records->fetch()) {
			$getid = $record['id'];
			print <<< EOT
				<tr>
					<td>{$record ['id']}</td>
					<td>{$record ['workdetail']}</td>
					<td>{$record ['worklimit']}</td>
					<td><form method="post" action="index.php"><input type="hidden" name="process" value="3"><input type="hidden" name="getid" value="{$getid}"><input type="checkbox" id = "flag" ></form></td>
					<td>{$record ['inputdate']}</td>
				</tr>
			EOT;
			// print '<tr>'; 
            // print '<td>' . $record ['id']. '</td>';
			// print '<td>' . $record ['workdetail'].'</td>'; 
			// print '<td>' . $record ['worklimit'].'</td>';
			// print '<td>' . "<form method=\"post\" action=\"index.php\"><input type=\"hidden\" name=\"process\" value=\"3\"><input type=\"hidden\" name=\"getid\" value=\"{$getid}\"><input type=\"submit\" value=\"完了ボタン\" ></form>" . '</td>';
			// print '<td>' . $record ['inputdate'].'</td>';
			// print '</tr>';
        }
		print '</table>';
	} catch(PDOException $e) {
		print 'DB接続エラー：' . $e->getMessage();
	}
?>
    </body>
</html>
