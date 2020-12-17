<script src="http://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>

<h3>Send Email</h3>
<!--Form to save data-->
<?php echo form_open_multipart('admin/send_email/send/',array("class"=>"form-horizontal")); ?>
<div class="card">
	<div class="card-body">
		<div class="form-group">
			<label for="Template" class="col-md-4 control-label">Template</label>
			<div class="col-md-8"> 
          <?php
        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->load->model('Template_model');
        $dataArr = $this->CI->Template_model->get_all_template();
        ?> 
          <select name="template_id" id="template_id"
					class="form-control" onChange="setTemplate(this.value);"/>
				<option value="">--Select--</option> 
            <?php
            for ($i = 0; $i < count($dataArr); $i ++) {
                ?> 
            <option value="<?=$dataArr[$i]['id']?>"><?=$dataArr[$i]['template_name']?></option> 
            <?php
            }
            ?> 
          </select>
			</div>
		</div>
		<div class="form-group">
			<label for="To Email" class="col-md-4 control-label">From Email</label>
			<div class="col-md-8">
				Email<input type="text" name="from_email" value=""
					class="form-control" id="from_email" /> Name<input type="text"
					name="from_name" value="" class="form-control" id="from_name" />
			</div>
		</div>
		<div class="form-group">
			<label for="Subject" class="col-md-4 control-label">Subject</label>
			<div class="col-md-8">
				<input type="text" name="subject"
					value="<?php echo ($this->input->post('subject') ? $this->input->post('subject') : $send_email['subject']); ?>"
					class="form-control" id="subject" />
			</div>
		</div>

		<div class="card">
			<div class="card-body">
				<div class="form-group">

					<div class="form-group">
						<label for="To Email" class="col-md-4 control-label">To Email</label>
						<div class="col-md-8">
							<input type="text" name="to_email" value="" class="form-control"
								id="to_email" />
						</div>
					</div>
					<div class="form-group">
						<label for="Group Leads" class="col-md-4 control-label">To Group</label>
						<div class="col-md-8"> 
          <?php
        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->load->model('Group_model');
        $dataArr = $this->CI->Group_model->get_all_group();
        ?> 
          <select name="group_id" id="group_id" class="form-control" />
							<option value="">--Select--</option> 
            <?php
            for ($i = 0; $i < count($dataArr); $i ++) {
                ?> 
            <option value="<?=$dataArr[$i]['id']?>"><?=$dataArr[$i]['group_name']?></option> 
            <?php
            }
            ?> 
          </select>
						</div>
					</div>
					<div class="form-group">
						<label for="Leads" class="col-md-4 control-label">To Leads</label>
						<div class="col-md-8"> 
          <?php
        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->load->model('Leads_model');
        $dataArr = $this->CI->Leads_model->get_all_leads();
        ?> 
          <select name="leads_id" id="leads_id" class="form-control" />
							<option value="">--Select--</option> 
            <?php
            for ($i = 0; $i < count($dataArr); $i ++) {
                ?> 
            <option value="<?=$dataArr[$i]['id']?>"><?=$dataArr[$i]['first_name']?> <?=$dataArr[$i]['last_name']?> <?=$dataArr[$i]['email']?></option> 
            <?php
            }
            ?> 
          </select>
						</div>
					</div>
					<hr>
					<div class="form-group">
						<label for="To CC" class="col-md-4 control-label">To CC</label>
						<div class="col-md-8">
							<input type="text" name="to_cc" value="" class="form-control"
								id="to_cc" />
						</div>
					</div>
					<div class="form-group">
						<label for="To BCC" class="col-md-4 control-label">To BCC</label>
						<div class="col-md-8">
							<input type="text" name="to_bcc" value="" class="form-control"
								id="to_bcc" />
						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="form-group">
			<label for="Message" class="col-md-4 control-label">Message</label>
			<div class="col-md-8">
				<textarea name="message" id="message" class="form-control" rows="4" /><?=$template?></textarea>
			</div>
		</div>

	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-4 col-sm-8">
		<button type="submit" class="btn btn-success">Send</button>
	</div>
</div>
<?php echo form_close(); ?>
<!--End of Form to save data//-->
<!--JQuery-->
<script>
	CKEDITOR.replace( 'message', {
	  height: 300,
	  filebrowserUploadUrl: "<?php echo site_url('admin/template/upload_picture'); ?>"
	 });
	 function setTemplate(id){
		 window.location.href = "<?php echo site_url('admin/send_email/index/'); ?>"+id;
	 }
	/*$("#template_id").on('change',()=>{
		      $.ajax({
					  method: "POST",
					  url: "<?php echo site_url('admin/send_email/template'); ?>",
					  data:{
							id:$("#template_id").val()
						   },
					  dataType: 'text',
					  success : function(data) {
							$("#message").html(data);
					  }
			});
		}); 	 
*/</script>