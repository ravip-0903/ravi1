{* $Id: main.tpl 10618 2010-09-13 11:50:51Z alexions $ *}

{block group="top" assign="top"}
{if ($controller == "profiles" && $mode == "update") || ($controller == "orders" && $mode == "search") || ($controller == "rma" && $mode == "returns") || ($controller == "reward_points" && $mode == "userlog") || ($controller == "wishlist" && $mode == "view") || ($controller == "subscriptions" && $mode == "search") || ($controller == "orders" && $mode == "downloads") || ($controller == "rate_vendor" && $mode == "manage")}
{block group="right" assign="left"}
{else}
{block group="left" assign="left"}
{block group="right" assign="right"}
{/if}


{block group="bottom" assign="bottom"}
<div id="container" class="container{if !$left|trim && !$right|trim}-long{elseif !$left|trim}-left{elseif !$right|trim}-right{/if}">
	{hook name="index:main_content"}
	<div id="header">{include file="top.tpl"}</div>
	{/hook}
	
	<div id="content">
		<div class="content-helper clear">
			{if $top|trim}
			<div class="header">
				{$top}
			</div>
			{/if}
			
			{hook name="index:columns"}
            <!--{if $controller neq "companies" && $mode neq "view"}
            	{assign var="companies_css" value="merchant_full"}
            {/if}-->
            
           
            <!--added by wiantech-->
            {if $controller == "companies" && $mode == "view"}
            	{assign var="companies_css" value="merchant_full"}
            {else}   
            	{assign var="companies_css" value=""}
            {/if}
            
            
            {if $controller == "products" && $mode == "view"}
            	{assign var="products_css" value="products_full"}
            {else}   
            	{assign var="products_css" value=""}
            {/if}
            <!--added by wiantech-->
            
			<div class="central-column {$companies_css} {$products_css}">
				<div class="central-content">
					{include file="common_templates/breadcrumbs.tpl"}
					{include file="common_templates/notification.tpl"}
					
					{block group="central"}
				</div>
			</div>
		
			{if $left|trim}
			<div class="left-column">

			{if ($controller == "profiles" && $mode == "update") || ($controller == "orders" && $mode == "search") || ($controller == "rma" && $mode == "returns") || ($controller == "reward_points" && $mode == "userlog") || ($controller == "wishlist" && $mode == "view") || ($controller == "subscriptions" && $mode == "search") || ($controller == "orders" && $mode == "downloads") || ($controller == "rate_vendor" && $mode == "manage")}
				<div class="sidebox-wrapper">
					<h3 class="sidebox-title">
					<span>My account</span></h3>
					<div class="sidebox-body">		
						{include file="blocks/my_account.tpl"}
					</div>
					<div class="sidebox-bottom">
						<span> &nbsp; </span>
					</div>
				</div>
			{else}
				{$left}
			{/if}

			</div>
			{/if}
			
			{if $controller == "companies" && $mode == "view"}
            {else}
            {if $right|trim}
            
            
            
            
            {if $controller == "products" && $mode == "view"}
            	{assign var="products_css" value="products_right"}
            {else}   
            	{assign var="products_css" value=""}
            {/if}
			<div class="right-column {$products_css}">
				{$right}
			</div>
			{/if}
            {/if}
			{/hook}
			
			{if $bottom|trim}
			<div class="bottom clear-both">
				{$bottom}
			</div>
			{/if}
		</div>
	</div>
	
	<div id="footer">
		<div class="footer-helper-container">
			<div class="footer-top-helper"><span class="float-left">&nbsp;</span><span class="float-right">&nbsp;</span></div>
			{include file="bottom.tpl"}
			<div class="footer-bottom-helper"><span class="float-left">&nbsp;</span><span class="float-right">&nbsp;</span></div>
		</div>
	</div>
</div>
