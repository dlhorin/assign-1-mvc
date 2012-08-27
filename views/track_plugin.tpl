
{if isset($smarty.session.is_tracking)}
<form name='track_plugin' action='stop_tracking.php'>
<a href='view_saved_wines.php' target='_blank'>View all wines you have searched for</a>
<input type='submit' value='Stop Tracking'/>
</form>

{else}
<form name='track_plugin' action='start_tracking.php'>
Track the wines you see, so you can view them later:
<input type='submit' value='Start Tracking'/>
</form>

{/if}



