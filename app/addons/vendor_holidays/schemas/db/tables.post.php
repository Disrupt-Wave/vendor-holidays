<?php

/**
 * Vendor Holidays - Database schema
 */

$schema['vendor_holidays'] = array(
    'fields' => array(
        'holiday_id' => array(
            'type' => 'int',
            'length' => 11,
            'not_null' => true,
            'auto_increment' => true
        ),
        'vendor_id' => array(
            'type' => 'int',
            'length' => 11,
            'not_null' => true,
            'default' => 0
        ),
        'date_from' => array(
            'type' => 'date',
            'not_null' => true,
            'default' => '0000-00-00'
        ),
        'date_to' => array(
            'type' => 'date',
            'not_null' => true,
            'default' => '0000-00-00'
        ),
        'status' => array(
            'type' => 'char',
            'length' => 1,
            'not_null' => true,
            'default' => 'A'
        )
    ),
    'primary_key' => array('holiday_id'),
    'indexes' => array(
        'vendor_id' => array('vendor_id')
    )
);

return $schema; 