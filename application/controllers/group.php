<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class group extends ApplicationBase {

    function __construct() {
        parent::__construct();
    }

    function psb() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "group/psb.html");
        // output
        parent::display();
    }

    function kepengurusan() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "group/kepengurusan.html");
        // output
        parent::display();
    }

    function akademik() {
        
    }

    function regional() {
        
    }

}
