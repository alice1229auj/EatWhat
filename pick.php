<?php
require_once("define.php");

$LastChoose = GetLastSelect();

//header('Content-Type: application/json; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8

# 這邊不能有其他的 echo 或者 print，不然無法正常回傳
# 只能回傳JSON格式
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //print("REQUEST_METHOD = " + $_POST);  
    if(isset($_POST['pickType'])){
        $pickType = $_POST['pickType'];
        // 存選擇
        $_SESSION[SESSION_NAME_LAST_CHOOSE] = $pickType;
        // 結果
        $pickResult = PickOne($pickType);
        
        echo json_encode(array(
            'pickType' => $pickType,
            'pickResult' => $pickResult
        ));
    }
    else{
        echo json_encode(array(
            'Error' => "No result"
        ));
    }
}

function GetLastSelect()
{
    if(isset($_SESSION[SESSION_NAME_LAST_CHOOSE])){
        return $_SESSION[SESSION_NAME_LAST_CHOOSE];
    }
    else{
        return '';
    }
}

function PickOne($pickyType){
    $tag = $pickyType === STR_ALL_RANDOM ? null : $pickyType;

    return pickOneByTag($tag);
}

/** 
 * 抽籤
 * @param string $tag 指定的Tag, null則全部可以選擇
*/
function pickOneByTag($tag = null){
    global $xml;
    $all_names=[];
    foreach ($xml->row as $row) {
        if ($tag === null || in_array(trim($tag), array_map('trim', explode(',', $row['tags'])))) {
            $all_names[] = (string)$row['name'];
        }
    }

    $randomIndex = array_rand($all_names);

    return $all_names[$randomIndex];
}

?>