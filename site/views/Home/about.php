<?php
$model = AboutModel::load()
?>
<div>
    <p style="color: darkgoldenrod"><?php
        echo $model->id;
        echo $model->name;
        ?></p>
</div>
