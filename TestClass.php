<?
use \Bitrix\Main\Loader; 
use \Bitrix\Main\Data\Cache;
use \Bitrix\Iblock\ElementTable;

class TestClass{

    const div = "_";

    public static function getList($iblockID = false, $order = array("ID" => "ASC"), $filter = false, $select = array("ID", "NAME"), $cacheTime = 3600, $cacheDir = "test_cache", $test = false){
        $result = false;
        if(Loader::includeModule("iblock")){
            
            if($iblockID && is_numeric($iblockID)){
                $tmpFilter = array("IBLOCK_ID" => intval($iblockID));
                if($filter && is_array($filter) && count($filter) > 0){
                    $filter = array_merge($tmpFilter, $filter);
                }else{
                    $filter = $tmpFilter;
                }
                
                $cacheId = "cache" . self::div . "test" . self::div . $iblockID . self::div . self::arr($order) . self::div . self::arr($filter) . self::div. self::arr($select);
                $cache = Cache::createInstance(); 
                if ($cache->initCache($cacheTime, $cacheId, $cacheDir))
                {
                    $result = $cache->getVars();
                    if($test){
                        echo "cache";
                    }
                    
                }elseif ($cache->startDataCache()){
                    $result = array();
                    
                    $getListArray = array();
                    
                    if(is_array($order) && count($order) > 0){
                        $getListArray["order"] = $order;    
                    }
                    
                    $getListArray["filter"] = $filter;    
                    
                    if(is_array($select) && count($select) > 0){
                        $getListArray["select"] = $select;    
                    }
                    
                    
                    $resDB = ElementTable::getList($getListArray);
                    
                    while($arr = $resDB->fetch()){
                        $result[] = $arr;
                    }
                    
                    if(count($result) <= 0){
                        $result = false;
                        $cache->abortDataCache();
                    }
                    
                    $cache->endDataCache($result);
                }
                
            }
        }
        
        return $result;
    }
    
    protected static function arr($arr){
        if($arr && is_array($arr)){
            $temp = array();
            foreach($arr as $key => $val){
                $temp[] = $key.self::div.$val;    
            }
            return implode(self::div, $temp);
        }
        return "";
    }    
}
?>