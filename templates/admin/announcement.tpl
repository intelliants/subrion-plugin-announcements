<form method="post" enctype="multipart/form-data" class="sap-form form-horizontal">
    {preventCsrf}

    <div class="wrap-list">
        <div class="wrap-group">
            <div class="wrap-group-heading">
                <h4>{lang key='general'}</h4>
            </div>

            <div class="row">
                <label class="col col-lg-2 control-label" for="input-title">{lang key='title'} {lang key='field_required'}</label>
                <div class="col col-lg-4">
                    <input type="text" name="title" value="{$item.title|escape:'html'}" id="input-title">
                </div>
            </div>

            <div class="row">
                <label class="col col-lg-2 control-label" for="body">{lang key='body'} {lang key='field_required'}</label>
                <div class="col col-lg-4">
                    <textarea name="body" rows="8" style="resize: none;">{$item.body|escape:'html'}</textarea>
                </div>
            </div>

            <div class="row">
                <label class="col col-lg-2 control-label" for="field_date_expire">{lang key='date_expire'}</label>

                <div class="col col-lg-4">
                    <div class="input-group">
                        <input type="text" class="datepicker js-datepicker" name="date_expire" id="field_date_expire"
                            value="{if $item.date_expire != '0000-00-00 00:00:00'}{$item.date_expire|date_format:'%Y-%m-%d %H:%M'}{/if}"
                            data-date-format="YYYY-MM-DD HH:mm:ss">
                        <span class="input-group-addon js-datepicker-toggle"><i class="i-calendar"></i></span>
                    </div>
                </div>
            </div>
        </div>

        {capture name='systems' append='fieldset_before'}
            <div class="row">
                <label class="col col-lg-2 control-label" for="input-language">{lang key='language'}</label>
                <div class="col col-lg-4">
                    <select name="lang" id="input-language"{if count($core.languages) == 1} disabled{/if}>
                        {foreach $core.languages as $code => $language}
                            <option value="{$code}"{if $item.lang == $code} selected{/if}>{$language.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        {/capture}

        {include 'fields-system.tpl' datetime=true}
    </div>
</form>

{ia_add_media files='datepicker'}