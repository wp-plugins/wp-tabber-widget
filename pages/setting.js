/**
 *     Tabber widget plugin for wordpress
 *     Copyright (C) 2012 - 2014 www.gopiplus.com
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

function gtabber_submit()
{
	if(document.gtabber_form.gtabber_group.value == "")
	{
		alert("Please select existing group (or) enter new tabber group.")
		document.gtabber_form.gtabber_group.focus();
		return false;
	}
	else if(document.gtabber_form.gtabber_text.value == "")
	{
		alert("Please enter tabber text.")
		document.gtabber_form.gtabber_text.focus();
		return false;
	}
}

function gtabber_delete(id)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_gtabber_display.action="options-general.php?page=wp-tabber-widget&ac=del&did="+id;
		document.frm_gtabber_display.submit();
	}
}	

function gtabber_group_load(val)
{
	if(val != "")
	{
		document.gtabber_form.gtabber_group.value = val;
	}
	else
	{
		document.gtabber_form.gtabber_group.value = "";
	}
}

function gtabber_redirect()
{
	window.location = "options-general.php?page=wp-tabber-widget";
}

function gtabber_help()
{
	window.open("http://www.gopiplus.com/work/2012/11/10/tabber-widget-plugin-for-wordpress/");
}