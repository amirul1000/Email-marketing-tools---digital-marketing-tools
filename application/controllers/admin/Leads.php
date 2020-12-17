<?php

/**
 * Author: Amirul Momenin
 * Desc:Leads Controller
 *
 */
class Leads extends CI_Controller
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
        $this->load->model('Leads_model');
        if (! $this->session->userdata('validated')) {
            redirect('admin/login/index');
        }
    }

    /**
     * Index Page for this controller.
     *
     * @param $start -
     *            Starting of leads table's index to get query
     *            
     */
    function index($start = 0)
    {
        $limit = 10;
        $data['leads'] = $this->Leads_model->get_limit_leads($limit, $start);
        // pagination
        $config['base_url'] = site_url('admin/leads/index');
        $config['total_rows'] = $this->Leads_model->get_count_leads();
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

        $data['_view'] = 'admin/leads/index';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Save leads
     *
     * @param $id -
     *            primary key to update
     *            
     */
    function save($id = - 1)
    {
        $file_picture = "";

        $created_at = "";
        $updated_at = "";

        if ($id <= 0) {
            $created_at = date("Y-m-d H:i:s");
        } else if ($id > 0) {
            $updated_at = date("Y-m-d H:i:s");
        }

        $params = array(
            'first_name' => html_escape($this->input->post('first_name')),
            'last_name' => html_escape($this->input->post('last_name')),
            'company' => html_escape($this->input->post('company')),
            'email' => html_escape($this->input->post('email')),
            'cell_phone' => html_escape($this->input->post('cell_phone')),
            'skype' => html_escape($this->input->post('skype')),
            'address' => html_escape($this->input->post('address')),
            'social_link' => html_escape($this->input->post('social_link')),
            'file_picture' => $file_picture,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        );

        $config['upload_path'] = "./public/uploads/images/leads";
        $config['allowed_types'] = "gif|jpg|png";
        $config['max_size'] = 100;
        $config['max_width'] = 1024;
        $config['max_height'] = 768;
        $this->load->library('upload', $config);

        if (isset($_POST) && count($_POST) > 0) {
            if (strlen($_FILES['file_picture']['name']) > 0 && $_FILES['file_picture']['size'] > 0) {
                if (! $this->upload->do_upload('file_picture')) {
                    $error = array(
                        'error' => $this->upload->display_errors()
                    );
                } else {
                    $file_picture = "uploads/images/leads/" . $_FILES['file_picture']['name'];
                    $params['file_picture'] = $file_picture;
                }
            } else {
                unset($params['file_picture']);
            }
        }

        if ($id > 0) {
            unset($params['created_at']);
        }
        if ($id <= 0) {
            unset($params['updated_at']);
        }
        $data['id'] = $id;
        // update
        if (isset($id) && $id > 0) {
            $data['leads'] = $this->Leads_model->get_leads($id);
            if (isset($_POST) && count($_POST) > 0) {
                $this->Leads_model->update_leads($id, $params);
                $this->session->set_flashdata('msg', 'Leads has been updated successfully');
                redirect('admin/leads/index');
            } else {
                $data['_view'] = 'admin/leads/form';
                $this->load->view('layouts/admin/body', $data);
            }
        } // save
        else {
            if (isset($_POST) && count($_POST) > 0) {
                $leads_id = $this->Leads_model->add_leads($params);
                $this->session->set_flashdata('msg', 'Leads has been saved successfully');
                redirect('admin/leads/index');
            } else {
                $data['leads'] = $this->Leads_model->get_leads(0);
                $data['_view'] = 'admin/leads/form';
                $this->load->view('layouts/admin/body', $data);
            }
        }
    }

    /**
     * Details leads
     *
     * @param $id -
     *            primary key to get record
     *            
     */
    function details($id)
    {
        $data['leads'] = $this->Leads_model->get_leads($id);
        $data['id'] = $id;
        $data['_view'] = 'admin/leads/details';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Deleting leads
     *
     * @param $id -
     *            primary key to delete record
     *            
     */
    function remove($id)
    {
        $leads = $this->Leads_model->get_leads($id);

        // check if the leads exists before trying to delete it
        if (isset($leads['id'])) {
            $this->Leads_model->delete_leads($id);
            $this->session->set_flashdata('msg', 'Leads has been deleted successfully');
            redirect('admin/leads/index');
        } else
            show_error('The leads you are trying to delete does not exist.');
    }

    /**
     * Search leads
     *
     * @param $start -
     *            Starting of leads table's index to get query
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
        $this->db->or_like('first_name', $key, 'both');
        $this->db->or_like('last_name', $key, 'both');
        $this->db->or_like('company', $key, 'both');
        $this->db->or_like('email', $key, 'both');
        $this->db->or_like('cell_phone', $key, 'both');
        $this->db->or_like('skype', $key, 'both');
        $this->db->or_like('address', $key, 'both');
        $this->db->or_like('social_link', $key, 'both');
        $this->db->or_like('file_picture', $key, 'both');
        $this->db->or_like('created_at', $key, 'both');
        $this->db->or_like('updated_at', $key, 'both');

        $this->db->order_by('id', 'desc');

        $this->db->limit($limit, $start);
        $data['leads'] = $this->db->get('leads')->result_array();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }

        // pagination
        $config['base_url'] = site_url('admin/leads/search');
        $this->db->reset_query();
        $this->db->like('id', $key, 'both');
        $this->db->or_like('first_name', $key, 'both');
        $this->db->or_like('last_name', $key, 'both');
        $this->db->or_like('company', $key, 'both');
        $this->db->or_like('email', $key, 'both');
        $this->db->or_like('cell_phone', $key, 'both');
        $this->db->or_like('skype', $key, 'both');
        $this->db->or_like('address', $key, 'both');
        $this->db->or_like('social_link', $key, 'both');
        $this->db->or_like('file_picture', $key, 'both');
        $this->db->or_like('created_at', $key, 'both');
        $this->db->or_like('updated_at', $key, 'both');

        $config['total_rows'] = $this->db->from("leads")->count_all_results();
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
        $data['_view'] = 'admin/leads/index';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Export leads
     *
     * @param $export_type -
     *            CSV or PDF type
     */
    function export($export_type = 'CSV')
    {
        if ($export_type == 'CSV') {
            // file name
            $filename = 'leads_' . date('Ymd') . '.csv';
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Type: application/csv; ");
            // get data
            $this->db->order_by('id', 'desc');
            $leadsData = $this->Leads_model->get_all_leads();
            // file creation
            $file = fopen('php://output', 'w');
            $header = array(
                "Id",
                "First Name",
                "Last Name",
                "Company",
                "Email",
                "Cell Phone",
                "Skype",
                "Address",
                "Social Link",
                "File Picture",
                "Created At",
                "Updated At"
            );
            fputcsv($file, $header);
            foreach ($leadsData as $key => $line) {
                fputcsv($file, $line);
            }
            fclose($file);
            exit();
        } else if ($export_type == 'Pdf') {
            $this->db->order_by('id', 'desc');
            $leads = $this->db->get('leads')->result_array();
            // get the HTML
            ob_start();
            include (APPPATH . 'views/admin/leads/print_template.php');
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
//End of Leads controller