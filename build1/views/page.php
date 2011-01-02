<h1><?php echo view_manager::getvalue('TITLE'); ?></h1>
<?php
$ul = array(
	'name'=>'ul',
	'value'=>array()
);
$len = rand(5,20);
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
?>
<div>This entry was posted on <?php echo date('f j, Y'); ?> at 11:23 pm and is filed under Uncategorized.<br />You can follow any responses to this entry through the RSS 2.0 feed.<br />You can leave a response, or trackback from your own site.</div>
<div id="comments">
	<form method="post" action="/wp-comments-post.php" id="commentform">
		<h3>Leave a Reply</h3>
        <div id="form-section-author" class="form-section">
            <input name="author" id="author" type="text" value="" tabindex="1" />
            <label for="author" class="required">Name</label>
        </div>
        <div id="form-section-email" class="form-section">
            <input name="email" id="email" type="text" value="" tabindex="2" />
            <label for="email" class="required">Email</label>
        </div>
        <div id="form-section-url" class="form-section">
            <input name="url" id="url" type="text" value="" tabindex="3" />
            <label for="url">Website</label>
        </div>
		<?php
		switch(view_manager::getvalue('DIVERSION')) {
			case 1:
				?><div id="form-section-orange" class="form-section">
					<input name="orange" id="orange" type="text" value="" tabindex="4" />
					<label for="orange">Type the word "orange"</label>
				</div><?php
				break;
			case 2:
				?><div id="form-section-check" class="form-section">
					<label for="checkme">Check this box</label>
					<input name="checkme" id="checkme" type="checkbox" value="checked" tabindex="4" />
				</div><?php
				break;
			case 3:
				?><div id="form-section-jsc" style="display:none;">
					<input name="js" id="js" type="text" value="" tabindex="4" />
					<script type="text/javascript">
					<!--
					var j = document.getElementById('js');
					j.value = "hooray";
					-->
					</script>
				</div><?php
				break;
			case 4:
				?><div id="form-section-name" style="display:none;">
					<input name="name" id="name" type="text" value="" tabindex="4" />
				</div><?php
				break;
			case 5:
				?><div id="form-section-math" class="form-section">
					<input name="mathcaptcha" id="mathcaptcha" type="text" value="" tabindex="4" />
					<label for="mathcaptcha"><?php echo view_manager::getvalue('MATH'); ?></label>
				</div><?php
				break;
		}
		?>
        <div id="form-section-comment" class="form-section">
        	<textarea name="comment" id="comment" tabindex="5" rows="10" cols="65"></textarea>
        </div>
        <div id="form-section-actions" class="form-section">
			<button name="submit" id="submit" type="submit" tabindex="6">Submit Comment</button>
        </div>
		<input type="hidden" value="<?php echo view_manager::getvalue('PATH'); ?>" name="comment_post_ID">
	</form>
</div>