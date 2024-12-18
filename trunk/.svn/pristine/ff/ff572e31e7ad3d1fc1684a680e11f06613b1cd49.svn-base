{* $Id: view.tpl 12279 2011-04-17 14:36:39Z 2tl $ *}

{assign var="discussion" value=$object_id|fn_get_discussion:$object_type|fn_html_escape}

{if $discussion && $discussion.type != "D"}

<div id="content_discussion">

{if $controller == "discussion"}
    {if "CRB"|strpos:$discussion.type !== false && !$discussion.disable_adding}
    
    {include file="common_templates/subheader.tpl" title=$lang.new_post}
    
    <form action="{""|fn_url}" method="post" name="add_post_form">
    <input type ="hidden" name="post_data[thread_id]" value="{$discussion.thread_id}" />
    <input type ="hidden" name="redirect_url" value="{$config.current_url}" />
    <input type="hidden" name="selected_section" value="" />
    
    <div class="form-field">
        <label for="dsc_name" class="cm-required">{$lang.your_name}:</label>
        <input type="text" id="dsc_name" name="post_data[name]" value="{if $discussion.post_data.name}{$discussion.post_data.name}{/if}" size="50" class="input-text" />
    </div>
    
    {if $discussion.type == "R" || $discussion.type == "B"}
    <div class="form-field">
        <label for="dsc_rating" class="cm-required">{$lang.your_rating}:</label>
        
        <input type="hidden" name="post_data[rating_value]" id="dsc_rating" />
        {literal}
    <script type="text/javascript">
        $('#star').raty({
                    click: function(score, evt) {
                        alert('ID: ' + $(this).attr('id') + '\nscore: ' + score + '\nevent: ' + evt);
                    }
        });
    </script>
    {/literal}
    
        <div id="click"></div>
        <div id="star"></div>
    {literal}
    <script type="text/javascript">
    $(function() {
        $('#click').raty({
            click: function(score, evt) {
                $('#dsc_rating').val(score);
            }
        });
    });
    </script>
    {/literal}    
        
    </div>
    {/if}
    
    {hook name="discussion:add_post"}
    {if $discussion.type == "C" || $discussion.type == "B"}
    <div class="form-field">
        <label for="dsc_message" class="cm-required">{$lang.your_message}:</label>
        <textarea id="dsc_message" name="post_data[message]" class="input-textarea" rows="5" cols="72">{$discussion.post_data.message}</textarea>
    </div>
    {/if}
    {/hook}
    
    {if $settings.Image_verification.use_for_discussion == "Y"}
        {include file="common_templates/image_verification.tpl" id="discussion"}
    {/if}
    
    <div class="buttons-container">
        {include file="buttons/button.tpl" but_text=$lang.submit but_name="dispatch[discussion.add_post]"}
    </div>
    
    </form>
    
    {/if}
{/if}

{if $wrap == true}
    {capture name="content"}
    {include file="common_templates/subheader.tpl" title=$title}
{/if}

{if $controller == "companies" }

 {if empty($smarty.request.filter)}
     
    {assign var="posts" value=$discussion.thread_id|fn_get_discussion_posts:$smarty.request.page}

    {else}
    
    {assign var="posts" value=$discussion.thread_id|fn_get_discussion_posts:$smarty.request.page:'':'':$smarty.request.filter}
         
        {/if}
 {/if}
 
 {if $controller == "products"}
     
 {if empty($filter_val_pro)}
     
    {assign var="posts" value=$discussion.thread_id|fn_get_discussion_posts:$smarty.request.page}

    {else}
    
    {assign var="posts" value=$discussion.thread_id|fn_get_discussion_posts:$smarty.request.page:'':'':$filter_val_pro}

        {/if}
        
     {/if}   
        
