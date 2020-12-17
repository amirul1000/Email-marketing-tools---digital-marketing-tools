<?php

/**
 * Author: Amirul Momenin
 * Desc:Send_email Controller
 *
 */
class Send_email extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->library('pagination');
        $this->load->library('Customlib');
        $this->load->helper(array(
            'cookie',
            'url'
        ));
        $this->load->database();
        $this->load->model('Send_email_model');
        if (! $this->session->userdata('validated')) {
            redirect('admin/login/index');
        }
    }

    /**
     * Index Page for this controller.
     *
     * @param $start -
     *            Starting of send_email table's index to get query
     *            
     */
    function index($template_id=-1)
    {
		$data['template']  = $this->template($template_id);
        $data['send_email'] = $this->Send_email_model->get_send_email(0);
        $data['_view'] = 'admin/send_email/form';
        $this->load->view('layouts/admin/body', $data);
    }

    function send()
    {
		
		$to_email = $this->input->post('to_email');
        // smtp
        $this->db->where('status', 'active');
        $resmtp = $this->db->get('smtp')->result_array();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }
		//from group_id
		$this->db->select('email');
		$this->db->where('group_id', $this->input->post('group_id'));
		$this->db->from('leads');
		$this->db->join('group_leads','group_leads.leads_id=leads.id');
        $reslead = $this->db->get()->result_array();
		
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }
		if(count($reslead)>0){
		   for($i=0;$i<count($reslead);$i++){	
		     $arr_email[] = $reslead[$i]['email'];  	
		   }
		   $to_email = implode(",",$arr_email);	
		}
		//from leads_id
		 $this->db->where('id', $this->input->post('leads_id'));
        $reslead = $this->db->get('leads')->result_array();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }
		if(count($reslead)>0){
		   $to_email = $reslead[0]['email'];	
		}

        $this->load->library('email');
        if(count($resmtp)>0){
			$config = array();
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = $resmtp[0]['host'];
			$config['smtp_user'] = $resmtp[0]['email'];
			$config['smtp_pass'] = $resmtp[0]['password'];
			$config['smtp_port'] = $resmtp[0]['port'];
			$this->email->initialize($config);
			$this->email->set_newline("\r\n");
		}

        $this->email->from($this->input->post('from_email'), $this->input->post('from_name'));
        $this->email->to($to_email);
		if($this->input->post('to_cc')!==null){
          $this->email->cc($this->input->post('to_cc'));
		}
		if($this->input->post('to_bcc')!==null){
          $this->email->bcc($this->input->post('to_bcc'));
		}
        $this->email->subject($this->input->post('subject'));
        $this->email->message($this->input->post('message'));
		
		//$this->email->attach('http://example.com/filename.pdf');

		
        // Send mail
        if ($this->email->send())
            $this->session->set_flashdata("email_sent", "Congragulation Email Send Successfully.");
        else
            $this->session->set_flashdata("email_sent", "You have encountered an error");
        $this->save();
	    $data['_view'] = 'admin/send_email/sent_status';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Save send_email
     *
     * @param $id -
     *            primary key to update
     *            
     */
    function save()
    {
        $params = array(
            'template_id' => html_escape($this->input->post('template_id')),
            'subject' => html_escape($this->input->post('subject')),
            'to_email' => html_escape($this->input->post('to_email')),
            'to_cc' => html_escape($this->input->post('to_cc')),
            'to_bcc' => html_escape($this->input->post('to_bcc')),
            'group_id' => html_escape($this->input->post('group_id')),
            'leads_id' => html_escape($this->input->post('leads_id')),
            'message' => html_escape($this->input->post('message'))
        );

        $this->Send_email_model->add_send_email($params);
    }
    function template($template_id=-1){
		 $result = $this->db->get_where('template', array(
            'id' => $template_id
        ))->row_array();
        if (! (array) $result) {
            $fields = $this->db->list_fields('template');
            foreach ($fields as $field) {
                $result[$field] = '';
            }
        }
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }
        return $result['content'];
	}
    /**
     * Details send_email
     *
     * @param $id -
     *            primary key to get record
     *            
     */
    function details($id)
    {
        $data['send_email'] = $this->Send_email_model->get_send_email($id);
        $data['id'] = $id;
        $data['_view'] = 'admin/send_email/details';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Deleting send_email
     *
     * @param $id -
     *            primary key to delete record
     *            
     */
    function remove($id)
    {
        $send_email = $this->Send_email_model->get_send_email($id);

        // check if the send_email exists before trying to delete it
        if (isset($send_email['id'])) {
            $this->Send_email_model->delete_send_email($id);
            $this->session->set_flashdata('msg', 'Send_email has been deleted successfully');
            redirect('admin/send_email/index');
        } else
            show_error('The send_email you are trying to delete does not exist.');
    }

    /**
     * Search send_email
     *
     * @param $start -
     *            Starting of send_email table's index to get query
     */
    function search($start = 0)
    {
        if (! empty($this->input->post('key'))) {
            $key = $this->input->post('key');
            $_SESSION['key'] = $key;
        } else {
            $key = $_SESSION['key'];
        }

        $limit = 10;
        $this->db->like('id', $key, 'both');
        $this->db->or_like('template_id', $key, 'both');
        $this->db->or_like('subject', $key, 'both');
        $this->db->or_like('to_email', $key, 'both');
        $this->db->or_like('group_leads_id', $key, 'both');
        $this->db->or_like('leads_id', $key, 'both');
        $this->db->or_like('message', $key, 'both');

        $this->db->order_by('id', 'desc');

        $this->db->limit($limit, $start);
        $data['send_email'] = $this->db->get('send_email')->result_array();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }

        // pagination
        $config['base_url'] = site_url('admin/send_email/search');
        $this->db->reset_query();
        $this->db->like('id', $key, 'both');
        $this->db->or_like('template_id', $key, 'both');
        $this->db->or_like('subject', $key, 'both');
        $this->db->or_like('to_email', $key, 'both');
        $this->db->or_like('group_leads_id', $key, 'both');
        $this->db->or_like('leads_id', $key, 'both');
        $this->db->or_like('message', $key, 'both');

        $config['total_rows'] = $this->db->from("send_email")->count_all_results();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }
        $config['per_page'] = 10;
        // Bootstrap 4 Pagination fix
        $config['full_tag_open'] = '<div class="pagging text-center"><nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav></div>';
        $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close'] = '</span></li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close'] = '<span aria-hidden="true"></span></span></li>';
        $config['next_tag_close'] = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close'] = '</span></li>';
        $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['first_tag_close'] = '</span></li>';
        $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['last_tag_close'] = '</span></li>';
        $this->pagination->initialize($config);
        $data['link'] = $this->pagination->create_links();

        $data['key'] = $key;
        $data['_view'] = 'admin/send_email/index';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Export send_email
     *
     * @param $export_type -
     *            CSV or PDF type
     */
    function export($export_type = 'CSV')
    {
        if ($export_type == 'CSV') {
            // file name
            $filename = 'send_email_' . date('Ymd') . '.csv';
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Type: application/csv; ");
            // get data
            $this->db->order_by('id', 'desc');
            $send_emailData = $this->Send_email_model->get_all_send_email();
            // file creation
            $file = fopen('php://output', 'w');
            $header = array(
                "Id",
                "Template Id",
                "Subject",
                "To Email",
                "Group Leads Id",
                "Leads Id",
                "Message"
            );
            fputcsv($file, $header);
            foreach ($send_emailData as $key => $line) {
                fputcsv($file, $line);
            }
            fclose($file);
            exit();
        } else if ($export_type == 'Pdf') {
            $this->db->order_by('id', 'desc');
            $send_email = $this->db->get('send_email')->result_array();
            // get the HTML
            ob_start();
            include (APPPATH . 'views/admin/send_email/print_template.php');
            $html = ob_get_clean();
            include (APPPATH . "third_party/mpdf60/mpdf.php");
            $mpdf = new mPDF('', 'A4');
            // $mpdf=new mPDF('c','A4','','',32,25,27,25,16,13);
            // $mpdf->mirrorMargins = true;
            $mpdf->SetDisplayMode('fullpage');
            // ==============================================================
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1; // Use values in classes/ucdn.php 1 = LATIN
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;
            $mpdf->autoLangToFont = true;
            $mpdf->setAutoBottomMargin = 'stretch';
            $stylesheet = file_get_contents(APPPATH . "third_party/mpdf60/lang2fonts.css");
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html);
            // $mpdf->AddPage();
            $mpdf->Output($filePath);
            $mpdf->Output();
            // $mpdf->Output( $filePath,'S');
            exit();
        }
    }
}
//End of Send_email controller