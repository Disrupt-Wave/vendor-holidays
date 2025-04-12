<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

/**
 * Hook: get_products
 */
fn_add_hook(
    'get_products',
    'post',
    function($params, $fields, $sortings, $condition, $join, $group_by) {
        return fn_vendor_holidays_get_products($params, $fields, $sortings, $condition, $join, $group_by);
    }
);

/**
 * Hook: get_companies
 */
fn_add_hook(
    'get_companies',
    'post',
    function($params, $fields, $sortings, $condition, $join, $group_by) {
        return fn_vendor_holidays_get_companies($params, $fields, $sortings, $condition, $join, $group_by);
    }
);

/**
 * Hook: get_product_data_post
 */
fn_add_hook(
    'get_product_data_post',
    'post',
    function($product_id, $field_list, $product_data) {
        return fn_vendor_holidays_get_product_data_post($product_id, $field_list, $product_data);
    }
);

/**
 * Hook: view_product_details
 */
fn_add_hook(
    'view_product_details',
    'pre',
    function(&$product_id) {
        $product = fn_get_product_data($product_id);
        if (!empty($product['company_id']) && fn_vendor_holidays_is_vendor_on_holiday($product['company_id'])) {
            fn_set_notification('W', __('warning'), __('vendor_on_holiday'));
        }
    }
);

/**
 * Hook: view_companies
 */
fn_add_hook(
    'view_companies',
    'pre',
    function(&$company_id) {
        if (fn_vendor_holidays_is_vendor_on_holiday($company_id)) {
            fn_set_notification('W', __('warning'), __('vendor_on_holiday'));
        }
    }
); 