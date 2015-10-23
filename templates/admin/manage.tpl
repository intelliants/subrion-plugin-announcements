<form method="post" enctype="multipart/form-data" class="sap-form form-horizontal">
	{preventCsrf}

	<div class="wrap-list">
		<div class="wrap-group">
			<div class="wrap-group-heading">
				<h4>{lang key='announcements'}</h4>
			</div>
			
			<div class="row">
				<label class="col col-lg-2 control-label" for="input-language">{lang key='language'}</label>
				<div class="col col-lg-4">
					<select name="lang" id="input-language"{if count($core.languages) == 1} disabled="disabled"{/if}>
						{foreach $core.languages as $code => $language}
							<option value="{$code}"{if $entry.lang == $code} selected="selected"{/if}>{$language.title}</option>
						{/foreach}
					</select>
				</div>
			</div>

			<div class="row">
				<label class="col col-lg-2 control-label" for="input-title">{lang key='title'}</label>
				<div class="col col-lg-4">
					<input type="text" name="title" value="{$entry.title|escape:'html'}" id="input-title">
				</div>
			</div>

			<div class="row">
				<label class="col col-lg-2 control-label" for="body">{lang key='body'}</label>
				<div class="col col-lg-4">
					<textarea name="body" rows="8" style="resize: none;">{$entry.body|escape:'html'}</textarea>
				</div>
			</div>

			<div class="row">
				<label class="col col-lg-2 control-label" for="input-date">{lang key='date'}</label>
				<div class="col col-lg-4">
					<div class="input-group">
						<input type="text" class="js-datepicker" name="date" id="input-date" value="{$entry.date}" data-date-show-time="true" data-date-format="yyyy-mm-dd H:i:s">
						<span class="input-group-addon js-datepicker-toggle"><i class="i-calendar"></i></span>
					</div>
				</div>
			</div>

			<div class="row">
				<label class="col col-lg-2 control-label" for="input-date">{lang key='expire_date'}</label>
				<div class="col col-lg-4">
					<div class="input-group">
						<input type="text" class="js-datepicker" name="expire_date" id="input-date" value="{$entry.expire_date}" data-date-show-time="true" data-date-format="yyyy-mm-dd H:i:s">
						<span class="input-group-addon js-datepicker-toggle"><i class="i-calendar"></i></span>
					</div>
				</div>
			</div>

			<div class="row">
				<label class="col col-lg-2 control-label" for="input-status">{lang key='status'}</label>
				<div class="col col-lg-4">
					<select name="status" id="input-status">
						<option value="active"{if iaCore::STATUS_ACTIVE == $entry.status} selected="selected"{/if}>{lang key='active'}</option>
						<option value="inactive"{if iaCore::STATUS_INACTIVE == $entry.status} selected="selected"{/if}>{lang key='inactive'}</option>
					</select>
				</div>
			</div>
		</div>

		<div class="form-actions inline">
			<button type="submit" name="save" class="btn btn-primary">{if iaCore::ACTION_EDIT == $pageAction}{lang key='save_changes'}{else}{lang key='add'}{/if}</button>
			{include file='goto.tpl'}
		</div>
	</div>
</form>

{ia_add_media files='datepicker'}