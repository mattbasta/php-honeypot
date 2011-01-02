<?php

/*

Spam Honeypot

*/

$db = cloud::create_db(
	'mysql',
	array(
		'server'=>'localhost',
		'database'=>'spam',
		'username'=>'spam',
		'password'=>'65DAAB2EIQT5QHUU'
	)
);

view_manager::addview('shell');
view_manager::setvalue('DB', $db);

$words = file(PATH_PREFIX . '/keywords.txt');
$words_articles = file(PATH_PREFIX . '/articles.txt');
$random_words = file(PATH_PREFIX . '/random.txt');

$urls = $db->get_table('url_served');

function generatePhrase($length=5, $impl=' ', $norand=false) {
	global $words, $words_articles, $random_words;
	$x = array();
	$length /= 3;
	for($i=0;$i<$length;$i++) {
		$x[] = trim($words[array_rand($words)]);
		$x[] = trim($words_articles[array_rand($words_articles)]);
		if(!$norand)
			$x[] = trim($random_words[array_rand($random_words)]);
	}
	return implode($impl, $x);
}

function generateURL() {
	global $urls, $path;
	
	switch(rand(0,10)) {
		case 0:
			$tack = 'wp-login.php';
			break;
		case 1:
			$tack = 'wp-mail.php';
			break;
		case 2:
			$tack = generatePhrase(rand(2,5),'-',true);
			break;
		default:
			$tack = generatePhrase(rand(2,5),'-');
			break;
	}
	
	$url = '/' . md5(uniqid()) . '/' . $tack;
	
	if($_SERVER["REMOTE_ADDR"] != '147.106.58.26')
		$urls->insert_row(
			0,
			array(
				'ip'=>$_SERVER["REMOTE_ADDR"],
				'timestamp'=>time(),
				'path'=>$url
			)
		);
	
	return $url;
	
}

function generateLink($text = '', $title='', $rel='') {
	
	$html = getLib('html');
	
	return $html->renderHTML(array(
		'name'=>'a',
		'attributes'=>array(
			'href'=>generateURL(),
			'title'=>$title,
			'rel'=>$rel
		),
		'value'=>empty($text)?generatePhrase():$text
	));
	
}

function generateContent() {
	
	$elements = array();
	
	$r1=rand(1,10);
	for($i=0;$i<$r1;$i++) {
		$y = array();
		
		$r2=rand(1,20);
		for($j=0;$j<$r2;$j++)
			$y[] = generatePhrase();
		
		$elements[] = array(
			'name'=>'p',
			'value'=>implode('. ', $y) . '.'
		);
		
	}
	$html = getLib('html');
	return $html->renderHTMLElements($elements);
	
}

$diversion = 0;

if(empty($path[0])) {
	// We're in the root
	
	view_manager::setvalue('TITLE', 'Just Another Wordpress Weblog');
	view_manager::setvalue('CONTENT', generateContent());
	view_manager::addview('home');
	
	
} else {
	
	if($path[0] == 'wp-comments-post.php') {
		
		$spams = $db->get_table('spams');
		$spams_params = $db->get_table('spams_params');
		
		$diversion = 0;
		$diversion_solved = false;
		
		if(!empty($_REQUEST['orange'])) {
			$diversion = 1;
			$diversion_solved = $_REQUEST['orange'] == 'orange';
		}elseif(!empty($_REQUEST['checkme'])) {
			$diversion = 2;
			$diversion_solved = $_REQUEST['checkme'] == 'checked';
		}elseif(!empty($_REQUEST['js'])) {
			$diversion = 3;
			$diversion_solved = $_REQUEST['js'] == 'hooray';
		}elseif(!empty($_REQUEST['name'])) {
			$diversion = 4;
			$diversion_solved = $_REQUEST['name'] == '';
		}elseif(!empty($_REQUEST['mathcaptcha'])) {
			$diversion = 5;
			$diversion_solved = $_REQUEST['mathcaptcha'] == $session->captcha;
		}
		
		$spam_id = $spams->insert_row(
			0,
			array(
				'ip'=>$_SERVER["REMOTE_ADDR"],
				'timestamp'=>time(),
				'path'=>$_REQUEST['comment_post_ID'],
				'cookies'=>$session->cookies,
				'detected_diversion'=>$diversion,
				'diversion_solved'=>$diversion_solved
			)
		);
		
		foreach($_GET as $k=>$v) {
			
			if($k == 'comment_post_ID')
				continue;
			
			$spams_params->insert_row(
				0,
				array(
					'post_id'=>$spam_id,
					'name'=>$k,
					'value'=>$v,
					'method'=>'g'
				)
			);
			
		}
		
		$trickery = '<p>Your comment:</p>';
		
		foreach($_POST as $k=>$v) {
			
			if($k == 'comment_post_ID')
				continue;
			
			$spams_params->insert_row(
				0,
				array(
					'post_id'=>$spam_id,
					'name'=>$k,
					'value'=>$v,
					'method'=>'p'
				)
			);
			
			$trickery .= "<p>" . htmlentities($v) . "</p>";
			
		}
		
		header('Location: /');
		
		echo $trickery;
		
		define('EXIT', true);
		
	} else {
		
		view_manager::setvalue('TITLE', generatePhrase() . ' - Just Another Wordpress Weblog');
		view_manager::setvalue('CONTENT', generateContent());
		
		$diversion = rand(0,5);
		
		view_manager::setvalue('DIVERSION', $diversion);
		view_manager::addview('page');
		
		switch($diversion) {
			case 5:
				$num1 = rand(5,10);
				$num2 = rand(11,90);
				$pm = rand(0,1) * 2 - 1;
				$session->captcha = $num2 + ($pm * $num1);
				
				view_manager::setvalue('MATH', $num2 . ($pm < 0 ? ' - ' : ' + ') . $num1 . ' = ');
				break;
		}
		
	}
	
}

if(!defined('EXIT')) {
	$hits = $db->get_table('hits');
	if($_SERVER["REMOTE_ADDR"] != '147.106.58.26')
		$hitid = $hits->insert_row(
			0,
			array(
				'ip'=>$_SERVER["REMOTE_ADDR"],
				'timestamp'=>time(),
				'path'=>'/' . implode('/', $path),
				'title'=>view_manager::getvalue('TITLE'),
				'content'=>view_manager::getvalue('CONTENT'),
				'diversion_technique'=>$diversion
			)
		);
	else
		$hitid = 0;
	
	view_manager::setvalue('HITID',$hitid);
	view_manager::setvalue('PATH',implode('/', $path));
	
	$session->cookies = true;

	echo view_manager::render();
}

$db->close();
