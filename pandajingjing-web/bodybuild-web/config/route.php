<?php
$G_CONFIG['route']['web']['/^\/$/'] = array(
    'Controller_Home'
);
$G_CONFIG['route']['Home'] = '/';

$G_CONFIG['route']['web']['/^\/ajax\/weight\/(\d+)\/$/'] = array(
    'Controller_Ajax_Weight',
    array(
        'iDay'
    )
);

$G_CONFIG['route']['web']['/^\/article\/$/'] = array(
    'Controller_Arcticle'
);
$G_CONFIG['route']['web']['/^\/article\/(\d+)\/$/'] = array();

$G_CONFIG['route']['web']['/^\/journal\/$/'] = array(
    'Controller_Journal'
);
$G_CONFIG['route']['web']['/^\/journal\/(\d+)\/$/'] = array(
    'Controller_Journal',
    array(
        'iPage'
    )
);

$G_CONFIG['route']['web']['/^\/(diet|motion|about\/treadmill|about\/dumbbell|about\/bmi)\/$/'] = array(
    'Controller_Html',
    array(
        'sKey'
    )
);
$G_CONFIG['route']['Diet'] = '/diet/';
$G_CONFIG['route']['Motion'] = '/motion/';
$G_CONFIG['route']['AboutTreadmill'] = '/about/treadmill/';
$G_CONFIG['route']['AboutDumbbell'] = '/about/dumbbell/';
$G_CONFIG['route']['AboutBMI'] = '/about/bmi/';