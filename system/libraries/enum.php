<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class enum {

    function get_enum_values( $table, $field ){
        $ci =& get_instance();
        $type = $ci->db->query( "SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'" )->row( 0 )->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
    }
}
