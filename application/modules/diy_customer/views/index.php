<h2 class="sub-header"><?php #echo lang('index_heading');?></h2>
<p><?php #echo lang('index_subheading');?></p>
<div class="table-responsive">


    <div id="infoMessage"><?php #echo $message;?></div>

<table class="table table-striped">
    <thead>
	<tr>
		<th>#</th>
		<th>short name</th>
		<th>name</th>
		<th>e-mail</th>
		<th>city</th>
		<th>address</th>
                <th>zip</th>
                <th>category</th>
                <th>phone #</th>
	</tr>
    </thead>
    <tbody>
	<?php foreach ($customers as $customer):?>
            <tr>
                <td><?php echo htmlspecialchars($customer->id,ENT_QUOTES,'UTF-8');?></td>
                <td><?php echo htmlspecialchars($customer->shortname,ENT_QUOTES,'UTF-8');?></td>
                <td><?php echo htmlspecialchars($customer->name,ENT_QUOTES,'UTF-8');?></td>
                <td><?php echo htmlspecialchars($customer->email,ENT_QUOTES,'UTF-8');?></td>
                <td><?php echo htmlspecialchars($customer->city,ENT_QUOTES,'UTF-8');?></td>
                <td><?php echo htmlspecialchars($customer->address,ENT_QUOTES,'UTF-8');?></td>
                <td><?php echo htmlspecialchars($customer->zip,ENT_QUOTES,'UTF-8');?></td>
                <td>
                    <?php foreach ($customer->groups as $group):?>
                        <?php echo anchor("diy_customer_type/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?><br />
                    <?php endforeach?>
                </td>
                <td><?php echo htmlspecialchars($customer->phoneno,ENT_QUOTES,'UTF-8');?></td>
                <td><?php echo anchor("diy_customer_type/edit_customer/".$customer->id, 'Edit') ;?></td>
            </tr>
	<?php endforeach;?>
    </tbody>
</table>

<p><?php echo anchor('diy_customer/create_customer', 'Create customer')?> | <?php echo anchor('diy_customer_type/create_group', 'Create group')?></p>
</div>