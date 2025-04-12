<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

$schema['vendor_holidays'] = array(
    'title' => 'Vendor Holidays',
    'href' => 'vendor_holidays.manage',
    'position' => 800,
    'parent' => 'companies',
    'section' => 'vendor_holidays',
    'type' => 'R',
    'status' => 'A'
);

$schema['vendor_holidays_vendor'] = array(
    'title' => 'Vendor Holidays',
    'href' => 'vendor_holidays.manage',
    'position' => 800,
    'parent' => 'dashboard',
    'section' => 'vendor_holidays',
    'type' => 'R',
    'status' => 'A'
); 