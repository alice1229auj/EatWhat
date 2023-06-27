<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 读取 XML 文件
    $xml = simplexml_load_file('lunch.xml');

    // 获取所有行数据
    $rows = $xml->row;

    // 获取抽签方式和选定的标签
    $drawMethod = $_POST['draw_method'];
    $selectedTag = $_POST['selected_tag'];

    // 根据抽签方式选择相应的行数据
    if ($drawMethod === 'random') {
        $randomIndex = mt_rand(0, count($rows) - 1);
        $selectedRow = $rows[$randomIndex];
    } elseif ($drawMethod === 'tag') {
        $filteredRows = [];
        foreach ($rows as $row) {
            $tags = explode(',', str_replace(' ', '', (string)$row['tags']));
            if (in_array($selectedTag, $tags)) {
                $filteredRows[] = $row;
            }
        }
        $randomIndex = mt_rand(0, count($filteredRows) - 1);
        $selectedRow = $filteredRows[$randomIndex];
    }

    // 将抽中的行数据传递给模板文件
    $result = (string)$selectedRow['name'];

    // 将选定的标签和抽签方式存储到会话中
    $_SESSION['selected_tag'] = $selectedTag;
    $_SESSION['draw_method'] = $drawMethod;

    // 引入 HTML 模板
    include 'index.html';
} else {
    // 显示初始的 HTML 表单
    $xml = simplexml_load_file('lunch.xml');
    $tags = [];
    foreach ($xml->row as $row) {
        $tagsString = (string)$row['tags'];
        if (!empty($tagsString)) {
            $tags = array_merge($tags, explode(',', str_replace(' ', '', $tagsString)));
        }
    }
    $tags = array_unique($tags);

    // 恢复之前存储的选定的标签和抽签方式
    $selectedTag = isset($_SESSION['selected_tag']) ? $_SESSION['selected_tag'] : '';
    $drawMethod = isset($_SESSION['draw_method']) ? $_SESSION['draw_method'] : '';

    include 'index.html';
}
?>
