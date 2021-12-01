{extends file="layout.tpl"}
{block name="title"}{if !empty($QA.seo_title_qa)}{$QA.seo_title_qa}{else}{#seo_faq_title#}{/if}{/block}
{block name="description"}{if !empty($QA.seo_desc_qa)}{$QA.seo_desc_qa}{else}{#seo_faq_desc#}{/if}{/block}
{block name='body:id'}faq{/block}
{block name="webType"}QAPage{/block}
{block name="styleSheet"}
    {$css_files = ["/skin/{$theme}/css/faq{if $setting.mode.value !== 'dev'}.min{/if}.css"]}
{/block}

{block name='article'}
    <article id="article" class="container" class="col-xs-12" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
    {block name='article:content'}
        <meta itemprop="name" content="{$QA.title_qa}"/>
        <div itemprop="hasPart" itemscope itemtype="http://schema.org/Question">
            <meta itemprop="answerCount" content="1">
            <h1 itemprop="name">{$QA.title_qa}</h1>
            <div itemprop="acceptedAnswer" itemscope itemtype="http://schema.org/Answer">
                <span itemprop="answerExplanation">{$QA.desc_qa}</span>
            </div>
        </div>
    {/block}
    </article>
{/block}