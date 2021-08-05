<style>
  .footer {
    text-align: center;
    margin-top: 102%;
    position: absolute;
    top: auto;
    width: 100%;
    background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.2) 25%, rgba(255, 255, 255, 0.2) 75%, rgba(255, 255, 255, 0) 100%);
    box-shadow: 0 0 25px rgba(0, 0, 0, 0.1), inset 0 0 1px rgba(255, 255, 255, 0.6);
  }
  .footer .commercy_footer
  {
  	border-top: solid 1px black;
  	color: rgba(0, 35, 122, 0.7);
  	padding-bottom: 5px;
  	padding-top: 5px;
  }
  .footer .commercy_copyright
  {
  	color: rgba(0, 35, 122, 0.7);
  }

</style>
<footer class="footer">
  <div class="commercy_footer">
  <span class="commercy_copyright">
    <div>
      Copyright © <?= date("Y") ?>
      vEngine
      |
      (<a href="https://nazhariagames.site/subscribe" class="support_footer"><?= $this->tr('Купить', 'Buy') ?></a>)
    </div>
  </span>
  </div>
</footer>
