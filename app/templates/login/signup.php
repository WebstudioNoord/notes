<?php
function display_errors($d_errors) {
	if ($d_errors) {
		foreach ($d_errors as $d_error) {
			echo('<p class="help-block">'.$d_error.'</p>');
		}
	} else {
		echo('<p></p>');
	}
}
?>
  <div class="row">
    <div class="col-md-6">
    <!--<pre><? print_r($post); ?></pre>
	<pre><? print_r($error); ?></pre>
    <pre><? print_r($errors); ?></pre>
    <pre><? print_r($transerrors); ?></pre>-->
          <form class="form-horizontal" action="/signup" method="POST">
          <fieldset>
            <div id="legend">
              <legend class=""><?= $this->trans('user.signup.form.title') ?></legend>
            </div>
            
            <div class="control-group <?= ($transerrors['username']) ? 'has-error' : null ?>">
              <label class="control-label" for="username"><?= $this->trans('user.signup.form.username') ?></label>
              <div class="controls">
                <input id="username" name="username" placeholder="" class="form-control input-lg" type="text" value="<?= $post['username'] ?>">
                <?php  display_errors($transerrors['username']); ?>
                <!--<p class="help-block">Username can contain any letters or numbers, without spaces</p>-->
              </div>
            </div>
         
            <div class="control-group <?= ($transerrors['email']) ? 'has-error' : null ?>">
              <label class="control-label" for="email"><?= $this->trans('user.signup.form.email') ?></label>
              <div class="controls">
                <input id="email" name="email" placeholder="" class="form-control input-lg" type="email" value="<?= $post['email'] ?>">
                <?php  display_errors($transerrors['email']); ?>
                <!--<p class="help-block">Please provide your E-mail</p>-->
              </div>
            </div>
         
            <div class="control-group <?= ($transerrors['password']) ? 'has-error' : null ?>">
              <label class="control-label" for="password"><?= $this->trans('user.signup.form.password') ?></label>
              <div class="controls">
                <input id="password" name="password" placeholder="" class="form-control input-lg" type="password">
                <?php  display_errors($transerrors['password']); ?>
                <!--<p class="help-block">Password should be at least 6 characters</p>-->
              </div>
            </div>
         
            <div class="control-group <?= ($transerrors['password_confirm']) ? 'has-error' : null ?>">
              <label class="control-label" for="password_confirm"><?= $this->trans('user.signup.form.password_confirm') ?></label>
              <div class="controls">
                <input id="password_confirm" name="password_confirm" placeholder="" class="form-control input-lg" type="password">
                <?php  display_errors($transerrors['password_confirm']); ?>
                <!--<p class="help-block">Please confirm password</p>-->
              </div>
            </div>
         
            <div class="control-group">
              <!-- Button -->
              <div class="controls">
                <button class="btn btn-success"><?= $this->trans('user.signup.form.button') ?></button>
              </div>
            </div>
          </fieldset>
        </form>
    
    </div> 
  </div>