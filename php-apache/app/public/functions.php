<?php
require_once('connection.php');

function getTodoList()
{
    return getAllRecords();
}

//関数を呼び出したURLで処理を分岐させる
function savePostedData($post)
{
    $path = getRefererPath();
    switch ($path) {
        case '/new.php'://新規作成
            createTodoData($post['content']);
            break;
        case '/edit.php'://編集
            updateTodoData($post);
            break;
        case '/index.php'://論理削除
            deleteTodoData($post['id']);
            break;
        default:
            break;
    }
}

function getRefererPath()
{
    $urlArray = parse_url($_SERVER['HTTP_REFERER']); //調べる 14:00
    return $urlArray['path'];
}

//データ更新処理
function getSelectedTodo($id)
{
    return getTodoTextById($id); 
}

//php_lessonがあるか確認
