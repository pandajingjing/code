<?php
$G_CONFIG['route']['web']['/^\/$/'] = array(
    'Controller_Home_Home'
);

$G_CONFIG['route']['web']['/^\/weight\/list(\/.*)?\/$/'] = array(
    'Controller_Weight_List',
    array(
        'sSearchKey'
    )
);

$G_CONFIG['route']['web']['/^\/weight\/add\/$/'] = array(
    'Controller_Weight_Add'
);