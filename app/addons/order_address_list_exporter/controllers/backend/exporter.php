<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Registry;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_REQUEST['order_ids'])) {

    // explode order IDs
    $order_ids = explode(',', $_REQUEST['order_ids']);
    foreach ($order_ids as $key => $id) {

        $id = trim($id);
        if (strpos($id, '-') != false) {
            unset($order_ids[$key]); // remove element

            list($start, $end) = explode('-', $id);
            for ($i = $start; $i <= $end; $i++) {
                array_push($order_ids, $i);
            }
        }
    }
    sort($order_ids); // sort ids
    //fn_print_r($order_ids); // test order ids explode

    // get order info
    $orders = array();
    foreach ($order_ids as $order_id) {
        $order = fn_get_order_info($order_id);
        array_push($orders, $order);
    }
    //fn_print_r($orders); // test get order info

    if ($mode == 'address_list') {
        // export address list
        $output = "OrderNo, Name, Address, City, Province, Post, Country, Tel" . PHP_EOL;
        foreach ($orders as $order) {
            $data['order_id'] = $order['order_id'];
            $data['name'] = $order['firstname'] . ' ' . $order['lastname'];
            $data['address'] = $order['s_address'] . ' ' . $order['s_address_2'];
            $data['city'] = $order['s_city'];
            $data['province'] = $order['s_state'];
            $data['country'] = $order['s_country'];
            $data['post'] = $order['s_zipcode'];
            $data['tel'] = $order['phone'];

            $output = $output . implode($data, ',') . PHP_EOL;
        }
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="address-list-'.date('Y_m_d_H_i_s').'.csv"');
        header('Cache-Control: max-age=0');
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Pragma: public'); // HTTP/1.0

        echo $output;
        exit;

    } elseif ($mode == 'orders') {
        // export orders
        foreach ($orders as $order) {
            $data['order_id'] = $order['order_id'];
            $data['name'] = $order['firstname'] . ' ' . $order['lastname'];
            $data['address'] = $order['s_address'] . ' ' . $order['s_address_2'];
            $data['city'] = $order['s_city'];
            $data['province'] = $order['s_state'];
            $data['country'] = $order['s_country'];
            $data['post'] = $order['s_zipcode'];
            $data['tel'] = $order['phone'];
        }
    }

    //return array(CONTROLLER_STATUS_REDIRECT, 'exporter.index'); // redirect to exporter.index
}

if ($mode == 'index') {
    // show form
}

