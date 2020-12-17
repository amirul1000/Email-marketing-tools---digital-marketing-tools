<?php

/**
 * Author: Amirul Momenin
 * Desc:Contentmeta Controller
 *
 */
class Contentmeta extends CI_Controller
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
        $this->load->model('Contentmeta_model');
        if (! $this->session->userdata('validated')) {
            redirect('admin/login/index');
        }
    }

    /**
     * Index Page for this controller.
     *
     * @param $start -
     *            Starting of contentmeta table's index to get query
     *            
     */
    function index($start = 0)
    {
        $limit = 10;
        $data['contentmeta'] = $this->Contentmeta_model->get_limit_contentmeta($limit, $start);
        // pagination
        $config['base_url'] = site_url('admin/contentmeta/index');
        $config['total_rows'] = $this->Contentmeta_model->get_count_contentmeta();
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

        $data['_view'] = 'admin/contentmeta/index';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Save contentmeta
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
            'content_id' => html_escape($this->input->post('content_id')),
            'meta_key' => html_escape($this->input->post('meta_key')),
            'meta_value' => html_escape($this->input->post('meta_value')),
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
            $data['contentmeta'] = $this->Contentmeta_model->get_contentmeta($id);
            if (isset($_POST) && count($_POST) > 0) {
                $this->Contentmeta_model->update_contentmeta($id, $params);
                $this->session->set_flashdata('msg', 'Contentmeta has been updated successfully');
                redirect('admin/contentmeta/index');
            } else {
                $data['_view'] = 'admin/contentmeta/form';
                $this->load->view('layouts/admin/body', $data);
            }
        } // save
        else {
            if (isset($_POST) && count($_POST) > 0) {
                $contentmeta_id = $this->Contentmeta_model->add_contentmeta($params);
                $this->session->set_flashdata('msg', 'Contentmeta has been saved successfully');
                redirect('admin/contentmeta/index');
            } else {
                $data['contentmeta'] = $this->Contentmeta_model->get_contentmeta(0);
                $data['_view'] = 'admin/contentmeta/form';
                $this->load->view('layouts/admin/body', $data);
            }
        }
    }

    /**
     * Details contentmeta
     *
     * @param $id -
     *            primary key to get record
     *            
     */
    function details($id)
    {
        $data['contentmeta'] = $this->Contentmeta_model->get_contentmeta($id);
        $data['id'] = $id;
        $data['_view'] = 'admin/contentmeta/details';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Deleting contentmeta
     *
     * @param $id -
     *            primary key to delete record
     *            
     */
    function remove($id)
    {
        $contentmeta = $this->Contentmeta_model->get_contentmeta($id);

        // check if the contentmeta exists before trying to delete it
        if (isset($contentmeta['id'])) {
            $this->Contentmeta_model->delete_contentmeta($id);
            $this->session->set_flashdata('msg', 'Contentmeta has been deleted successfully');
            redirect('admin/contentmeta/index');
        } else
            show_error('The contentmeta you are trying to delete does not exist.');
    }

    /**
     * Search contentmeta
     *
     * @param $start -
     *            Starting of contentmeta table's index to get query
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
        $this->db->or_like('content_id', $key, 'both');
        $this->db->or_like('meta_key', $key, 'both');
        $this->db->or_like('meta_value', $key, 'both');
        $this->db->or_like('created_at', $key, 'both');
        $this->db->or_like('updated_at', $key, 'both');

        $this->db->order_by('id', 'desc');

        $this->db->limit($limit, $start);
        $data['contentmeta'] = $this->db->get('contentmeta')->result_array();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }

        // pagination
        $config['base_url'] = site_url('admin/contentmeta/search');
        $this->db->reset_query();
        $this->db->like('id', $key, 'both');
        $this->db->or_like('content_id', $key, 'both');
        $this->db->or_like('meta_key', $key, 'both');
        $this->db->or_like('meta_value', $key, 'both');
        $this->db->or_like('created_at', $key, 'both');
        $this->db->or_like('updated_at', $key, 'both');

        $config['total_rows'] = $this->db->from("contentmeta")->count_all_results();
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
        $data['_view'] = 'admin/contentmeta/index';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Export contentmeta
     *
     * @param $export_type -
     *            CSV or PDF type
     */
    function export($export_type = 'CSV')
    {
        if ($export_type == 'CSV') {
            // file name
            $filename = 'contentmeta_' . date('Ymd') . '.csv';
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Type: application/csv; ");
            // get data
            $this->db->order_by('id', 'desc');
            $contentmetaData = $this->Contentmeta_model->get_all_contentmeta();
            // file creation
            $file = fopen('php://output', 'w');
            $header = array(
                "Id",
                "Content Id",
                "Meta Key",
                "Meta Value",
                "Created At",
                "Updated At"
            );
            fputcsv($file, $header);
            foreach ($contentmetaData as $key => $line) {
                fputcsv($file, $line);
            }
            fclose($file);
            exit();
        } else if ($export_type == 'Pdf') {
            $this->db->order_by('id', 'desc');
            $contentmeta = $this->db->get('contentmeta')->result_array();
            // get the HTML
            ob_start();
            include (APPPATH . 'views/admin/contentmeta/print_template.php');
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
//End of Contentmeta controller