<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Registry;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($mode == 'update') {
        $holiday_id = !empty($_REQUEST['holiday_id']) ? $_REQUEST['holiday_id'] : 0;
        $vendor_id = !empty($_REQUEST['vendor_id']) ? $_REQUEST['vendor_id'] : 0;
        $date_from = !empty($_REQUEST['date_from']) ? $_REQUEST['date_from'] : '';
        $date_to = !empty($_REQUEST['date_to']) ? $_REQUEST['date_to'] : '';
        $status = !empty($_REQUEST['status']) ? $_REQUEST['status'] : 'A';

        if (empty($vendor_id) || empty($date_from) || empty($date_to)) {
            fn_set_notification('E', __('error'), __('error_required_fields'));
            return array(CONTROLLER_STATUS_REDIRECT, 'vendor_holidays.manage');
        }

        $holiday_data = array(
            'vendor_id' => $vendor_id,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'status' => $status
        );

        if ($holiday_id) {
            db_query("UPDATE ?:vendor_holidays SET ?u WHERE holiday_id = ?i", $holiday_data, $holiday_id);
        } else {
            db_query("INSERT INTO ?:vendor_holidays ?e", $holiday_data);
        }

        fn_set_notification('N', __('notice'), __('vendor_holiday_updated'));
        return array(CONTROLLER_STATUS_REDIRECT, 'vendor_holidays.manage');
    }

    if ($mode == 'delete') {
        $holiday_id = !empty($_REQUEST['holiday_id']) ? $_REQUEST['holiday_id'] : 0;
        
        if ($holiday_id) {
            db_query("DELETE FROM ?:vendor_holidays WHERE holiday_id = ?i", $holiday_id);
            fn_set_notification('N', __('notice'), __('vendor_holiday_deleted'));
        }
        
        return array(CONTROLLER_STATUS_REDIRECT, 'vendor_holidays.manage');
    }

    if ($mode == 'm_delete') {
        $holiday_ids = !empty($_REQUEST['holiday_ids']) ? $_REQUEST['holiday_ids'] : array();
        
        if (!empty($holiday_ids)) {
            db_query("DELETE FROM ?:vendor_holidays WHERE holiday_id IN (?n)", $holiday_ids);
            fn_set_notification('N', __('notice'), __('vendor_holidays_deleted'));
        }
        
        return array(CONTROLLER_STATUS_REDIRECT, 'vendor_holidays.manage');
    }

    if ($mode == 'm_update_statuses') {
        $holiday_ids = !empty($_REQUEST['holiday_ids']) ? $_REQUEST['holiday_ids'] : array();
        $status = !empty($_REQUEST['status']) ? $_REQUEST['status'] : 'A';
        
        if (!empty($holiday_ids)) {
            db_query("UPDATE ?:vendor_holidays SET status = ?s WHERE holiday_id IN (?n)", $status, $holiday_ids);
            fn_set_notification('N', __('notice'), __('vendor_holidays_updated'));
        }
        
        return array(CONTROLLER_STATUS_REDIRECT, 'vendor_holidays.manage');
    }

    if ($mode == 'manage') {
        // Handle POST requests for managing holidays
        if (!empty($_REQUEST['holiday_id'])) {
            // Update existing holiday
            db_query("UPDATE ?:vendor_holidays SET ?u WHERE holiday_id = ?i", $_REQUEST['holiday'], $_REQUEST['holiday_id']);
        } else {
            // Add new holiday
            db_query("INSERT INTO ?:vendor_holidays ?e", $_REQUEST['holiday']);
        }
        
        return [CONTROLLER_STATUS_REDIRECT, 'vendor_holidays.manage'];
    }
}

if ($mode == 'manage') {
    $params = $_REQUEST;
    $params['items_per_page'] = !empty($params['items_per_page']) ? $params['items_per_page'] : Registry::get('settings.Appearance.admin_elements_per_page');
    
    list($holidays, $search) = fn_get_vendor_holidays($params);
    
    $auth = Tygh::$app['session']['auth'];
    $companies = fn_get_companies(array('status' => 'A'), $auth, array('company_id', 'company'), 0, 'company');
    
    Tygh::$app['view']->assign('holidays', $holidays);
    Tygh::$app['view']->assign('search', $search);
    Tygh::$app['view']->assign('companies', $companies);

    // Add breadcrumbs
    fn_add_breadcrumb(__('vendor_holidays'), 'vendor_holidays.manage');
    
    // Set page title
    Registry::set('navigation.tabs', array(
        'general' => array(
            'title' => __('general'),
            'js' => true
        )
    ));
    Registry::set('navigation.active_tab', 'general');
    Registry::set('navigation.dynamic.title', __('vendor_holidays'));
}

if ($mode == 'update') {
    $holiday_id = !empty($_REQUEST['holiday_id']) ? $_REQUEST['holiday_id'] : 0;
    
    if ($holiday_id) {
        $holiday = db_get_row("SELECT * FROM ?:vendor_holidays WHERE holiday_id = ?i", $holiday_id);
        Tygh::$app['view']->assign('holiday', $holiday);
    }
    
    $companies = fn_get_companies(array('status' => 'A'), array('company_id', 'company'), 0, 'company');
    Tygh::$app['view']->assign('companies', $companies);
}

/**
 * Get vendor holidays
 */
function fn_get_vendor_holidays($params)
{
    $default_params = array(
        'page' => 1,
        'items_per_page' => 0,
        'vendor_id' => 0
    );
    
    $params = array_merge($default_params, $params);
    
    $condition = db_quote(" WHERE 1=1");
    if (!empty($params['vendor_id'])) {
        $condition .= db_quote(" AND vendor_id = ?i", $params['vendor_id']);
    }
    
    $limit = '';
    if (!empty($params['items_per_page'])) {
        $params['total_items'] = db_get_field("SELECT COUNT(*) FROM ?:vendor_holidays $condition");
        $limit = db_paginate($params['page'], $params['items_per_page'], $params['total_items']);
    }
    
    $holidays = db_get_array(
        "SELECT * FROM ?:vendor_holidays $condition ORDER BY date_from DESC $limit"
    );
    
    return array($holidays, $params);
} 