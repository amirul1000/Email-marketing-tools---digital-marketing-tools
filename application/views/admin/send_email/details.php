<a href="<?php echo site_url('admin/send_email/index'); ?>"
	class="btn btn-info"><i class="arrow_left"></i> List</a>
<h5 class="font-20 mt-15 mb-1"><?php echo str_replace('_',' ','Send_email'); ?></h5>
<!--Data display of send_email with id-->
<?php
$c = $send_email;
?>
<table class="table table-striped table-bordered">
	<tr>
		<td>Template</td>
		<td><?php
$this->CI = & get_instance();
$this->CI->load->database();
$this->CI->load->model('Template_model');
$dataArr = $this->CI->Template_model->get_template($c['template_id']);
echo $dataArr['template_name'];
?>
									</td>
	</tr>

	<tr>
		<td>Subject</td>
		<td><?php echo $c['subject']; ?></td>
	</tr>

	<tr>
		<td>To Email</td>
		<td><?php echo $c['to_email']; ?></td>
	</tr>

	<tr>
		<td>Group Leads</td>
		<td><?php
$this->CI = & get_instance();
$this->CI->load->database();
$this->CI->load->model('Group_leads_model');
$dataArr = $this->CI->Group_leads_model->get_group_leads($c['group_leads_id']);
echo $dataArr['created_at'];
?>
									</td>
	</tr>

	<tr>
		<td>Leads</td>
		<td><?php
$this->CI = & get_instance();
$this->CI->load->database();
$this->CI->load->model('Leads_model');
$dataArr = $this->CI->Leads_model->get_leads($c['leads_id']);
echo $dataArr['first_name'];
?>
									</td>
	</tr>

	<tr>
		<td>Message</td>
		<td><?php echo $c['message']; ?></td>
	</tr>


</table>
<!--End of Data display of send_email with id//-->
