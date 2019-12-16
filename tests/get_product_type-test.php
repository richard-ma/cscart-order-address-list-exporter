<?php

function get_product_type($name) {
    $name = strtolower($name); // change $name to lowercase
    $keyword = array(
        "man" => array(
            "/\\sman('s)?(\\s)?/i", 
            "/^man('s)?(\\s)?/i", 
            "/\\smen('s)?(\\s)?/i", 
            "/^men('s)?(\\s)?/i"),
        "woman" => array(
            "/\\swoman('s)?(\\s)?/i", 
            "/^woman('s)?(\\s)?/i", 
            "/\\swomen('s)?(\\s)?/i", 
            "/^women('s)?(\\s)?/i", 
            "/\\slady(\\s)?/i", 
            "/^lady(\\s)?/i", 
            "/^ladies(\\s)?/i"),
        "kid" => array(
            "/\\skid('s|s)?(\\s)?/i", 
            "/^kid('s|s)?(\\s)?/i", 
            "/\\syouth(\\s)?/i", 
            "/^youth(\\s)?/i", 
            "/\\spreschool(\\s)?/i", 
            "/^preschool(\\s)?/i", 
            "/\\snewborn(\\s)?/i",
            "/^newborn(\\s)?/i"),
    );
    //print_r($keyword);
    $flg = Null;
    foreach ($keyword as $key => $words) {
        foreach ($words as $word) {
            if (preg_match($word, $name) > 0) { // match!
                $flg = $key;
                break;
            }
        }
    }
    if ($flg == "man") {
        return "男装";
    } else if ($flg == "woman") {
        return "女装";
    } else if ($flg == "kid") {
        return "童装";
    } else {
        return "未识别";
    }
}

function test() {
    echo "TEST START!\n";
    $tests = array(
        " man " => '男装',
        " man's " => '男装',
        "man " => '男装',
        "man's " => '男装',
        " hellomanhello " => '未识别',
        " men " => '男装',
        " men's " => '男装',
        "men " => '男装',
        "men's " => '男装',
        " hellomenhello " => '未识别',
        " woman " => '女装',
        " woman's " => '女装',
        "woman " => '女装',
        "woman's " => '女装',
        " hellowomanhello " => '未识别',
        " women " => '女装',
        " women's " => '女装',
        "women " => '女装',
        "women's " => '女装',
        " hellowomenhello " => '未识别',
        " kid " => '童装',
        " kid's " => '童装',
        " kids " => '童装',
        "kid " => '童装',
        "kid's " => '童装',
        "kids " => '童装',
        " hellokidhello " => '未识别',
        " youth " => '童装',
        "youth " => '童装',
        " hellowyouthhello " => '未识别',
        " preschool" => '童装',
        "preschool " => '童装',
        " hellopreschoolhello " => '未识别',
        " newborn" => '童装',
        "newborn " => '童装',
        " hellonewbornhello " => '未识别',
    );

    foreach ($tests as $test => $result) {
        if ($result != get_product_type($test)) {
            print "\n";
            print '['.$test.' => '.$result.'] ';
            print get_product_type($test);
        } else {
            print '.';
        }
    }
    print "\n";
    echo "TEST END!\n";
}

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 1);

test();
