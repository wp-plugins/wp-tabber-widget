<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_gtabber_display']) && $_POST['frm_gtabber_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	
	$gtabber_success = '';
	$gtabber_success_msg = FALSE;
	
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
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', 'wp-tabber-widget'); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('gtabber_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".GTabberTable."`
					WHERE `gtabber_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$gtabber_success_msg = TRUE;
			$gtabber_success = __('Selected record was successfully deleted.', 'wp-tabber-widget');
		}
	}
	
	if ($gtabber_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $gtabber_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Wp tabber widget', 'wp-tabber-widget'); ?>
	<a class="add-new-h2" href="<?php echo WP_gtabber_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'wp-tabber-widget'); ?></a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".GTabberTable."` order by gtabber_id desc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo WP_gtabber_PLUGIN_URL; ?>/pages/setting.js"></script>
		<form name="frm_gtabber_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th class="check-column" scope="col" style="width:15px;"><input type="checkbox" name="gtabber_group_item[]" /></th>
			<th scope="col" width="70%"><?php _e('Text', 'wp-tabber-widget'); ?></th>
			<th scope="col"><?php _e('Tabber group', 'wp-tabber-widget'); ?></th>
            <th scope="col"><?php _e('Link target', 'wp-tabber-widget'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <th class="check-column" scope="col" style="height:15px;"><input type="checkbox" name="gtabber_group_item[]" /></th>
			<th scope="col" width="70%"><?php _e('Text', 'wp-tabber-widget'); ?></th>
			<th scope="col"><?php _e('Tabber group', 'wp-tabber-widget'); ?></th>
            <th scope="col"><?php _e('Link target', 'wp-tabber-widget'); ?></th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td align="left"><input type="checkbox" value="<?php echo $data['gtabber_id']; ?>" name="gtabber_group_item[]"></td>
						<td><a href="<?php echo $data['gtabber_link']; ?>" target="<?php echo stripslashes($data['gtabber_target']); ?>"><?php echo stripslashes($data['gtabber_text']); ?></a>
						<div class="row-actions">
						<span class="edit"><a title="Edit" href="<?php echo WP_gtabber_ADMIN_URL; ?>&amp;ac=edit&amp;did=<?php echo $data['gtabber_id']; ?>"><?php _e('Edit', 'wp-tabber-widget'); ?></a> | </span>
						<span class="trash"><a onClick="javascript:gtabber_delete('<?php echo $data['gtabber_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'wp-tabber-widget'); ?></a></span> 
						</div>
						</td>
						<td>
							<?php echo stripslashes($data['gtabber_group']); ?>
						</td>
						<td><?php echo stripslashes($data['gtabber_target']); ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				} 	
			}
			else
			{
				?><tr><td colspan="4" align="center"><?php _e('No records available.', 'wp-tabber-widget'); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('gtabber_form_show'); ?>
		<input type="hidden" name="frm_gtabber_display" value="yes"/>
      </form>	
	  <div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo WP_gtabber_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'wp-tabber-widget'); ?></a>
	  <!--<a class="button add-new-h2" href="<?php echo WP_gtabber_ADMIN_URL; ?>&amp;ac=set">Plugin setting</a>-->
	  <a class="button add-new-h2" target="_blank" href="<?php echo WP_gtabber_FAV; ?>"><?php _e('Help', 'wp-tabber-widget'); ?></a>
	  </h2>
	  </div>
	  <div style="height:5px"></div>
	<h3><?php _e('Plugin configuration option', 'wp-tabber-widget'); ?></h3>
	<ol>
		<li><?php _e('Drag and drop the widget to your sidebar.', 'wp-tabber-widget'); ?></li>
	</ol>
	<p class="description">
		<?php _e('Check official website for more information', 'wp-tabber-widget'); ?>
		<a target="_blank" href="<?php echo WP_gtabber_FAV; ?>"><?php _e('click here', 'wp-tabber-widget'); ?></a>
	</p>
	</div>
</div>