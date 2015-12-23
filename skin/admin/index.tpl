{extends file="layout.tpl"}
{block name='body:id'}plugins-{$pluginName}{/block}
{block name="styleSheet" append}
    {include file="css.tpl"}
{/block}
{block name="article:content"}
    {include file="nav.tpl"}
    <!-- Notifications Messages -->
    <div class="mc-message clearfix"></div>
    <p id="addbtn"{if {$pages|count} == 4} class="hide""{/if}>
        <a class="btn btn-primary" href="{$pluginUrl}&amp;getlang={$smarty.get.getlang}&amp;action=add">
            <span class="fa fa-plus"></span>
            {#add_faq#|ucfirst}
        </a>
    </p>
    <!-- Maintenance Messages -->
    {*<p class="col-sm-12 alert alert-info fade in">
        <span class="fa fa-info-circle"></span>
    </p>*}
    <table class="table table-bordered table-condensed table-hover">
        <thead>
        <tr>
            <th>{#question#|ucfirst}</th>
            <th>{#answer#|ucfirst}</th>
            <th><span class="fa fa-edit"></span></th>
            <th><span class="fa fa-trash-o"></span></th>
        </tr>
        </thead>
        <tbody id="list_qa">
        {if isset($pages) && !empty($pages)}
            {include file="loop/list.tpl" pages=$pages}
        {/if}
        {include file="no-entry.tpl" pages=$pages}
        </tbody>
    </table>
    {include file="modal/delete.tpl"}
{/block}
{block name='javascript'}
    {include file="js.tpl"}
{/block}