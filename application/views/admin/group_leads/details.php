<a href="<?php echo site_url('admin/group_leads/index'); ?>"
	class="btn btn-info"><i class="arrow_left"></i> List</a>
<h5 class="font-20 mt-15 mb-1"><?php echo str_replace('_',' ','Group_leads'); ?></h5>
<!--Data display of group_leads with id-->
<?php
$c = $group_leads;
?>
<table class="table table-striped table-bordered">
	<tr>
		<td>Group</td>
		<td><?php
$this->CI = & get_instance();
$this->CI->load->database();
$this->CI->load->model('Group_model');
$dataArr = $this->CI->Group_model->get_group($c['group_id']);
echo $dataArr['group_name'];
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
		<td>Created At</td>
		<td><?php echo $c['created_at']; ?></td>
	</tr>

	<tr>
		<td>Updated At</td>
		<td><?php echo $c['updated_at']; ?></td>
	</tr>


</table>
<!--End of Data display of group_leads with id//-->
