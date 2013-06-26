<?php $modelName = key($data); ?>
<?php foreach($data[$modelName] as $key => $value): ?>
<?php if (in_array($key, $ignoreFields)) { continue; } ?>
<tr>
    <th>
        <?php echo __(Inflector::humanize($key)); ?>
    </th>
    <td>
        <?php echo $value; ?>
    </td>
</tr>
<?php endforeach; ?>