<?php
require_once("define.php");

session_start();

$tags = GetXmlTags();

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
            if (!in_array($tag, $tags)) {
                $tags[] = $tag;
            }
        }
    }
    return $tags;
}
?>
<?php
###### 筆記 ###### 
# jQuery 的 $.ajax() 筆記
# https://hackmd.io/@nfu-johnny/B1N50eGju

# 如何使用jquery-ajax-submit-傳送form表單serialize-方法
# https://ucamc.com/289-%E5%A6%82%E4%BD%95%E4%BD%BF%E7%94%A8jquery-ajax-submit-%E5%82%B3%E9%80%81form%E8%A1%A8%E5%96%AEserialize-%E6%96%B9%E6%B3%95


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>中午吃個飯</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
    <!-- 選擇抽籤方式 -->
    <form id="pickForm">
        <select name="pickType" id="pickType">
            <?php foreach ($tags as $tag) { ?>
                <option value="<?php echo $tag; ?>">
                    <?php echo $tag; ?>
                </option>
            <?php } ?>
        </select>
        <button type="button" id="pick_submit">抽!</button>
    </form>
    <h2>Result:</h2>
    <p id="result"></p> <!-- 顯示回傳資料 -->
    <!-- 顯示回傳的資料 -->
    <script type="text/javascript">
        $(document).ready(function () {
            $("#pick_submit").click(
                function () {
                    $.ajax(
                        {
                            type: "POST", // 傳送方式
                            url: "pick.php",
                            dataType: "json", // 資料格式
                            data: {
                                pickType: $("#pickType").val()
                            }
                            ,
                            success: function (data) {
                                if (data.pickType) {
                                    //$("#pickForm")[0].reset(); //重設 ID 為 pickForm 的 form (表單)
                                    // 調整 id="result" 內容
                                    $("#result").html(data.pickResult);
                                }
                                //console.log("success" + data.pickResult);
                            },
                            error: function (jqXHR, testStatus, errorThrown) {
                                console.log("1 非同步呼叫返回失敗,XMLHttpResponse.readyState:" + jqXHR.readyState);
                                console.log("2 非同步呼叫返回失敗,XMLHttpResponse.status:" + jqXHR.status);
                                console.log("3 非同步呼叫返回失敗,textStatus:" + testStatus);
                                console.log("4 非同步呼叫返回失敗,errorThrown:" + errorThrown);
                            }
                        }
                    )
                }
            )
        }
        )
    </script>
</body>

</html>