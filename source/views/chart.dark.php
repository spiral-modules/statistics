<?php #compiled
if (empty(${'statistics' . md5($this->namespace . $this->view)})) {
    ${'statistics' . md5($this->namespace . $this->view)} = true;
    ?>
    <statistics:init/>
<?php } #compiled ?>
<div id="${id}" node:attributes>
    <?php #compiled
    /** @var array $data */
    $this->runtimeVariable('data', '${data}');
    /** @var array $options */
    $this->runtimeVariable('options', '${options}');
    /** @var string $chart */
    $this->runtimeVariable('chart', '${chart}');
    ?>

    <?php
    if (empty($data)) {
        $data = [];
    }
    if (empty($options)) {
        $options = [];
    }

    if (!is_array($data)) { ?>

        <block:dataError>[[Bad chart data.]]</block:dataError>

    <?php } elseif (!is_array($options)) { ?>

        <block:optionsError>[[Bad chart options.]]</block:optionsError>

    <?php } else { ?>

        <script type="text/javascript">
            google.setOnLoadCallback(function () {
                var data = google.visualization.arrayToDataTable(<?= json_encode($data) ?>);
                var options = <?= json_encode($options) ?>;
                var chart = new google.visualization.<?= $chart ?>(document.getElementById('${id}'));
                chart.draw(data, options);
            });
        </script>

    <?php } ?>
</div>
