{* 
    @author:- Shashi kant
    @description:- My Feedback on My Account page
    @created date:- 17/02/2014
*}
<div class="mobile_pndng_feedbck" style="width:773px; margin:auto;">
<div class="box_header">
<h1 class="box_heading">{$lang.my_feedbacks}</h1>
</div>
</div>
{if $post_feedback_count>0 || $pend_feedback_count>0}
    
<div style="margin:auto;">
    {$lang.submitted_and_pending_feedbacks}
  </div> 
<div class="feedback_box blueclr" >
    {if $post_feedback_count == 0}
           <a href="javascript:void(0)">0</a>
           <h1 class="box_heading">{$lang.submitted_feedbacks}</h1>
        {else}
           <a href="index.php?dispatch=profiles.submitted_feedback">{$post_feedback_count}</a>
           <h1 class="box_heading">{$lang.submitted_feedbacks}</h1>
        {/if}

</div>
<div class="feedback_box orangeclr" >
    {if $pend_feedback_count == 0}
               <a href="javascript:void(0)">0</a>
               <h1 class="box_heading">{$lang.pending_feedbacks}</h1>
        {else}
               <a href="index.php?dispatch=profiles.pending_feedback">{$pend_feedback_count}</a> 
               <h1 class="box_heading">{$lang.pending_feedbacks}</h1>
        {/if}

</div>
        {else}
           {$lang.no_submitted_and_pending_feedbacks}   
{/if}
