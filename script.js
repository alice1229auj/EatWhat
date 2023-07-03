document.addEventListener('DOMContentLoaded', function () {
    updateItems();

    ChangeXmlTableVisable(false);
    ChangeBlockAndDelBtnVisable(false);

    PickOneAddEventListener();

    //#region addEventListener : xml modify
    AddXmlFormAddEventListener();
    GetXmlDataAddEventLister();
    ChagneXmlDataAddEventLister();
    DelXmlAddEventLister();
    //#endregion

    //#region addEventListener : visable modify
    BtnVisableCtrlDataAddEventListener();
    BtnVisableTableAddEventListener();
    //#endregion
});

//#region 抽籤相關
/** 抽籤按鈕的監聽與按下後處理
 *  
 */
function PickOneAddEventListener() {
    const pickbtn = document.getElementById("pick_submit");
    pickbtn.addEventListener('click', function (e) {
        $.ajax({
            type: "POST", // 傳送方式
            url: "pick.php",
            dataType: "json", // 資料格式
            data: {
                pickType: $("#pickType").val()
            },
            success: function (data) {
                if (data.pickType) {
                    //$("#pickForm")[0].reset(); //重設 ID 為 pickForm 的 form (表單)
                    // 調整 id="result" 內容
                    //$("#result").html(data.pickResult);
                    document.getElementById("result").textContent = data.pickResult;
                }
                //console.log("success" + data.pickResult);
            },
            error: function (jqXHR, testStatus, errorThrown) {
                console.log("1 非同步呼叫返回失敗,XMLHttpResponse.readyState:" + jqXHR.readyState);
                console.log("2 非同步呼叫返回失敗,XMLHttpResponse.status:" + jqXHR.status);
                console.log("3 非同步呼叫返回失敗,textStatus:" + testStatus);
                console.log("4 非同步呼叫返回失敗,errorThrown:" + errorThrown);
            }
        })
    });
}
//#endregion

//#region xml modify addEventListener function
function AddXmlFormAddEventListener() {
    const addXmlForm = document.getElementById('addXml');
    addXmlForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const name = document.getElementById('addXmlName').value;
        const tags = document.getElementById('addXmlTags').value;
        addItem(name, tags);
        addXmlForm.reset();
    });
}
function GetXmlDataAddEventLister() {
    const changeXmlS1Form = document.getElementById('formChangeXml_s1');
    changeXmlS1Form.addEventListener('submit', function (e) {
        e.preventDefault();
        const idx = document.getElementById('changeXmlID').value;
        changeItemS1(idx);
    });
}
function ChagneXmlDataAddEventLister() {
    const changeXmlS2Form = document.getElementById('formChangeXml_s2');
    changeXmlS2Form.addEventListener('submit', function (e) {
        e.preventDefault();
        const idx = document.getElementById('changeXmlID').value;
        const name = document.getElementById("changeXmlName").value;
        const tags = document.getElementById("changeXmlTags").value;
        changeItem(idx, name, tags);
    });
}
function DelXmlAddEventLister() {
    const delForm = document.getElementById("delXml");
    delForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const idx = document.getElementById('changeXmlID').value;
        DelItem(idx);
    });
}
//#endregion

//#region Xml管理內容
/**更新Tags項目
 * 
 */
function updateTags() {
    fetch('getXmlTags.php')
        .then(response => response.json())
        .then(data => {
            // 清空原有的选项
            const selectElement = document.getElementById('pickType');
            selectElement.innerHTML = '';

            // 添加新的选项
            for (const tag of data) {
                const option = document.createElement('option');
                option.value = tag;
                option.textContent = tag;
                selectElement.appendChild(option);
            }
        })
        .catch(error => {
            console.error('发生错误：', error);
        });
}
/** 更新 xml 表格內容 + tags
 * 
 */
function updateItems() {
    updateTags();

    const itemList = document.getElementById('tbody_xml_row');
    itemList.innerHTML = '';

    // 發送 GET 請求以獲取 XML 資料
    fetch('lunch.xml')
        .then(response => response.text())
        .then(data => {
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(data, 'text/xml');
            const rows = xmlDoc.getElementsByTagName('row');
            for (let i = 0; i < rows.length; i++) {
                const name = rows[i].getAttribute('name');
                const tags = rows[i].getAttribute('tags');

                const tr = document.createElement('tr');
                for (let j = 0; j < 3; j++) {
                    let content = "";
                    const td = document.createElement('td');
                    switch (j) {
                        case 0:
                            {
                                content = i;
                            }
                            break;
                        case 1:
                            {
                                content = name;
                            }
                            break;
                        case 2:
                            {
                                content = tags;
                            }
                            break;
                        default:
                            break;
                    }
                    td.textContent = content;
                    tr.appendChild(td);
                }

                itemList.appendChild(tr);
            }
        });
}
/** 增加Xml內容
 * @param {*} name 
 * @param {*} tags 
 */
