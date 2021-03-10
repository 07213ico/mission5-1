<?php
      //データベースに接続   
 	$dsn ='データベース名';
	$user ='ユーザー名';
	$password ='パスワード';
	$pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));



    $sql = "CREATE TABLE IF NOT EXISTS mission6"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name char(32),"
    ."comment TEXT,"
    ."created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,"
    ."pass char(32)"
    .");";
    $stmt = $pdo->query($sql);
    
    $edit_number="";
    $edit_pass="";
    $row_pass="";

$delete=$_POST["delete"];
$name=$_POST["name"];
$comment=$_POST["comment"]; 
$Epost = $_POST["Epost"];
$edit_number = $_POST["edit"];
$pass = $_POST["pass"];
$del_pass = $_POST["del_pass"];
$edit_pass = $_POST["edit_pass"];


  //編集フォームに入力がある場合,番号と対応する名前とコメントを取得
if(!empty($edit_number) && !empty($edit_pass)){
    $edit_number=$_POST['edit'];
    $edit_pass=$_POST['edit_pass'];
    $sql = 'SELECT * FROM mission6 WHERE id=:edit_number';
    $stmt =$pdo->prepare($sql);
    $stmt->bindParam(':edit_number', $edit_number, PDO::PARAM_INT);
    $stmt->execute();
    $results=$stmt->fetchAll();
    foreach($results as $row){
        $row_pass=$row['pass'];
    }
    if($row_pass==$edit_pass){
        $sql = 'SELECT * FROM mission6 WHERE id=:edit_number';
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':edit_number', $edit_number, PDO::PARAM_INT);
        $stmt->execute();
        $results=$stmt->fetchAll();
        foreach($results as $row){
            $edit_name=$row['name'];
            $edit_comment=$row['comment'];
        }    
    }
    echo "編集番号取得完了<br>";
}



if(!empty($_POST['name']) && !empty($_POST['comment'])&&!empty($_POST['pass'])){
    if(empty($_POST["Epost"])){
        $sql = $pdo -> prepare("INSERT INTO mission6(name, comment,  pass) VALUES (:name, :comment, :pass)");
	    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
        $name=$_POST['name'];
        $comment=$_POST['comment'];
        $pass=$_POST['pass'];
    	$sql -> execute();
                
        echo "送信完了"."<br>";
        echo "削除・編集の際はパスワードが必要です。大切に管理してください。"."<br>";
    }
     else{
         $name=$_POST['name'];
         $comment=$_POST['comment']; 
         $id = $_POST['Epost'];
         $sql = 'UPDATE mission6 SET name=:name, comment=:comment WHERE id =:id';
         $stmt = $pdo -> prepare($sql);
         $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
         $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
         $stmt -> bindParam(':pass', $pass, PDO::PARAM_INT);
         $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
         $stmt->execute();
         
        echo "編集完了";
     }
   
}

    //削除フォームに記入がある場合

if(!empty($_POST["delete"]) && !empty($_POST["del_pass"])){
    $sql = 'SELECT *FROM mission6';
    $stmt = $pdo -> query($sql);
    $results = $stmt -> fetchAll();
    
    foreach($results as $row){
        if($_POST["delete"] == $row['id'] &&$_POST["del_pass"]==$row['pass']){
      
        	$id = $row['id'];
        	$sql = 'delete from mission6 where id=:id';
        	$stmt = $pdo->prepare($sql);
        	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	        $stmt->execute();

            }
        }
        echo "削除完了<br>";
    }
         
  //すべての処理を終えデータベースに書き込まれた情報をブラウザに表示
	$sql = 'SELECT * FROM mission6';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){

		echo $row['id'].'名前：';
		echo $row['name'].'コメント：';
		echo $row['comment'].'投稿日時：';
		echo $row['created_at'].'<br>';
	echo "<hr>";
	}
  ?>


<!DOCTYPE html>
<html lang = "ja">
<head>
    <meta charstic = "UTF-8">
    <title>mission5</title>
</head>
<body>
    <form action = '' method = 'post'>
        <input type = 'text' name = 'Epost '
        value = '<?php if(!empty($_POST["edit"])){echo $_POST["edit"];} ?>' >
        <input type = 'text' name = 'name'placeholder = '名前' 
        value = '<?php if(!empty($edit_name)){echo"$edit_name";} ?>'>
        <input type = 'text' name = 'comment' placeholder = 'コメント'
        value = '<?php if(!empty($edit_comment)) {echo"$edit_comment";} ?>' >
        <input type = 'text' name = 'pass' placeholder = 'パスワード'>
        <input type = 'submit' value = '送信' ><br>
        <input type = 'text' name = 'delete' placeholder = '削除番号'>
        <input type = 'text' name = 'del_pass' placeholder = '削除する投稿のパス'>
        <input type = 'submit' value = '削除' > <br>
        <input type = 'text' name= 'edit' placeholder = '編集番号'>
        <input type = 'text' name = 'edit_pass' placeholder = '編集する投稿のパス'>
        <input type = 'submit' value = '編集'><br>
    </form>
</body>
</html>