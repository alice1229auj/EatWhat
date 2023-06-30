<?php
require_once("define.php");

define("ETYPE_ADD_XML", "ADD_XML");
define("ETYPE_DEL_XML", "DEL_XML");
define("ETYPE_CHANGE_XML_1", "CHANGE_XML_1");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['eType'])) {
        echo "EType" + $_POST['eType'];
        if($_POST['eType'] === ETYPE_ADD_XML){
            AddXml($_POST["name"], $_POST["tags"]);
        }
    }
}
function ReturnError($from)
{
    echo json_encode(array(
        'Error' => "No result",
        'Result'=> "000"
    ));
}
function AddXml($name, $tags)
{
    ReturnError(ETYPE_ADD_XML);
    return;

    if ($name != null && $tags != null) {
        global $xml;
        $row = $xml->addChild('row');
        $row->addAttribute('name', $name);
        $row->addAttribute('tags', $tags);

        $xml->asXML(FILE_NAME);
        FormatXml();

        echo json_encode(array(
            'Error' => "No result"
        ));
    } else {
        ReturnError(ETYPE_ADD_XML);
    }
}


?>