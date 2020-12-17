<a href="<?php echo site_url('admin/contentmeta/index'); ?>"
	class="btn btn-info"><i class="arrow_left"></i> List</a>
<h5 class="font-20 mt-15 mb-1"><?php echo str_replace('_',' ','Contentmeta'); ?></h5>
<!--Data display of contentmeta with id-->
<?php
$c = $contentmeta;
?>
<table class="table table-striped table-bordered">
	<tr>
		<td>Content</td>
		<td><?php
$this->CI = & get_instance();
$this->CI->load->database();
$this->CI->load->model('Content_model');
$dataArr = $this->CI->Content_model->get_content($c['content_id']);
echo $dataArr['content_title'];
?>
									</td>
	</tr>

	<tr>
		<td>Meta Key</td>
		<td><?php echo $c['meta_key']; ?></td>
	</tr>

	<tr>
		<td>Meta Value</td>
		<td><?php echo $c['meta_value']; ?></td>
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
<!--End of Data display of contentmeta with id//-->
