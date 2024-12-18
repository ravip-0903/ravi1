
{capture name="mainbox"}

<form action="{$index_script}" method="post" name="development_tools" class="cm-form-highlight">
<input type="hidden" name="selected_section" value="{$selected_section}" />

{notes}
	<p>Development Tools</p>
{/notes}


<div class="buttons-container buttons-bg">

	
	<div class="float-left">
		
		<div class="buttons-container">			
			{include file="buttons/button.tpl" 
			but_text="Smarty Caching Toggle" 
			but_name="dispatch[devtools.toggletplcaching]" 
			but_meta="" 
			but_role="action"}
			
			{include file="buttons/button.tpl" 
			but_text="Recompile Active Skins Now" 
			but_name="dispatch[devtools.compileskinsonce]" 
			but_meta="" 
			but_role="action"}
			
			{include file="buttons/button.tpl" 
			but_text="Run 10k users test" 
			but_name="dispatch[devtools.add10kuserstest]" 
			but_meta="" 
			but_role="action"}
	
		</div>

	</div>
</div>

</form>
{/capture}
{include file="common_templates/mainbox.tpl" title="Development Tools" content=$smarty.capture.mainbox select_languages=true}
