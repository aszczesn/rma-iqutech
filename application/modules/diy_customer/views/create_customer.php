<h1><?php echo $title;?></h1>
<p>Please enter customer data.</p>
<?php echo anchor("diy_customer/", "Go Back");?>
<div id="infoMessage"><?php echo $message;?></div>
<?php echo form_open("diy_customer/create_customer");?>
    <p>
        <label for="shortname">Shortname</label> <br />
        <?php echo form_input($shortname);?>
    </p>
    <p>
        <label for="name">Name</label> <br />
        <?php echo form_input($name);?>
    </p>
    <p>
        <?php echo lang('create_user_phone_label', 'phone');?> <br />
        <?php echo form_input($phone);?>
    </p>
    <p>
        <label for="email">Email</label> <br />
        <?php echo form_input($email);?>
    </p>
    <p>
        <label for="categoryid">Category</label> <br />
        <?php echo form_input($categoryid);?>
    </p>
    <p>
        <label for="city">City</label> <br />
        <?php echo form_input($city);?>
    </p>
    <p>
        <label for="address">Address</label> <br />
        <?php echo form_input($address);?>
    </p>
    <p>
        <label for="zip">ZIP</label> <br />
        <?php echo form_input($zip);?>
    </p>
    <p>
        <label for="country">Country</label> <br />
        <?php echo form_input($country);?>
    </p>
    <p><?php echo form_submit('submit', 'Create customer');?></p>

<?php echo form_close();?>
