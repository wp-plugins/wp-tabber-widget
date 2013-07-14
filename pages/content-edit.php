<div class="wrap">
<?php
$did = isset($_GET['did']) ? $_GET['did'] : '0';

// First check if ID exist with requested ID
$sSql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".GTabberTable."
	WHERE `gtabber_id` = %d",
	array($did)
);
$result = '0';
$result = $wpdb->get_var($sSql);

if ($result != '1')
{
	?><div class="error fade"><p><strong>Oops, selected details doesn't exist.</strong></p></div><?php
}
else
{
	$gtabber_errors = array();
	$gtabber_success = '';
	$gtabber_error_found = FALSE;
	
	$sSql = $wpdb->prepare("
		SELECT *
		FROM `".GTabberTable."`
		WHERE `gtabber_id` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'gtabber_text' => $data['gtabber_text'],
		'gtabber_link' => $data['gtabber_link'],
		'gtabber_group' => $data['gtabber_group'],
		'gtabber_target' => $data['gtabber_target'],
		'gtabber_id' => $data['gtabber_id']
	);
}
// Form submitted, check the data
if (isset($_POST['gtabber_form_submit']) && $_POST['gtabber_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('gtabber_form_edit');
	
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
		$sSql = $wpdb->prepare(
				"UPDATE `".GTabberTable."`
				SET `gtabber_text` = %s,
				`gtabber_link` = %s,
				`gtabber_group` = %s,
				`gtabber_target` = %s
				WHERE gtabber_id = %d
				LIMIT 1",
				array($form['gtabber_text'], $form['gtabber_link'], $form['gtabber_group'], $form['gtabber_target'], $did)
			);
		$wpdb->query($sSql);
		
		$gtabber_success = 'Details was successfully updated.';
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
      <h3>Update details</h3>
	  
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
		<input name="gtabber_group" type="text" id="gtabber_group" value="<?php echo esc_html(stripslashes($form['gtabber_group'])); ?>" maxlength="25" size="30" /></td>
		<p>Select existing group (or) enter new tabber group</p>
		
		<label for="tag-title">Tabber text</label>
		<input name="gtabber_text" type="text" id="gtabber_text" value="<?php echo esc_html(stripslashes($form['gtabber_text'])); ?>" maxlength="250" size="120" />
		<p>Please enter your tabber content.</p>
		
		<label for="tag-title">Link</label>
		<input name="gtabber_link" type="text" id="gtabber_link" value="<?php echo esc_html(stripslashes($form['gtabber_link'])); ?>" size="103" />
		<p>When someone clicks on the message, where do you want to send them.</p>
		
		<label for="tag-title">Target</label>
		<select name="gtabber_target" id="gtabber_target">
			<option value='_blank' <?php if($form['gtabber_target'] == '_blank' ) { echo 'selected' ; } ?>>_blank</option>
			<option value='_parent' <?php if($form['gtabber_target'] == '_parent' ) { echo 'selected' ; } ?>>_parent</option>
			<option value='_self' <?php if($form['gtabber_target'] == '_self' ) { echo 'selected' ; } ?>>_self</option>
			<option value='_top' <?php if($form['gtabber_target'] == '_parent' ) { echo 'selected' ; } ?>>_top</option>
		  </select>
		 <p>Do you want to open link in new window?</p>
	  
	  	  
      <input name="gtabber_id" id="gtabber_id" type="hidden" value="<?php echo $form['gtabber_id']; ?>">
      <input type="hidden" name="gtabber_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button add-new-h2" value="Update Details" type="submit" />&nbsp;
        <input name="publish" lang="publish" class="button add-new-h2" onclick="gtabber_redirect()" value="Cancel" type="button" />&nbsp;
        <input name="Help" lang="publish" class="button add-new-h2" onclick="gtabber_help()" value="Help" type="button" />
      </p>
	  <?php wp_nonce_field('gtabber_form_edit'); ?>
    </form>
</div>
<p class="description"><?php echo WP_gtabber_LINK; ?></p>
</div>