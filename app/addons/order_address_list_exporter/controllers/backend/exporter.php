<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'address_list') {
    // export address list
    fn_print_r('address list');
} else if ($mode == 'orders') {
    // export orders
    fn_print_r('orders');
} else {
    fn_print_r('Error!');
}
