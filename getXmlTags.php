<?php
require_once("define.php");
header('Content-Type: application/json');
$tags = GetXmlTags();
echo json_encode($tags, JSON_UNESCAPED_UNICODE);
function GetXmlTags()
{
    global $xml;
    // 取得所有標籤
    $tags = [];
    $tags[] = STR_ALL_RANDOM;
    foreach ($xml->row as $row) {
        $rowTags = explode(',', $row['tags']);
        foreach ($rowTags as $tag) {
            $tag = trim($tag);
            if (!empty($tag) && !in_array($tag, $tags)) {
                $tags[] = $tag;
            }
        }
    }
    return $tags;
}
?>