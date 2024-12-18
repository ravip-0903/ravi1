{if $config.ques_ans_block_enable}
    {* $Id: features.tpl 11191 2010-11-11 11:56:01Z klerik $ *}
    {** block-description:question_answer **}
    <div style="float:left; width:100%;">
        {if $question_data}
            {include file="views/products/seller_connect_redirect.tpl"}
            {include file="common_templates/pagination.tpl"}
            {foreach from=$question_data item="data"}
               
                <div class="faq margin_top">
                    <div class="asideLeft">
                        <label>{$lang.ques_heading}</label>

                    </div>

                    <div class="asideRight">        
                        <span class="question">{$data.question}</span>
                    </div>
                    <div class="clearboth"></div>
                </div>

                <div class="faq">
                    <div class="asideLeft">
                        <label>{$lang.ans_heading}</label>
                        <label>
                            <span>{"jS M Y"|date:$data.timestamp}</span>
                        </label>
                    </div>

                    <div class="asideRight bottom_border">        
                        <span class="answer">{$data.answer}</span>
                    </div>
                    <div class="clearboth"></div>

                </div>
            {/foreach}
            {include file="common_templates/pagination.tpl"}
            {$lang.ques_ans_help_msg}
        {else}
            {$lang.no_ques_ans_msg}
        {/if}

        <div class="clearboth"></div>
        <div class="float-left">
            {include file="views/products/seller_connect_redirect.tpl"}
        </div>
    </div>
{/if}
