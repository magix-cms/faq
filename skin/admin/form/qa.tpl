<div class="row">
    <form id="edit_qa" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;tabs=qa&amp;action={if !$edit}add{else}edit{/if}" method="post" class="validate_form{if !$edit} add_form collapse in{else} edit_form{/if} col-ph-12 col-sm-8">
        {include file="language/brick/dropdown-lang.tpl"}
        <div class="row">
            <div class="col-ph-12">
                <div class="tab-content">
                    {foreach $langs as $id => $iso}
                        <div role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                            <fieldset>
                                <legend>Texte</legend>
                                <div class="row">
                                    <div class="col-ph-12 col-sm-8">
                                        <div class="form-group">
                                            <label for="title_qa_{$id}">{#title_qa#|ucfirst} :</label>
                                            <input type="text" class="form-control" id="title_qa_{$id}" name="qa[content][{$id}][title_qa]" value="{$qa.content[{$id}].title_qa}" />
                                        </div>
                                    </div>
                                    <div class="col-ph-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="qa[content][{$id}][published_qa]">Statut</label>
                                            <input id="qa[content][{$id}][published_qa]" data-toggle="toggle" type="checkbox" name="qa[content][{$id}][published_qa]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger"{if (!isset($qa) && $iso@first) || $qa.content[{$id}].published_qa} checked{/if}>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group{if $faq_config.mode_faq === 'list'} hide{/if}">
                                    <label for="qa[content][{$id}][url_qa]">{#url_rewriting#|ucfirst}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="qa[content][{$id}][url_qa]" name="qa[content][{$id}][url_qa]" readonly="readonly" size="30" value="{$qa.content[{$id}].url_qa}" />
                                        <span class="input-group-addon">
                                            <a class="unlocked" href="#"><span class="fa fa-lock"></span></a>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="desc_qa_{$id}">{#content_qa#|ucfirst}&nbsp;:</label>
                                    <textarea class="form-control mceEditor" id="desc_qa_{$id}" name="qa[content][{$id}][desc_qa]" cols="65" rows="3">{$qa.content[{$id}].desc_qa}</textarea>
                                </div>
                                <div class="form-group{if $faq_config.mode_faq === 'list'} hide{/if}">
                                    <button class="btn collapsed btn-collapse" role="button" data-toggle="collapse" data-parent="#accordion" href="#metas-{$id}" aria-expanded="true" aria-controls="metas-{$id}">
                                        <span class="fa"></span> {#display_metas#|ucfirst}
                                    </button>
                                </div>
                                <div id="metas-{$id}" class="collapse{if $faq_config.mode_faq === 'list'} hide{/if}" role="tabpanel" aria-labelledby="heading{$id}">
                                    <div class="row">
                                        <div class="col-ph-12 col-sm-8">
                                            <div class="form-group">
                                                <label for="qa[content][{$id}][seo_title_qa]">{#title#|ucfirst} :</label>
                                                <textarea class="form-control" id="qa[content][{$id}][seo_title_qa]" name="qa[content][{$id}][seo_title_qa]" cols="70" rows="3">{$qa.content[{$id}].seo_title_qa}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-ph-12 col-sm-8">
                                            <div class="form-group">
                                                <label for="qa[content][{$id}][seo_desc_qa]">{#desc#|ucfirst} :</label>
                                                <textarea class="form-control" id="qa[content][{$id}][seo_desc_qa]" name="qa[content][{$id}][seo_desc_qa]" cols="70" rows="3">{$qa.content[{$id}].seo_desc_qa}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
        <fieldset>
            <legend>Enregistrer</legend>
            {if $edit}<input type="hidden" name="qa[id_qa]" value="{$qa.id_qa}" />{/if}
            <button class="btn btn-main-theme" type="submit">{#save#|ucfirst}</button>
        </fieldset>
    </form>
</div>