{extends file="layout.tpl"}
{block name="title"}{seo_rewrite config_param=['level'=>'0','idmetas'=>'1','default'=>#seo_t_static_plugin_faq#]}{/block}
{block name="description"}{seo_rewrite config_param=['level'=>'0','idmetas'=>'2','default'=>#seo_d_static_plugin_faq#]}{/block}
{block name='body:id'}faq{/block}
{block name="webType"}QAPage{/block}

{block name='article'}
    <article id="article" class="col-xs-12" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
    {block name='article:content'}
        <h1 itemprop="name">{#faq_h1#|ucfirst}</h1>
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            {foreach $faq as $QA}
                <div class="panel panel-default" itemprop="hasPart" itemscope itemtype="http://schema.org/Question">
                    <div class="panel-heading" role="tab" id="heading{$QA@index +1}">
                        <h2 class="panel-title">
                            <a{if !$QA@first} class="collapsed"{/if} role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{$QA@index +1}" aria-expanded="true" aria-controls="collapse{$QA@index +1}">
                                <span class="fa"></span><span itemprop="name">{$QA.title}</span>
                            </a>
                        </h2>
                    </div>
                    <div id="collapse{$QA@index +1}" class="panel-collapse collapse {if $QA@first}in{/if}" role="tabpanel" aria-labelledby="heading{$QA@index +1}">
                        <div class="panel-body" itemprop="acceptedAnswer" itemscope itemtype="http://schema.org/Answer">
                            <span itemprop="text">{$QA.content}</span>
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>
    {/block}
    </article>
{/block}