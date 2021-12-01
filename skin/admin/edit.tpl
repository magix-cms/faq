{extends file="layout.tpl"}
{block name='head:title'}faq{/block}
{block name='body:id'}faq{/block}
{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}">{#root_faq#}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
        <div class="panels row">
            <section class="panel col-xs-12 col-md-12">
                {if $debug}
                    {$debug}
                {/if}
                <header class="panel-header">
                    <h2 class="panel-heading h5">{if $edit}{#edit_qa#|ucfirst}{else}{#add_qa#|ucfirst}{/if}</h2>
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>
                    {include file="form/qa.tpl" controller="faq"}
                </div>
            </section>
        </div>
    {/if}
{/block}

{block name="foot" append}
    {include file="section/footer/editor.tpl"}
{/block}