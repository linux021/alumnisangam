<?php
// auto-generated by sfPropelCrud
// date: 2009/02/10 08:19:38
?>
<h1>hobbies</h1>

<table>
<thead>
<tr>
  <th>Id</th>
  <th>Name</th>
</tr>
</thead>
<tbody>
<?php foreach ($hobbiess as $hobbies): ?>
<tr>
    <td><?php echo link_to($hobbies->getId(), 'hobbies/show?id='.$hobbies->getId()) ?></td>
      <td><?php echo $hobbies->getName() ?></td>
  </tr>
<?php endforeach; ?>
</tbody>
</table>

<?php echo link_to ('create', 'hobbies/create') ?>