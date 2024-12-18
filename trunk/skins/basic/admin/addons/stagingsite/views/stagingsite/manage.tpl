{* $Id: manage.tpl 9517 2010-05-19 14:02:43Z klerik $ *}

{capture name="mainbox"}

<form action="{$index_script}" method="post" name="stagingsite" class="cm-form-highlight">
<input type="hidden" name="selected_section" value="{$selected_section}" />

{notes}
	<p>Staging site replication can be used to synchronise the content and data of this site with a copy of this site.<br><br> This is so that you can try things out on an exact replica of your site before changing your production site.</p>
{/notes}
<p>Select some items to synch with your staging site, enter a target path to deploy to and click "Deploy/Synch" button.</p>
<p>This process can take a long time to complete depending on the size of your database, the size of your images, the number and setup of addons you have installed.</p>


<fieldset>
	<div class="form-field">
		<label for="dbdump_tables">{$lang.select_tables_for_staging_site}:</label>
		<select name="dbdump_tables[]" id="dbdump_tables" multiple="multiple" size="12">
			{foreach from=$all_tables item=tbl}
				<option value="{$tbl}"{if $tbl|strpos:$smarty.const.TABLE_PREFIX === 0} selected="selected"{/if}>{$tbl}</option>
			{/foreach}
		</select>
		<p><a onclick="$('#dbdump_tables').selectOptions(true); return false;" class="underlined">{$lang.select_all}</a> / <a onclick="$('#dbdump_tables').selectOptions(false); return false;" class="underlined">{$lang.unselect_all}</a></p>
	</div>
	
	<div class="form-field">
		<label for="sandbox_copyImages">{$lang.sandbox_copyImages}:</label>
		<input type="checkbox" name="sandbox_copyImages" id="sandbox_copyImages" value="Y" disabled="true" checked="checked" class="checkbox" />
	</div>

	
	<div class="form-field">
		<input type="hidden" name="dbdump_compress" value="Y" />
		<input type="hidden" name="dbdump_schema" value="Y" />
		<input type="hidden" name="dbdump_data" value="Y" />	
	</div>
	
	<div class="form-field">
		<label for="sandbox_pathname">{$lang.sandbox_pathname}:</label>
		<p>{$stagingsite_cartPathName}
		/ <input type="text" name="sandbox_pathname" id="sandbox_pathname" size="6" value="sandbox" class="input-text" /> /</p>
	</div>
	
	<!--<div class="form-field" onClick="javascript: $('#databaseOptions').toggle('slow')">
		 <b>Advanced Options</b>  
	</div>-->
	<div id="databaseOptions" style="display: none">
	<!-- <p>This Cart's database user has CREATE/DROP/GRANT privileges on the server</p>  -->
	<div class="form-field">
		<label for="sandbox_dbName">{$lang.sandbox_dbName}:</label>
		<input type="text" name="sandbox_dbName" id="sandbox_dbName" size="6" value="" class="input-text" />
	</div>
	
	</div>
 	{literal}
	<script>
	if (true)
		  $('#databaseOptions').toggle('fast', function() {
		    // Animation complete.
		  });
	</script>
	{/literal} 
		
	<div class="form-field">
		<label for="sandbox_flushDir">{$lang.sandbox_flushDir}:</label>
		<input type="checkbox" name="sandbox_flushDir" id="sandbox_flushDir" value="Y"  class="checkbox" />
	</div>
</fieldset>

<div class="buttons-container buttons-bg">
	<div class="float-left">
		
		<div class="buttons-container">			
			{include file="buttons/button.tpl"
			but_text="Deploy/Synch staging site manually" 
			but_name="dispatch[stagingsite.createstagingsite]" 
			but_meta="" 
			but_role="action"}
			
			{include file="buttons/button.tpl"
			but_text="Create a staging site archive" 
			but_name="dispatch[stagingsite.createstagingsitearchive]" 
			but_meta="" 
			but_role="action"}
			
			{include file="buttons/button.tpl" 
			but_text="Generate a script with these settings" 
			but_name="dispatch[stagingsite.generatescheduledtaskscript]" 
			but_meta="" 
			but_role="action"}
		</div>

	</div>
</div>

</form>
{/capture}
{include file="common_templates/mainbox.tpl" title="Staging Site Replication" content=$smarty.capture.mainbox select_languages=true}
