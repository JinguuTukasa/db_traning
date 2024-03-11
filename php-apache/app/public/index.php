<?php
require_once('functions.php');
header('Set-Cookie: userId=123');
setToken();
?>

<!DOCTYPE html>
  <html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <?php if (!empty($_SESSION['err'])): ?>
      <p><?= $_SESSION['err']; ?></p>
    <?php endif; ?>
    <h1>welcome hello world</h1>
    <div>
      <a href="new.php">
        <!-- <aタグは全部Get> -->
        <p class = new>新規作成</p>
      </a>
    </div>
    <div> 
      <table class = menu>
        <tr>
          <th>ID</th>
          <th>内容</th>
          <th>更新</th>
          <th>削除</th>
        </tr>
        <?php foreach (getTodoList() as $todo): ?>
          <tr>
            <td><?= e($todo['id']); ?></td>
            <td><?= e($todo['content']); ?></td>
            <td>
              <a href="edit.php?id=<?= e($todo['id']); ?>">更新</a>  
            </td>
            <td>
              <form action="store.php" method="post">
                <input type="hidden" name="id" value="<?= e($todo['id']); ?>">
                <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                <button type="submit">削除</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>  
      </table>
    </div>
    <?php unsetError(); ?> 
  </body>
</html>