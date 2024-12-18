{*include file="common_templates/subheader.tpl" title=$lang.sdeep_feedback*}
{assign var="feedback" value=$company_data.company_id|merchant_detail_rating}
{assign var="feedback30" value=$company_data.company_id|merchant_detail_rating_30days}
{assign var="feedback90" value=$company_data.company_id|merchant_detail_rating_90days}
{assign var="feedback365" value=$company_data.company_id|merchant_detail_rating_365days}


<div class="ml_merchantfeedback" style="width:550px;">
<div class="ml_merchantfeedback_header">
<a name="rating"></a>
<h1 class="ml_merchantfeedback_header_heading" ><a name="feedback_heading"></a>{$lang.feedback_title_microsite}</h1> <a href="merchant-ratings.html" class="ml_merchantfeedback_header_link">What do these mean?</a>
</div>

<div class="stats_merchantfeedback" style="margin-top: 5px;">
	<div class="mer_mic_sit_left">
    	<ul class="mer_mic_sit_left_ul">
        	<li class="mer_mic_sit_left_li">&nbsp;</li>
            <li class="mer_mic_sit_left_li">Positive</li>
            <li class="mer_mic_sit_left_li">Neutral</li>
            <li class="mer_mic_sit_left_li">Negative</li>
            <li class="mer_mic_sit_left_li">Count</li>
        </ul>    
    </div>
    <div class="mer_mic_sit_right">
    	<div class="mer_mic_sit_row mer_mic_sit_heading">
        	<div class="mer_mic_col">30 Days</div>
            <div class="mer_mic_col">90 Days</div>
            <div class="mer_mic_col">365 Days</div>
            <div class="mer_mic_col">Lifetime</div>
        </div>
        <div class="mer_mic_sit_row">
        	<div class="mer_mic_col">{$feedback30.positive|default:0}%</div>
            <div class="mer_mic_col">{$feedback90.positive|default:0}%</div>
            <div class="mer_mic_col">{$feedback365.positive|default:0}%</div>
            <div class="mer_mic_col">{$feedback.positive|default:0}%</div>
        </div>
        <div class="mer_mic_sit_row">
        	<div class="mer_mic_col">{$feedback30.neutral|default:0}%</div>
            <div class="mer_mic_col">{$feedback90.neutral|default:0}%</div>
            <div class="mer_mic_col">{$feedback365.neutral|default:0}%</div>
            <div class="mer_mic_col">{$feedback.neutral|default:0}%</div>
        </div>
        <div class="mer_mic_sit_row">
        	<div class="mer_mic_col">{$feedback30.negative|default:0}%</div>
            <div class="mer_mic_col">{$feedback90.negative|default:0}%</div>
            <div class="mer_mic_col">{$feedback365.negative|default:0}%</div>
            <div class="mer_mic_col">{$feedback.negative|default:0}%</div>
        </div>
        <div class="mer_mic_sit_row border_none">
        	<div class="mer_mic_col">{$feedback30.count|default:0}</div>
            <div class="mer_mic_col">{$feedback90.count|default:0}</div>
            <div class="mer_mic_col">{$feedback365.count|default:0}</div>
            <div class="mer_mic_col">{$feedback.count|default:0}</div>
        </div>
    </div>
</div>
</div>