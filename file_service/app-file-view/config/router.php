<?php
$G_CONFIG['router']['/view'] = array(
    '/^\/view\/([a-z]{1,10})\/([a-z0-9]{40})\/(\d*)x(\d*)\.(jpg|gif|png|bmp)/i', // 5
    '/^\/view\/([a-z]{1,10})\/([a-z0-9]{40})\/(\d*)x(\d*)\_([a-z]+)\.(jpg|gif|png|bmp)/i', // 6
    '/^\/view\/([a-z]{1,10})\/([a-z0-9]{40})\.(.*)/i', // 3
    '/^\/view\/([a-z0-9]{40})\/(\d*)x(\d*)\.(jpg|gif|png|bmp)/i', // 4
    '/^\/view\/([a-z0-9]{40})\/(\d*)x(\d*)\_([a-z]+)\.(jpg|gif|png|bmp)/i', // 5
    '/^\/view\/([a-z0-9]{40})\.(.*)/i' // 2
);

$G_CONFIG['router']['/interface/view'] = array(
    '/^\/interface\/view\/([a-z]{1,10})\/([a-z0-9]{40})\.(.*)/i', // 3
    '/^\/interface\/view\/([a-z]{1,10})\/([a-z0-9]{40})\/(\d*)x(\d*)\.(jpg|gif|png|bmp)/i' // 5
);