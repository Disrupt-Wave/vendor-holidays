<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

$schema['vendor_holidays'] = array(
    'primary_key' => 'holiday_id',
    'fields' => array(
        'holiday_id' => array(
            'type' => 'int',
            'length' => 11,
            'auto_increment' => true,
        ),
        'vendor_id' => array(
            'type' => 'int',
            'length' => 11,
            'not_null' => true,
        ),
        'start_date' => array(
            'type' => 'date',
            'not_null' => true,
        ),
        'end_date' => array(
            'type' => 'date',
            'not_null' => true,
        ),
        'status' => array(
            'type' => 'char',
            'length' => 1,
            'default' => 'A',
        ),
        'created_at' => array(
            'type' => 'timestamp',
            'default' => 'CURRENT_TIMESTAMP',
        ),
    ),
    'indexes' => array(
        'vendor_id' => array(
            'type' => 'key',
            'fields' => array('vendor_id'),
        ),
    ),
); 