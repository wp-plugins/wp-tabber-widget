<div class="wrap">
<?php
$gtabber_errors = array();
$gtabber_success = '';
$gtabber_error_found = FALSE;

// Preset the form fields
$form = array(
	'gtabber_text' => '',
	'gtabber_link' => '',
	'gtabber_group' => '',
	'gtabber_group' => '',
	'gtabber_id' => ''
);

// Form submitted, check the data
if (isset($_POST['gtabber_form_submit']) && $_POST['gtabber_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('gtabber_form_add');
	
	$form['gtabber_text'] = isset($_POST['gtabber_text']) ? $_POST['gtabber_text'] : '';
	if ($form['gtabber_text'] == '')
	{
		$gtabber_errors[] = __('Please enter tabber text.', gtabber_UNIQUE_NAME);
		$gtabber_error_found = TRUE;
	}	
	$form['gtabber_link'] = isset($_POST['gtabber_link']) ? $_POST['gtabber_link'] : '';
	$form['gtabber_group'] = isset($_POST['gtabber_group']) ? $_POST['gtabber_group'] : '';
	if ($form['gtabber_group'] == '')
	{
		$gtabber_errors[] = __('Please select existing group (or) enter new tabber group.', gtabber_UNIQUE_NAME);
		$gtabber_error_found = TRUE;
	}
	$form['gtabber_target'] = isset($_POST['gtabber_target']) ? $_POST['gtabber_target'] : '';

	//	No errors found, we can add this Group to the table
	if ($gtabber_error_found == FALSE)
	{
		$sql = $wpdb->prepare(
			"INSERT INTO `".GTabberTable."`
			(`gtabber_text`, `gtabber_link`, `gtabber_group`, `gtabber_target`)
			VALUES(%s, %s, %s, %s)",
			array($form['gtabber_text'], $form['gtabber_link'], $form['gtabber_group'], $form['gtabber_target'])
		);
		$wpdb->query($sql);
		
		$gtabber_success = __('New details was successfully added.', gtabber_UNIQUE_NAME);
		
		// Reset the form fields
		$form = array(
			'gtabber_text' => '',
			'gtabber_link' => '',
			'gtabber_group' => '',
			'gtabber_target' => '',
			'gtabber_id' => ''
		);
	}
}

if ($gtabber_error_found == TRUE && isset($gtabber_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $gtabber_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($gtabber_error_found == FALSE && strlen($gtabber_success) > 0)
{
	?>
	<div class="updated fade">
		<p><strong><?php echo $gtabber_success; ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=wp-tabber-widget">Click here</a> to view the details</strong></p>
	</div>
	<?php
}
?>
<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/wp-tabber-widget/pages/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php echo WP_gtabber_TITLE; ?></h2>
	<form name="gtabber_form" method="post" action="#" onsubmit="return gtabber_submit()"  >
      <h3>Add details</h3>
      
		<label for="tag-title">Tabber group</label>
		<select style="width:190px;" name="gtabber_group_drop" id="gtabber_group_drop" onchange="gtabber_group_load(this.value)">
		<option value=''>Please select (or) enter new</option>
		<?php
		$data = $wpdb->get_results("SELECT distinct gtabber_group FROM wp_gtabber");
		if ( !empty($data) ) 
        {
			foreach ( $data as $data ) 
			{
				?>
				<option value="<?php echo stripslashes($data->gtabber_group); ?>"><?php echo stripslashes($data->gtabber_group); ?></option>
				<?php
			}
		}
		?>
		</select>
		<input name="gtabber_group" type="text" id="gtabber_group" value="" maxlength="25" size="30" /></td>
		<p>Select existing group (or) enter new tabber group</p>
		
		<label for="tag-title">Tabber text</label>
		<input name="gtabber_text" type="text" id="gtabber_text" value="" maxlength="250" size="120" />
		<p>Please enter your tabber content.</p>
		
		<label for="tag-title">Link</label>
		<input name="gtabber_link" type="text" id="gtabber_link" value="" size="120" />
		<p>When someone clicks on the message, where do you want to send them.</p>
		
		<label for="tag-title">Target</label>
		<select name="gtabber_target" id="gtabber_target">
            <option value='_blank'>_blank</option>
            <option value='_parent'>_parent</option>
            <option value='_self'>_self</option>
            <option value='_top'>_top</option>
          </select>
		 <p>Do you want to open link in new window?</p>
	  	  
      <input name="gtabber_id" id="gtabber_id" type="hidden" value="">
      <input type="hidden" name="gtabber_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button add-new-h2" value="Insert Details" type="submit" />&nbsp;
        <input name="publish" lang="publish" class="button add-new-h2" onclick="gtabber_redirect()" value="Cancel" type="button" />&nbsp;
        <input name="Help" lang="publish" class="button add-new-h2" onclick="gtabber_help()" value="Help" type="button" />
      </p>
	  <?php wp_nonce_field('gtabber_form_add'); ?>
    </form>
</div>
<p class="description"><?php echo WP_gtabber_LINK; ?></p>
</div>