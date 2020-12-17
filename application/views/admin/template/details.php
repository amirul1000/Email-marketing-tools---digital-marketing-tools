<a href="<?php echo site_url('admin/template/index'); ?>"
	class="btn btn-info"><i class="arrow_left"></i> List</a>
<h5 class="font-20 mt-15 mb-1"><?php echo str_replace('_',' ','Template'); ?></h5>
<!--Data display of template with id-->
<?php
$c = $template;
?>
<table class="table table-striped table-bordered">
	<tr>
		<td>Template Name</td>
		<td><?php echo $c['template_name']; ?></td>
	</tr>

	<tr>
		<td>Content</td>
		<td><?php echo $c['content']; ?></td>
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
<!--End of Data display of template with id//-->
