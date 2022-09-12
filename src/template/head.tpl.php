<header class="p-3 bg-dark text-white">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li class="hover-menu"><a href="/" class="nav-link px-2 text-white"> <?php $this->tr('Главная', 'Home') ?> </a></li>
          <li class="hover-menu"><a href="news" class="nav-link px-2 text-white"> <?= $this->tr('Новости', 'News') ?> </a></li>
          <li class="hover-menu"><a href="market" class="nav-link px-2 text-white"> <?= $this->tr('Магазин', 'Store') ?> </a></li>
          <li class="hover-menu"><a href="http://support.<?= $_SERVER['HTTP_HOST'] ?>/" class="nav-link px-2 text-white"> <?= $this->tr('Служба поддержки', 'Support'); ?> </a></li>
        </ul>
				<?php if($var['activeAutch']): ?>
					<?php if (empty($var['userLogin'])): ?>
						<div class="text-end">
							<form method="POST">
								<span style="margin-right: 10px;"><?= 'guest-' . $var['guestid'] ?></span>
								<a class="btn btn-outline-light me-2" name="register" href="login"> <?= $this->tr('Авторизация', 'Login'); ?> </a>
								<a class="btn btn-outline-light" name="register" href="reg"> <?= $this->tr('Регистрация', 'Registation'); ?> </a>
							</form>
							<div class="ml-2"></div>
						</div>
					<?php else: ?>
						<div class="text-end">
							<form method="POST">
							<span><?= $var['userLogin'] ?></span>
							<a class="btn btn-outline-light me-2 pl-1" name="profile" href="user"> <?= $this->tr('Профиль', 'Profile'); ?> </a>
							</form>
						</div>
					<?php endif; ?>
				<?php endif; ?>
      </div>
    </div>
  </header>
