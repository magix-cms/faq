{extends file="layout.tpl"}
{block name='head:title'}faq{/block}
{block name='body:id'}faq{/block}
{block name="stylesheets" append}
    <link rel="stylesheet" href="/{baseadmin}/min/?f=plugins/{$smarty.get.controller}/css/admin.min.css" media="screen" />
{/block}
{block name='article:header'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
        <div class="pull-right">
            <p class="text-right">
                <a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" title="{#add_qa_btn#}" class="btn btn-link">
                    <span class="fa fa-plus"></span> {#add_qa_btn#|ucfirst}
                </a>
            </p>
        </div>
    {/if}
    <h1 class="h2">FAQ</h1>
{/block}
{block name="article:content"}
    {if {employee_access type="view" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header panel-nav">
                <h2 class="panel-heading h5">{#root_faq#}</h2>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#qas" aria-controls="qas" role="tab" data-toggle="tab">{#qas#}</a></li>
                    <li role="presentation"><a href="#text" aria-controls="text" role="tab" data-toggle="tab">{#text#}</a></li>
                    <li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab">{#config#}</a></li>
                </ul>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="qas">
                        {include file="section/form/table-form-3.tpl" idcolumn='id_qa' data=$qas activation=false sortable=true controller="faq" change_offset=false}
                    </div>
                    <div role="tabpanel" class="tab-pane" id="text">
                        {include file="form/content.tpl" controller="faq"}
                    </div>
                    <div role="tabpanel" class="tab-pane" id="config">
                        <div class="row">
                            <form id="edit_config" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$faq_config.id_config}" method="post" class="validate_form edit_form col-ph-12 col-md-4">
                                <div class="form-group">
                                    <label>{#mode_faq#}</label>
                                    <div id="mode_faq" class="btn-group">
                                        <label for="mode_list" class="btn {if $faq_config.mode_faq === 'list'}btn-main-theme{else}btn-default{/if}">
                                            <input type="radio" name="faq_config[mode_faq]" value="list" id="mode_list" autocomplete="off"{if $faq_config.mode_faq === 'list'} checked{/if}> {#mode_list#}
                                        </label>
                                        <label for="mode_pages" class="btn {if $faq_config.mode_faq === 'pages'}btn-main-theme{else}btn-default{/if}">
                                            <input type="radio" name="faq_config[mode_faq]" value="pages" id="mode_pages" autocomplete="off"{if $faq_config.mode_faq === 'pages'} checked{/if}> {#mode_pages#}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="switch">
                                        <input type="checkbox" id="random" name="faq_config[accordion_mode]" class="switch-native-control"{if $faq_config.accordion_mode} checked{/if} />
                                        <div class="switch-bg">
                                            <div class="switch-knob"></div>
                                        </div>
                                    </div>
                                    <label for="random">{#accordion_mode#}</label>
                                </div>
                                <div id="submit">
                                    <input type="hidden" id="id_config" name="faq_config[id_config]" value="{$faq_config.id_config}">
                                    <button class="btn btn-main-theme" type="submit">{#save#|ucfirst}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    {include file="modal/delete.tpl" data_type='qa' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_faq_message#}}
    {include file="modal/error.tpl"}
    {else}
        {include file="section/brick/viewperms.tpl"}
    {/if}
{/block}

{block name="foot" append}
    {include file="section/footer/editor.tpl"}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=libjs/vendor/jquery-ui-1.12.min.js,
        {baseadmin}/template/js/table-form.min.js,
        plugins/faq/js/admin.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}
    <script type="text/javascript">
        $(function(){
            var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}";
            var offset = "{if isset($offset)}{$offset}{else}null{/if}";
            if (typeof tableForm == "undefined")
            {
                console.log("tableForm is not defined");
            }else{
                tableForm.run(controller,offset);
            }
        });
    </script>
{/block}