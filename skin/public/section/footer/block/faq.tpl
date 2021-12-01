{if $smarty.get.controller !== 'faq'}{widget_faq_data}{/if}
{if !empty($page)}
    <div class="col-12 col-sm-6 col-md-3 col-lg-2 block">
        <p class="h4"><a href="{$url}/{$lang}/faq/" title="{$page.name_page}">{$page.name_page}</a></p>
        {if !empty($QAs)}
        <ul class="link-list list-unstyled">
            {foreach $QAs as $QA}
                <li><a href="{$url}/{$lang}/faq/{$QA.id_qa}-{$QA.url}/" title="{$QA.title}">{$QA.title}</a></li>
            {/foreach}
        </ul>
        {/if}
    </div>
{/if}