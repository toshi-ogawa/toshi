<?php	
	$dsn = 'データベース名';
	$user = '名前';
	$password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    $date = date("Y/m/d H:i:s");

    //テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS tb1"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"//自動で入力される番号
	. "name char(32),"//名前　32文字まで
    . "comment TEXT,"//コメント
    . "date char(32),"
    . "pass TEXT"
	.");";
    $stmt = $pdo->query($sql);//実行

       //送信ボタンを押したとき
 if(!empty($_POST["submit_btn"])&&!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["submit_pass"])){
    //編集番号がないとき＝通常の送信時
    if(empty($_POST["edit_post"])){
       $name = $_POST["name"];             
       $comment = $_POST["comment"];
       $submit_pass=$_POST["submit_pass"]; 
       $sql = $pdo -> prepare("INSERT INTO tb1 (name, comment,date,pass) VALUES (:name, :comment,:date,:pass)");
       $sql -> bindParam(':name', $name, PDO::PARAM_STR);
       $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
       $sql -> bindParam(':date', $date, PDO::PARAM_STR);
       $sql -> bindParam(':pass', $submit_pass, PDO::PARAM_STR);
       $sql -> execute();
    }else{//edit_postが埋まっていた時＝編集する
       $edit_post = $_POST["edit_post"];
       $name = $_POST["name"];             
       $comment = $_POST["comment"]; 
       $submit_pass=$_POST["submit_pass"];
       $pass=$submit_pass;
       $id = $edit_post; //変更する投稿番号
       $sql = 'UPDATE tb1 SET name=:name,comment=:comment,pass=:pass WHERE id=:id';
       $stmt = $pdo->prepare($sql);
       $stmt->bindParam(':name', $name, PDO::PARAM_STR);
       $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
       $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
       $stmt->bindParam(':id', $id, PDO::PARAM_INT);
       $stmt->execute(); 
    }
    
}
    if(!empty($_POST["edit_num"])&&!empty($_POST["edit_btn"])&&!empty($_POST["edit_pass"])){
        //編集ボタンが押されたとき＝入力フォームに名前とコメントを入れるための準備
        $edit_num=$_POST["edit_num"];
        $edit_pass=$_POST["edit_pass"];
        $id = $edit_num;
        $pass=$edit_pass;
        $sql = 'SELECT * FROM tb1 WHERE id = :id AND pass = :pass';
        $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->execute(array(':id' => $id,':pass'=>$pass));
        $result = 0;
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
    }
  
  if(!empty($_POST["delete_num"])&&!empty($_POST["delete_btn"])&&!empty($_POST["delete_pass"])){
      //削除ボタンが押されたとき＝削除する
    $delete_num=$_POST["delete_num"];
    $delete_pass=$_POST["delete_pass"];
    $id = $delete_num;
    $pass=$delete_pass;
	$sql = 'delete from tb1 where id=:id and pass=:pass';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
    $stmt->execute();
  }


?>
<html lang="ja"><!--投稿フォーム-->
    
<head>
    <meta charset="UTF-8">
    <title>mission5-1</title>
</head>

<body>
  
    <form action="" method="post">
        <table>
            <tr>好きなスポーツ</tr>
            <tr>
               
                <input type="hidden" name="edit_post" placeholder="触らない"
                value="<?php if (!empty($result['id'])) echo(htmlspecialchars($result['id'], ENT_QUOTES, 'UTF-8'));?>" >
                        
            </tr>    
            <tr>
                <td><input type="str" name="name" placeholder="名前" 
                value="<?php if (!empty($result['name'])) echo(htmlspecialchars($result['name'], ENT_QUOTES, 'UTF-8'));?>"></td>
                     
            </tr>
            <tr>
                <td><input type="str" name="comment" placeholder="コメント" 
                value="<?php if (!empty($result['comment'])) echo(htmlspecialchars($result['comment'], ENT_QUOTES, 'UTF-8'));?>"></td>
            </tr>
            <tr>
                <td><input type="text" name="submit_pass" placeholder="パスワード"></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit_btn"></td>
            </tr>
            <tr>
                <td><input type="number" name="delete_num" min="1"placeholder="削除対象番号"></td>
            </tr>
            <tr>
                <td><input type="text" name="delete_pass" placeholder="パスワード"></td>
            </tr>
            <tr>
                <td><input type="submit" name="delete_btn" value="削除"></td>
            </tr>
            <tr>
                <td><input type="number" name="edit_num" min="1"  placeholder="編集対象番号"></td>
            </tr>
            <tr>
                <td><input type="text" name="edit_pass" placeholder="パスワード"></td>
            </tr>
            <tr>
       　        <td><input type="submit" name="edit_btn" value="編集"></td>
            </tr>
            </table>    
    </form>
    </body>
   <?php
    
    //画面表示
    $sql = 'SELECT * FROM tb1';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
        echo "<hr>";
	}
    ?>