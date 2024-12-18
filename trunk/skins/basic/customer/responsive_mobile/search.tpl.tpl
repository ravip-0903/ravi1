{* $Id: search.tpl 11501 2010-12-29 09:23:57Z klerik $ *}

<form action="{""|fn_url}" name="search_form" id="search_form" method="get" onsubmit="return check_blank()">
<input type="hidden"  id="search_for"/> <!--this is for checking which form is to search at client side-->
<input type="hidden" name="subcats" value="Y" />
<input type="hidden" name="status" value="A" />
<!--<input type="hidden" name="pshort" value="Y" />
<input type="hidden" name="pfull" value="Y" />-->
<input type="hidden" name="pname" value="Y" />
<input type="hidden" name="product_code" value="Y" />
<input type="hidden" name="match" value="all" />
<input type="hidden" name="pkeywords" value="Y" />
<input type="hidden" name="search_performed" value="Y" />
{hook name="search:additional_fields"}{/hook} 

{strip}

{if $smarty.session.changecatidtozero==1}
{assign var="selectcat" value="0"}
{else}
{assign var="selectcat" value=$smarty.request.cid}
{/if}
{if !$settings.General.search_objects}

<select	name="cid" id="cid" style="font:13px trebuchet ms;" class="search-selectbox float_left">
	<option	value="0">{$lang.all_categories}</option>
	{foreach from=0|fn_get_subcategories item="cat"}
	<option	value="{$cat.category_id}" {if $mode == "search" && $selectcat == $cat.category_id}selected="selected"{elseif $smarty.request.category_id == $cat.category_id}selected="selected"{/if}>{$cat.category|escape:html}</option>
	{/foreach}
</select>

{/if}

<div class="search-input">
<input type="text" id="q" name="q" value="{if $search.q}{$search.q}{else}Search for Item{/if}" onfocus="if(this.value=='Search for Item') this.value='';this.select();document.getElementById('search_for').value='top_search_bar'" class="float_left" onblur="if(this.value=='') this.value='Search for Item'" />
</div>

{if $settings.General.search_objects}
	{include file="buttons/go.tpl" but_name="search.results" alt=$lang.search}
{else}
	{include file="buttons/go.tpl" but_name="products.search" alt=$lang.search}
{/if}
{*{if !$hide_advanced_search}
<a href="{"products.search"|fn_url}" class="search-advanced">{$lang.advanced_search}</a>
{/if}*}
{/strip}

</form>
{literal}
<script>
  function check_blank()
  {
  	  var qitem = document.getElementById('q').value;
	  if(qitem=='Search for Item' || jQuery.trim(qitem)=='')
	  {
		  alert('Please Enter A Item');
		  return false;
	  }
	  else
	  {
		  return true;
	  }
  }

  jQuery(document).ready(function($){
  	
	$("#q").autocomplete({
		source: "autosuggest.php",
		minLength: 1,
		select: function(event, ui) {
	    if(ui.item){
	        $('#q').val(ui.item.value);
	        $('#cid').val($('#cid').val()); 
	    }
	    	$('#search_form').submit();
		}
	});
	
	$["ui"]["autocomplete"].prototype["_renderItem"] = function( ul, item) {
            return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( $( "<a></a>" ).html( item.label ) )
            .appendTo( ul );
        };

  });



</script>
{/literal}
