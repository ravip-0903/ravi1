{if $config.fb_comment_block && !$config.isResponsive}
{* $Id: features.tpl 11191 2010-11-11 11:56:01Z klerik $ *}
{** block-description:facebook comment **}
<div id="fb-root"></div>
{literal}
<script>
    (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId={/literal}{$config.fb_comment_app_id}{literal}";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
    {/literal}
    {assign var="path" value=$config.http_location|cat:"/index.php?"|cat:$smarty.server.QUERY_STRING}
<div class="fb-comments" data-href="{$path|fn_url}" data-numposts="{$config.no_of_fb_comment_show}" data-colorscheme="light"></div>
{/if}