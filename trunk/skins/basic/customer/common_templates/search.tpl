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


{if $config.zettata_master_switch}
  <input type="hidden" name="z" id="z" value="{$smarty.request.z}"/>
{else}
   <input type="hidden" name="z" id= "z" value="0"/>
{/if}

{assign var="searchitem" value="Search for Item"}
{if !empty($smarty.request.company_id) && $config.merchant_search}
    {assign var="urlcompid" value=$smarty.request.company_id}
    <input type="hidden" name="company_id" value="{$urlcompid}" />
    {assign var="companyname" value=$urlcompid|fn_get_company_name}
    {assign var="searchitem" value="Search in "|cat:$companyname}    
{/if}

{hook name="search:additional_fields"}{/hook} 

{strip}

{if $smarty.session.changecatidtozero==1}
{assign var="selectcat" value="0"}
{else}
{assign var="selectcat" value=$smarty.request.cid}
{/if}
{if !$settings.General.search_objects}
    {if !empty($smarty.request.category_id) || !empty($smarty.request.cid)} 
        {if !empty($smarty.request.category_id)} 
            {assign var="urlcatid" value=$smarty.request.category_id}
         {else}
            {assign var="urlcatid" value=$smarty.request.cid} 
         {/if}
        {assign var="subcatname" value=$urlcatid|fn_get_subcategory_name}
        {if !empty($subcatname)}  {assign var="searchitem" value="Search in $subcatname"} {/if}
    {/if}



{/if}


<input class="search-input" type="text" id="q" name="q" placeholder="{$searchitem}" x-webkit-speech value="{$smarty.request.q}" />

{if $settings.General.search_objects}
	{include file="buttons/search_go.tpl" but_name="search.results" alt=$lang.search}
{else}
	{include file="buttons/search_go.tpl" but_name="products.search" alt=$lang.search}
{/if}

<select	name="cid" id="cid" class="search-selectbox no_mobile">
	{if !empty($subcatname)}             
            <option value="{$smarty.request.category_id}" selected="selected">{$subcatname}</option> 
        {/if}
        <option value="0">{$lang.all_categories}</option>
  {foreach from=0|fn_get_subcategories item="cat"}
            {if $urlcatid == $cat.category_id}
                {assign var="searchitem" value="Search in "|cat:$cat.category|escape:html}
            {/if}
  <option value="{$cat.category_id}" {if $mode == "search" && $selectcat == $cat.category_id}selected="selected"{elseif $smarty.request.category_id == $cat.category_id}selected="selected"{/if}>{$cat.category|escape:html}</option>
  {/foreach}
</select>
{*{if !$hide_advanced_search}
<a href="{"products.search"|fn_url}" class="search-advanced">{$lang.advanced_search}</a>
{/if}*}
{/strip}

</form>
{literal}
<script>

{/literal}{if $config.zettata_master_switch}{literal}var use_zettata = {/literal} {$config.zettata_master_switch};{else}{literal}var use_zettata =0;{/literal}{/if}{literal}

  function check_blank()
  {
  	 
     var qitem = document.getElementById('q').value;
	  if(qitem=='Search for Item' || jQuery.trim(qitem)=='')
	  {
		  //alert('Please Enter A Item');
      	  $('#q').focus();
		      return false;

    }else if($('#z').val()==''){

      if(use_zettata==1){

          var zet=search_source();    
      }else{
        
          var zet=0;
      }
      
      $('#z').val(zet);
      $('#q').val(qitem);
      document.getElementById("search_form").submit();

             
      }else{

        $('#q').val(qitem);
        document.getElementById("search_form").submit();
        
      }                   
   }
  


if(use_zettata==1){

  jQuery(document).ready(function($){
  var suggestion=new Array();

  $("#q").autocomplete({
    source:function(request,response){
        
        var field=$('#q').val();
       
      if( field in suggestion ) {
          
            response(suggestion[field]);

      }else{
        
          $.ajax({
              url: "autosuggest.php",
              type: 'get',
              dataType: 'json',
              data: {'term':field},
              success: function(output) {
                  //alert(JSON.stringify(output));
                   suggestion[field] = output;
                   response(output);
              }
          });
      }  
    },
    
    extraParams:{"cid":$("#cid").val()},
    minLength: 1,
    select: function(event, ui) {
      if(ui.item){
       
         var par=ui.item.value;
         $('#q').val(par);
          
          if(ui.item.id){
              var cid=ui.item.id;
              $('#cid').append('<option value='+cid+' selected="selected">All Categories</option>');
           }
       }
        check_blank();
    }
  });
  
  $["ui"]["autocomplete"].prototype["_renderItem"] = function( ul, item) {
            return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( $( "<a></a>" ).html( item.label ) )
            .appendTo( ul );
        };

  });
}else{

 jQuery(document).ready(function($){
  
  var suggestion=new Array();

  $("#q").autocomplete({

    source: function(request,response){
        
        var field=$('#q').val();
       
      if( field in suggestion ) {
          
            response(suggestion[field]);

      }else{
        
          $.ajax({
              url: "autosuggest.php",
              type: 'get',
              dataType: 'json',
              data: {'term':field},
              success: function(output) {
                  //alert(JSON.stringify(output));
                   suggestion[field] = output;
                   response(output);
              }
          });
      }  
    },
     
    extraParams:{"cid":$("#cid").val()},
    minLength: 1,
    select: function(event, ui) {
      
      if(ui.item){
          $('#q').val(ui.item.value);
          $('#cid').val($('#cid').val()); 
      }
        check_blank();
    }
  });
  
  $["ui"]["autocomplete"].prototype["_renderItem"] = function( ul, item) {
            return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( $( "<a></a>" ).html( item.label ) )
            .appendTo( ul );
        };

  });

}

function search_source(){

            var z='';
            var d = new Date();
            var cur_time=d.getTime();
            var val=cur_time%9;
            {/literal}{if $config.zettata_threshold}{literal}var zettata_threshold = {/literal} {$config.zettata_threshold};{else}{literal}var zettata_threshold =0;{/literal}{/if}{literal}
            if(val < zettata_threshold){
                 
                    z=1;

                }else{

                    z=0;
                }
    return z;
}

</script>
{/literal}
