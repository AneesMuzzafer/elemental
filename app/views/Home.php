<body>
    <h1>HTML</h1>
    <h2><?= $data ?></h2>
    <h3><?= $abc ?></h3>
    <?= component("child", ["name"=> "Anees", "comp" => component("anotherChild")]) ?>
</body>
