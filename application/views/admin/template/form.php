<script src="http://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>

<a href="<?php echo site_url('admin/template/index'); ?>"
	class="btn btn-info"><i class="arrow_left"></i> List</a>
<h5 class="font-20 mt-15 mb-1"><?php if($id<0){echo "Save";}else { echo "Update";} echo " "; echo str_replace('_',' ','Template'); ?></h5>
<!--Form to save data-->
<?php echo form_open_multipart('admin/template/save/'.$template['id'],array("class"=>"form-horizontal")); ?>
<div class="card">
	<div class="card-body">
		<div class="form-group">
			<label for="Template Name" class="col-md-4 control-label">Template
				Name</label>
			<div class="col-md-8">
				<input type="text" name="template_name"
					value="<?php echo ($this->input->post('template_name') ? $this->input->post('template_name') : $template['template_name']); ?>"
					class="form-control" id="template_name" />
			</div>
		</div>
		<div class="form-group">
			<label for="Content" class="col-md-4 control-label">Content</label>
			<div class="col-md-8">
				<textarea name="content" id="content" class="form-control" rows="4" /><?php echo ($this->input->post('content') ? $this->input->post('content') : $template['content']); ?></textarea>
			</div>
		</div>

	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-4 col-sm-8">
		<button type="submit" class="btn btn-success"><?php if(empty($template['id'])){?>Save<?php }else{?>Update<?php } ?></button>
	</div>
</div>
<?php echo form_close(); ?>
<!--End of Form to save data//-->
<!--JQuery-->
<script>
	CKEDITOR.replace( 'content', {
	  height: 300,
	  filebrowserUploadUrl: "<?php echo site_url('admin/template/upload_picture'); ?>"
	 });
</script>