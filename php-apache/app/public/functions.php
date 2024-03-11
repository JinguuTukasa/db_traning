<?php
require_once('connection.php');
session_start();


// エスケープ処理
function e($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function getTodoList()
{
    return getAllRecords();
}



// SESSIONにtokenを格納する
function setToken()
{
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
}

// SESSIONに格納されたtokenのチェックを行い、SESSIONにエラー文を格納する
function checkToken($token)
{
    if (empty($_SESSION['token']) || ($_SESSION['token'] !== $token)) {
        $_SESSION['err'] = '不正な操作です';
        redirectToPostedPage();
    }
}

function unsetError()
{
    $_SESSION['err'] = '';
}

function redirectToPostedPage()
{
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}



//関数を呼び出したURLで処理を分岐させる
function savePostedData($post)
{
    checkToken($post['token']);
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
    $urlArray = parse_url($_SERVER['HTTP_REFERER']);
    return $urlArray['path'];
}

//データ更新処理
function getSelectedTodo($id)
{
    return getTodoTextById($id); 
}
