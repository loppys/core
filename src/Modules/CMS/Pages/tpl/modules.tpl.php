<br>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Название</th>
      <th scope="col">Версия</th>
      <th scope="col">Тип модуля</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($this->_data->data['module'] as $key => $value): ?>
      <tr>
        <th scope="row"><?= $key ?></th>
        <td><?= $value['name'] ?></td>
        <td><?= $value['version'] ?></td>
        <td><?= $value['type'] ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
