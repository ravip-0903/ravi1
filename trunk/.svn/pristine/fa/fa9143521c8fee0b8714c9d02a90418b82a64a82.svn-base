{* $Id: top.tpl 12547 2011-05-27 12:48:46Z alexions $ *}
{assign var="group_privileges" value=""|group_privileges}
<div class="header-wrap">
<div id="header">
    <div id="logo" {if !empty($smarty.session.auth.company_id)}style="width:282px"{/if} >
     {if !empty($smarty.session.auth.company_id)}
         {$lang.new_storemanager_logo}
        {/if}
		{assign var="name" value=$settings.Company.company_name|substr:0:40}
		{if $settings.Company.company_name|fn_strlen > 40}
			{assign var="name" value="`$name`..."}
		{/if}
		
                <a href="{$index_script|fn_url}" {if !empty($smarty.session.auth.company_id)}style="margin:0px 0 0 155px; float:left; display:block;"{/if}>{$name}</a><a href="{$config.http_location|fn_url}" class="view-storefront-icon" target="_blank" title="{$lang.view_storefront}">&nbsp;</a>
                	
	</div>
    
	<div id="top_quick_links">
                {if $auth.user_id}
		<div class="nowrap">
			{include file="top_quick_links.tpl"}
		</div>
		{/if}
	</div>
    
	<div id="top_menu">
	<ul id="alt_menu">
	{if $auth.user_id && $navigation.static}
	{foreach from=$navigation.static key=first_level_title item=m name="first_level_top"}
	{if $first_level_title == "administration" || $first_level_title == "design" || $first_level_title == "settings"}
		<li{if $first_level_title == $navigation.selected_tab} class="active"{/if}>
		<a class="drop {$first_level_title}">{$lang.$first_level_title}</a>
			<div class="dropdown-column">
			<div class="col">
				<ul>
				{foreach from=$m key=second_level_title item="second_level" name="sec_level_top"}
					<li class="{if $second_level.subitems}sub-level {/if}
					{if $second_level_title == $navigation.subsection}active{/if}">
					<a href="{$second_level.href|fn_url}">
					{if $second_level.title}{$second_level.title}{else}{$lang[$second_level_title]}{/if}
					</a>
					{if $second_level.subitems}
						<div class="dropdown-second-level{if "COMPANY_ID"|defined} drop-left{/if}">
						<ul>
						{foreach from=$second_level.subitems key=subitem_title item=sm}
							<li{if $subitem_title == $navigation.subitem} class="active"{/if}>
							<a href="{$sm.href}">{$lang[$subitem_title]}</a></li>
						{/foreach}
						</ul>
						</div>
					{/if}
				</li>
				{/foreach}
				</ul>
				</div>
			</div>
		</li>
	{/if}
	{/foreach}
	{/if}

	</ul>
	</div>
     
