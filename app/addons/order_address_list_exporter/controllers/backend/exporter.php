<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Registry;

if ($mode == 'index') {
    // show form
    //fn_print_r(Registry::get('view')->assign('test', 'test'));
}

if ($mode == 'address_list') {
    // export address list
    //fn_print_r(fn_get_order_info('97'));
    fn_print_r($_POST);
}

if ($mode == 'orders') {
    // export orders
    fn_print_r($_POST);
}
