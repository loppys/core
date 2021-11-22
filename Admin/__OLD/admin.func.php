  <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: auto; margin-left: 2%; position: relative; height: auto;">

    <!--  -->
    <form action="" method="POST" name="infouser" class="infouser">
    <p>Info User</p>
    <input type="text" name="values_id" placeholder="id user">
    <input type="submit" name="button_u">
    <br>
    <br>
    <?= $admin->user($_POST['values_id']) ?>
  </form>
  <hr>
  <form action="" method="POST" name="productlist" class="productlist">
    <p>Product List (count: <?= $admin->productcount() ?>) </p>
    <input type="submit" name="button_p" value="check">
    <br>
    <br>
    <?= $admin->productlist($post['button_p']) ?>
  </form>
  <hr>
  <form action="" method="POST" name="userlist" class="userlist">
    <p>User List (count: <?= $admin->usercount() ?>) </p>
    <input type="submit" name="button_ul" value="check">
    <br>
    <br>
    <?= $admin->userlist($post['button_ul']) ?>
  </form>
  <hr>
  <form action="" method="POST" name="teamlist" class="teamlist">
    <p>Team List</p>
    <p>Admins</p>
    <?= $admin->admteam() ?>
    <br>
    <p>Supports and Moderators</p>
    <?= $admin->supteam() ?>
  </form>
  <hr>
  <form action="" method="POST" name="addproduct" class="addproduct">
    <p>Add product</p>
    <input type="text" name="name" value="" placeholder="name">
    <input type="text" name="description" value="" placeholder="description">
    <input type="text" name="price" value="$" placeholder="price">
    <input type="text" name="type" value="" placeholder="type">
    <input type="text" name="market" value="" placeholder="market">
    <input type="submit" name="button_ap" value="add">
    <br>
    <?= $admin->addproduct($post['name'], $post['description'], $post['price'], $post['type'], $post['market'], $post['button_ap']) ?>
  </form>
  <hr>
  <form action="" method="POST" name="logs" class="logs">
    <p>LOG</p>
    <?= $admin->read_log($post['button_lg']) ?>
    <input type="submit" name="button_lg" value='login'>
  </form>
  <hr>
  <form action="" method="POST" name="setbalance" class="setbalance">
    <p>Set Balance</p>
    <input type="text" name="id" value="" placeholder="ID">
    <input type="text" name="value_add" value="" placeholder="Value">
    <input type="submit" name="button_sb" value="add">
    <br>
    <?= $admin->setbalance($post['id'], $post['value_add'], $post['button_sb']) ?>
  </form>
  <hr>
  <form action="" method="POST" name="unbalance" class="unbalance">
    <p>Del Balance</p>
    <input type="text" name="id" value="" placeholder="ID">
    <input type="text" name="value_unset" value="" placeholder="Value">
    <input type="submit" name="button_ub" value="add">
    <br>
    <?= $admin->unbalance($post['id'], $post['value_unset'], $post['button_ub']) ?>
  </form>
  <hr>
  <form action="" method="POST" name="_news" class="news" style="display: flex; flex-direction: column;">
    <p>Добавить статью</p>
    <input type="text" name="title" value="" placeholder="Title">
    <input type="text" name="intro" value="" placeholder="Intro">
    <input type="text" name="autor" value="<?= $_SESSION[$this->session_name]->login; ?>" placeholder="Autor">
    <textarea type="text" name="text" style="min-width: 150px; min-height: 100px;">

    </textarea>
    <input type="submit" name="button_news" value="add">
    <br>
    <?= $admin->news($post['button_news'], $post['title'], $post['intro'], $post['autor'], $post['text']) ?>
  </form>
  <hr>
  <a href="user">Profile</a>
  <br>
  <input type="text" value="Version: <?= $ver ?>" disabled>
  <br>
  <input type="text" value="Build: <?= $version ?>" disabled>
  <br>
  <a href="?_LOG_MODE=CLEAR">Очистить временные логи</a>
<!--  -->

  </div>
