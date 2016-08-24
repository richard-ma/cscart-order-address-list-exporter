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
            $data['name'] = $order['s_firstname'] . ' ' . $order['s_lastname'];
            $data['address'] = $order['s_address'] . ' ' . $order['s_address_2'];
            $data['city'] = $order['s_city'];
            $data['province'] = $order['s_state'];
            $data['country'] = $order['s_country'];
            $data['post'] = $order['s_zipcode'];
            $data['tel'] = $order['s_phone'];

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
        require_once Registry::get('config.dir.lib') . "vendor/PHPExcel/Classes/PHPExcel.php";

        //Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
                    
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Richard Ma")
                ->setLastModifiedBy("Richard Ma")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
                                                                                                                                                                                                                                                                       
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

        // Add some data
        $delta = 6;
        $start = 1;

        foreach ($orders as $order) {
            $data['order_id'] = $order['order_id'];
            $data['name'] = $order['s_firstname'] . ' ' . $order['s_lastname'];
            $data['address'] = $order['s_address'] . ' ' . $order['s_address_2'];
            $data['city'] = $order['s_city'];
            $data['province'] = $order['s_state'];
            $data['country'] = $order['s_country'];
            $data['post'] = $order['s_zipcode'];
            $data['tel'] = $order['s_phone'];

            //fn_print_r($order);
            foreach($order['products'] as $product) {
                //fn_print_r($product);

                $product_id = $product['product_id'];
                $icon = fn_get_cart_product_icon($product_id);
                $image = $icon['detailed']['image_path'];
                //fn_print_r($image); // print product image path
                $size = 'N/A';
                if (!empty($product['product_options'])) {
                    foreach ($product['product_options'] as $po) {
                        $size = ($po['option_name'] == 'Size') ? $po['variant_name'] : 'N/A';
                    }
                }

        		$end = $start + $delta - 1;
                $objPHPExcel->getActiveSheet()
                        ->mergeCells('A'.$start.':A'.$end.'')
                        ->setCellValue('A'.$start.'', $data['order_id'])
    
                        ->setCellValue('B'.$start.'', 'size: ' . $size)
                        ->mergeCells('B'.(string)($start+1).':B'.$end.'')

                        ->setCellValue('C'.$start.'', $data['name'])
                        ->setCellValue('C'.(string)($start + 1).'', $order['s_address'])
                        ->setCellValue('C'.(string)($start + 2).'', $order['s_address_2'])
                        ->setCellValue('C'.(string)($start + 3).'', $data['city']. ', ' .$data['province']. ' ' .$data['post'])
                        ->setCellValue('C'.(string)($start + 4).'', $data['country'])
                        ->setCellValue('C'.(string)($start + 5).'', $data['tel'])

                        ->setCellValue('D'.$start.'', $product['product'])
                        ->setCellValue('D'.(string)($start + 1).'', 'Qty: '.$product['amount'])
                        ->setCellValue('D'.(string)($start + 2).'', 'SKU: '.$product['product_code'])

                        ->getStyle('C'.(string)($start + 5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                // add picture
                $imageUrl = strtok($image, '?'); // only get url without params
                $imagePath = str_replace(pathinfo(fn_url())['dirname'], Registry::get('config.dir.root'), $imageUrl);
                //fn_print_r($imagePath); // test image path
                
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($imagePath);
                $objDrawing->setCoordinates('B'.(string)($start+1));
                $objDrawing->setHeight(80);
                $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

                $start = $start + $delta;
            }
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Orders');
        
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="orders-'.date('Y_m_d_H_i_s').'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        exit;
    }

    //return array(CONTROLLER_STATUS_REDIRECT, 'exporter.index'); // redirect to exporter.index
}

if ($mode == 'index') {
    // show form
}

