<?php
$G_CONFIG['upload']['aCrossDomain'] = array(
		1 => 'dev.ipo.com'
); //线上配置

$G_CONFIG['upload']['aUpdType'] = array( 
		'/ananzu\.com|anhouse\.cn|ipo\.com|pinganfang\.com|anhouse\.com|anhouse\.com\.cn|local\.com|pinganhaofang\.com/' => array( 
				'' => array( 
						'gif',
						'jpg',
						'png' 
				),
				'interact' => array(
					'gif',
					'jpg',
					'png'
				),
				'document' => array( 
						'txt',
						'doc',
						'docx',
						'xls',
						'xlsx',
						'ppt',
						'pptx',
						'wps',
						'zip',
						'rar',
						'pdf'
				),
				'secret' => array(
						'gif',
						'jpg',
						'png',
						'txt',
						'doc',
						'docx',
						'pdf',
						'rar',
						'zip',
						'xls',
						'xlsx'
				),
				'project' => array(
						'gif',
						'jpg',
						'png',
						'txt',
						'doc',
						'docx',
						'xls',
						'xlsx',
						'ppt',
						'pptx',
						'wps',
						'zip',
						'rar',
						'pdf'
				),
				'export' => array(
						'txt',
						'doc',
						'docx',
						'xls',
						'xlsx',
						'ppt',
						'pptx',
						'wps',
						'zip',
						'rar',
						'pdf'
				),
				'agreement' => array(
					'pdf'
				),
		)
);
$G_CONFIG['upload']['aUpdSize'] = array( 
		'/ananzu\.com|anhouse\.cn|ipo\.com|pinganfang\.com|anhouse\.com|anhouse\.com\.cn|local\.com|pinganhaofang\.com/' => array( 
				'' => array( 
						'iMin' => 1,
						'iMax' => 15728640 
				) 
		) 
);
$G_CONFIG['upload']['aStorageHost'] = array( 
		array( 
				'iHostID' => 1,
				'iWeight' => 2 
		),
		array( 
				'iHostID' => 2,
				'iWeight' => 3 
		) 
);//线上配置
