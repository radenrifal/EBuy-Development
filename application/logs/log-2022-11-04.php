<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-11-04 18:30:53 --> Query error: FUNCTION freshids_dbs.GetLineActive does not exist - Invalid query: SELECT SQL_CALC_FOUND_ROWS 
                        IFNULL(COUNT(*), 0) AS total_trx,
                        IFNULL( SUM(G.`omzet`), 0 ) AS total_omzet,
                        IFNULL( SUM(G.`bv`), 0 ) AS total_bv,
                        IFNULL( SUM(G.`amount`), 0 ) AS total_amount,
                        IFNULL( SUM(G.`qty`), 0 ) AS total_qty,
                        IFNULL( SUM(G.`point`), 0 ) AS total_point,
                        GetLineActive(G.id_member, 150, DATE_FORMAT(G.date, "%Y-%m")) AS active_rank
                    FROM ddm_member_omzet AS G
                    INNER JOIN ddm_member AS M ON (M.id = G.id_member)
                    WHERE G.id > 0  AND ( sponsor = 3 OR id_member = 3 ) 
ERROR - 2022-11-04 18:31:38 --> Severity: Notice --> Undefined variable: in_cart D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\views\shop\pages\product\detail.php 17
ERROR - 2022-11-04 18:31:50 --> Severity: Notice --> Undefined variable: in_cart D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\views\shop\pages\product\detail.php 17
ERROR - 2022-11-04 18:32:17 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\views\shop\pages\product\detail.php 6
ERROR - 2022-11-04 18:32:17 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\views\shop\pages\product\detail.php 7
ERROR - 2022-11-04 18:32:17 --> Severity: Notice --> Undefined variable: in_cart D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\views\shop\pages\product\detail.php 17
ERROR - 2022-11-04 18:32:17 --> Severity: Notice --> Undefined property: stdClass::$price_customer D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\views\shop\pages\product\detail.php 55
ERROR - 2022-11-04 18:32:17 --> Severity: Notice --> Undefined variable: in_cart D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\views\shop\pages\product\detail.php 69
ERROR - 2022-11-04 18:32:17 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\views\shop\components\products.php 6
ERROR - 2022-11-04 18:32:17 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\views\shop\components\products.php 7
ERROR - 2022-11-04 18:32:17 --> Severity: Notice --> Undefined property: stdClass::$price_customer D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\views\shop\components\products.php 29
ERROR - 2022-11-04 18:32:17 --> Severity: Notice --> Undefined property: stdClass::$price_customer D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\views\shop\components\products.php 29
ERROR - 2022-11-04 18:32:17 --> Severity: Notice --> Undefined property: stdClass::$price_customer D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\views\shop\components\products.php 29
ERROR - 2022-11-04 18:41:51 --> Severity: Notice --> Undefined variable: in_cart D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\views\shop\pages\product\detail.php 17
ERROR - 2022-11-04 18:51:52 --> Severity: Notice --> Undefined variable: in_cart D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\views\shop\pages\product\detail.php 17
ERROR - 2022-11-04 19:01:52 --> Severity: Notice --> Undefined variable: in_cart D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\views\shop\pages\product\detail.php 17
