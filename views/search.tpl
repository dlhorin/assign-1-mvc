{include file='header.tpl'}

<form action="answer_page.php" method="get">
<h4> Search for a wine</h4>
<table id="search_table">

<tr>
<td>Enter wine name (or part of):</td> <td><input type="text" name="wine_name" size="20" /></td>
</tr>

<tr>
<td>Enter winery name (or part of):</td> <td><input type="text" name="winery_name" size="20" /></td>
</tr>

<tr>
<td>Select region:</td>
<td>
<select name="region_id" id="region_id">
{section name=i loop=$regions}
    <option value="{$regions[i]['region_id']}">{$regions[i]['region']}</option>
{/section}
</td>
</tr>

<tr>
<td>Select grape variety:</td>
<td>
<select name="variety_id" id="variety_id">
{section name=i loop=$varieties}
    <option value="{$varieties[i]['variety_id']}">{$varieties[i]['variety']}</option>
{/section}
</select>
</td>
</tr>

<tr>
<td>Year: (select range)</td>
<td>
<select name="year_min" id="year_min">
{section name=i loop=$years}
    <option value="{$years[i]['year']}">{$years[i]['year']}</option>
{/section}
</select>

  -to-  

<select name="year_max" id="year_max">
{section name=i loop=$years}
    <option value="{$years[i]['year']}">{$years[i]['year']}</option>
{/section}
</select>

</td>
</tr>

<tr>
<td>Minimum amount in stock:</td> <td><input type="text" name="min_on_hand" size="20" /></td>
</tr>

<tr>
<td>Minimum amount ordered:</td> <td><input type="text" name="min_ordered" size="20" /></td>
</tr>

<tr>
<td>Price Range:</td>
<td>
<input type="text" name="cost_min" size="6" />
  -to-  
<input type="text" name="cost_max" size="6" />
</td>
</tr>
</table>

<input type="submit" value="Submit" name="submit_search"/>

{include file='footer.tpl'}
