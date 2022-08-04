<section>
<form class="form-signin" method="POST" style="padding-top: 100px; width: 39%; text-align: center;">
      <div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal"><?= $this->tr('Авторизация', 'Login'); ?></h1>
      </div>

      <div class="form-label-group">
        <input type="text" id="inputLogin" name="login" class="form-control" placeholder='<?= $this->tr('Логин', 'Login'); ?>' required="" autofocus="">
        <label for="inputLogin"></label>
      </div>

      <div class="form-label-group">
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder='<?= $this->tr('Пароль', 'Password'); ?>' required="">
        <label for="inputPassword"></label>
      </div>

      <input class="btn btn-lg btn-primary btn-block" type="submit" name="login-button" value='<?= $this->tr('Войти', 'Login'); ?>'>

      <?= !empty($errors) ? errorRender($errors) : '' ?>
</form>
</section>
