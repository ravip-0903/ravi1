{* $Id: completed_notification.tpl 11786 2011-02-08 09:44:48Z 2tl $ *}

{$user.firstname} {$user.lastname}<br /><br />
{$lang.revisions_history}: <a href="{$history_link|replace:'&amp;':'&'}">{$history_link|replace:'&amp;':'&'}</a><br /><br />
{$lang.date}: {$time|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}