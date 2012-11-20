<?php
/**
 *     Tabber widget plugin for wordpress
 *     Copyright (C) 2011 - 2013 www.gopiplus.com
 *     http://www.gopiplus.com/work/2012/11/10/tabber-widget-plugin-for-wordpress/
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?>

<div class="wrap">
  <?php
  	// Variable declaration
  	global $wpdb;
    $mainurl = get_option('siteurl')."/wp-admin/options-general.php?page=wp-tabber-widget/wp-tabber-widget.php";
    $DID = @$_GET["DID"];
    $AC = @$_GET["AC"];
    $submittext = "Insert Message";
	$gtabber_id_x = "";
	$gtabber_text_x = "";
	$gtabber_link_x = "";
	$gtabber_group_x = "";
	$gtabber_target_x = "";
	
	if($AC <> "DEL" and trim(@$_POST['gtabber_text']) <> "")
    {
			if($_POST['gtabber_id'] == "" )
			{
					$sql = "insert into ".GTabberTable.""
					. " set `gtabber_text` = '" . mysql_real_escape_string(trim($_POST['gtabber_text']))
					. "', `gtabber_link` = '" . mysql_real_escape_string(trim($_POST['gtabber_link']))
					. "', `gtabber_group` = '" . mysql_real_escape_string(trim($_POST['gtabber_group']))
					. "', `gtabber_target` = '" . mysql_real_escape_string(trim($_POST['gtabber_target']))
					. "'";	
			}
			else
			{
					$sql = "update ".GTabberTable.""
					. " set `gtabber_text` = '" . mysql_real_escape_string(trim($_POST['gtabber_text']))
					. "', `gtabber_link` = '" . mysql_real_escape_string(trim($_POST['gtabber_link']))
					. "', `gtabber_group` = '" . mysql_real_escape_string(trim($_POST['gtabber_group']))
					. "', `gtabber_target` = '" . mysql_real_escape_string(trim($_POST['gtabber_target']))
					. "' where `gtabber_id` = '" . $_POST['gtabber_id'] 
					. "'";	
			}
			$wpdb->get_results($sql);
    }
    
    if($AC=="DEL" && $DID > 0)
    {
        $wpdb->get_results("delete from ".GTabberTable." where gtabber_id=".$DID);
    }
    
    if($DID<>"" and $AC <> "DEL")
    {
        $data = $wpdb->get_results("select * from ".GTabberTable." where gtabber_id=$DID limit 1");
        if ( empty($data) ) 
        {
           echo "<div id='message' class='error'><p>No data available! use below form to create!</p></div>";
           return;
        }
        $data = $data[0];
		if ( !empty($data) )
		{
			$gtabber_id_x = htmlspecialchars(stripslashes($data->gtabber_id));
			$gtabber_text_x = htmlspecialchars(stripslashes($data->gtabber_text)); 
			$gtabber_link_x = htmlspecialchars(stripslashes($data->gtabber_link)); 
			$gtabber_group_x = htmlspecialchars(stripslashes($data->gtabber_group));
			$gtabber_target_x = htmlspecialchars(stripslashes($data->gtabber_target));
		}
        $submittext = "Update Message";
    }
    ?>
  <h2>Wp tabber widget</h2>
  <script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/wp-tabber-widget/setting.js"></script>
  <form name="gtabber_form" method="post" action="<?php echo $mainurl; ?>" onsubmit="return gtabber_submit()"  >
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="30">Select existing group (or) Enter new tabber group * :</td>
      </tr>
      <tr>
        <td>
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
		<input name="gtabber_group" type="text" id="gtabber_group" value="<?php echo @$gtabber_group_x; ?>" maxlength="25" size="30" /></td>
      </tr>
      <tr>
        <td height="30">Enter tabber text * :</td>
      </tr>
      <tr>
        <td><input name="gtabber_text" type="text" id="gtabber_text" value="<?php echo @$gtabber_text_x; ?>" maxlength="250" size="120" /></td>
      </tr>
      <tr>
        <td height="30">Enter tabber text link :</td>
      </tr>
      <tr>
        <td><input name="gtabber_link" type="text" id="gtabber_link" value="<?php echo @$gtabber_link_x; ?>" maxlength="250" size="120" /></td>
      </tr>
      <tr>
        <td height="30">Select link target :</td>
      </tr>
      <tr>
        <td><select style="width:130px;" name="gtabber_target" id="gtabber_target">
            <option value='_blank' <?php if(@$gtabber_target_x=='_blank') { echo 'selected' ; } ?>>_blank</option>
            <option value='_parent' <?php if(@$gtabber_target_x=='_parent') { echo 'selected' ; } ?>>_parent</option>
            <option value='_self' <?php if(@$gtabber_target_x=='_self') { echo 'selected' ; } ?>>_self</option>
            <option value='_top' <?php if(@$gtabber_target_x=='_parent') { echo 'selected' ; } ?>>_top</option>
          </select></td>
      </tr>
      <tr>
        <td height="40"><input name="publish" lang="publish" class="button-primary" value="<?php echo $submittext?>" type="submit" />
          <input name="publish" lang="publish" class="button-primary" onClick="gtabber_redirect()" value="Cancel" type="button" />
          <input name="Help" lang="publish" class="button-primary" onclick="gtabber_help()" value="Help" type="button" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
    <input name="gtabber_id" id="gtabber_id" type="hidden" value="<?php echo $gtabber_id_x; ?>">
  </form>
  <div class="tool-box">
    <?php
	$data = $wpdb->get_results("select * from ".GTabberTable." order by gtabber_group,gtabber_id");
	if ( !empty($data) ) 
	{
		?>
    <form name="frm_tabber_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th width="20%" align="left" scope="col">Tabber group
              </td>
            <th scope="col">Tabber text
              </td>
            <th width="8%" align="left" scope="col">Action
              </td>
          </tr>
        </thead>
        <?php 
			$i = 0;
			foreach ( $data as $data ) { 
			?>
        <tbody>
          <tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
            <td align="left" valign="middle"><?php echo(stripslashes($data->gtabber_group)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->gtabber_text)); ?></td>
            <td align="left" valign="middle"><a href="options-general.php?page=wp-tabber-widget/wp-tabber-widget.php&DID=<?php echo($data->gtabber_id); ?>">Edit</a> &nbsp; <a onClick="javascript:gtabber_delete('<?php echo($data->gtabber_id); ?>')" href="javascript:void(0);">Delete</a></td>
          </tr>
        </tbody>
        <?php $i = $i+1; } ?>
      </table>
    </form>
    <?php
	}
	else
	{
		// No data available
	}
	?>
    <br />
    Check official page for live demo and more information <a target="_blank" href="http://www.gopiplus.com/work/2012/11/10/tabber-widget-plugin-for-wordpress/">click here</a> </div>
</div>
