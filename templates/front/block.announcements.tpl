{if $entries}
	<div class="media-items">
		{foreach $entries as $post}
			<div class="media">
				<div class="media-body">
					<h4 class="media-heading">{$post.title|escape:'html'}</h4>
					<p>{$post.body|escape:'html'}</p>
					<p class="media-date">{$post.date|date_format:$core.config.date_format}</p>
				</div>
			</div>
		{/foreach}
	</div>
{else}
	<p>{lang key='no_announcements_yet'}</p>
{/if}