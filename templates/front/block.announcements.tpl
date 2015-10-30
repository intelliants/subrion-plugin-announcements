{if $entries}
	<div class="ia-items">
		{foreach $entries as $post}
			<div class="ia-item ia-item--border-bottom an-post">
				<div class="ia-item__content">
					<h5 class="ia-item__title">{$post.title|escape:'html'}</h5>
					<p class="an-post__date">{$post.date|date_format:$core.config.date_format}</p>
					<p>{$post.body|escape:'html'}</p>
				</div>
			</div>
		{/foreach}
	</div>
{else}
	<p>{lang key='no_announcements_yet'}</p>
{/if}