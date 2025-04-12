<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

$schema['manage_vendor_holidays'] = array(
    'permissions' => array('GET' => 'manage_vendor_holidays', 'POST' => 'manage_vendor_holidays'),
    'modes' => array(
        'manage' => array(
            'permissions' => 'manage_vendor_holidays'
        ),
        'update' => array(
            'permissions' => 'manage_vendor_holidays'
        ),
        'delete' => array(
            'permissions' => 'manage_vendor_holidays'
        )
    )
); 