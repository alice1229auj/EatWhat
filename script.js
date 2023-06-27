// 讀取 XML 檔案
function loadXml() {
    fetch('lunch.xml')
      .then(response => response.text())
      .then(xml => {
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(xml, 'application/xml');
  
        // 取得所有的 row 元素
        const rows = xmlDoc.getElementsByTagName('row');
  
        // 顯示 XML 內容
        const xmlContent = document.getElementById('xmlContent');
        xmlContent.innerHTML = xml;
  
        // 顯示篩選標籤的選項
        const tagFilter = document.getElementById('tagFilter');
        const tags = new Set();
  
        for (let i = 0; i < rows.length; i++) {
          const row = rows[i];
          const tagsAttr = row.getAttribute('tags');
          if (tagsAttr) {
            const rowTags = tagsAttr.split(',');
            rowTags.forEach(tag => tags.add(tag.trim()));
          }
        }
  
        tags.forEach(tag => {
          const option = document.createElement('option');
          option.value = tag;
          option.textContent = tag;
          tagFilter.appendChild(option);
        });
      })
      .catch((error) => {
        console.error('無法讀取 XML 檔案:', error);
      });
}

// 全隨機抽籤
function randomSelection() {
  // 隱藏篩選標籤選項
  document.getElementById('filterSection').style.display = 'none';

  fetch('lunch.xml')
    .then(response => response.text())
    .then(xml => {
      const parser = new DOMParser();
      const xmlDoc = parser.parseFromString(xml, 'application/xml');

      // 取得所有的 row 元素
      const rows = xmlDoc.getElementsByTagName('row');

      // 隨機選取一個項目
      const randomIndex = Math.floor(Math.random() * rows.length);
      const selectedRow = rows[randomIndex];
      const selectedName = selectedRow.getAttribute('name');

      // 顯示抽籤結果
      const result = document.getElementById('result');
      result.textContent = `今天的抽籤結果是：${selectedName}`;
    })
    .catch((error) => {
      console.error('無法讀取 XML 檔案:', error);
    });
}

// 顯示篩選標籤選項
function showFilterSection() {
  // 顯示篩選標籤選項
  document.getElementById('filterSection').style.display = 'block';
}

// 根據篩選標籤抽籤
function filterSelection() {
  const tagFilter = document.getElementById('tagFilter');
  const selectedTag = tagFilter.value;

  fetch('lunch.xml')
    .then(response => response.text())
    .then(xml => {
      const parser = new DOMParser();
      const xmlDoc = parser.parseFromString(xml, 'application/xml');

      // 取得所有符合標籤的 row 元素
      const rows = xmlDoc.getElementsByTagName('row');
      const filteredRows = Array.from(rows).filter(row => {
        const tagsAttr = row.getAttribute('tags');
        if (tagsAttr) {
          const rowTags = tagsAttr.split(',');
          return rowTags.includes(selectedTag);
        }
        return false;
      });

      if (filteredRows.length > 0) {
        // 隨機選取一個項目
        const randomIndex = Math.floor(Math.random() * filteredRows.length);
        const selectedRow = filteredRows[randomIndex];
        const selectedName = selectedRow.getAttribute('name');

        // 顯示抽籤結果
        const result = document.getElementById('result');
        result.textContent = `今天的抽籤結果是：${selectedName}`;
      } else {
        // 無符合標籤的項目
        const result = document.getElementById('result');
        result.textContent = '找不到符合標籤的項目';
      }
    })
    .catch((error) => {
      console.error('無法讀取 XML 檔案:', error);
    });
}

// 伸縮 XML 內容
function toggleXml() {
  const xmlContent = document.getElementById('xmlContent');
  const toggleButton = document.querySelector('button');

  if (xmlContent.style.display === 'none') {
    xmlContent.style.display = 'block';
    toggleButton.textContent = '縮合';
  } else {
    xmlContent.style.display = 'none';
    toggleButton.textContent = '展開';
  }
}

// 初始化
function init() {
  loadXml();
}

// 網頁載入完成後執行初始化
window.addEventListener('load', init);

