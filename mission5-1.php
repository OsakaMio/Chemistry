<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-1</title>
    </head>
    

    <?php
//テーブル作成
//DB接続作成
$dsn='データベース名'
$user = 'ホスト名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        //入力データを受け取る
        $name=@$_POST["name"];
        $com=@$_POST["com"];
        //日付データを取得
        $date=date("Y/m/d H:i:s");
        $edit=@$_POST["edit"];
        $pass=@$_POST["pass"];
        $pass2=@$_POST["pass2"];
        $pass3=@$_POST["pass3"];

//テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS tb2"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
    . "com TEXT,"
    . "date DATE,"
    . "pass char(32)"
	.");";
	$stmt = $pdo->query($sql);

//投稿機能    
    //フォームに値が入力されたとき    
    if(!empty($_POST["name"])&&($_POST["com"])&&($_POST["pass"])){
    //新規投稿フォーム
        //かつ編集番号が空の時
      if(empty($_POST["cost"])){
        //INSERT文を用いてデータをテーブルに転送
        $sql = $pdo -> prepare("INSERT INTO tb2 (name, com, date, pass) VALUES (:name, :com, :date, :pass)");
        $sql -> bindParam(":name", $name_date, PDO::PARAM_STR);
        $sql -> bindParam(":com", $com_date, PDO::PARAM_STR);
        $sql -> bindParam(":date", $date_date, PDO::PARAM_STR);
        $sql -> bindParam(":pass", $pass_date, PDO::PARAM_STR);
        $name_date=$name;
        $com_date=$com;
        $date_date=$date;
        $pass_date=$pass;
	    $sql -> execute();
           
      }else{
            //編集番号が空ではないとき編集投稿
            $id = $_POST["cost"]; // idがこの値のデータだけを抽出したい、とする
            $sql = 'SELECT * FROM tb2 WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
            
            foreach ($results as $row){
              if ($row['pass'] == $pass) {
                // 入力されているデータレコードの内容を編集
                $name_data = $name;
                $com_data = $com;  
                $date_data = $date;
                $pass_data = $pass;
                $sql = 'UPDATE tb2 SET name=:name,com=:com,date=:date,pass=:pass WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name_data, PDO::PARAM_STR);
                $stmt->bindParam(':com', $com_data, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date_data, PDO::PARAM_STR);
                $stmt->bindParam(':pass', $password_data, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
              }
            }
          }
        }

    //削除機能
    //削除番号が入力されたとき
    if(!empty($_POST["banngou"])){
        $delnum=@$_POST["banngou"];

        //ファイル読み込み
        $sql="SELECT * FROM tb2";
        $stmt=$pdo->query($sql);
        $results=$stmt->fetchAll();
        foreach($results as $row){
        //投稿時のパスワードと削除フォームに入力したパスワードが一致したとき
        if($_POST["pass2"]==$row["pass"]){
            $id=$delnum;
            $sql="delete from tb2 where id=:id";
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();            
        }else{
            echo "正しいパスワードが入力されていません";
        }
        }
    }
        

        //編集が行われるとき
        
        if(isset($_POST["edit"])){
            //ファイル読み込み
            $sql= "SELECT * FROM tb2";
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();

            //パスワードが投稿時のものと一致したとき
            
                $sql="SELECT * FROM tb2";
                $stmt=$pdo->query($sql);
                $results=$stmt->fetchAll();
                foreach ($results as $row){
                    if($_POST["pass3"]==$row["pass"]){
                    if($edit==$row["id"]){
                        $editname=$row["name"];
                        $editcom=$row["com"];
                    }
            }else{
                echo "正しいパスワードが入力されていません";
            }
        }
        }
    ?>
    
    <body>
        <form action=""method="post">
            <!--名前、コメント、削除、編集のフォーム-->
            <input type="text"name="name"placeholder="名前"value="<?php if(isset ($editname)){echo $editname;}?>"><br>
            <input type="text"name="com"placeholder="コメント"value="<?php if(isset ($editcom)){echo $editcom;}?>"><br>
            <input type="password"name="pass"placeholder="パスワード"><br>
            <!--見えなくなる編集フォーム-->
            <?php if(!empty($edit)):?>
            <input type="text"name="cost"value="<?php echo $edit;?>">
            <?php endif;?>
            <input type="submit"name="submit"><br><br>
        </form>
        
        <!--削除フォーム-->
        <form action=""method="post">
            <input type="text"name="banngou"placeholder="削除対象番号"><br>
             <input type="password"name="pass2"placeholder="パスワード">
            <input type="submit"name="削除"value="削除"><br><br>
        </form>   
        
        <!--編集フォーム-->
        <form action=""method="post">
            <input type="text"name="edit"placeholder="編集対象番号"><br>
             <input type="password"name="pass3"placeholder="パスワード">
            <input type="submit"name="編集"value="編集"><br><br>
        </form>
    
    <?php
    //ブラウザに表示
    //SELECT文
    $sql = 'SELECT * FROM tb2';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
        echo $row['com'].',';
        echo $row['date'].'<br>';
	echo "<br>";
	}
    ?>
    </body>
    </html>
