{extends file="layout.tpl"}
{block name="title"}{if !empty($page.seo_title_page)}{$page.seo_title_page}{else}{#seo_faq_title#}{/if}{/block}
{block name="description"}{if !empty($page.seo_desc_page)}{$page.seo_desc_page}{else}{#seo_faq_desc#}{/if}{/block}
{block name='body:id'}faq{/block}
{block name="webType"}FAQPage{/block}
{block name="styleSheet"}
    {$css_files = ["faq"]}{/block}
{block name='article'}
    <article id="article" class="container" class="col-xs-12" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
    {block name='article:content'}
        <h1 itemprop="name">{$page.name_page}</h1>
        {$page.content_page}
        {if $faq_config.mode_faq === 'list'}
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
            <meta itemprop="itemListOrder" content="Unordered" />
            <meta itemprop="numberOfItems" content="{$QAs|count}" />
        {foreach $QAs as $QA}
            <div class="panel panel-default" itemprop="itemListElement" itemscope itemtype="http://schema.org/Question">
                <meta itemprop="answerCount" content="1">
                <div class="panel-heading" role="tab" id="heading{$QA.id_qa}">
                    <h2 class="panel-title {if ((!isset($smarty.get.open) && $QA@first) || $smarty.get.open == $QA.id_qa) && $faq_config.accordion_mode}open{else}collapsed{/if}" data-toggle="collapse"{if $faq_config.accordion_mode} data-parent="#accordion"{/if} data-target="#qa_{$QA.id_qa}" aria-expanded="true" aria-controls="collapse{$QA@index +1}">
                        <span itemprop="name">{$QA.title}</span>
                        <span class="icon">
                            <span class="show-more"><i class="ico ico-add"></i></span>
                            <span class="show-less"><i class="ico ico-remove"></i></span>
                        </span>
                    </h2>
                </div>
                <div id="qa_{$QA.id_qa}" class="panel-collapse collapse{if ((!isset($smarty.get.open) && $QA@first) || $smarty.get.open == $QA.id_qa) && $faq_config.accordion_mode} in{/if}" role="tabpanel" aria-labelledby="heading{$QA.id_qa}">
                    <div class="panel-body" itemprop="acceptedAnswer" itemscope itemtype="http://schema.org/Answer">
                        <span itemprop="answerExplanation">{$QA.content}</span>
                    </div>
                </div>
            </div>
        {/foreach}
        </div>
        {else}
        <ul class="link-list" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
            <meta itemprop="itemListOrder" content="Unordered" />
            <meta itemprop="numberOfItems" content="{$QAs|count}" />
        {foreach $QAs as $QA}
              <li itemprop="itemListElement" itemscope itemtype="http://schema.org/Question"><a href="{$url}/{$lang}/faq/{$QA.id_qa}-{$QA.url}/" itemprop="url">{$QA.title}</a></li>
        {/foreach}
        </ul>
        {/if}
    {/block}
    </article>
{/block}