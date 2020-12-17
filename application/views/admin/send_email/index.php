<h5 class="font-20 mt-15 mb-1"><?php echo str_replace('_',' ','Send_email'); ?></h5>
<?php
echo $this->session->flashdata('msg');
?>
<!--Action-->
<div>
	<div class="float_left padding_10">
		<a href="<?php echo site_url('admin/send_email/save'); ?>"
			class="btn btn-success">Add</a>
	</div>
	<div class="float_left padding_10">
		<i class="fa fa-download"></i> Export <select name="xeport_type"
			class="select"
			onChange="window.location='<?php echo site_url('admin/send_email/export'); ?>/'+this.value">
			<option>Select..</option>
			<option>Pdf</option>
			<option>CSV</option>
		</select>
	</div>
	<div class="float_right padding_10">
		<ul class="left-side-navbar d-flex align-items-center">
			<li class="hide-phone app-search mr-15">
                <?php echo form_open_multipart('admin/send_email/search/',array("class"=>"form-horizontal")); ?>
                    <input name="key" type="text"
				value="<?php echo isset($key)?$key:'';?>" placeholder="Search..."
				class="form-control">
				<button type="submit" class="mr-0">
					<i class="fa fa-search"></i>
				</button>
                <?php echo form_close(); ?>
            </li>
		</ul>
	</div>
</div>
<!--End of Action//-->

<!--Data display of send_email-->
<table class="table table-striped table-bordered">
	<tr>
		<th>Template</th>
		<th>Subject</th>
		<th>To Email</th>
		<th>Group Leads</th>
		<th>Leads</th>
		<th>Message</th>

		<th>Actions</th>
	</tr>
	<?php foreach($send_email as $c){ ?>
    <tr>
		<td><?php
    $this->CI = & get_instance();
    $this->CI->load->database();
    $this->CI->load->model('Template_model');
    $dataArr = $this->CI->Template_model->get_template($c['template_id']);
    echo $dataArr['template_name'];
    ?>
									</td>
		<td><?php echo $c['subject']; ?></td>
		<td><?php echo $c['to_email']; ?></td>
		<td><?php
    $this->CI = & get_instance();
    $this->CI->load->database();
    $this->CI->load->model('Group_leads_model');
    $dataArr = $this->CI->Group_leads_model->get_group_leads($c['group_leads_id']);
    echo $dataArr['created_at'];
    ?>
									</td>
		<td><?php
    $this->CI = & get_instance();
    $this->CI->load->database();
    $this->CI->load->model('Leads_model');
    $dataArr = $this->CI->Leads_model->get_leads($c['leads_id']);
    echo $dataArr['first_name'];
    ?>
									</td>
		<td><?php echo $c['message']; ?></td>

		<td><a
			href="<?php echo site_url('admin/send_email/details/'.$c['id']); ?>"
			class="action-icon"> <i class="zmdi zmdi-eye"></i></a> <a
			href="<?php echo site_url('admin/send_email/save/'.$c['id']); ?>"
			class="action-icon"> <i class="zmdi zmdi-edit"></i></a> <a
			href="<?php echo site_url('admin/send_email/remove/'.$c['id']); ?>"
			onClick="return confirm('Are you sure to delete this item?');"
			class="action-icon"> <i class="zmdi zmdi-delete"></i></a></td>
	</tr>
	<?php } ?>
</table>
<!--End of Data display of send_email//-->

<!--No data-->
<?php
if (count($send_email) == 0) {
    ?>
<div align="center">
	<h3>Data is not exists</h3>
</div>
<?php
}
?>
<!--End of No data//-->

<!--Pagination-->
<?php
echo $link;
?>
<!--End of Pagination//-->
