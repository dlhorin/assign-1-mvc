{include file='header.tpl'}

<p>

{if isset($table_data)}
Here are all the wines you have viewed so far

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

{else}
You have not viewed any wines so far

{/if}

</p>



{include file='footer.tpl'}
