<?php
define('STR_ALL_RANDOM', "完全隨機");
define('SESSION_NAME_LAST_CHOOSE', "LastChoose");

// Xml define
define('FILE_NAME', "lunch.xml");
define("XML_NAME", "name");
define("XML_TAGS", "tags");

$xml = simplexml_load_file(FILE_NAME);

function GetXmlMaxRow()
{
    global $xml;
    return count($xml->row);
}

function FormatXml()
{
    // 載入XML檔案
    $file = FILE_NAME;
    $dom = new DOMDocument();
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->load($file);
    // 將格式化後的XML內容保存回檔案
    $dom->save($file);
}
?>