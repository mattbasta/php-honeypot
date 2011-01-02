<?php

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
<title><?php echo view_manager::getvalue('TITLE'); ?></title>
<style type="text/css">
body {position:absolute;left:-10000px;}
html,body {background:red url(/static/warning.jpg) no-repeat center center;height:100%;}
</style>
<meta name="description" content="Just Another WordPress Weblog" />
<link rel="pingback" href="http://onourpoop.com/wordpress/xmlrpc.php" />
</head>
<body>
<div class="nav"><?php
echo generateLink('View All Posts', 'View all posts', 'category-tag');
?></div>
<?php echo view_manager::render(); ?>
<div><p>Powered by WordPress</p></div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-91087-26");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>