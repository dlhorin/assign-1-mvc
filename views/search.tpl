{include file='header.tpl'}

{include file='track_plugin.tpl'}

<form action="answer_page.php" method="get">


{if $server_error}
    <p>Server Error: Unfortunately, the search function is down at the moment. Please refresh your browser or contact the webmaster if it continues.
    </p>

{else}



    <table id="search_table">

    {if isset($errors)}
    <tr>
        <td colspan='2' style='color:red'>
        <b>!</b> Your form has errors. Please correct and submit again
        </td>
    </tr>
    {/if}    

    <tr>
        <th colspan='2'>Search for a wine</th><td></td>
    </tr>

    <tr>
        <td class='label'>Wine name (or part of):</td> 
        <td class='input'><input type="text" name="wine_name" size="20" class='full_width' {if isset($form)}value='{$form.wine_name}'{/if}/></td>
    </tr>


    <tr>
        <td class='label'>Winery name (or part of):</td> 
        <td class='input'><input type="text" name="winery_name" size="20" class='full_width' {if isset($form)}value='{$form.winery_name}'{/if}/></td>
    </tr>


    <tr>
        <td class='label'>Region:</td>
        <td class='input'>
            <select name="region_id" id="region_id" class='full_width'>
                {section name=i loop=$regions}
                <option value="{$regions[i]['region_id']}" {if isset($form)}{if $regions[i]['region_id'] == $form.region_id}selected='selected'{/if}{/if} >
                    {$regions[i]['region']}
                </option>
                {/section}
            </select>
        </td>
    </tr>


    <tr>
        <td class='label'>Grape variety:</td>
        <td class='input'>
            <select name="variety_id" id="variety_id" class='full_width'>
                {section name=i loop=$varieties}
                <option value="{$varieties[i]['variety_id']}" {if isset($form) && $varieties[i]['variety_id'] == $form.variety_id}selected='selected'{/if}>
                    {$varieties[i]['variety']}
                </option>
                {/section}
            </select>
        </td>
    </tr>


    <tr>
        <td class='label'>Year range:</td>
        <td class='input'>
            <select name="year_min" id="year_min" class='half_width'>
            {section name=i loop=$years}
                <option value="{$years[i]['year']}" {if isset($form) && $years[i]['year'] == $form.year_min}selected='selected'{/if}>
                    {$years[i]['year']}
                </option>
            {/section}
            </select>

          -to-  

        <select name="year_max" id="year_max" class='half_width'>
        {section name=i loop=$years}
            <option value="{$years[i]['year']}" {if isset($form) && $years[i]['year'] == $form.year_max}selected='selected'{/if}>{$years[i]['year']}</option>
        {/section}
        </select>

        </td>
    </tr>

    {if isset($errors)&& $errors.compare_years }
    <tr>
        <td></td><td class="error">{$errors.compare_years}</td>
    </tr>
    {/if}

    <tr>
        <td class='label'>Minimum in stock:</td> 
        <td class='input'><input type="text" name="min_on_hand" size="20" class='full_width' {if isset($form)}value='{$form.min_on_hand}'{/if} /></td>
    </tr>

    {if isset($errors) && $errors.min_on_hand}
    <tr>
        <td></td><td class="error">{$errors.min_on_hand}</td>
    </tr>
    {/if}

    <tr>
        <td class='label'>Minimum ordered:</td> 
        <td class='input'><input type="text" name="min_ordered" size="20" class='full_width' {if isset($form)}value='{$form.min_ordered}'{/if} /></td>
    </tr>

    {if isset($errors) && $errors.min_ordered}
    <tr>
        <td></td><td class="error">{$errors.min_ordered}</td>
    </tr>
    {/if}

    <tr>
        <td class='label'>Price Range:</td>
        <td class='input'>
        <input type="text" name="cost_min" size="6" class='half_width' {if isset($form)}value='{$form.cost_min}'{/if} />
          -to-  
        <input type="text" name="cost_max" size="6" class='half_width' {if isset($form)}value='{$form.cost_max}'{/if}/>
        </td>
    </tr>

    {if isset($errors) && array_key_exists('cost', $errors) &&  $errors.cost}
    <tr>
        <td></td><td class="error">{$errors.cost}</td>
    </tr>
    {/if}

    {if isset($errors) && $errors.compare_costs }
    <tr>
        <td></td><td class="error">{$errors.compare_costs}</td>
    </tr>
    {/if}


    <tr>
        <td class='label'></td>
        <td class='input'>
        <input type="submit" value="Submit" name="submit_search" class="submit"/>
        </td>
    </tr>


    </table>
{/if}


{include file='footer.tpl'}
