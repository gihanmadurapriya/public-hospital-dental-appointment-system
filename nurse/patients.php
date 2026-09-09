<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_role('NURSE'); $title='Patients';
$stmt=$pdo->query("SELECT p.*,u.full_name,u.username,u.phone FROM patients p JOIN users u ON u.id=p.user_id ORDER BY u.full_name");
$patients=$stmt->fetchAll();
include __DIR__.'/../partials/header.php'; ?>
<div class="page-head"><div><h1>Patients</h1><p class="muted">Registered dental clinic patients.</p></div><a class="btn" href="patient_form.php">+ Register Patient</a></div>
<div class="card"><div class="table-wrap"><table><tr><th>Patient No.</th><th>Name</th><th>Username</th><th>Phone</th><th>Action</th></tr>
<?php foreach($patients as $p): ?><tr><td><?=h($p['patient_number'])?></td><td><?=h($p['full_name'])?></td><td><?=h($p['username'])?></td><td><?=h($p['phone'])?></td><td><a class="btn small secondary" href="patient_form.php?id=<?=$p['id']?>">Edit</a></td></tr><?php endforeach;?>
</table></div></div>
<?php include __DIR__.'/../partials/footer.php'; ?>