function addItem(name, tags) {
    const xmlResult = document.getElementById("xmlResult");

    // 發送 POST 請求以新增項目到 XML
    const formData = new FormData();
    formData.append('eType', "ADD_XML");
    formData.append('name', name);
    formData.append('tags', tags);

    fetch('changeXml.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            //console.log(data);
            if (data.success) {
                xmlResult.textContent = data.message;// name + "成功增加";
                updateItems();
            }
            else {
                xmlResult.textContent = '新增項目失敗！';
            }

            //loadItems();
        })
        .catch(error => {
            console.error('發生錯誤：', error);
        });
}

/** 調整Xml內容: 輸入編號後顯示目前Xml內容
 * @param {編號} idx 
 */
function changeItemS1(idx) {
    const xmlResult = document.getElementById("xmlResult");
    const formData = new FormData();
    formData.append('eType', "Get_XML_ROWDATA");
    formData.append('idx', idx);

    fetch('changeXml.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                ChangeBlockAndDelBtnVisable(true);
                // 將區域2的數值設定成讀取到的資料
                document.getElementById("changeXmlName").value = data.name;
                document.getElementById("changeXmlTags").value = data.tags;
            }
            else {
                xmlResult.textContent = data.message;
            }
        })
        .catch(error => {
            console.error('發生錯誤：', error);
        });
}

/** 調整Xml內容: 寫入Xml當中
 * @param {*} idx 
 * @param {*} name 
 * @param {*} tags 
 */
function changeItem(idx, name, tags) {
    const xmlResult = document.getElementById("xmlResult");
    const formData = new FormData();
    formData.append('eType', "CHANGE_XML");
    formData.append('idx', idx);
    formData.append('name', name);
    formData.append('tags', tags);

    fetch('changeXml.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            ChangeBlockAndDelBtnVisable(false);
            xmlResult.textContent = data.message;
            updateItems();
        })
        .catch(error => {
            ChangeBlockAndDelBtnVisable(false);
            console.error('發生錯誤：', error);
        });
}

function DelItem(idx) {
    const xmlResult = document.getElementById("xmlResult");
    const formData = new FormData();
    formData.append('eType', "DEL_XML");
    formData.append('idx', idx);
    fetch('changeXml.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            ChangeBlockAndDelBtnVisable(false);
            xmlResult.textContent = data.message;
            updateItems();
        })
        .catch(error => {
            ChangeBlockAndDelBtnVisable(false);
            console.error('發生錯誤：', error);
        });
}
//#endregion

//#region visable modify addEventListener function
function BtnVisableCtrlDataAddEventListener() {
    const btnShowCtrlTablePanel = document.getElementById("btnShowCtrlXmlPanel");
    const btnHideCtrlTablePanel = document.getElementById("btnHideCtrlXmlPanel");
    const tableControlPanel = document.getElementById("tableControlPanel");

    btnShowCtrlTablePanel.addEventListener('click', () => {
        btnShowCtrlTablePanel.style.display = 'none';
        btnHideCtrlTablePanel.style.display = 'block';
        tableControlPanel.style.display = 'block';
    });

    btnHideCtrlTablePanel.addEventListener('click', ()=>{
        btnShowCtrlTablePanel.style.display = 'block';
        btnHideCtrlTablePanel.style.display = 'none';
        tableControlPanel.style.display = 'none';
    });
}

function BtnVisableTableAddEventListener() {
    const btnShowTable = document.getElementById("btnShowAllXml");
    const btnHideTable = document.getElementById("btnHideAllXml");

    btnShowTable.addEventListener('click', () => {
        ChangeXmlTableVisable(true);
    });

    btnHideTable.addEventListener('click', () => {
        ChangeXmlTableVisable(false);
    });
}
//#endregion

//#region 顯示或隱藏
/** 顯示或隱藏 {Name & Tag} + 修改按鈕 + 刪除按鈕
 * @param {是否顯示介面} isVisable 
 */
function ChangeBlockAndDelBtnVisable(isVisable) {
    var newDisplay = isVisable ? 'block' : 'none';

    // 區域2
    const s2Form = document.getElementById("divChangeXmlS2");
    s2Form.style.display = newDisplay;
    // 刪除按鈕區域
    const divDel = document.getElementById("divDelXml");
    divDel.style.display = newDisplay;
}

function ChangeXmlTableVisable(isVisable) {
    var newDisplay = isVisable ? 'block' : 'none';

    const divXmlTable = document.getElementById("div_all_xml_datas");
    divXmlTable.style.display = newDisplay;

}
//#endregion