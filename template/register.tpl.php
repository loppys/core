<form class="form-signin" method="POST" style="width: 39%; text-align: center;">
      <div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal"><?= $this->tr('Регистрация', 'Registration'); ?></h1>
      </div>

      <div class="form-label-group">
        <input type="text" id="inputLogin" name="login" class="form-control" placeholder='<?= $this->tr('Логин', 'Login'); ?>' required="" autofocus="">
        <label for="inputLogin"></label>
      </div>

      <div class="form-label-group">
        <input type="text" id="inputEmail" name="email" class="form-control" placeholder='Email' required="" autofocus="">
        <label for="inputEmail"></label>
      </div>

      <div class="form-label-group">
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder='<?= $this->tr('Пароль', 'password'); ?>' required="">
        <label for="inputPassword"></label>
      </div>

      <div class="form-label-group">
        <input type="password" id="inputPassword" name="confirm_password" class="form-control" placeholder='<?= $this->tr('Повторите пароль', 'confirm password'); ?>' required="">
        <label for="inputPassword"></label>
      </div>

      <?php include 'error.tpl.php'; ?>

      <input class="btn btn-lg btn-primary btn-block" type="submit" name="register_button" value='<?= $this->tr('Регистрация', 'Register'); ?>'>
    </form>
