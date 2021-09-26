<?php
session_start();
require('dbconnect.php');
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time();
  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();
} else {
  header('Location: login.php');
  exit();
}

if (!empty($_POST)) {
  if ($_POST['message'] !== '') {
    $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, kind=?, created=NOW()');
    $message->execute(array(
      $member['id'],
      $_POST['message'],
      $_POST['kind']
    ));

    header('Location: index.php');
    exit();
  }
}

$posts = $db->query('SELECT m.name, p.* FROM members m,posts p WHERE m.id=p.member_id ORDER BY p.created DESC');
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>W.news</title>
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <header class="header">
    <div class="header__inner inner">
      <h1 class="header__logo"><a href="/"><img src="img/wnews_logo.png" alt=""></a></h1>
      <div class="drawer">
        <input type="checkbox" class="drawer__checkbox" id="drawerCheckbox">
        <label for="drawerCheckbox" class="drawer__icon">
          <span class="drawer__icon-parts"></span><!-- /.drawer__icon-parts -->
        </label><!-- /.drawer__icon -->
        <label for="drawerChechbox" class="drawer__overlay"></label><!-- /.drawer__overlay -->
        <nav class="drawer__menu">
          <ul>
            <li class="drawer__item"><a href="/" class="drawer__item-inner">トップページ</a>
              <!-- /.drawer__item-inner -->
            </li><!-- /.drawer__item -->
            <li class="drawer__item"><a href="/" class="drawer__item-inner">ログイン</a>
              <!-- /.drawer__item-inner -->
            </li><!-- /.drawer__item -->
          </ul>
        </nav><!-- /.drawer__menu -->
      </div><!-- /.drawer -->
    </div>
  </header><!-- /.header -->

  <section class="now__calender inner">
    <div class="SPACER--60"></div>
    <h1 class="month__wx"><span>10月</span><?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?>の天気予報</h1>
    <table class="table">
      <tr class="table__date">
        <th>日付</th>
        <th>10/1<br>金</th>
        <th>10/2<br>土</th>
        <th>10/3<br>日</th>
        <th>10/4<br>月</th>
        <th>10/5<br>火</th>
        <th>10/6<br>水</th>
        <th>10/7<br>木</th>
      </tr>
      <tr class="table__wx">
        <td>天気</td>
        <td><img src="img/Frame.png" alt=""></td>
        <td><img src="img/Frame.png" alt=""></td>
        <td><img src="img/Frame.png" alt=""></td>
        <td><img src="img/Frame.png" alt=""></td>
        <td><img src="img/Frame.png" alt=""></td>
        <td><img src="img/Frame.png" alt=""></td>
        <td><img src="img/Frame.png" alt=""></td>
      </tr>
      <tr class="table__mental">
        <td>メンタル</td>
        <td><img src="img/verygood.png" alt=""></td>
        <td><img src="img/good.png" alt=""></td>
        <td><img src="img/bad.png" alt=""></td>
        <td><img src="img/verybad.png" alt=""></td>
        <td><img src="img/verygood.png" alt=""></td>
        <td><img src="img/verygood.png" alt=""></td>
        <td><img src="img/verygood.png" alt=""></td>
      </tr>
    </table>
    <div class="SPACER--100"></div>
  </section>

  <section class="now__mental inner">
    <h1>あなたの今日のメンタルは？</h1>
    <div class="now__mental--inner">
      <div class="card__items">
        <div class="card__wrapper">
          <div class="card__item">
            <img src="img/verygood.png" alt="">
          </div>
        </div>
        <div class="card__wrapper">
          <div class="card__item">
            <img src="img/good.png" alt="">
          </div>
        </div>
        <div class="card__wrapper">
          <div class="card__item">
            <img src="img/bad.png" alt="">
          </div>
        </div>
        <div class="card__wrapper">
          <div class="card__item">
            <img src="img/verybad.png" alt="">
          </div>
        </div>
      </div>
    </div>
    <div class="SPACER--100"></div>
  </section>

  <section class="today__action inner">
    <h1>あなたの今日のご予定は？</h1>
    <div class="today__action--form">
      <form action="" method="post" class="today__action--wrapper">
        <dl class="today__action--dl">
          <div class="today__action--select">
            <select name="kind" id="your-label">
              <?php $kind = ['job'=>'仕事', 'hobby'=>'遊び', 'house'=>'家事', 'study'=>'勉強', 'buy'=>'買い物', 'trip'=>'旅行', 'sports'=>'運動', 'hospital'=>'病院'];
                foreach($kind as $kind_key => $kind_val){
                  $kind .= "<option value='". $kind_key;
                  $kind .= "'>". $kind_val. "</option>";
                }
              ?>
              <?php echo $kind;?>
              <!-- <option value="job">仕事</option>
              <option value="hobby">遊び</option>
              <option value="house">家事</option>
              <option value="study">勉強</option>
              <option value="buy">買い物</option>
              <option value="trip">旅行</option>
              <option value="sports">運動</option>
              <option value="hospital">病院</option> -->
            </select>
          </div>
          <div class="today__action--input">
            <input name="message" type="text" id="your-input" placeholder="一言メモ">
          </div>
          <div class="today__action--btn">
            <input type="submit" value="保存">
          </div>
        </dl>
      </form>
      <div class="SPACER--100"></div>
    </div>
  </section>

  <section class="old__calender inner">
    <div class="SPACER--60"></div>
    <h1 class="month__wx">過去に10月1日と似た気象条件の日</h1>
    <table class="table__old">
      <tr class="table__old--list">
        <th>日付</th>
        <th>天気</th>
        <th>メンタル</th>
        <th>行動</th>
      </tr>
      <tr class="table__old--wx">
        <td>
          <p>2021年2月1日</p>
        </td>
        <td><img src="img/Frame.png" alt=""></td>
        <td><img src="img/good.png" alt=""></td>
        <td>
          <p>仕事で残業した</p>
        </td>
      </tr>
    </table>
    <div class="SPACER--60"></div>
    <div class="share">
      <h2>結果をシェアしよう！</h2>
      <a href="#" class="facebook">Facebook</a>
      <a href="#" class="twitter">Twitter</a>
    </div>
    <div class="SPACER--100"></div>
  </section>

  <footer class="footer">
    <small>Copyright @kabutomushi</small>
  </footer>

</body>

</html>