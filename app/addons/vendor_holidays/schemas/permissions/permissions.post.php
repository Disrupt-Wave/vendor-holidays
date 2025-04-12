<?php

/**
 * Vendor Holidays - Permissions schema
 */

$schema['vendor_holidays'] = array(
    'permissions' => array(
        'manage_vendor_holidays' => array(
            'is_default' => 'Y',
            'section_id' => 'vendors',
            'group_id' => 'vendors',
            'is_view' => 'Y'
        ),
        'view_vendor_holidays' => array(
            'is_default' => 'Y',
            'section_id' => 'vendors',
            'group_id' => 'vendors',
            'is_view' => 'Y'
        )
    )
);

return $schema; 