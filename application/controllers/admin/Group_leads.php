<?php

/**
 * Author: Amirul Momenin
 * Desc:Group_leads Controller
 *
 */
class Group_leads extends CI_Controller
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
        $this->load->model('Group_leads_model');
        if (! $this->session->userdata('validated')) {
            redirect('admin/login/index');
        }
    }

    /**
     * Index Page for this controller.
     *
     * @param $start -
     *            Starting of group_leads table's index to get query
     *            
     */
    function index($start = 0)
    {
        $limit = 10;
        $data['group_leads'] = $this->Group_leads_model->get_limit_group_leads($limit, $start);
        // pagination
        $config['base_url'] = site_url('admin/group_leads/index');
        $config['total_rows'] = $this->Group_leads_model->get_count_group_leads();
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

        $data['_view'] = 'admin/group_leads/index';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Save group_leads
     *
     * @param $id -
     *            primary key to update
     *            
     */
    function save($id = - 1)
    {
        $created_at = "";
        $updated_at = "";

        if ($id <= 0) {
            $created_at = date("Y-m-d H:i:s");
        } else if ($id > 0) {
            $updated_at = date("Y-m-d H:i:s");
        }

        $params = array(
            'group_id' => html_escape($this->input->post('group_id')),
            'leads_id' => html_escape($this->input->post('leads_id')),
            'created_at' => $created_at,
            'updated_at' => $updated_at
        );

        if ($id > 0) {
            unset($params['created_at']);
        }
        if ($id <= 0) {
            unset($params['updated_at']);
        }
        $data['id'] = $id;
        // update
        if (isset($id) && $id > 0) {
            $data['group_leads'] = $this->Group_leads_model->get_group_leads($id);
            if (isset($_POST) && count($_POST) > 0) {
                $this->Group_leads_model->update_group_leads($id, $params);
                $this->session->set_flashdata('msg', 'Group_leads has been updated successfully');
                redirect('admin/group_leads/index');
            } else {
                $data['_view'] = 'admin/group_leads/form';
                $this->load->view('layouts/admin/body', $data);
            }
        } // save
        else {
            if (isset($_POST) && count($_POST) > 0) {
                $group_leads_id = $this->Group_leads_model->add_group_leads($params);
                $this->session->set_flashdata('msg', 'Group_leads has been saved successfully');
                redirect('admin/group_leads/index');
            } else {
                $data['group_leads'] = $this->Group_leads_model->get_group_leads(0);
                $data['_view'] = 'admin/group_leads/form';
                $this->load->view('layouts/admin/body', $data);
            }
        }
    }

    /**
     * Details group_leads
     *
     * @param $id -
     *            primary key to get record
     *            
     */
    function details($id)
    {
        $data['group_leads'] = $this->Group_leads_model->get_group_leads($id);
        $data['id'] = $id;
        $data['_view'] = 'admin/group_leads/details';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Deleting group_leads
     *
     * @param $id -
     *            primary key to delete record
     *            
     */
    function remove($id)
    {
        $group_leads = $this->Group_leads_model->get_group_leads($id);

        // check if the group_leads exists before trying to delete it
        if (isset($group_leads['id'])) {
            $this->Group_leads_model->delete_group_leads($id);
            $this->session->set_flashdata('msg', 'Group_leads has been deleted successfully');
            redirect('admin/group_leads/index');
        } else
            show_error('The group_leads you are trying to delete does not exist.');
    }

    /**
     * Search group_leads
     *
     * @param $start -
     *            Starting of group_leads table's index to get query
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
        $this->db->or_like('group_id', $key, 'both');
        $this->db->or_like('leads_id', $key, 'both');
        $this->db->or_like('created_at', $key, 'both');
        $this->db->or_like('updated_at', $key, 'both');

        $this->db->order_by('id', 'desc');

        $this->db->limit($limit, $start);
        $data['group_leads'] = $this->db->get('group_leads')->result_array();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }

        // pagination
        $config['base_url'] = site_url('admin/group_leads/search');
        $this->db->reset_query();
        $this->db->like('id', $key, 'both');
        $this->db->or_like('group_id', $key, 'both');
        $this->db->or_like('leads_id', $key, 'both');
        $this->db->or_like('created_at', $key, 'both');
        $this->db->or_like('updated_at', $key, 'both');

        $config['total_rows'] = $this->db->from("group_leads")->count_all_results();
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
        $data['_view'] = 'admin/group_leads/index';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Export group_leads
     *
     * @param $export_type -
     *            CSV or PDF type
     */
    function export($export_type = 'CSV')
    {
        if ($export_type == 'CSV') {
            // file name
            $filename = 'group_leads_' . date('Ymd') . '.csv';
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Type: application/csv; ");
            // get data
            $this->db->order_by('id', 'desc');
            $group_leadsData = $this->Group_leads_model->get_all_group_leads();
            // file creation
            $file = fopen('php://output', 'w');
            $header = array(
                "Id",
                "Group Id",
                "Leads Id",
                "Created At",
                "Updated At"
            );
            fputcsv($file, $header);
            foreach ($group_leadsData as $key => $line) {
                fputcsv($file, $line);
            }
            fclose($file);
            exit();
        } else if ($export_type == 'Pdf') {
            $this->db->order_by('id', 'desc');
            $group_leads = $this->db->get('group_leads')->result_array();
            // get the HTML
            ob_start();
            include (APPPATH . 'views/admin/group_leads/print_template.php');
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
//End of Group_leads controller