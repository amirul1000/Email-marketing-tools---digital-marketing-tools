<?php

/**
 * Author: Amirul Momenin
 * Desc:Contentmeta Model
 */
class Contentmeta_model extends CI_Model
{

    protected $contentmeta = 'contentmeta';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get contentmeta by id
     *
     * @param $id -
     *            primary key to get record
     *            
     */
    function get_contentmeta($id)
    {
        $result = $this->db->get_where('contentmeta', array(
            'id' => $id
        ))->row_array();
        if (! (array) $result) {
            $fields = $this->db->list_fields('contentmeta');
            foreach ($fields as $field) {
                $result[$field] = '';
            }
        }
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }
        return $result;
    }

    /**
     * Get all contentmeta
     */
    function get_all_contentmeta()
    {
        $this->db->order_by('id', 'desc');
        $result = $this->db->get('contentmeta')->result_array();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }
        return $result;
    }

    /**
     * Get limit contentmeta
     *
     * @param $limit -
     *            limit of query , $start - start of db table index to get query
     *            
     */
    function get_limit_contentmeta($limit, $start)
    {
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $start);
        $result = $this->db->get('contentmeta')->result_array();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }
        return $result;
    }

    /**
     * Count contentmeta rows
     */
    function get_count_contentmeta()
    {
        $result = $this->db->from("contentmeta")->count_all_results();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }
        return $result;
    }

    /**
     * Get all users-contentmeta
     */
    function get_all_users_contentmeta()
    {
        $this->db->order_by('id', 'desc');
        $this->db->where('users_id', $this->session->userdata('id'));
        $result = $this->db->get('contentmeta')->result_array();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }
        return $result;
    }

    /**
     * Get limit users-contentmeta
     *
     * @param $limit -
     *            limit of query , $start - start of db table index to get query
     *            
     */
    function get_limit_users_contentmeta($limit, $start)
    {
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $start);
        $this->db->where('users_id', $this->session->userdata('id'));
        $result = $this->db->get('contentmeta')->result_array();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }
        return $result;
    }

    /**
     * Count users-contentmeta rows
     */
    function get_count_users_contentmeta()
    {
        $this->db->where('users_id', $this->session->userdata('id'));
        $result = $this->db->from("contentmeta")->count_all_results();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }
        return $result;
    }

    /**
     * function to add new contentmeta
     *
     * @param $params -
     *            data set to add record
     *            
     */
    function add_contentmeta($params)
    {
        $this->db->insert('contentmeta', $params);
        $id = $this->db->insert_id();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }
        return $id;
    }

    /**
     * function to update contentmeta
     *
     * @param $id -
     *            primary key to update record,$params - data set to add record
     *            
     */
    function update_contentmeta($id, $params)
    {
        $this->db->where('id', $id);
        $status = $this->db->update('contentmeta', $params);
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }
        return $status;
    }

    /**
     * function to delete contentmeta
     *
     * @param $id -
     *            primary key to delete record
     *            
     */
    function delete_contentmeta($id)
    {
        $status = $this->db->delete('contentmeta', array(
            'id' => $id
        ));
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }
        return $status;
    }
}
