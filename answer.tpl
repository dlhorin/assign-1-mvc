{include file='header.php'}

<p>

{if $server_error}
    Well, this is embarrassing<br/>
    We seem to be experiencing a problem processing your query<br/>
    Please try again later, or if the problem persists, contact the webmaster
    on webmaster [[at]] mywinesearch.org

{elseif $form_errors}
Your search could not be processed<br/>
Please fix and search again<br/>
<ul>
    {section name=i loop=$form_errors}
        <li>{$form_errors[i]}</li>
    {/section}
</ul>

{elseif !count($table_data)}
Your search did not match any of our wines.

{else}

<table border='1'>

    <tr>
{foreach from=$table_data[0] item=item key=key}
        <th>{$key}</th>
{/foreach}
    </tr>

{foreach from=$table_data item=row}
    <tr>
    {foreach from=$row key=key item=item}
        <td>{$item}</td>
    {/foreach}
    </tr>
{/foreach}
</table>

{/if}

</p>


<form action="search_page.php" method="get">
<input type="submit" value="Back to Search Page"/>
</form>


{include file='footer.php'}
