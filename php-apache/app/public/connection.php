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
    $sql = 'INSERT INTO todos (content) VALUES ("' . $todoText . '")';
    $dbh->query($sql);
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
    $sql = 'UPDATE todos SET content = "' . $post['content'] . '" WHERE id = ' . $post['id'];
    $dbh->query($sql);
}


//現在の保持されてるデータ取得処理
function getTodoTextById($id)
{
    $dbh = connectPdo();

    // SQL文を準備し、$idをダブルクォーテーションで囲む
    $sql = "SELECT content FROM todos WHERE id = $id AND deleted_at IS NULL";

    // SQL文を実行し、結果セットを取得
    $result = $dbh->query($sql);

    // 結果セットからデータを取得し、連想配列として格納
    //PDO::FETCH_ASSOCここの記述必要なのかは不明
    $data = $result->fetch(PDO::FETCH_ASSOC);
    
    // contentを返す
    return $data['content'];
}

//データの論理削除
function deleteTodoData($id)
{
    $dbh = connectPdo();
    $now = date('Y-m-d H:i:s');

    //UPDATE処理で論理削除を上書きして表示されないようにするだけ
    $sql = "UPDATE todos SET deleted_at = '$now' WHERE id = $id";

    $dbh->query($sql);
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