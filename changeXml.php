<?php
require_once("define.php");

define("ETYPE_ADD_XML", "ADD_XML");
define("ETYPE_DEL_XML", "DEL_XML");
define("ETYPE_GET_INDEX_DATA", "Get_XML_ROWDATA");
define("ETYPE_CHANGE_XML", "CHANGE_XML");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['eType'])) {
        //region
        switch($_POST['eType'])
        {
            case ETYPE_ADD_XML:
                {
                    AddXml($_POST["name"], $_POST["tags"]);
                }
                break;
            case ETYPE_GET_INDEX_DATA:
                {
                    GetXmlIDData($_POST["idx"]);
                }
                break;
            case ETYPE_CHANGE_XML:
                {
                    ChangeXml($_POST["idx"], $_POST["name"], $_POST["tags"]);
                }
                break;
            case ETYPE_DEL_XML:
                {DelXml($_POST["idx"]);}break;
            default:
            {
                EchoError("錯誤的類型".$_POST['eType']);
            }
            break;
        }
        //endregion
    }
}


function AddXml($name, $tags)
{
    $response = array();
    if ($name != null && $tags != null) {
        global $xml;
        $row = $xml->addChild('row');
        $row->addAttribute(XML_NAME, $name);
        $row->addAttribute(XML_TAGS, $tags);

        $xml->asXML(FILE_NAME);
        FormatXml();

        $response['success'] = true;
        $response['message'] = "name = " . $name . " 項目已成功新增！";
    } else {
        $response['success'] = false;
        $response['message'] = 'Name 或者 TAGS 資料為空';
    }
    echo json_encode($response);
}

function GetXmlIDData($uncheckedId)
{
    $response = array();
    global $xml;
    $id = (int) $uncheckedId;
    // 最大行數
    $maxRows = getXmlMaxRow();
    if ($id >= 0 && $id < $maxRows) {
        $response['success'] = true;
        $response['message'] = "Show";
        $rowData = $xml->row[$id];

        $response['name'] = (string) $rowData[XML_NAME];
        $response['tags'] = (string) $rowData[XML_TAGS];
        // 保留 Unicode 字符而不進行轉譯
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } else {
        EchoErrorRange();
    }
}
function ChangeXml($uncheckedId, $name, $tags)
{
    $response = array();
    global $xml;
    $id = (int) $uncheckedId;
    // 最大行數
    $maxRows = getXmlMaxRow(); // count($xml->row);
    if ($id >= 0 && $id < $maxRows) {
        // $xml->row[][] 這邊沒有問題，但不知道為甚麼檢查會有錯
        $xml->row[$id]['name'] = $name;
        $xml->row[$id]['tags'] = $tags;
        $xml->asXML('lunch.xml');

        FormatXml();

        $response['success'] = true;
        $response['message'] = "成功調整" . $name;
        // 保留 Unicode 字符而不進行轉譯
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } else {
        EchoErrorRange();
    }
}

function DelXml($itemIndex)
{
    global $xml;

    // 最大行數
    $maxRows = count($xml->row);

    // 檢查索引是否有效
    if ($itemIndex >= 0 && $itemIndex < $maxRows) {
        // 從 XML 中刪除指定的項目
        unset($xml->row[(int) $itemIndex]);

        // 將修改後的 XML 寫回到檔案
        $xml->asXML('lunch.xml');

        FormatXml();

        // 回傳成功訊息
        $response = array(
            'success' => true,
            'message' => '項目已成功刪除。'
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } else {
        EchoErrorRange();
    }    
}
/**
 * 印出應該要填入的數值範圍
 *
 * @return void
 */
function EchoErrorRange(){
    $maxRows = getXmlMaxRow()-1;
    EchoError("GetXmlIDData id range is 0 ~ " . $maxRows);
}
function EchoError($strFrom)
{
    $response['success'] = false;
    $response['message'] = "Error: " . $strFrom;
    echo json_encode($response);
}