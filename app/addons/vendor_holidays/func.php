<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

/**
 * Install function
 */
function fn_vendor_holidays_install()
{
    // Create vendor_holidays table
    db_query("DROP TABLE IF EXISTS ?:vendor_holidays");
    db_query("CREATE TABLE ?:vendor_holidays (
        holiday_id int(11) NOT NULL AUTO_INCREMENT,
        vendor_id int(11) NOT NULL DEFAULT '0',
        date_from date NOT NULL DEFAULT '0000-00-00',
        date_to date NOT NULL DEFAULT '0000-00-00',
        status char(1) NOT NULL DEFAULT 'A',
        PRIMARY KEY (holiday_id),
        KEY vendor_id (vendor_id)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8");

    // Add language variables
    $languages = db_get_fields("SELECT lang_code FROM ?:languages");
    $lang_entries = array(
        'vendor_holidays' => 'Vendor Holidays',
        'vendor_holidays.manage' => 'Manage Holidays',
        'vendor_holidays.menu_title' => 'Vendor Holidays',
        'vendor_holidays.menu_description' => 'Manage vendor holiday periods',
        'vendor_holiday' => 'Vendor Holiday',
        'vendor_holiday_updated' => 'Vendor holiday has been updated successfully',
        'vendor_holiday_deleted' => 'Vendor holiday has been deleted successfully',
        'vendor_holidays_deleted' => 'Selected vendor holidays have been deleted successfully',
        'vendor_holidays_updated' => 'Selected vendor holidays have been updated successfully',
        'add_vendor_holiday' => 'Add Vendor Holiday',
        'new_holiday' => 'New Holiday',
        'date_from' => 'Date From',
        'date_to' => 'Date To',
        'select_vendor' => 'Select Vendor'
    );

    foreach ($languages as $lang_code) {
        foreach ($lang_entries as $name => $value) {
            db_query("REPLACE INTO ?:language_values (lang_code, name, value) VALUES (?s, ?s, ?s)",
                $lang_code, $name, $value);
        }
    }

    // Add menu items
    $admin_menu = array(
        'parent_id' => 0,
        'id_path' => 'vendor_holidays',
        'status' => 'A',
        'type' => 'R',
        'object_id' => 0,
        'position' => 800,
        'parent' => 'companies',
        'href' => 'vendor_holidays.manage'
    );
    
    $admin_menu_id = db_query("INSERT INTO ?:menus ?e", $admin_menu);
    
    $admin_menu_desc = array(
        'menu_id' => $admin_menu_id,
        'lang_code' => 'en',
        'title' => 'Vendor Holidays',
        'description' => 'Manage vendor holiday periods'
    );
    
    db_query("INSERT INTO ?:menus_descriptions ?e", $admin_menu_desc);

    // Add vendor menu item
    $vendor_menu = array(
        'parent_id' => 0,
        'id_path' => 'vendor_holidays',
        'status' => 'A',
        'type' => 'R',
        'object_id' => 0,
        'position' => 800,
        'parent' => 'dashboard',
        'href' => 'vendor_holidays.manage'
    );
    
    $vendor_menu_id = db_query("INSERT INTO ?:menus ?e", $vendor_menu);
    
    $vendor_menu_desc = array(
        'menu_id' => $vendor_menu_id,
        'lang_code' => 'en',
        'title' => 'Vendor Holidays',
        'description' => 'Manage your holiday periods'
    );
    
    db_query("INSERT INTO ?:menus_descriptions ?e", $vendor_menu_desc);

    // Add permissions for admin
    $admin_privileges = array(
        array('privilege' => 'manage_vendor_holidays', 'is_vendor' => 0, 'section_id' => 'vendor_holidays'),
        array('privilege' => 'view_vendor_holidays', 'is_vendor' => 0, 'section_id' => 'vendor_holidays')
    );

    foreach ($admin_privileges as $privilege) {
        // Check if privilege exists before inserting
        $exists = db_get_field("SELECT COUNT(*) FROM ?:privileges WHERE privilege = ?s AND section_id = ?s", 
            $privilege['privilege'], 
            $privilege['section_id']
        );
        
        if (!$exists) {
            db_query("INSERT INTO ?:privileges ?e", $privilege);
        }
    }

    // Add permissions for vendor
    $vendor_privileges = array(
        array('privilege' => 'manage_vendor_holidays', 'is_vendor' => 1, 'section_id' => 'vendor_holidays'),
        array('privilege' => 'view_vendor_holidays', 'is_vendor' => 1, 'section_id' => 'vendor_holidays')
    );

    foreach ($vendor_privileges as $privilege) {
        // Check if privilege exists before inserting
        $exists = db_get_field("SELECT COUNT(*) FROM ?:privileges WHERE privilege = ?s AND section_id = ?s", 
            $privilege['privilege'], 
            $privilege['section_id']
        );
        
        if (!$exists) {
            db_query("INSERT INTO ?:privileges ?e", $privilege);
        }
    }

    // Clear cache
    fn_clear_cache();
}

/**
 * Uninstall function
 */
function fn_vendor_holidays_uninstall()
{
    // Get menu IDs by href instead of title
    $admin_menu_id = db_get_field("SELECT menu_id FROM ?:menus WHERE href = 'vendor_holidays.manage' AND parent = 'companies'");
    $vendor_menu_id = db_get_field("SELECT menu_id FROM ?:menus WHERE href = 'vendor_holidays.manage' AND parent = 'dashboard'");

    // Delete menu items
    if ($admin_menu_id) {
        db_query("DELETE FROM ?:menus WHERE menu_id = ?i", $admin_menu_id);
        db_query("DELETE FROM ?:menus_descriptions WHERE menu_id = ?i", $admin_menu_id);
    }
    if ($vendor_menu_id) {
        db_query("DELETE FROM ?:menus WHERE menu_id = ?i", $vendor_menu_id);
        db_query("DELETE FROM ?:menus_descriptions WHERE menu_id = ?i", $vendor_menu_id);
    }

    // Clear cache
    fn_clear_cache();
}

/**
 * Check if vendor is on holiday
 */
function fn_vendor_holidays_is_vendor_on_holiday($vendor_id)
{
    if (empty($vendor_id)) {
        return false;
    }

    $current_date = date('Y-m-d');
    
    $holiday = db_get_row(
        "SELECT * FROM ?:vendor_holidays 
        WHERE vendor_id = ?i 
        AND status = 'A' 
        AND ?s BETWEEN date_from AND date_to",
        $vendor_id,
        $current_date
    );

    return !empty($holiday);
}

/**
 * Hook: get_products
 */
function fn_vendor_holidays_get_products($params, $fields, $sortings, $condition, $join, $group_by)
{
    if (!empty($params['company_id'])) {
        $vendor_id = $params['company_id'];
    } else {
        $vendor_id = db_get_field("SELECT company_id FROM ?:products WHERE product_id = ?i", $params['pid']);
    }

    if (fn_vendor_holidays_is_vendor_on_holiday($vendor_id)) {
        $condition .= db_quote(" AND ?:products.product_id NOT IN (SELECT product_id FROM ?:products WHERE company_id = ?i)", $vendor_id);
    }

    return array($params, $fields, $sortings, $condition, $join, $group_by);
}

/**
 * Hook: get_companies
 */
function fn_vendor_holidays_get_companies($params, $fields, $sortings, $condition, $join, $group_by)
{
    $current_date = date('Y-m-d');
    
    $join .= db_quote(" LEFT JOIN ?:vendor_holidays ON ?:companies.company_id = ?:vendor_holidays.vendor_id 
        AND ?:vendor_holidays.status = 'A' 
        AND ?s BETWEEN ?:vendor_holidays.date_from AND ?:vendor_holidays.date_to", $current_date);
    
    $fields[] = "IF(?:vendor_holidays.holiday_id IS NOT NULL, 'Y', 'N') as is_on_holiday";

    return array($params, $fields, $sortings, $condition, $join, $group_by);
}

/**
 * Hook: get_product_data_post
 */
function fn_vendor_holidays_get_product_data_post($product_id, $field_list, $product_data)
{
    if (!empty($product_data['company_id']) && fn_vendor_holidays_is_vendor_on_holiday($product_data['company_id'])) {
        $product_data['is_on_holiday'] = true;
    }
    
    return array($product_id, $field_list, $product_data);
}

/**
 * Post-hook for get_products to filter out products from vendors on holiday
 *
 * @param array $params Product search parameters
 * @param array $fields Product fields
 * @param array $products Products array
 */
function fn_vendor_holidays_get_products_post($params, $fields, &$products)
{
    if (empty($products)) {
        return;
    }

    $current_date = TIME;
    
    // Get all vendors who are on holiday
    $holiday_vendors = db_get_hash_array(
        "SELECT vendor_id 
        FROM ?:vendor_holidays 
        WHERE start_date <= ?i 
        AND end_date >= ?i 
        AND status = 'A'",
        'vendor_id',
        $current_date,
        $current_date
    );

    if (empty($holiday_vendors)) {
        return;
    }

    // Filter out products from vendors on holiday
    foreach ($products as $key => $product) {
        if (isset($holiday_vendors[$product['company_id']])) {
            unset($products[$key]);
        }
    }

    // Reindex array after unsetting elements
    $products = array_values($products);
} 