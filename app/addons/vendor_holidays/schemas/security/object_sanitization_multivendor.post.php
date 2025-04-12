<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

$schema['vendor_holidays'] = array(
    'fields' => array(
        'holiday_id' => array(
            'sanitize' => 'int',
        ),
        'vendor_id' => array(
            'sanitize' => 'int',
        ),
        'start_date' => array(
            'sanitize' => 'date',
        ),
        'end_date' => array(
            'sanitize' => 'date',
        ),
        'status' => array(
            'sanitize' => 'char',
        ),
        'created_at' => array(
            'sanitize' => 'timestamp',
        ),
    ),
); 