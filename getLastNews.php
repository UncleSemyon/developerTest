<?
define("RSS_NEWS", "https://lenta.ru/rss");
define("MAX", 5);
$rss = file_get_contents(RSS_NEWS);
$news = new SimpleXMLElement($rss);
$counter = 0;
foreach($news->channel->item as $n){ 
    $counter++;
    echo "TITLE: ".$n->title."\n";
    echo "LINK: ".$n->link."\n";
    echo "DESCRIPTION: ".$n->description."\n";
    echo "---------------------------------------------\n";
    if($counter >= MAX){
        break;
    }
}
?>