<div class="unit one-fourth">
  <div class="boxes">
    <div class="box">
      <div class="box-content">
        <div class="settings-nav">
          <a href='/settings/account'>Account</a>
          <a href='/settings/profile'>Profile</a>
		  <?php if(GlobalUtils::$user->isAdmin()) echo "\r\n<a href='/settings/admin'>Admin</a>";?>
        </div>
      </div>
    </div>
  </div>
</div>
