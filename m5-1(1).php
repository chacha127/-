<!DOCTYPE html>
<html lang="ja">
     <head>
         <meta charset="UTF-8">
         <title>mission_5-1(1)</title>
     </head>
     
     <body>
         <?php
                $dsn = 'データベース名';
                $user = 'ユーザー名';
                $password = 'パスワード';
                $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                   
                //存在していない場合、テーブル5を作成
                    $sql = "CREATE TABLE IF NOT EXISTS tb6"
                    ." ("
                    . "id INT AUTO_INCREMENT PRIMARY KEY,"
                    . "name TEXT,"//名前
                    . "comment TEXT,"//コメント
                    . "date DATETIME,"//日時
                    . "pass TEXT"//パスワード
                    .");";
                //$sqlに格納したSQL文が実行される
                $stmt = $pdo->query($sql);
//条件分岐開始
//入力フォームに値があり隠しテキストボックスが空のとき＝新規投稿を実行
if(!empty($_POST["name"])&&!empty($_POST["str"])&&!empty($_POST["pass"])&&empty($_POST["hidden"]))
{
        
        $sql = $pdo -> prepare("INSERT INTO tb6 (name, comment, date, pass) VALUES (:name, :comment, now(), :pass)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $str, PDO::PARAM_STR);
        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
    

        $name=$_POST["name"];//氏名
        $str=$_POST["str"];//コメント内容
        $date=date("Y/m/d H:i:s");//日時  
        $pass=$_POST["pass"];//パスワード
      
        $sql -> execute();}
       
 
            //編集実行機能
if(!empty($_POST["hidden"]) && !empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["pass"])){
                
                $hidden = $_POST["hidden"];
                
                $id = $hidden; //編集する投稿番号
                $name = $_POST["name"];//編集して送信された名前
                $str = $_POST["str"]; //編集して送信されたコメント
                $date = date("Y/m/d H:i:s");//日時
                $sql = 'UPDATE tb6 SET name=:name, comment=:comment, date=:date, pass=:pass WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $str, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                
                $stmt->execute();
                
            }
            
            //編集選択機能
if(!empty($_POST["enum"]) && !empty($_POST["epass"]) ){
                
                $enum = $_POST["enum"];
                $epass = $_POST["epass"];
                
                $id = $enum;
                $sql = "SELECT * FROM tb6 WHERE id=:id AND pass=:pass";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':pass', $epass, PDO::PARAM_STR);
                $stmt->execute();
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    
                    $ename = $row["name"];
                    $estr = $row["comment"];
                    
                }
            }
            
             ?>
         
        
           <form action=""method="post">
            
            <input type="text"name="name"placeholder="氏名"
            value="<?php if(!empty($ename)){echo $ename;}?>"><br>
            
            <input type="text"name="str"placeholder="コメント"
            value="<?php if (!empty($estr)){echo $estr;}?>"><br>
            
            <input type="text"name="pass"placeholder="パスワード"
            value=""><br>
            
             <input type="hidden"name="hidden"
            value="<?php if (!empty($enum)){echo ($enum);}?>"><br><!--編集番号を反映する隠しテキストボックス-->
           
           
            <input type="submit"name="submit">
        </form> <!--送信フォーム設置完了-->
        
         <form action=""method="post">
            <input type="number"name="dnum"placeholder="削除対象番号"><br>
             <input type="text"name="dpass"placeholder="パスワード"
            value=""><br>
            <input type="submit"name="delete"value="削除">
        </form> <!--削除フォーム設置完了-->
        
         <form action=""method="post">
            <input type="number"name="enum"placeholder="編集対象番号"><br>
             <input type="text"name="epass"placeholder="パスワード"
            value=""><br>
            <input type="submit"name="edit"value="編集">
        </form> <!--編集フォーム設置完了-->
            
      
<?php        


    
  
//削除フォームに値がある場合削除を実行 DELETE文でいけるか
if(!empty($_POST["dnum"])&&!empty($_POST["dpass"]))
{
    //テキストファイルを変数として定義　
    $dnum=$_POST["dnum"];//削除番号の定義
    $dpass=$_POST["dpass"];  
    //dnumと一致するデータを削除
    $id = $dnum;
    $sql = "delete from tb6 where id=:id AND pass=:pass";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':pass', $dpass, PDO::PARAM_STR);
    $stmt->execute();
   
}



//すべての処理の後、テーブルの中身をブラウザに表示
//テーブルを選択
    $sql = 'SELECT * FROM tb6';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();//すべて取ってくるという意味
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
        echo "<hr>";
    }

?>
</body>
</html>
