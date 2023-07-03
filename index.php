<?php
require_once("define.php");

session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>中午吃個飯</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link href="tableStyle.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="/food.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="/food.ico" type="image/x-icon" />
</head>

<body>
    <!-- 選擇抽籤方式 -->
    <form id="pickForm">
        <select name="pickType" id="pickType">
        </select>
        <p></p>
        <button type="button" class="btn_pick_submit" id="pick_submit">　抽!　</button>
    </form>
    <h2>Result:</h2>
    <p id="result">　　　</p> <!-- 顯示回傳資料 -->
    
    <table>
        <tr>
            <td>
                <!-- 顯示Xml 內容 -->
                <button type="button" id="btnShowAllXml">顯示Xml</button>
            </td>
            <td>
                <!--  Xml管理系列  -->
                <button type="button" id="btnShowCtrlXmlPanel"> 顯示管理Xml </button>
                <button type="button" id="btnHideCtrlXmlPanel"> 隱藏管理Xml </button>
            </td>
        </tr>
    </table>
    
    <div id="tableControlPanel">        
        <h4>新增</h4>
        <form id="addXml">
            <label for="addXmlName">名稱：</label>
            <input type="text" name="addXmlName" id="addXmlName" required>
            <label for="addXmlTags">標籤：</label>
            <input type="text" name="addXmlTags" id="addXmlTags" required>
            <button type="submit" id="addXmlSubmit">新增</button>
        </form>
        <h4>修改 或 刪除</h4>
        <form id="formChangeXml_s1">
            <label for="changeXmlID">編號：</label>
            <input type="text" name="changeXmlID" id="changeXmlID" pattern="[0-9]+" placeholder="正整數" required>
            <button type="submit" id="changeXml_step1">顯示Xml內容</button>
        </form>
        <div id="divChangeXmlS2">
            <!-- 空一行 -->
            <p></p>
            <form id="formChangeXml_s2">
                <label for="changeXmlName">名稱：</label>
                <input type="text" name="changeXmlName" id="changeXmlName" required>
                <label for="changeXmlTags">標籤：</label>
                <input type="text" name="changeXmlTags" id="changeXmlTags" required>
                <button type="submit" id="btnChangeXmlStep2">調整</button>
            </form>
        </div>
        <div id="divDelXml">
            <form id="delXml">
                <button type="submit" id="btnDelXml">刪除</button>
            </form>
        </div>
        <!-- Xml調整結果 -->
        <h4>Xml變動結果:</h4>
        <p id="xmlResult"></p>
    </div>
    
    <div id="div_all_xml_datas">
        <button type="button" id="btnHideAllXml">隱藏Xml</button>
        <p></p>
        <table class="txmlAll">
            <thead>
                <th>ID</th>
                <th>Name</th>
                <th>Tags</th>
            </thead>
            <tbody id="tbody_xml_row">
            </tbody>
        </table>
    </div>
    <script src="script.js"></script>
</body>

</html>