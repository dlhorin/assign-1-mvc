<?php
require_once('config.php');
require_once('db.php');

$db = db_connect();

if(!$db)
    db_showerror();

//$query = 'select wine_name as \'Wine\', year as Year, winery_name as \'Winery\', region_name as \'Region\', on_hand, cost as Price, sum(qty) as num_ordered, sum(price) as Revenue from wine, winery, region, wine_variety, grape_variety, inventory, items where wine.winery_id=winery.winery_id and winery.region_id=region.region_id and wine.wine_id=wine_variety.wine_id and wine_variety.variety_id=grape_variety.variety_id and wine.wine_id=items.wine_id and inventory.wine_id=wine.wine_id ';

   $query = "select wine_name as 'Wine', year as Year, winery_name as 'Winery', region_name as 'Region', on_hand, cost as Price, sum(qty) as num_ordered, sum(price) as Revenue from wine, winery, region, wine_variety, grape_variety, inventory, items where wine.winery_id=winery.winery_id and winery.region_id=region.region_id and wine.wine_id=wine_variety.wine_id and wine_variety.variety_id=grape_variety.variety_id and wine.wine_id=items.wine_id and inventory.wine_id=wine.wine_id";
$query = $query . ' GROUP BY wine.wine_id';
$query = $query . ' ORDER BY wine.wine_name';

echo $query;

$stmt = $db->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row){
    foreach($row as $key => $val){
        echo $key.' => '.$val."<br/>\n";
    }
}

?>