{if $smarty.const.PRODUCT_TYPE == "MULTIVENDOR" || $smarty.const.PRODUCT_TYPE == "MULTISHOP"}
	<div class="float-left" {if !empty($smarty.session.auth.company_id)}style="margin-left:10px;"{/if}>
	{if $s_companies|sizeof > 1}
		{assign var="company_name" value=$s_companies.$s_company.company|substr:0:40}
		{if $s_companies.$s_company.company|fn_strlen > 40}
			{assign var="company_name" value="`$company_name`..."}
		{/if}
		{include file="common_templates/ajax_select_object.tpl" data_url="companies.get_companies_list?show_all=Y&action=href" text=$company_name id="top_company_id"}
	{else}
			{$lang.vendor}: <a href="{"companies.update?company_id=`$s_company`"|fn_url}">{$s_companies.$s_company.company}</a>
	{/if}
            	</div>
                
            <div style="float:left;">
                
                    
                {** Show MID at the top on vendor side **} 
                {if !empty($smarty.session.auth.company_id)}
                    <span style="margin:2px 0 0 10px; float:left; color:#fff;"> MID: {$smarty.session.auth.company_id} </span>
               {/if}
                
              {assign var="status_descriptions" value=$smarty.session.auth.company_id|merchant_status_descriptions}
                     
                       
                         
                    {if  $status_descriptions.company_id=='A'}
                       
                        {$lang.store_offline_popup}
                        
                        {$lang.store_offline_image_show}
                           
                            {if $smarty.request.store == 'store_offline' }
                         
                               {assign var="company_update" value=$smarty.session.auth.company_id|fn_companies_change_status:'F':''}
                            
                                {literal}
                                    
                                        <script type="text/javascript">

                                            location.reload();

                                            </script>
                                            
                                        {/literal}
                            {/if}
                          
                    
                     
                      {/if}
                      
                      {if  $status_descriptions.company_id=='F' }
                                
                                 {$lang.store_online_image_show}
                                 {$lang.store_online_popup}
                              
                            
                              {if $smarty.request.store == 'store_online'}
                               
                                 {assign var="company_update" value=$smarty.session.auth.company_id|fn_companies_change_status:'A':''}
                               
                               {literal}
                                     <script type="text/javascript">
                                         
                                         location.reload();
                                         
                                         </script>
                                  {/literal}
                                  
                           {/if}   
                       
                      {/if}
                </div>
      {if !empty($smarty.session.auth.company_id)}
        <div style="float:right; margin:-8px -45px 0 0 ;position: relative; z-index: 55555;">
            {assign var="company_data" value=$smarty.session.auth.user_id|fn_get_company_user_data}
             <a href="index.php?dispatch=companies.contact_form&user_id={$smarty.session.auth.user_id}&company_id={$company_data.company_id}&email={$company_data.email}&name={$company_data.name}&phone={$company_data.phone}">{$lang.contact_form}</a>
        </div>
     {/if}
{/if}
<!--<div style="float:left">
    {if !"COMPANY_ID"|defined}
      <ul>
		<li class="search">
			{hook name="index:global_search"}
				<form id="global_search" method="get" action="{""|fn_url}">
					<input type="hidden" name="dispatch" value="search.results" />
					<input type="hidden" name="compact" value="Y" />
					<div>
						<button type="submit" id="search_button">Go</button>
						<label for="gs_text"><a><input type="text" class="cm-tooltip" id="gs_text" name="q" autocomplete="off" title="{$lang.search_tooltip}" /></a></label>
					</div>
				</form>
			{/hook}
		</li>
        </ul>
   {/if}
