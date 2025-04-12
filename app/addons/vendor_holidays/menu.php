<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

$schema['top']['administration']['items']['vendors']['subitems']['vendor_holidays'] = array(
    'title' => 'vendor_holidays',
    'href' => 'vendor_holidays.manage',
    'position' => 100,
    'type' => 'A',
    'alt' => 'vendor_holidays.manage',
    'permissions' => 'manage_vendor_holidays'
); 