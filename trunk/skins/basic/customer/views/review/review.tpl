{*
    @author:- Raj Chaudhary
    @description:- view for product review
    @created date:- 07/09/2013
*}
{assign var="discussion" value=$object_id|fn_get_discussion:$object_type|fn_html_escape}
<link href="{$config.skin_path}/css/ui/grid.css" rel="stylesheet" type="text/css"/>
<div class="mainContainer container_16 clearfix">
    <div class="grid_11">
        <form action="{""|fn_url}" method="post" id="review_form" name="review_form">
            <div class="top-cntnr">
                <input type ="hidden" id="thread_id" value="{$discussion.thread_id}" />
                <input type ="hidden" name="redirect_url" value="{"products.view&product_id=`$object_id`"|fn_url}">
                <h1>{$lang.write_reviews}</h1>
                <span><a href="{"products.view&product_id=`$object_id`"|fn_url}#Reviews">{$lang.read_reviews}({$reviews})</a></span>
            </div>
            <div class="title-cntnr">
                <input type="hidden" id="dsc_rating" />
                <label>{$lang.your_rating}</label>
                <div id="starRatRev"></div>
                <span>(Click to rate on scale of 1-5)</span>
            </div>
            <div class="title-cntnr">
                <label>{$lang.review_title}</label>
                <input id="revtitle" type="text" name="post_data[name]">
                <span>(Upto 20 words)</span>
            </div>
            <div class="title-cntnr">
                <label>{$lang.review_summary}</label>
                <textarea id="revSummary" name="post_data[message]"></textarea>
                <span>(Please make sure your reviews contains at least 200 characters.)</span>
            </div>
            
            <div class="btn-cntnr">
                <input id="submitButton" type="submit" name="dispatch[review.review]" value="Submit">
                <a id="takeMeBack" href="{"products.view&product_id=`$object_id`"|fn_url}">Take me back to Product page</a>
                <div id="loader">
                    <img src="/images/loader.gif"/>
                </div>
                <div id="review_result"></div>
            </div>
        </form>
        <div class="last-cntnr">
            <div class="left">
                {assign var="product_new_id" value=$object_id|base64_encode}
                {$lang.have_a_question|replace:'[product_id]':$product_new_id}
            </div>
            <span id="separator"></span>
            <div class="right">
                {$lang.had_a_great_experience}
            </div>
        </div>

        <div class="example1">{$lang.example1}</div>
        <div class="example2">{$lang.example2}</div>
    </div>
    <div class="grid_5">
        <div class="head">You have chosen to review</div>
        <div class="product">
            <div class="img-cntnr">
                {assign var="pro_images" value=$object_id|fn_get_image_pairs:'product':'M'}
                {include file="common_templates/image.tpl" image_width="50" image_height="50" obj_id=$object_id images=$pro_images object_type="product" alt_text=$product_name }
            </div>
            <div class="desc">
                <div class="name"><span>{$product_name}</span></div>
                {if $list_price neq 0}
                    <div><label>List Price: Rs.</label><span>{$list_price}</span></div>
                {/if}
                <div><label>Selling Price: Rs.</label><span>{$selling_price}</span></div>
                {if $deal_price neq 0}
                    <div class="deal"><label>Deal Price: Rs.</label><span>{$deal_price}</span></div>
                {/if}
            </div>
        </div>
        <div class="help">
            {$lang.review_guidelines}
        </div>
    </div>
</div>

{literal}
<script type="text/javascript">

    // On document ready!
    $(document).ready(function(){
        $('form[name=review_form]').submit(false);

        $('#starRatRev').raty({
            click: function(score, evt) {
                $('#dsc_rating').val(score);
            }
        });

        function validation(title, rating, summary) {
            var error = false;
            var errMsg = "";
            if(title.trim() == ""){
                error = true;
                errMsg = "{/literal}{$lang.review_title_error}{literal}.";
            }
            else if(summary.trim() == "" || summary.trim().length < 200){
                error = true;
                errMsg = "{/literal}{$lang.review_summary_error}{literal}.";
            }
            else if(rating.length == 0){
                error = true;
                errMsg = "{/literal}{$lang.review_rating_error}{literal}.";
            }
            else{
                error = false;
            }
            return {"error":error, "errMsg":errMsg};
        }

        // On submit of review!
        $('#submitButton').click(function(){
            var title 				= $('#revtitle').val();
            var rating 				= $('#dsc_rating').val();
            var summary 			= $('#revSummary').val();
            // First do validations here!
            var errObj = validation(title, rating, summary);
            if(errObj.error){
                $('#review_result').css("color","red");
                $('#review_result').html(errObj.errMsg);
                $('#review_result').show();
            }
            else{
                $('#review_result').hide();
                $('#review_result').html();
                $("#loader").show();
                var ip=$('#sc_uses').val();
                var thread_id = $('#thread_id').val();
                $.ajax({
                    type: "POST",
                    url: "index.php",
                    data: { dispatch: 'review.review', name:title, message:summary, rating_value:rating, thread_id:thread_id,redirect_url:"{/literal}{"products.view&product_id=`$object_id`"|fn_url}{literal}"},
                    success: function(msg) {
                        $("#loader").hide();
                        if(msg == 'done'){
                            var success_message = '{/literal}{$lang.thanks_for_your_review}{literal}';
                            $('#review_result').html(success_message);
                            $('#review_result').css("color","green");
                            $('#review_result').fadeIn(5000);
                            $('#review_result').fadeOut(5000);
                            $('#revtitle').val('');
                            $('#revSummary').val('');
                            $("#takeMeBack").click();
                        }else if(msg == 'failed'){
                            var fail_message = '{/literal}{$lang.some_error_occured}{literal}';
                            $('#review_result').html(fail_message);
                            $('#review_result').css("color","red");
                            $('#review_result').fadeIn(5000);
                            $('#review_result').fadeOut(5000);
                        }else if(msg == 'title_error'){
                            var fail_message = '{/literal}{$lang.title_error}{literal}';
                            $('#review_result').html(fail_message);
                            $('#review_result').css("color","red");
                            $('#review_result').fadeIn(5000);
                            $('#review_result').fadeOut(5000);
                        }else if(msg == 'rating_error'){
                            var fail_message = '{/literal}{$lang.rating_error}{literal}';
                            $('#review_result').html(fail_message);
                            $('#review_result').css("color","red");
                            $('#review_result').fadeIn(5000);
                            $('#review_result').fadeOut(5000);
                        }else if(msg == 'character_error'){
                            var fail_message = '{/literal}{$lang.character_limit_exceeded}{literal}';
                            $('#review_result').html(fail_message);
                            $('#review_result').css("color","red");
                            $('#review_result').fadeIn(5000);
                            $('#review_result').fadeOut(5000);

                        }
                        else if(msg == 'limitPerProduct_exceed_error'){
                            var fail_message = '{/literal}{$lang.limitPerProduct_exceed_error}{literal}';
                            $('#review_result').html(fail_message);
                            $('#review_result').css("color","red");
                            $('#review_result').fadeIn(5000);
                            $('#review_result').fadeOut(5000);

                        }
                        else if(msg == 'limitPerDay_exceed_error'){
                            var fail_message = '{/literal}{$lang.limitPerDay_exceed_error}{literal}';
                            $('#review_result').html(fail_message);
                            $('#review_result').css("color","red");
                            $('#review_result').fadeIn(5000);
                            $('#review_result').fadeOut(5000);

                        }
                    }
                });
            }
        });
    });
</script>
{/literal}