</div>-->

	<ul id="menu">
		<li class="dashboard {if !$navigation.selected_tab}dashboard-active{/if}">
			<a href="{$index_script|fn_url}" title="{$lang.dashboard}">&nbsp;</a>
		</li>

		{if $auth.user_id && $navigation.static}
		{foreach from=$navigation.static key=first_level_title item=m name="first_level"}
		{if $first_level_title != "administration" && $first_level_title != "design" && $first_level_title != "settings"}
                    
		<li{if $first_level_title == $navigation.selected_tab} class="active"{/if}>
                     
                         <a class="drop">{if $first_level_title=='vendors' && "COMPANY_ID"|defined }{$lang.settings_menu}{else}{$lang.$first_level_title}{/if}
                        </a> 

			<div class="dropdown-column">
				<div class="col">
				<ul>
				{foreach from=$m key=second_level_title item="second_level" name="sec_level"}

					<li class="blank {$second_level_title}
					{if $second_level.subitems 
					|| ($second_level_title eq 'shipments' && ( $manage_shipment_links ne '')) || $second_level_title eq 'outofSLA' || $second_level_title eq 'actionreport'  || $second_level_title eq 'Other'  || ($second_level_title eq 'billing_admin' && ($billing_admin_links ne '' || $upload_transaction_file_links ne '')) || $second_level_title eq 'manage_milkrun_process' || $second_level_title eq 'manage_package_qa' || $second_level_title eq 'manage_basic_fulfillment' || $second_level_title eq 'bulk_order_state' || ($second_level_title eq 'Other' && $fulfillment_admin_links ne '') } sub-level{/if}
						{if $second_level_title == $navigation.subsection && $first_level_title == $navigation.selected_tab}
						active{/if}">
                    
			{if $second_level_title=='billing_admin' }
                          {if $billing_admin_links!='' || $upload_transaction_file_links !=''}
                            <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span>
                            {if $lang[$second_level.description] != "_`$second_level_title`_menu_description"}
							{if $settings.Appearance.show_menu_descriptions == "Y"}
								<span class="hint">{$lang[$second_level.description]}</span>
							{/if}
						{/if}
                          {/if}
                       {elseif $second_level_title=='shipments'}
                          {if $manage_shipment_links!=''}
                              <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span>
                              {if $lang[$second_level.description] != "_`$second_level_title`_menu_description"}
                                {if $settings.Appearance.show_menu_descriptions == "Y"}
                                    <span class="hint">{$lang[$second_level.description]}</span>
                                {/if}
                            {/if}
                          {/if}
                         {elseif $second_level_title=='vendor_communication'}
                         {if $vendor_communication_links!=''}
                              <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span>
                              {if $lang[$second_level.description] != "_`$second_level_title`_menu_description"}
                                {if $settings.Appearance.show_menu_descriptions == "Y"}
                                    <span class="hint">{$lang[$second_level.description]}</span>
                                {/if}
                            {/if}
                          {/if}
                          
                          {elseif $second_level_title=='bulk_order_state'}
                          {if $manage_bulk_shipments_links!=''}
                              <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span>
                              {if $lang[$second_level.description] != "_`$second_level_title`_menu_description"}
                                {if $settings.Appearance.show_menu_descriptions == "Y"}
                                    <span class="hint">{$lang[$second_level.description]}</span>
                                {/if}
                            {/if}
                          {/if}
                          {elseif "COMPANY_ID"|defined && $second_level_title=='manage_basic_fulfillment'}
                          {elseif !"COMPANY_ID"|defined && ($second_level_title=='manifest_dispatch_report' || $second_level_title=='manifest_search' || $second_level_title=='manifest_ui')}
                          {elseif !"COMPANY_ID"|defined && $second_level_title=='manage_basic_fulfillment'}
                          {if $manifest_create_link!='' || $manifest_search_link!='' || $manifest_ui_link!='' }
                              <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span>
                              {if $lang[$second_level.description] != "_`$second_level_title`_menu_description"}
                                {if $settings.Appearance.show_menu_descriptions == "Y"}
                                    <span class="hint">{$lang[$second_level.description]}</span>
                                {/if}
                              {/if}
                            {/if}
			<!--Added by Sudhir dt 27th Nov 2012 for merchant report link hide for vendors bigin here -->
                        {elseif $second_level_title=='sales_reports'}
				{if in_array('sales_reports', $group_privileges) || ($auth.user_id ==1)}
	                                <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span></a>
				{/if}
                        {elseif $second_level_title=='merchant_reports'}

                               {if !"COMPANY_ID"|defined && in_array('merchant_reports', $group_privileges) || ($auth.user_id ==1)}
                                <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span></a>
                               {/if}
                        {elseif $second_level_title=='catalog_reports'}
                               {if !"COMPANY_ID"|defined && in_array('catalog_reports', $group_privileges) || ($auth.user_id ==1)}
                                <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span></a>
                               {/if}
                               
                        {elseif $second_level_title=='storeanalytics'}
                               {if !"COMPANY_ID"|defined && in_array('analytics_reports', $group_privileges) || ($auth.user_id ==1)}
                                <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span></a>
                               {/if}
                        {elseif $second_level_title=='storeperformance'}
                               {if "COMPANY_ID"|defined && in_array('analytics_reports', $group_privileges)}
                                <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span></a>
                               {/if}
			{elseif $second_level_title=='revenue_reports'}
                               {if !"COMPANY_ID"|defined && in_array('revenue_reports', $group_privileges) || ($auth.user_id ==1)}
                                <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span></a>
                               {/if}
                        {elseif $second_level_title=='system_reports'}
                               {if !"COMPANY_ID"|defined && in_array('system_reports', $group_privileges) || ($auth.user_id ==1)}
                                <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span></a>
                               {/if}
                        {elseif $second_level_title=='fulfillment_reports'}
                               {if !"COMPANY_ID"|defined && in_array('fulfillment_reports', $group_privileges) || ($auth.user_id ==1)}
                                <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span></a>
                               {/if}

			{elseif $second_level_title=='revenue_reports'}

                               {if !"COMPANY_ID"|defined && in_array('revenue_reports', $group_privileges) || ($auth.user_id ==1)}

                                <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span></a>

                               {/if}
                        {elseif $second_level_title=='system_reports'}
                               {if !"COMPANY_ID"|defined && in_array('system_reports', $group_privileges) || ($auth.user_id ==1)}
                                <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span></a>
                               {/if}
                        {elseif $second_level_title=='fulfillment_reports'}
                               {if !"COMPANY_ID"|defined && in_array('fulfillment_reports', $group_privileges) || ($auth.user_id ==1)}
                                <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span></a>
                               {/if}
                         {elseif $second_level_title=='category_level_reports'}
                               {if !"COMPANY_ID"|defined && in_array('category_level_reports', $group_privileges) || ($auth.user_id ==1)}
                                <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span></a>
                               {/if}
                          {elseif $second_level_title=='scorecard_level_reports'}
                               {if !"COMPANY_ID"|defined && in_array('scorecard_level_reports', $group_privileges) || ($auth.user_id ==1)}
                                <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span></a>
                               {/if}
                        
			<!--Added by Sudhir dt 27th Nov 2012 for merchant report link hide for vendors end here -->
			{elseif $second_level_title=='store_setup'}
                               {if "COMPANY_ID"|defined && $second_level_title=='store_setup'}
                                <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span></a>
                                {else}
                                
                               {/if}
                        {elseif $second_level_title=='Other'}  
                            {if $fulfillment_admin_links!=''}
                               <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span>
                              {if $lang[$second_level.description] != "_`$second_level_title`_menu_description"}
                                {if $settings.Appearance.show_menu_descriptions == "Y"}
                                    <span class="hint">{$lang[$second_level.description]}</span>
                                {/if}
                              {/if}
                            {/if}  
			{elseif $second_level_title=='vendors' && "COMPANY_ID"|defined}
				<a href="{$second_level.href|fn_url}"><span>{$lang.settings_second_level}</span></a>
                         {elseif $second_level_title=='global_options' && "COMPANY_ID"|defined}

                         {elseif $second_level_title=='payment_policy' && "COMPANY_ID"|defined}
				<a href="http://www.shopclues.com/payment-policy.html" target="_blank"><span>{$lang.$second_level_title}</span></a>
                          
                         {elseif $second_level_title=='how_does_it_work' && "COMPANY_ID"|defined}
                             	<a href="http://www.shopclues.com/index.php?dispatch=pages.view&page_id=157" target="_blank"><span>{$lang.$second_level_title}</span></a>
                          
                          {elseif $second_level_title=='how_does_it_work' && !"COMPANY_ID"|defined}
                          {elseif $second_level_title=='my_user_question' && !"COMPANY_ID"|defined}
                          {elseif $second_level_title=='payment_faq' && "COMPANY_ID"|defined}
                             <a href="http://www.shopclues.com/index.php?dispatch=pages.view&page_id=137" target="_blank"><span>{$lang.$second_level_title}</span></a>
                          {elseif $second_level_title=='payment_faq' && !"COMPANY_ID"|defined}
                         {elseif $second_level_title=='milkrun_manifest_download' && !"COMPANY_ID"|defined} 
						 {else}
                         <a href="{$second_level.href|fn_url}"><span>{$lang.$second_level_title}</span>
                         {if $lang[$second_level.description] != "_`$second_level_title`_menu_description"}
							{if $settings.Appearance.show_menu_descriptions == "Y"}
								<span class="hint">{$lang[$second_level.description]}</span>
							{/if}
						{/if}
                        {/if}
                        
                        
						
                        
						</a>
						{if $second_level.subitems}
							<div class="dropdown-second-level">
								<ul>
									{foreach from=$second_level.subitems key=subitem_title item=sm}
										<li{if $subitem_title == $navigation.subitem} class="active"{/if}>
										<a href="{$sm.href}">{$lang[$subitem_title]}</a>
										</li>
									{/foreach}
								</ul>
							</div>
						{/if}
						{if $second_level_title eq 'shipments'}
						  {if  $manage_shipment_links ne ''}
							<div class="dropdown-second-level">
							
								<ul>
                                    <li><a href="?dispatch=create_shipment.new">Download Shipment Data</a></li>
                                    <li><a href="?dispatch=create_shipment.upload">Upload Shipment Data</a></li>
									
								</ul>
								
							</div>
							{/if}
						{/if}
                        {if $second_level_title eq 'manage_milkrun_process'}
                           <div class="dropdown-second-level">
								<ul>
                                   <li><a href="?dispatch=milkrun_create.milkrun_generate">MilkRun Initiation</a></li>
                  				   <li><a href="?dispatch=milkrun_create.milkrun_distribution">MilkRun Distribution</a></li>
                                   <li><a href="?dispatch=mri.mrisearch">Milkrun Receiving</a></li>
								   <li><a href="?dispatch=milkrun_completed.milkrun_initiate_list">MilkRun Completion</a></li>
                                </ul>
                           </div>
                        {/if}
                        {if $second_level_title eq 'manage_basic_fulfillment' && !"COMPANY_ID"|defined}
                            <div class="dropdown-second-level">
								<ul>
                                {if $manifest_create_link!=''}
                                  <li><a href="?dispatch=manifest_create.manifest_generate">Manifest & Dispatch Report</a></li>
                                {/if}
                                {if $manifest_search_link!=''}
                  				  <li><a href="?dispatch=manifest_search.manifest_list">Manifest Search</a></li>
                                {/if}
                                {if $manifest_ui_link!=''}
                                   <li><a href="?dispatch=manifest_ui.manifest">Manifest Orders UI</a></li>
                                {/if}   
                                </ul>
                            </div>
                        {/if}
                        {if $second_level_title eq 'manage_package_qa'}
                            <div class="dropdown-second-level">
								<ul>
                                   <li><a href="?dispatch=manifest_ui.order_onwards_QA_screen&onwardsreturn=onwards">Onwards Order QA Screen</a></li>
                                    <li><a href="?dispatch=manifest_ui.order_onwards_QA_screen&onwardsreturn=return">Return Order QA Screen</a></li>
                                </ul>
                            </div>
                        {/if}
                        {if $second_level_title eq 'bulk_order_state'}
                            <div class="dropdown-second-level">
								<ul>
                                {if $manage_bulk_shipments_links!=''}
                                   <li><a href="?dispatch=import_orders.import_orders_list">Bulk Update Tracking Status</a></li>
                                    <li><a href="?dispatch=shipment_completed.shipment_initiate_list">Bulk Update Order Status</a></li>
                                 {/if}
                                </ul>
                            </div>
                        {/if}
                        {if $second_level_title eq 'outofSLA'}
							<div class="dropdown-second-level">
							
								<ul>
									<li><a href="?ff=1&redirect_if_one=N&hint_=Search...&dispatch=orders.manage&dispatch[orders.manage]=Search&orderage=2&status[]=P&period=A&hint_new_view=Name">Paid</a></li>
                                    <li><a href="?ff=1&redirect_if_one=N&hint_=Search...&dispatch=orders.manage&dispatch[orders.manage]=Search&orderage=2&status[]=E&period=A&hint_new_view=Name">MilkRun Initiated</a></li>
                                    <li><a href="?ff=1&redirect_if_one=N&hint_=Search...&dispatch=orders.manage&dispatch[orders.manage]=Search&orderage=2&status[]=G&period=A&hint_new_view=Name">Milkrun Complete</a></li>
                                    <li><a href="?ff=1&redirect_if_one=N&hint_=Search...&dispatch=orders.manage&dispatch[orders.manage]=Search&orderage=2&status[]=L&period=A&hint_new_view=Name">Manifested</a></li>
                                    <li><a href="?ff=1&redirect_if_one=N&hint_=Search...&dispatch=orders.manage&dispatch[orders.manage]=Search&orderage=5&status[]=A&period=A&hint_new_view=Name">Shipped</a></li>
                                    <li><a href="?ff=1&redirect_if_one=N&hint_=Search...&dispatch=orders.manage&dispatch[orders.manage]=Search&orderage=2&status[]=H&period=A&hint_new_view=Name">Delivered</a></li>
                                    <li><a href="?ff=1&redirect_if_one=N&hint_=Search...&dispatch=orders.manage&dispatch[orders.manage]=Search&orderage=4&status[]=J&period=A&hint_new_view=Name">RTO</a></li>
                                    <li><a href="?ff=1&redirect_if_one=N&hint_=Search...&dispatch=orders.manage&dispatch[orders.manage]=Search&orderage=4&status[]=R&period=A&hint_new_view=Name">RTO Received</a></li>
								</ul>
								
							</div>
						{/if}
                        {if $second_level_title eq 'actionreport'}
							<div class="dropdown-second-level">
							
								<ul>
                                    <li><a href="?ff=1&redirect_if_one=N&hint_=Search...&dispatch=orders.manage&dispatch[orders.manage]=Search&exception_cause[]=9&exception_cause[]=14&exception_cause[]=21&exception_cause[]=47&period=A&&hint_new_view=Name">Fullfillment</a></li>
                                    <li><a href="?ff=1&redirect_if_one=N&hint_=Search...&dispatch=orders.manage&dispatch[orders.manage]=Search&exception_cause[]=7&exception_cause[]=86&period=A&&hint_new_view=Name">BD</a></li>
                                    <li><a href="?ff=1&redirect_if_one=N&hint_=Search...&dispatch=orders.manage&dispatch[orders.manage]=Search&exception_cause[]=8&exception_cause[]=12&exception_cause[]=13&exception_cause[]=15&exception_cause[]=51&period=A&&hint_new_view=Name">Customer Support</a></li>
                                    <li><a href="?ff=1&redirect_if_one=N&hint_=Search...&dispatch=orders.manage&dispatch[orders.manage]=Search&exception_cause[]=8&exception_cause[]=10&exception_cause[]=11&period=A&&hint_new_view=Name">Catalog</a></li>
                                    <li><a href="?ff=1&redirect_if_one=N&hint_=Search...&dispatch=orders.manage&dispatch[orders.manage]=Search&exception_cause[]=15&period=A&&hint_new_view=Name">Fraud & Risk</a></li>
								</ul>
								
							</div>
						{/if}
                        {if $second_level_title eq 'billing_admin'}
                          <div class="dropdown-second-level">
                            <ul>
                                    {if $upload_transaction_file_links ne ''}
                                    <li><a href="?dispatch=upload_transactional_data.upload">Reconcile Order</a></li>
									 {if !"COMPANY_ID"|defined}
									 <li><a href="?dispatch=upload_pgw_settle_data.upload">PGW Settlement Data</a></li>
									 {/if}
                                    {/if}
                                    {if $billing_admin_links ne ''}
                                    
                                    <li><a href="?dispatch=merchant_billing.merchant_billing">Merchant Billing Setup</a></li>
                                    <!--<li><a href="?dispatch=companies_related_master.companies_commision">Billing - Companies Commissions</a></li>
                                    <li><a href="?dispatch=companies_related_master.companies_shippingrates">Billing - Companies Shipping Rates</a></li>-->
                                    <li><a href="?dispatch=billing_category_master.billing_category">Billing - Categories</a></li>
                                    
                                    {/if}
                                    </ul>
                          </div>
                        {/if}
                        {if $second_level_title eq 'Other'}
							<div class="dropdown-second-level">
							 {if $fulfillment_admin_links ne ''}
								<ul>
                              
                                    <li><a href="?dispatch=bd_company_rel.manage">Merchant Account Manager Assignment</a></li>
                                    <li><a href="?dispatch=pickupboy_company_rel.manage">Merchant Pickup Boy Assignment</a></li>
                                    <li><a href="?dispatch=pickupboy_lookup.manage">Manage Pickup Boys</a></li>
                                    <li><a href="?dispatch=carrier_related_master.carrier_lookup">Manage 3PL Partners</a></li>
                                    <li><a href="?dispatch=exception_master.reason_lookup">Exception Causes Lookup</a></li>
                                    <li><a href="?dispatch=email_template.email_template">Email Templates</a></li>
                                    <li><a href="?dispatch=awb_management.view">{$lang.awb_assign}</a></li>
                                   
								</ul>
							{/if}	
							</div>
						{/if}
					</li>
				{/foreach}
				</ul>
				</div>
			</div>
		</li>
        
		{/if}
		{/foreach}
		{/if}

	</ul>
<!--header--></div></div>

{literal}
<script type="text/javascript">
//<![CDATA[
if((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {
    $("#menu li a").click(function(){
    });
	$('#header').css('padding-top', '15px !important'); 
}
//]]>
   
$(document).ready(function(){
    
      $('#store_offline').click(function() {
          
                   $('#store_offline_popup').show();
              
             });
       
         $('#store_online').click(function() {
          
                   $('#store_online_popup').show();
                                             
              
             });
     
     }); 

</script>
{/literal}
{if $smarty.session.auth.company_id!=0}
    {$lang.storemanager_googleanalytic}
{/if}