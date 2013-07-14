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
		?><div class="error fade"><p><strong>Oops, selected details doesn't exist (1).</strong></p></div><?php
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
			$gtabber_success = __('Selected record was successfully deleted. ('.$did.')', gtabber_UNIQUE_NAME);
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
    <h2><?php echo WP_gtabber_TITLE; ?><a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=wp-tabber-widget&amp;ac=add">Add New</a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".GTabberTable."` order by gtabber_id desc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/wp-tabber-widget/pages/setting.js"></script>
		<form name="frm_gtabber_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th class="check-column" scope="col" style="width:15px;"><input type="checkbox" name="gtabber_group_item[]" /></th>
			<th scope="col" width="70%">Text</th>
			<th scope="col">Tabber group</th>
            <th scope="col">Link target</th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <th class="check-column" scope="col" style="height:15px;"><input type="checkbox" name="gtabber_group_item[]" /></th>
			<th scope="col" width="70%">Text</th>
			<th scope="col">Tabber group</th>
            <th scope="col">Link target</th>
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
						<td><a href="<?php echo stripslashes($data['gtabber_link']); ?>" target="<?php echo stripslashes($data['gtabber_target']); ?>"><?php echo stripslashes($data['gtabber_text']); ?></a>
						<div class="row-actions">
							<span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=wp-tabber-widget&amp;ac=edit&amp;did=<?php echo $data['gtabber_id']; ?>">Edit</a> | </span>
							<span class="trash"><a onClick="javascript:gtabber_delete('<?php echo $data['gtabber_id']; ?>')" href="javascript:void(0);">Delete</a></span> 
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
				?><tr><td colspan="4" align="center">No records available.</td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('gtabber_form_show'); ?>
		<input type="hidden" name="frm_gtabber_display" value="yes"/>
      </form>	
	  <div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=wp-tabber-widget&amp;ac=add">Add New</a>
	  <!--<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=wp-tabber-widget&amp;ac=set">Plugin setting</a>-->
	  <a class="button add-new-h2" target="_blank" href="<?php echo WP_gtabber_FAV; ?>">Help</a>
	  </h2>
	  </div>
	  <div style="height:5px"></div>
	<h3>Plugin configuration option</h3>
	<ol>
		<li>Drag and drop the widget to your sidebar.</li>
	</ol>
	<p class="description"><?php echo WP_gtabber_LINK; ?></p>
	</div>
</div>