<?php 
    $attributes = array('class' => 'form-signin');
    echo form_open("auth/login", $attributes);
?>
<h2 class="form-signin-heading"><?php echo lang('login_heading');?></h2>
<p><?php echo lang('login_subheading');?></p>
<div id="infoMessage"><?php echo $message;?></div>
<label for="identity" class="sr-only">
    <?php echo lang('login_identity_label', 'identity');?>
</label>
<?php 
    echo form_input($identity);?>

<label for="password" class='sr-only'>
    <?php echo lang('login_password_label', 'password');?>
</label>
    <?php echo form_input($password);?>


<div class="checkBox">
    
    <label for="remember">
        <?php echo lang('login_remember_label', 'remember');?>
        <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
    </label>
</div>

  <p><?php 
    $data = array(
        'name' => lang('login_submit_btn'),
        'type' => 'submit',
        'class' => 'btn btn-lg btn-primary btn-block',
        'content' => 'Signin',
        );
    echo form_button($data);
    #echo form_submit('submit', lang('login_submit_btn'));
   ?></p>

<?php echo form_close();?>

<p class='form-signin'><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>