<?php
$ul = array(
	'name'=>'ul',
	'value'=>array()
);
$len = rand(5,120);
for($i=0;$i<$len;$i++) {
	$ul['value'][] = array(
		'name'=>'li',
		'dontsanitize'=>true,
		'value'=>generateLink()
	);
}
$html = getLib('html');
echo $html->renderHTML($ul);
echo view_manager::getvalue('CONTENT');