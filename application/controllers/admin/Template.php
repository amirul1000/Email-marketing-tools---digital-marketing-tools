<?php

/**
 * Author: Amirul Momenin
 * Desc:Template Controller
 *
 */
class Template extends CI_Controller
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
        $this->load->model('Template_model');
        if (! $this->session->userdata('validated')) {
            redirect('admin/login/index');
        }
    }

    /**
     * Index Page for this controller.
     *
     * @param $start -
     *            Starting of template table's index to get query
     *            
     */
    function index($start = 0)
    {
        $limit = 10;
        $data['template'] = $this->Template_model->get_limit_template($limit, $start);
        // pagination
        $config['base_url'] = site_url('admin/template/index');
        $config['total_rows'] = $this->Template_model->get_count_template();
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

        $data['_view'] = 'admin/template/index';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Save template
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
            'template_name' => html_escape($this->input->post('template_name')),
            'content' => $this->input->post('content'),
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
            $data['template'] = $this->Template_model->get_template($id);
            if (isset($_POST) && count($_POST) > 0) {
                $this->Template_model->update_template($id, $params);
                $this->session->set_flashdata('msg', 'Template has been updated successfully');
                redirect('admin/template/index');
            } else {
                $data['_view'] = 'admin/template/form';
                $this->load->view('layouts/admin/body', $data);
            }
        } // save
        else {
            if (isset($_POST) && count($_POST) > 0) {
                $template_id = $this->Template_model->add_template($params);
                $this->session->set_flashdata('msg', 'Template has been saved successfully');
                redirect('admin/template/index');
            } else {
                $data['template'] = $this->Template_model->get_template(0);
                $data['_view'] = 'admin/template/form';
                $this->load->view('layouts/admin/body', $data);
            }
        }
    }

    /**
     * Details template
     *
     * @param $id -
     *            primary key to get record
     *            
     */
    function details($id)
    {
        $data['template'] = $this->Template_model->get_template($id);
        $data['id'] = $id;
        $data['_view'] = 'admin/template/details';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Deleting template
     *
     * @param $id -
     *            primary key to delete record
     *            
     */
    function remove($id)
    {
        $template = $this->Template_model->get_template($id);

        // check if the template exists before trying to delete it
        if (isset($template['id'])) {
            $this->Template_model->delete_template($id);
            $this->session->set_flashdata('msg', 'Template has been deleted successfully');
            redirect('admin/template/index');
        } else
            show_error('The template you are trying to delete does not exist.');
    }

    /**
     * ckeditor upload picture
     */
    function upload_picture()
    {
        $this->load->model('Content_model');
        $this->load->model('Contentmeta_model');

        $params = array(
            'content_users_id' => $this->session->userdata('id'),
            'content_title' => 'uploading',
            'description' => 'ckeditor',
            'created_at' => date("Y-m-d H:i:s")
        );
        $content_id = $this->Content_model->add_content($params);

        $config['upload_path'] = "./public/uploads/ckeditor/";
        $config['allowed_types'] = "gif|jpg|png|pdf|text|doc|docx";
        /*
         * $config['max_size'] = 100;
         * $config['max_width'] = 1024;
         * $config['max_height'] = 768;
         */
        $this->load->library('upload', $config);

        if (isset($_POST) && count($_POST) > 0) {
            if (strlen($_FILES['upload']['name']) > 0 && $_FILES['upload']['size'] > 0) {
                if (! $this->upload->do_upload('upload')) {
                    $error = array(
                        'error' => $this->upload->display_errors()
                    );
                } else {
                    $upload = "uploads/ckeditor/" . $_FILES['upload']['name'];
                    $params['upload'] = $upload;
                }
            }
        }

        $params = array(
            'content_id' => $content_id,
            'meta_key' => $_GET['CKEditorFuncNum'],
            'meta_value' => $upload,
            'created_at' => date("Y-m-d H:i:s")
        );
        $contentmeta_id = $this->Contentmeta_model->add_contentmeta($params);

        $function_number = $_GET['CKEditorFuncNum'];
        $url = base_url("public/" . $upload);
        $message = '';
        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($function_number, '$url', '$message');</script>";
    }

    /**
     * Search template
     *
     * @param $start -
     *            Starting of template table's index to get query
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
        $this->db->or_like('template_name', $key, 'both');
        $this->db->or_like('content', $key, 'both');
        $this->db->or_like('created_at', $key, 'both');
        $this->db->or_like('updated_at', $key, 'both');

        $this->db->order_by('id', 'desc');

        $this->db->limit($limit, $start);
        $data['template'] = $this->db->get('template')->result_array();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }

        // pagination
        $config['base_url'] = site_url('admin/template/search');
        $this->db->reset_query();
        $this->db->like('id', $key, 'both');
        $this->db->or_like('template_name', $key, 'both');
        $this->db->or_like('content', $key, 'both');
        $this->db->or_like('created_at', $key, 'both');
        $this->db->or_like('updated_at', $key, 'both');

        $config['total_rows'] = $this->db->from("template")->count_all_results();
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
        $data['_view'] = 'admin/template/index';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Export template
     *
     * @param $export_type -
     *            CSV or PDF type
     */
    function export($export_type = 'CSV')
    {
        if ($export_type == 'CSV') {
            // file name
            $filename = 'template_' . date('Ymd') . '.csv';
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Type: application/csv; ");
            // get data
            $this->db->order_by('id', 'desc');
            $templateData = $this->Template_model->get_all_template();
            // file creation
            $file = fopen('php://output', 'w');
            $header = array(
                "Id",
                "Template Name",
                "Content",
                "Created At",
                "Updated At"
            );
            fputcsv($file, $header);
            foreach ($templateData as $key => $line) {
                fputcsv($file, $line);
            }
            fclose($file);
            exit();
        } else if ($export_type == 'Pdf') {
            $this->db->order_by('id', 'desc');
            $template = $this->db->get('template')->result_array();
            // get the HTML
            ob_start();
            include (APPPATH . 'views/admin/template/print_template.php');
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
//End of Template controller