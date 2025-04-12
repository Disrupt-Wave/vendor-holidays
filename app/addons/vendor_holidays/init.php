<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

// Register hooks
fn_register_hooks(
    'get_products',
    'get_companies',
    'get_product_data_post'
); 