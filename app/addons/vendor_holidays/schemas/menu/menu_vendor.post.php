<?php

/**
 * Vendor Holidays - Menu schema for vendor panel
 */

$schema['central']['dashboard']['items']['vendor_holidays'] = array(
    'attrs' => array(
        'class' => 'is-addon'
    ),
    'href' => 'vendor_holidays.manage',
    'position' => 800,
    'title' => __('vendor_holidays.menu_title'),
    'subitems' => array(
        'vendor_holidays_manage' => array(
            'href' => 'vendor_holidays.manage',
            'position' => 100,
            'title' => __('vendor_holidays.manage')
        ),
    )
);

return $schema; 