{foreach $pages as $page}
    <tr id="order_{$page.id}">
        <td><a href="{$pluginUrl}&amp;getlang={$smarty.get.getlang}&amp;action=edit&amp;edit={$page.id}">{$page.title}</a></td>
        <td>{if $page.content}<span class="fa fa-check"></span>{else}<span class="fa fa-warning"></span>{/if}</td>
        <td><a href="{$pluginUrl}&amp;getlang={$smarty.get.getlang}&amp;action=edit&amp;edit={$page.id}"><span class="fa fa-edit"></span></a></td>
        <td><a class="toggleModal" data-toggle="modal" data-target="#deleteModal" href="#{$page.id}"><span class="fa fa-trash-o"></span></a></td>
    </tr>
{/foreach}