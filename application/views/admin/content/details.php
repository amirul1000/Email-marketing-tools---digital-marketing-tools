<a href="<?php echo site_url('admin/content/index'); ?>"
	class="btn btn-info"><i class="arrow_left"></i> List</a>
<h5 class="font-20 mt-15 mb-1"><?php echo str_replace('_',' ','Content'); ?></h5>
<!--Data display of content with id-->
<?php
$c = $content;
?>
<table class="table table-striped table-bordered">
	<tr>
		<td>Content Users</td>
		<td><?php
$this->CI = & get_instance();
$this->CI->load->database();
$this->CI->load->model('Users_model');
$dataArr = $this->CI->Users_model->get_users($c['content_users_id']);
echo $dataArr['email'];
?>
									</td>
	</tr>

	<tr>
		<td>Content Title</td>
		<td><?php echo $c['content_title']; ?></td>
	</tr>

	<tr>
		<td>Description</td>
		<td><?php echo $c['description']; ?></td>
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
<!--End of Data display of content with id//-->
