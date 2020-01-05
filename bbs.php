<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="utf-8">
	<meta http-equiv-"X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>掲示板</title>
	<!-- bootstrap CDN -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<style type=text/css>
		div#main {
			padding: 30px;
			background-color: #efefef;
		}
	</style>
</head>

<body>
    <div class="container">
	   <div id="main">
	<?php
	// データベースに接続する
	$pdo = new PDO("mysql:host=127.0.0.1;dbname=lesson;charset=utf8", "root", "");
	//print_r($_POST);

	// 受け取ったidのレコードの削除
	if (isset($_POST["delete_id"])) {
		$delete_id = $_POST["delete_id"];
		$sql  = "DELETE FROM bbs WHERE id = :delete_id;";
		$stmt = $pdo->prepare($sql);
		$stmt -> bindValue(":delete_id", $delete_id, PDO::PARAM_INT);
		$stmt -> execute();
	}

	// 受け取ったデータを書き込む
	if (isset($_POST["content"]) && isset($_POST["username"])) {
		$content = $_POST["content"];
		$username = $_POST["username"];
		$sql  = "INSERT INTO bbs (content, username,  updated_at) VALUES (:content, :username, NOW());";
		$stmt = $pdo->prepare($sql);
		$stmt -> bindValue(":content", $content, PDO::PARAM_STR);
		$stmt -> bindValue(":username", $username, PDO::PARAM_STR);
		$stmt -> execute();
	} ?>

	<h1>掲示板</h1>

	<h2>投稿フォーム</h2>
	<form class="form" action="bbs.php" method="post">
	  <div class="form-group">
		<label class="control-label">投稿内容</label>
		<input class="form-control" type="text" name="content">
	  </div>
	  <div class="form-group">
		<label class="control-label">投稿者</label>
		<input class="form-control" type="text" name="username">
	  </div>
		<button class="btn btn-primary" type="submit">送信</button>
	</form>

	<h2>発言リスト</h2>
	<?php
	// データベースからデータを取得する
	$sql = "SELECT * FROM bbs ORDER BY updated_at;";
	$stmt = $pdo->prepare($sql);
	$stmt -> execute();
	?>
	<table class="table table-striped">
		<tr>
			<th>id</th>
			<th>日時</th>
			<th>投稿内容</th>
			<th>投稿者</th>
			<th></th>
		</tr>
		<?php
		// 取得したデータを表示する
		while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) { ?>
			<tr>
				<td><?= $row["id"] ?></td>
				<td><?= $row["updated_at"] ?></td>
				<td><?= $row["content"] ?></td>
				<td><?= $row["username"] ?></td>
				<td>
					<form action="bbs.php" method="post">
						<input type="hidden" name="delete_id" value=<?= $row["id"] ?>>
						<button class="btn btn-danger" type="submit">削除</button>
					</form>
				</td>
			</tr>
		<?php } ?>
	</table>
  </div>	
</body>

</html>