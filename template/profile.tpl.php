<div class="container-profile">
    <div class="profile">
    <a id="profile"></a>

    <?= userLoadInfo() ?>

    <hr>
    <div class="purchases">
      <h2>Покупки</h2> <br>
      <a href="#purchases"></a>
    </div>
    <hr>
    <div class="settings">
      <a id="settings"></a>
      <h2>Настройки</h2> <br>
        <form class="settings-form" method="POST">
          <select name="currency">
            <option disabled selected> <?= $this->tr('Выберите валюту'); ?></option>
            <option>RUB</option>
            <option>DOLLAR</option>
            <option>EURO</option>
          </select>
          <input type="submit" name="currency__submit" value="сменить">
        </form>
        <br>
        <form class="settings-form" action="" method="post">
          <input type="password" name="pass_current" placeholder="Введите текущий пароль">
          <input type="password" name="pass_new" placeholder="Введите новый пароль">
          <input type="submit" name="pass_change" value="<?= $_GET['nameFieldPassChange'] ?>">
          <br>
          <h5>Текущий email -> <?= returnEmailUser() ?> </h5>
          <input type="text" name="new_email" placeholder="Введите новый email">
          <input type="submit" name="confirm_email" value="Подтвердить">
        </form>
    </div>
    <hr>
    <div class="button">
      <form class="button-form" method="POST" action="">
        <input type="submit" name="referal" value="Реферальная система">
        <input type="submit" name="reset_referal" value="Обнулить реферальные бонусы">
        <input type="submit" name="del_acc" value="Удаление аккаунта">
        <input type="submit" name="Feedback" value="Обратная связь">
        <input type="submit" name="logout" value="Выход">
      </form>
    </div>
  </div>
</div>
