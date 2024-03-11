<?php
require_once('config.php');//Q1

// PDOクラスのインスタンス化
//エラーが起きた時の処理、、、、？
function connectPdo() {         //Q2
    try {
        
        // var_dump(DB_USER,DB_PASSWORD);
        // exit;

        return new PDO(DSN, DB_USER, DB_PASSWORD); //PDOクラスをインスタンス化してる
        //コンストラクトで接続を試してる
        //失敗したら飛んでくるようになってる
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
}

// 新規作成処理
function createTodoData($todoText)
{
    $dbh = connectPdo();
    $sql = 'INSERT INTO todos (content) VALUES (:todoText)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':todoText', $todoText, PDO::PARAM_STR);
    $stmt->execute();
}

//データ取得処理
function getAllRecords()
{
    $dbh = connectPdo();
    $sql = 'SELECT * FROM todos WHERE deleted_at IS NULL';
    echo '<pre>';
    //var_dump ($dbh->query($sql)->fetchAll());
    // exit;
    return $dbh->query($sql)->fetchAll();
}

// 更新処理
function updateTodoData($post)
{
    $dbh = connectPdo();
    $sql = 'UPDATE todos SET content = :todoText WHERE id = :id';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':todoText', $post['content'], PDO::PARAM_STR);
    $stmt->bindValue(':id', (int) $post['id'], PDO::PARAM_INT);
    $stmt->execute();
}


//現在の保持されてるデータ取得処理
function getTodoTextById($id)
{
    $dbh = connectPdo();

    $sql = "SELECT content FROM todos WHERE id = :todoId AND deleted_at IS NULL";

    $stmt = $dbh->prepare($sql);//追記
    $stmt->bindValue('todoId',$id,PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->execute();
    
    return $data['content'];
}

//データの論理削除
function deleteTodoData($id)
{
    $dbh = connectPdo();
    $now = date('Y-m-d H:i:s');
    $sql = "UPDATE todos SET deleted_at = :todoNow WHERE id = :todoId";

    $stmt = $dbh->prepare($sql);

    // プレースホルダーに値をバインド
    $stmt->bindValue(':todoNow', $now, PDO::PARAM_STR);
    $stmt->bindValue(':todoId', $id, PDO::PARAM_INT);

    // 準備された文を実行
    $stmt->execute();
}


//最初にサーバーを立てた時にテーブルを定義
function useDb()
{
    $dbh = connectPdo();
    $sql = 'use php_lesson' ;
    $dbh = query($sql);

}

function tableCreate()
{
    $dbh = connectPdo();
    $sql = "CREATE TABLE `todos` (
        `id` MEDIUMINT NOT NULL AUTO_INCREMENT,
        `content` VARCHAR(255),
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `deleted_at` DATETIME NULL DEFAULT NULL,
        PRIMARY KEY(`id`)
        ) " ;

    $dbh = query($sql);

}

//テーブル情報を取得
function confirmationTabel()
{
    $sql = 'SHOW TABLES';
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "テーブル名: " . $row["Tables_in_$dbname"]. "<br>";
        }
    } else {
        echo "テーブルはありません";
    }
}