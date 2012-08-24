{include file='header.tpl'}

<p>

{if $server_error}
    Well, this is embarrassing<br/>
    We seem to be experiencing a problem processing your query<br/>
    Please try again later, or if the problem persists, contact the webmaster
    on webmaster [[at]] mywinesearch.org

{elseif !isset($table_data) || !$table_data || !count($table_data)}
Your search did not match any of our wines.

{else}

<table id="answer_table">

    <tr>
        <th>Wine</th><th>Year</th><th>Grape Varieties</th><th>Winery</th>
        <th>Region</th><th>Cost</th><th>Amount Stocked</th><th>Amount Sold</th>
        <th>Revenue</th>
    </tr>

{foreach from=$table_data item=wine}
    <tr>
        <td>{$wine->Wine}</td><td>{$wine->Year}</td>
        <td>{$wine->grape_varieties}</td><td>{$wine->Winery}</td>
        <td>{$wine->Region}</td><td>{$wine->Cost}</td>
        <td>{$wine->AmountStocked}</td><td>{$wine->AmountSold}</td>
        <td>{$wine->Revenue}</td>
    </tr>
{/foreach}

</table>

{/if}

</p>


<form action="search_page.php" method="get">
<input type="submit" value="Back to Search Page" class="submit"/>
</form>


{include file='footer.tpl'}
