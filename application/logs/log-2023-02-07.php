<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2023-02-07 11:55:48 --> Severity: Warning --> Missing argument 2 for DDM_XLS::setHeader(), called in D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\libraries\DDM_XLS.php on line 263 and defined D:\Xampp\htdocs\freshindonesiasehatdev\ddmapp\libraries\DDM_XLS.php 85
ERROR - 2023-02-07 11:57:18 --> Query error: FUNCTION freshids_dbs.GetLineActive does not exist - Invalid query: SELECT SQL_CALC_FOUND_ROWS 
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
ERROR - 2023-02-07 11:58:32 --> Query error: FUNCTION freshids_dbs.GetLineActive does not exist - Invalid query: SELECT SQL_CALC_FOUND_ROWS 
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
ERROR - 2023-02-07 11:58:45 --> Query error: FUNCTION freshids_dbs.GetLineActive does not exist - Invalid query: SELECT SQL_CALC_FOUND_ROWS 
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
ERROR - 2023-02-07 11:58:52 --> Query error: FUNCTION freshids_dbs.GetLineActive does not exist - Invalid query: SELECT SQL_CALC_FOUND_ROWS 
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
ERROR - 2023-02-07 13:42:17 --> Severity: Error --> Call to undefined method Shop::sethtmlcustomerlistproduct() D:\Xampp\htdocs\ebuyshopdev\application\controllers\Shop.php 309
ERROR - 2023-02-07 13:44:08 --> Severity: Error --> Call to undefined method Shop::sethtmlcustomerlistproduct() D:\Xampp\htdocs\ebuyshopdev\application\controllers\Shop.php 310
ERROR - 2023-02-07 13:44:35 --> Severity: Error --> Call to undefined method Shop::sethtmlcustomerlistproduct() D:\Xampp\htdocs\ebuyshopdev\application\controllers\Shop.php 312
ERROR - 2023-02-07 13:45:59 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\controllers\Shop.php 346
ERROR - 2023-02-07 13:45:59 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\controllers\Shop.php 347
ERROR - 2023-02-07 13:45:59 --> Severity: Notice --> Undefined property: stdClass::$price_agent D:\Xampp\htdocs\ebuyshopdev\application\controllers\Shop.php 369
ERROR - 2023-02-07 13:50:12 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\controllers\Shop.php 617
ERROR - 2023-02-07 13:50:12 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\controllers\Shop.php 618
ERROR - 2023-02-07 13:50:17 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\controllers\Shop.php 617
ERROR - 2023-02-07 13:50:17 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\controllers\Shop.php 618
ERROR - 2023-02-07 13:50:24 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\controllers\Shop.php 617
ERROR - 2023-02-07 13:50:24 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\controllers\Shop.php 618
ERROR - 2023-02-07 13:55:11 --> Severity: Notice --> Undefined variable: cfg_minorder D:\Xampp\htdocs\ebuyshopdev\application\views\shop\pages\shop\cart_agent.php 64
ERROR - 2023-02-07 13:55:11 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Xampp\htdocs\ebuyshopdev\application\views\shop\pages\shop\cart_agent.php 148
ERROR - 2023-02-07 13:55:11 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\helpers\shop_helper.php 402
ERROR - 2023-02-07 13:55:11 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\helpers\shop_helper.php 403
ERROR - 2023-02-07 13:55:11 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\helpers\shop_helper.php 402
ERROR - 2023-02-07 13:55:11 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\helpers\shop_helper.php 403
ERROR - 2023-02-07 13:57:01 --> Severity: Notice --> Undefined variable: cfg_minorder D:\Xampp\htdocs\ebuyshopdev\application\views\shop\pages\shop\cart_agent.php 64
ERROR - 2023-02-07 13:57:01 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Xampp\htdocs\ebuyshopdev\application\views\shop\pages\shop\cart_agent.php 148
ERROR - 2023-02-07 13:57:01 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\helpers\shop_helper.php 402
ERROR - 2023-02-07 13:57:01 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\helpers\shop_helper.php 403
ERROR - 2023-02-07 13:57:01 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\helpers\shop_helper.php 402
ERROR - 2023-02-07 13:57:01 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\helpers\shop_helper.php 403
ERROR - 2023-02-07 13:57:27 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Xampp\htdocs\ebuyshopdev\application\views\shop\pages\shop\cart_agent.php 148
ERROR - 2023-02-07 13:57:27 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\helpers\shop_helper.php 402
ERROR - 2023-02-07 13:57:27 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\helpers\shop_helper.php 403
ERROR - 2023-02-07 13:57:27 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\helpers\shop_helper.php 402
ERROR - 2023-02-07 13:57:27 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\helpers\shop_helper.php 403
ERROR - 2023-02-07 13:59:05 --> Severity: Notice --> Undefined property: stdClass::$price D:\Xampp\htdocs\ebuyshopdev\application\views\shop\pages\user\register.php 186
ERROR - 2023-02-07 13:59:05 --> Severity: Notice --> Undefined property: stdClass::$price D:\Xampp\htdocs\ebuyshopdev\application\views\shop\pages\user\register.php 199
ERROR - 2023-02-07 13:59:05 --> Severity: Notice --> Undefined property: stdClass::$price D:\Xampp\htdocs\ebuyshopdev\application\views\shop\pages\user\register.php 186
ERROR - 2023-02-07 13:59:05 --> Severity: Notice --> Undefined property: stdClass::$price D:\Xampp\htdocs\ebuyshopdev\application\views\shop\pages\user\register.php 199
ERROR - 2023-02-07 14:21:12 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Xampp\htdocs\ebuyshopdev\application\views\shop\pages\shop\cart_agent.php 148
ERROR - 2023-02-07 14:21:12 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\helpers\shop_helper.php 402
ERROR - 2023-02-07 14:21:12 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\helpers\shop_helper.php 403
ERROR - 2023-02-07 14:21:12 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\helpers\shop_helper.php 402
ERROR - 2023-02-07 14:21:12 --> Severity: Notice --> Trying to get property of non-object D:\Xampp\htdocs\ebuyshopdev\application\helpers\shop_helper.php 403
