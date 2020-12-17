<a href="<?php echo site_url('admin/contentmeta/index'); ?>"
	class="btn btn-info"><i class="arrow_left"></i> List</a>
<h5 class="font-20 mt-15 mb-1"><?php if($id<0){echo "Save";}else { echo "Update";} echo " "; echo str_replace('_',' ','Contentmeta'); ?></h5>
<!--Form to save data-->
<?php echo form_open_multipart('admin/contentmeta/save/'.$contentmeta['id'],array("class"=>"form-horizontal")); ?>
<div class="card">
	<div class="card-body">
		<div class="form-group">
			<label for="Content" class="col-md-4 control-label">Content</label>
			<div class="col-md-8"> 
          <?php
        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->load->model('Content_model');
        $dataArr = $this->CI->Content_model->get_all_content();
        ?> 
          <select name="content_id" id="content_id" class="form-control" />
				<option value="">--Select--</option> 
            <?php
            for ($i = 0; $i < count($dataArr); $i ++) {
                ?> 
            <option value="<?=$dataArr[$i]['id']?>"
					<?php if($contentmeta['content_id']==$dataArr[$i]['id']){ echo "selected";} ?>><?=$dataArr[$i]['content_title']?></option> 
            <?php
            }
            ?> 
          </select>
			</div>
		</div>
		<div class="form-group">
			<label for="Meta Key" class="col-md-4 control-label">Meta Key</label>
			<div class="col-md-8">
				<input type="text" name="meta_key"
					value="<?php echo ($this->input->post('meta_key') ? $this->input->post('meta_key') : $contentmeta['meta_key']); ?>"
					class="form-control" id="meta_key" />
			</div>
		</div>
		<div class="form-group">
			<label for="Meta Value" class="col-md-4 control-label">Meta Value</label>
			<div class="col-md-8">
				<textarea name="meta_value" id="meta_value" class="form-control"
					rows="4" /><?php echo ($this->input->post('meta_value') ? $this->input->post('meta_value') : $contentmeta['meta_value']); ?></textarea>
			</div>
		</div>

	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-4 col-sm-8">
		<button type="submit" class="btn btn-success"><?php if(empty($contentmeta['id'])){?>Save<?php }else{?>Update<?php } ?></button>
	</div>
</div>
<?php echo form_close(); ?>
<!--End of Form to save data//-->
<!--JQuery-->
<script>
	$( ".datepicker" ).datepicker({
		dateFormat: "yy-mm-dd", 
		changeYear: true,
		changeMonth: true,
		showOn: 'button',
		buttonText: 'Show Date',
		buttonImageOnly: true,
		buttonImage: '<?php echo base_url(); ?>public/datepicker/images/calendar.gif',
	});
</script>