{if $controller == "products" && $mode == "view"}
    {assign var="detail_rating" value=$product.product_id|fn_get_detail_rating:"P"}
    <div class="clearboth"></div>
    {literal}
    <script type="text/javascript">
    $(document).ready(function() {
    	$('.product_rating').ratingbar();
    });
	function expand_useful_pro_div(id,read)
	{
		if(read==1)
		{
			$('#pro_truncate_useful_'+id).toggle();
			$('#pro_full_useful_'+id).slideToggle();
		}
		else
		{
			$('#pro_full_useful_'+id).slideToggle(function(){
					$('#pro_truncate_useful_'+id).toggle();									   
														   });
			
		}
		
		
	}
	function expand_recent_pro_div(id,read)
	{
		if(read==1)
		{
			$('#pro_truncate_recent_'+id).toggle();
			$('#pro_full_recent_'+id).slideToggle();
		
		}
		else
		{
			$('#pro_full_recent_'+id).slideToggle(function(){
					$('#pro_truncate_recent_'+id).toggle();									   
														   });
			
		}
	}
    </script>
    {/literal}
    {assign var="recent_reviews" value=$product.product_id|fn_get_recent_reviews:'P'}
    {assign var="useful_reviews" value=$product.product_id|fn_get_useful_reviews:'P'}
    {assign var="average_rating_prod" value=$product.product_id|fn_get_average_rating:'P'}
    {assign var="avg_rating_cnt" value=$product.product_id|fn_get_average_rating_cnt:'P'}
    <div style="background:url(http://cdn.shopclues.com/images/skin/sep_line_review.gif) center bottom no-repeat; padding:0 0 25px; float:left; position:relative; {if empty($recent_reviews) && empty($useful_reviews) && !empty($avg_rating_cnt)} width:100%{/if}">
    
    {if $avg_rating_cnt > "0"}
    <div id="rating_bar" class="rating_bar" style="width:250px; padding-left:0;border:0;">
        <div class="rating_info">
            {include file="addons/discussion/views/discussion/components/average_rating.tpl" object_id=$product.product_id object_type="P" location="reviews"}
        </div>    
    
        <div class="rating_summary">
            <div class="ml_rating_summary"> 
        		 <div class="ml_rating_summary_fieldname"><a href="{"products.view&product_id=`$smarty.request.product_id`"|fn_url}?filter=5">5 Star</a></div>	 
                 {assign var="rating_for_ind" value=$detail_rating.5*100/$avg_rating_cnt}             
                 <div class="product_rating">{$rating_for_ind|default:"0"}</div>
                 <div class="product_ratingabout">{$detail_rating.5}</div> 
        	</div>
        	<div class="ml_rating_summary">
        		 <div class="ml_rating_summary_fieldname"><a href="{"products.view&product_id=`$smarty.request.product_id`"|fn_url}?filter=4">4 Star</a></div>
        		 {assign var="rating_for_ind" value=$detail_rating.4*100/$avg_rating_cnt}             
                 <div class="product_rating">{$rating_for_ind|default:"0"}</div>
                 <div class="product_ratingabout">{$detail_rating.4}</div>
        	</div>
            <div class="ml_rating_summary">
        		 <div class="ml_rating_summary_fieldname"><a href="{"products.view&product_id=`$smarty.request.product_id`"|fn_url}?filter=3">3 Star</a></div>
        		 {assign var="rating_for_ind" value=$detail_rating.3*100/$avg_rating_cnt}             
                 <div class="product_rating">{$rating_for_ind|default:"0"}</div>
                 <div class="product_ratingabout">{$detail_rating.3}</div>
        	</div>
            <div class="ml_rating_summary">
        		 <div class="ml_rating_summary_fieldname"><a href="{"products.view&product_id=`$smarty.request.product_id`"|fn_url}?filter=2">2 Star</a></div>
        		 {assign var="rating_for_ind" value=$detail_rating.2*100/$avg_rating_cnt}             
                 <div class="product_rating">{$rating_for_ind|default:"0"}</div> 
                 <div class="product_ratingabout">{$detail_rating.2}</div>
        	</div>
            <div class="ml_rating_summary">
        		 <div class="ml_rating_summary_fieldname"><a href="{"products.view&product_id=`$smarty.request.product_id`"|fn_url}?filter=1">1 Star</a></div>
        		 {assign var="rating_for_ind" value=$detail_rating.1*100/$avg_rating_cnt}             
                 <div class="product_rating">{$rating_for_ind|default:"0"}</div>
                 <div class="product_ratingabout">{$detail_rating.1}</div>
        	</div>
        </div>
         <div style="clear:both;"></div>

        {assign var="new_review_enabled" value=""|is_review_enabled}

        {if $new_review_enabled}
            {assign var="review_url" value="index.php?dispatch=review.review&product_id=`$smarty.request.product_id`"}
            <!--a class="write_new_review" href="{$review_url}">{$lang.write_areivew}</a-->
            <a class="fb-popup-login_new write_new_review" rev="{$review_url}" href='index.php?dispatch=auth.fb_login'>{$lang.write_areivew}</a>
        {else}
            <a class="write_new_review" href="{"products.view&product_id=`$product.product_id`"|fn_url}#write_new_review" title="Write a Review">{$lang.write_areivew}</a>
        {/if}
    </div>
    <!--added by ankur for the new design-->
   
     
     {if !empty($recent_reviews)}
        <div  class="rating_bar">
          <h2 class="subtitle">{$lang.most_recent_reviews}</h2>
          <div>
            
            {assign var="count" value=0}
             {foreach from=$recent_reviews item="review"}
             {assign var="count" value=$count+1}
             <div>
                  <div class="margin_top_5px" style="text-transform:uppercase;">{$review.name|escape}{if $review.thread_id==0}<span class="rev_prd_pj2_certi_user">Certified Buyer</span>{/if}</div>
                  {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$review.rating|fn_get_discussion_rating}
                  <span>{$review.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</span>
             </div>
              <div id="pro_truncate_recent_{$count}" class="pj2_prduct_review_text">
                    {$review.review|truncate:50:"...."}
                    {if $review.review|strlen >50}
                        <div class="pj2_review_more"><a href="javascript:void(0)" onclick="expand_recent_pro_div('{$count}',1)">Read More</a></div>
                    {/if}
               </div>
               <div id="pro_full_recent_{$count}" class="pj2_prduct_review_text" style="float:left; display:none">
                    {$review.review}
                    <div style="float:right"><a href="javascript:void(0)" onclick="expand_recent_pro_div('{$count}',2)">Go Back</a></div>
               </div>
               <div class="clearboth"></div>
              
             {/foreach}
          
          </div>
        </div>
     {/if}
    
    {if !empty($useful_reviews)}
        <div  class="rating_bar">
         <h2 class="subtitle">{$lang.most_useful_reviews}</h2>
          <div>
             {assign var="count" value=0}
             {foreach from=$useful_reviews item="review"}
             {assign var="count" value=$count+1}
              <div>
              <div>
                  <div class="margin_top_5px" style="text-transform:uppercase;">{$review.name|escape}{if $review.thread_id==0}<span class="rev_prd_pj2_certi_user">Certified Buyer</span>{/if}</div>
                  {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$review.rating|fn_get_discussion_rating}
                  <span>{$review.date|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</span>
                </div>
               <div id="pro_truncate_useful_{$count}" class="pj2_prduct_review_text">
                {$review.review|truncate:50:"...."}
                {if $review.review|strlen >50}
                	<div class="pj2_review_more"><a href="javascript:void(0)" onclick="expand_useful_pro_div('{$count}',1)">Read More</a></div>
                {/if}
               </div>
               <div id="pro_full_useful_{$count}" class="pj2_prduct_review_text" style="float:left; display:none">
               	{$review.review}
                <div style="float:right"><a href="javascript:void(0)" onclick="expand_useful_pro_div('{$count}',2)">Go Back</a></div>
               </div>
               <div class="clearboth"></div>
               
              </div>
             {/foreach}
          
          </div>
        </div>
    {/if}
    <!--end-->
    <div class="clearboth"></div>
    {/if}
    </div>
{/if}
<!-- Code by ankur for the new design on merchant page  -->
{if $controller == "companies" && $mode == "view"}
   {assign var="detail_rating" value=$smarty.request.company_id|fn_get_detail_rating:"M"}
   <div class="clearboth"></div>
    {literal}
    <script type="text/javascript">
    $(document).ready(function() {
    	$('.merchant_rating_bar').ratingbar();
    });
	function expand_useful_div(id,read)
	{
		if(read==1)
		{
			$('#truncate_useful_'+id).toggle();
			$('#full_useful_'+id).slideToggle();
			
		}
		else
		{
			$('#full_useful_'+id).slideToggle(function(){
					$('#truncate_useful_'+id).toggle(); });
		}
		
	}
	function expand_recent_div(id,read)
	{
		if(read==1)
		{
			$('#truncate_recent_'+id).toggle();
			$('#full_recent_'+id).slideToggle();
		}
		else
		{
			$('#full_recent_'+id).slideToggle(function(){
					$('#truncate_recent_'+id).toggle();							   
													   });
		}
		
		
	}
    </script>
    {/literal}

    {assign var="recent_reviews" value=$smarty.request.company_id|fn_get_recent_reviews:'M'}
    {assign var="useful_reviews" value=$smarty.request.company_id|fn_get_useful_reviews:'M'}
    
    <div style="background:url(http://cdn.shopclues.com/images/skin/sep_line_review.gif) center bottom no-repeat; padding:0 0 25px; float:left;{if empty($recent_reviews) && empty($useful_reviews)} width:100%{/if}">
	<div style="float:left;">  

    {assign var="avg_rating_cnt" value=$smarty.request.company_id|fn_get_average_rating_cnt:'M'}
     {if $avg_rating_cnt > "0"}
        
      <div id="rating_bar_merchant" class="rating_bar_merchant border_none mer_rev_distr">
          <div class="subheader_nl">{$lang.merchant_rating_distribution}</div>
        <div class="rating_info">
            {include file="addons/discussion/views/discussion/components/average_rating.tpl" object_id=$smarty.request.company_id object_type="M" location="reviews"}
        </div>    
    	
        <div class="rating_summary">
            <div class="ml_rating_summary"> 
        		 <div class="ml_rating_summary_fieldname"><a href="{"companies.view&company_id=`$smarty.request.company_id`"|fn_url}?filter=5">5 Star</a></div>
        	 	 
                 {assign var="rating_for_ind" value=$detail_rating.5*100/$avg_rating_cnt}
                 
                 <div class="merchant_rating_bar">{$rating_for_ind|default:"0"}</div>
                 
                 <div class="merchant_rating_barabout">{$detail_rating.5}</div> 
        	</div>
        	<div class="ml_rating_summary">
        		 <div class="ml_rating_summary_fieldname"><a href="{"companies.view&company_id=`$smarty.request.company_id`"|fn_url}?filter=4">4 Star</a></div>
        		 {assign var="rating_for_ind" value=$detail_rating.4*100/$avg_rating_cnt}             
                 <div class="merchant_rating_bar">{$rating_for_ind|default:"0"}</div>
                 <div class="merchant_rating_barabout">{$detail_rating.4}</div>
        	</div>
            <div class="ml_rating_summary">
        		 <div class="ml_rating_summary_fieldname"><a href="{"companies.view&company_id=`$smarty.request.company_id`"|fn_url}?filter=3">3 Star</a></div>
        		 {assign var="rating_for_ind" value=$detail_rating.3*100/$avg_rating_cnt}             
                 <div class="merchant_rating_bar">{$rating_for_ind|default:"0"}</div>
                 <div class="merchant_rating_barabout">{$detail_rating.3}</div>
        	</div>
            <div class="ml_rating_summary">
        		 <div class="ml_rating_summary_fieldname"><a href="{"companies.view&company_id=`$smarty.request.company_id`"|fn_url}?filter=2">2 Star</a></div>
        		 {assign var="rating_for_ind" value=$detail_rating.2*100/$avg_rating_cnt}             
                 <div class="merchant_rating_bar">{$rating_for_ind|default:"0"}</div> 
                 <div class="merchant_rating_barabout">{$detail_rating.2}</div>
        	</div>
            <div class="ml_rating_summary">
        		 <div class="ml_rating_summary_fieldname"><a href="{"companies.view&company_id=`$smarty.request.company_id`"|fn_url}?filter=1">1 Star</a></div>
        		 {assign var="rating_for_ind" value=$detail_rating.1*100/$avg_rating_cnt}             
                 <div class="merchant_rating_bar">{$rating_for_ind|default:"0"}</div>
                 <div class="merchant_rating_barabout">{$detail_rating.1}</div>
        	</div>
        </div>
      </div>
    
     {if $controller == "companies" && $mode == "view"}
         
     {if !empty($smarty.request.company_id)}
         
     {assign var="tot_avg_rate" value=$smarty.request.company_id|fn_get_avg_merchant_review_rating}
     
     
     <div class="box_reviewdetails mer_dtl_rat_nl">
         <div class="subheader_nl">{$lang.detailed_merchant_ratings}</div>
     	{$lang.rating_header_lang}&nbsp;{$tot_avg_rate.0.avg|number_format:2}
     <div class="box_reviewdetails_starrating">
       <!-- <div class="box_reviewdetails_starrating_heading">{$lang.detailed_merchant_rating} <span>Total rating: {$tot_avg_rate.0.avg|number_format:2}</span> </div> -->

       <div class="box_negativerating">
        <div class="box_negativerating_heading">Shipping Time :</div>
           <div class="box_negativerating_stars">
               
                {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$tot_avg_rate.1.shipping_time}
                
                <span class="avg_rat">{$tot_avg_rate.0.shipping_time|number_format:1} </span>
                
                </div>
            </div>
        <div class="box_negativerating">
        <div class="box_negativerating_heading">Shipping Cost :</div>
        <div class="box_negativerating_stars">
        
                
     {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$tot_avg_rate.1.shipping_cost} 
     
      <span class="avg_rat">  {$tot_avg_rate.0.shipping_cost|number_format:1} </span>
                
        </div>
        </div>
        <div class="box_negativerating">
        <div class="box_negativerating_heading">Product Quality :</div>
        <div class="box_negativerating_stars">
                           
          {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$tot_avg_rate.1.product_quality} 
     
          
             <span class="avg_rat">{$tot_avg_rate.0.product_quality|number_format:1}</span>
                
        </div>
        </div>
        <div class="box_negativerating">
        <div class="box_negativerating_heading">Value For Money :</div>
        <div class="box_negativerating_stars">
        
        {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$tot_avg_rate.1.value_for_money} 
     
        <span class="avg_rat">{$tot_avg_rate.0.value_for_money|number_format:1} </span>
        </div>
        </div>


        </div>
         {$lang.rating_footer_val}
        </div>
     
 {/if}  
 </div>
 {/if}
 <div class="clearboth"></div>
      {if !empty($recent_reviews)}
      <div class="subheader" style="clear:both; padding-top:25px;">{$lang.merchant_review_summary}</div>
      <div  class="rating_bar_merchant border_none nl_rat_mer margin_top_none" style="padding-left:0;">
      <h2 class="subtitle">{$lang.most_recent_reviews}</h2>
      <div>
         {assign var="count" value=0}
         {foreach from=$recent_reviews item="review"}
         {assign var="count" value=$count+1}
          <div>
                  <div class="margin_top_5px" style="text-transform:uppercase;">{$review.name|escape}{if $review.thread_id==0}<span class="rev_prd_pj2_certi_user">Certified Buyer</span>{/if}</div>
                  {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$review.rating|fn_get_discussion_rating}
                  <span>{$review.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</span>
          </div>
          <div id="truncate_recent_{$count}"  class="pj2_prduct_review_text">
                {$review.review|truncate:80:"...."}
                {if $review.review|strlen >80}
                	<div class="pj2_review_more"><a href="javascript:void(0)" onclick="expand_recent_div('{$count}')">Read More</a></div>
                {/if}
           </div>
           <div id="full_recent_{$count}" class="pj2_prduct_review_text" style="float:left; display:none">
               	{$review.review}
                <div style="float:right"><a href="javascript:void(0)" onclick="expand_recent_div('{$count}')">Go Back</a></div>
           </div>
           <div class="clearboth"></div>
          
         {/foreach}
      
      </div>
      </div>
      {/if}
       
       {if !empty($useful_reviews)}
        <div  class="rating_bar_merchant nl_rat_mer margin_top_none">
          <h2 class="subtitle">{$lang.most_useful_reviews}</h2>
          <div>
            {assign var="count" value=0}
             {foreach from=$useful_reviews item="review"}
             {assign var="count" value=$count+1}
              <div>
              <div>
                  <div class="margin_top_5px" style="text-transform:uppercase;">{$review.name|escape}{if $review.thread_id==0}<span class="rev_prd_pj2_certi_user">Certified Buyer</span>{/if}</div>
                  {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$review.rating|fn_get_discussion_rating}
                  <span>{$review.date|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</span>
              </div>
               <div id="truncate_useful_{$count}" class="pj2_prduct_review_text">
                {$review.review|truncate:80:"...."}
                {if $review.review|strlen >80}
                	<div class="pj2_review_more"><a href="javascript:void(0)" onclick="expand_useful_div('{$count}')">Read More</a></div>
                {/if}
               </div>
               <div id="full_useful_{$count}" class="pj2_prduct_review_text" style="float:left; display:none">
               	{$review.review}
                <div style="display:inline"><a href="javascript:void(0)" onclick="expand_useful_div('{$count}')">Go Back</a></div>
               </div>
               <div class="clearboth"></div>
               
              </div>
             {/foreach}
          
          </div>
        </div>
       {/if}
       <div class="clearboth"></div>
      {/if}
    </div>  
{/if}
<!-- End -->
<!-- Done by sapna to show expert review -->
 {if $controller != 'companies'}
{assign var=expert_review value=$product.product_id|fn_get_expert_review}
{if !empty($expert_review) }
{$lang.expert_review}
<div style="background:url(http://cdn.shopclues.com/images/skin/sep_line_review.gif) center bottom no-repeat; padding:0 0 25px; display:block; position:relative; float: left;">
<div class="expert_review_section">
<div style="float:left">{$lang.logo_for_exprt_revw}</div>
<div style="flaot:left; margin-top:10px; font: bold 15px trebuchet ms; text-transform: uppercase; display:inline-block;">{$expert_review.title}</div>
<div>{$expert_review.review}</div>
</div>
</div>
{/if}
{/if}
{if !empty($smarty.request.company_id)}<div class="subheader" style="clear:both;">{$lang.detailed_merchant_review}</div>{/if}
{if $posts}

{include file="common_templates/pagination.tpl" id="pagination_contents_comments_`$object_id`"}
{foreach from=$posts item=post}
{if $controller=="discussion" && $mode=="view"}

    <div style="border:1px solid #e5e5e5; border-radius:5px; float:left; width:96%; padding:10px;  margin-top:10px;">
    <div class="float_left" style="width:150px; background-color:#eee; border-radius:5px; padding:5px; margin-right:10px;">
    <div class="float_left" style="width:100%; font:15px trebuchet ms; color:#4cc3ff;">{$post.name|escape} </div>
    <div class="float_left" style="width:100%; font:11px verdana; color:#949799;">{$post.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</div>
    </div>
    <p style="width:100%; font:13px trebuchet ms; line-height:20px; color:#636566; margin:0px; padding:0px;">
    {assign var="mess" value=$post.message|escape|nl2br}
        {if $post.thread_id==0}
        {assign var="messarr" value="hprconcatsc"|explode:$post.message}
        {assign var="mess" value= $messarr[0]|cat:'-'|cat:$messarr[1]}
        {/if}
        {if $discussion.type == "C" || $discussion.type == "B"}
        
        "{$mess}"
        
        {/if}
    </p>
    </div>


{else}

    <div class="posts{cycle values=", manage-post"} pj2_post" id="post_{$post.post_id}">
    
    {hook name="discussion:items_list_row"}
    <!--Product Reviews -->
    <div class="box_productreview" {if isset($smarty.request.company_id)}  style="margin-bottom:0px;" {/if}>
    
    
    <div class="box_productreview_details" {if isset($smarty.request.company_id)} style="width:100%;" {/if}>
    
    <div class="box_productreview_details_header">
    <div class="box_productreview_details_header_username">
    <div class="prd_review_username">{$post.name|escape} </div>
    <span class="box_productreview_details_header_username_updatetime">
    
    {$post.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}
    
    </span>
    
    {if $post.thread_id==0}
    <span class="box_productreview_details_header_username_certified prd_pj2_certi_user">
    Certified Buyer
    </span>
    {/if}
    
    <!--<div class="clearboth"></div>-->
    
    </div>
    <div class="clearboth"></div>
    <div class="box_productreview_details_header_starrating">
    <div class="box_RatingSmall">
    
    <div class="box_RatingSmall_star">
    {if $controller=='products'}
    {if $discussion.type == "R" || $discussion.type == "B"}
    {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$post.rating_value|fn_get_discussion_rating}
    {/if}
    {elseif $controller=='companies'}
     {assign var="rating" value=$post.post_id|fn_get_merchant_review_rating:$post.thread_id}
     {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$rating|fn_get_discussion_rating}
    {/if}
    </div>
    </div>
    </div>
    </div>
  
    <div class="box_productreview_details_reviewtext">
    
    {assign var="mess" value=$post.message|escape|nl2br}
        {if $post.thread_id==0}
           {assign var="messarr" value="hprconcatsc"|explode:$post.message}
          {if $messarr[0]!=''} 
           {assign var="mess" value= $messarr[0]|cat:'-'|cat:$messarr[1]}
          {else}
           {assign var="mess" value=$messarr[1]}
          {/if}
           
        {/if}
        {if $discussion.type == "C" || $discussion.type == "B"}
        
        "{$mess}"
        {if $controller =='companies' && $mode =='view'}
        {assign var="replies" value=$post.post_id|fn_vendor_review_replies}
        {foreach from=$replies item="reply"}
            <div style="margin:5px 10px;"> 
                <span class="rep_time_mer" style="font-size:11px;">
                    {if $reply.reply_direction == "M2C"}{$lang.merchant_comment} ({$reply.time_created|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}): {$reply.message}</span>
                        {elseif $reply.reply_direction == "C2M"}
                          {$reply.reply_from} ({$reply.time_created|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}): {$reply.message}</span>  
                        {/if}
    
          </div> 
        {/foreach}
        
        {/if}
        
        {/if}
                
    </div>
    <!--added by ankur for the new design-->
    <div class="pj2_useful_review" id="useful_bar_{$post.post_id}">
    <div class="pj2_useful_review_text">{$lang.is_review_useful}</div>
    <div id="but_{$post.post_id}">
     <input type="button" id="yes_useful_{$post.post_id}" value="Yes" onclick="useful_count('{$post.post_id}','yes','{$controller}','{$obj_id}')" /> <input type="button" id="no_useful_{$post.post_id}" value="No" onclick="useful_count('{$post.post_id}','no','{$controller}','{$obj_id}')" /></div>
     <div id="wait_{$post.post_id}" style="display:none">Please Wait.....</div>
    </div>
    <div class="useful_review_declaration"id="useful_bar_msg_{$post.post_id}" style="display:none">
     {$lang.useful_bar_msg|escape}
    </div>
     <!-- end -->
    <div>
    {if $controller=='products'}
    {assign var="obj_id" value=$product.product_id}
    {else}
    {assign var="obj_id" value=$smarty.request.company_id}
    {/if}
    
    </div>
    {if $post.thread_id==0}
    <div class="box_productreview_details_reviewvideo">
    {if $messarr[2] != ''}
    <div class="box_productreview_details_reviewvideo_fieldname">Product Video URL:</div>
    
    <div class="box_productreview_details_reviewvideo_field">
    
            
            
                <a href="{$messarr[2]}">{$messarr[2]}</a>
            
            
        
    </div>
    {/if}
    </div>
    
    {/if}
    
    </div>
    {if !isset($smarty.request.company_id)}
     {if $post.thread_id==0}
        {if $messarr[3] != ''}
        <div class="box_productreview_image">
            <img src="images/reviews/{$messarr[3]}" width="100" />
        </div>
        {/if}
      {/if}  
    {/if}
    
    
    
    </div>
    <!--End Product Reviews -->
    {/hook}
    <div class="clearboth"></div>
    </div>

{/if}

{/foreach}
{if $object_type == "E" && $current_post_id}
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){$ldelim}
	if ($('#post_' + {$current_post_id}).length) {$ldelim}
		jQuery.scrollToElm($('#post_' + {$current_post_id}));
	{$rdelim}
{$rdelim});
//]]>
</script>
{/if}
{include file="common_templates/pagination.tpl" id="pagination_contents_comments_`$object_id`"}
{else}
<p class="no-items">{$lang.no_posts_found}</p>
{/if}

{if $controller != "discussion"}
{if "CRB"|strpos:$discussion.type !== false && !$discussion.disable_adding}
    {assign var="new_review_enabled" value=""|is_review_enabled}
    {if !$new_review_enabled}
        {include file="common_templates/subheader.tpl" title=$lang.new_post}

         {* Anonymous review and rating setting *}

         {assign var="is_allowed" value=""|fn_user_rating_status}

         {if !$is_allowed || $is_allowed!=3}

        <form class="review-form" action="{""|fn_url}" method="post" name="add_post_form" id="add_post_form">
        <input type ="hidden" name="post_data[thread_id]" id="thread_id" value="{$discussion.thread_id}" />
        <input type ="hidden" name="redirect_url" value="{$config.current_url}" />
        <input type="hidden" name="selected_section" value="" />
        <!--Form Small -->
        <div class="form_onecolumnsmall">
        <div class="form_onecolumnsmall_row">
        <div class="form_onecolumnsmall_fieldname"><label for="dsc_name" class="cm-required">{$lang.your_name}:</label></div>
        <div class="form_onecolumnsmall_field">
        <input class="form_onecolumnsmall_field_textbox" type="text" id="dsc_name" name="post_data[name]" value="{if !empty($smarty.session.auth.user_id)} {$smarty.session.cart.user_data.firstname|cat:' '|cat:$smarty.session.cart.user_data.lastname} {elseif $discussion.post_data.name}{$discussion.post_data.name}{/if}" size="50" />
        </div>
        </div>
        {if $discussion.type == "R" || $discussion.type == "B"}
        <div class="form_onecolumnsmall_row">
        <div class="form_onecolumnsmall_fieldname"><label for="dsc_rating" class="cm-required">{$lang.your_rating}:</label></div>
        <div class="form_onecolumnsmall_field">
        <input type="hidden" name="post_data[rating_value]" id="dsc_rating" />
        {literal}
        <script type="text/javascript">
            $('#star').raty({
                        click: function(score, evt) {
                            alert('ID: ' + $(this).attr('id') + '\nscore: ' + score + '\nevent: ' + evt);
                        }
            });
        </script>
        {/literal}
        <div id="click"></div>
        <div id="star"></div>
        {literal}
        <script type="text/javascript">
        $(function() {
            $('#click').raty({
                click: function(score, evt) {
                    $('#dsc_rating').val(score);
                }
            });
        });
        </script>
        {/literal}

        </div>
        </div>
        {/if}

        {hook name="discussion:add_post"}
        {if $discussion.type == "C" || $discussion.type == "B"}
        <div class="form_onecolumnsmall_row">
        <div class="form_onecolumnsmall_fieldname"><label for="dsc_message" class="cm-required">{$lang.your_message}:</label></div>
        <div class="form_onecolumnsmall_field">
        <textarea class="form_onecolumnsmall_field_textarea" id="dsc_message" name="post_data[message]" rows="5" cols="72">{$discussion.post_data.message}</textarea>
        </div>
        </div>
        {/if}
        {/hook}

        </div>
        <!--End Form Small -->

        {*{if $settings.Image_verification.use_for_discussion == "Y"}
            {include file="common_templates/image_verification.tpl" id="discussion"}
        {/if}
        *}
        <div id="captcha_value" style="float: left; padding: 15px 0 0 280px; margin:0;"></div>
        <div id="captcha_error" style="clear:both; float: left; padding: 0px 0 0 280px; margin:0; width:320px;"></div>
        {literal}
        <script type="text/javascript">
            $(function(){
                show_numbers('post_data');
            });
        </script>
        {/literal}

        <div class="box_functions">
        <div class="buttons-container">
            {include file="buttons/button.tpl" but_text=$lang.submit but_name="dispatch[discussion.add_post]"}
        </div>
        </div>

        </form>
    {/if}
        
{else}
        {assign var="new_review_enabled" value=""|is_review_enabled}
        {if $new_review_enabled}
            {assign var="review_url" value="index.php?dispatch=review.review&product_id=`$smarty.request.product_id`"}
            <!--a href="{$review_url}">{$lang.write_areivew}</a-->
            <a class="fb-popup-login_new write_new_review_two" rev="{$review_url}" href='index.php?dispatch=auth.fb_login'>{$lang.write_areivew}</a>

        {else}
            <a href='index.php?dispatch=auth.login_form&return_url={"products.view&product_id=`$smarty.request.product_id`"|fn_url|urlencode}'>{$lang.login_to_comment}</a>
        {/if}
    
{/if} 

<div id="review_result" style="display:none; border-radius: 5px 5px 5px 5px;float: right; text-align:right; font-weight: bold; margin: 0 0 0 270px; padding: 5px 0; width: 341px;"></div>
{/if}
{/if}

{if $wrap == true}
	{/capture}
	{include file="common_templates/group.tpl" content=$smarty.capture.content}
{else}
	{capture name="mainbox_title"}{$title}{/capture}
{/if}
</div>
{/if}

{literal}
<script type="text/javascript">
function useful_count(id,call_type,controller,obj_id)
{
   $('#but_'+id).hide();
  $('#wait_'+id).show();
  if(controller=='products')
  {
	  dispatch='products.useful_count';
	  obj_type='P';
  }
  else
  {
	  dispatch='companies.useful_count';
	  obj_type='M';
  }
  $.ajax({
	  type: "GET",
	  url: "index.php",
	  data: { dispatch:dispatch,id:id,call_type:call_type,obj_type:obj_type,obj_id:obj_id}
	  }).done(function( msg ) {
		 if(msg==1)
		 {
			 $('#wait_'+id).hide();
			 $('#useful_bar_'+id).hide();
			 $('#useful_bar_msg_'+id).show();
		 }
		 else
		 {
			 $('#but_'+id).show();
			 $('#wait_'+id).hide();
			 alert('Error,Please Try Again');
		 }
		  });
}


function show_numbers(field_arr){
	var captcha_number = Math.floor((Math.random()*1000000)+1);
	var captcha_text = {/literal}'<p>{$lang.enter_the_text_for_captcha} </p>'{literal};
	var captcha_text = captcha_text + '<label for="verification_code" class="cm-required captcha_lbl" style="float:left; background:#CCC; cursor:pointer; height:26px; text-align:center; color:#000; display:block; padding:0 5px; width:63px; margin-right:10px; font:bold 13px/26px trebuchet ms; border:1px solid #666; margin-left:0px;" onclick="show_numbers(\''+field_arr+'\')">' + captcha_number + '</label>';
	var captcha_text = captcha_text + '<input type="hidden" id="captcha_number" name="'+field_arr+'[captcha_number]" value="'+captcha_number+'" />';
	var captcha_text = captcha_text + '<input type="text" class="form_onecolumnsmall_field_textbox round_five profile_detail_field" style="width:70px;" id="verification_code" name="'+field_arr+'[verification_code]" value="" />';
	$('#captcha_value').html(captcha_text);
}

function check_captcha(){
	var captcha_number = $('#captcha_number').val();
	var verification_code = $('#verification_code').val();	
	if(captcha_number != verification_code){
		show_numbers('post_data');
		$('#captcha_error').html('<span style="color:red;">{/literal}{$lang.verification_code_not_same}{literal}</span>');
		return false;
	}else{
		return true;
	}
}

$('#add_post_form').submit(function(){
	var ip=$('#sc_uses').val();
	var name 				= $('#dsc_name').val();
	if (name == "") {
      $("#dsc_name").focus();
      return false;
    }
	var rating 				= $('#dsc_rating').val();
	if (rating == "") {
      $("#dsc_rating").focus();
      return false;
    }
	var message 			= $('#dsc_message').val();
	if (message == "") {
      $("#dsc_message").focus();
      return false;
    }
	var verification_code 	= $('#verification_code').val();
	if (verification_code == "") {
      $("#verification_code").focus();
      return false;
    }else{
		if(check_captcha()){			
		}else{
			return false;	
		}
	}
	
	var thread_id = $('#thread_id').val();
	$('#review_result').html('{/literal}{$lang.please_wait_for_captcha}{literal}');
	$('#review_result').css('display','block');
	$('#captcha_error').html('');
	$.ajax({
      type: "POST",
      url: "index.php",
      data: { dispatch: 'discussion.add_cpost', name:name, message:message, rating_value:rating, thread_id:thread_id, captcha_number:$('#captcha_number').val(), verification_code:$('#verification_code').val(),ip:ip},
      success: function(msg) {
       	if(msg == 'done'){
			var success_message = '<span style="color:green;">{/literal}{$lang.thanks_for_your_review}{literal}</span>';
			$('#review_result').html(success_message);
			$('#review_result').fadeIn(5000);	
			$('#review_result').fadeOut(5000);	
			$('#dsc_name').val('');
			$('#dsc_rating').val('');
			$('#dsc_message').val('');
			$('#verification_code').val('');
			show_numbers('post_data');			
		}else if(msg == 'failed'){
			var fail_message = '<span style="color:red;">{/literal}{$lang.some_error_occured}{literal}</span>';
			$('#review_result').html(fail_message);
			$('#review_result').fadeIn(5000);	
			$('#review_result').fadeOut(5000);
		}else if(msg == 'captcha_error'){
			var fail_message = '<span style="color:red;">{/literal}{$lang.captcha_error}{literal}</span>';
			$('#review_result').html(fail_message);
			$('#review_result').fadeIn(5000);	
			$('#review_result').fadeOut(5000);
		}else if(msg == 'captcha_code_missing'){
			var fail_message = '<span style="color:red;">{/literal}{$lang.captcha_code_missing}{literal}</span>';
			$('#review_result').html(fail_message);
			$('#review_result').fadeIn(5000);	
			$('#review_result').fadeOut(5000);
		}else if(msg == 'character_error'){
                    var fail_message = '<span style="color:red;">{/literal}{$lang.character_limit_exceeded}{literal}</span>';
			$('#review_result').html(fail_message);
			$('#review_result').fadeIn(5000);	
			$('#review_result').fadeOut(5000);
                    
                 }
      }
     });
    return false;
});

</script>
{/literal}