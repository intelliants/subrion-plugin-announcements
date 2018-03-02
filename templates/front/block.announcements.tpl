{if $announcements}
    <div class="ia-items">
        {foreach $announcements as $announcement}
            <div class="ia-item ia-item--border-bottom an-post">
                <div class="ia-item__content">
                    <h5 class="ia-item__title">{$announcement.title|escape:'html'}</h5>
                    <p class="an-post__date">{$announcement.date_added|date_format:$core.config.date_format}</p>
                    <p>{$announcement.body}</p>
                </div>
            </div>
        {/foreach}
    </div>

{else}
    <p>{lang key='no_announcements_yet'}</p>
{/if}