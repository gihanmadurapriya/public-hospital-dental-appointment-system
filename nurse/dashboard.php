<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_role('NURSE'); $title='Nurse Dashboard';

$today = date('Y-m-d');
$stmt=$pdo->prepare("SELECT COUNT(*) FROM appointments WHERE appointment_date=? AND status='SCHEDULED'");
$stmt->execute([$today]); $todayCount=(int)$stmt->fetchColumn();
$stmt=$pdo->query("SELECT COUNT(*) FROM reschedule_requests WHERE status='PENDING'");
$pending=(int)$stmt->fetchColumn();
$stmt=$pdo->query("SELECT COUNT(*) FROM patients"); $patients=(int)$stmt->fetchColumn();
$stmt=$pdo->query("SELECT COUNT(*) FROM appointments WHERE status='COMPLETED'"); $completed=(int)$stmt->fetchColumn();

$stmt=$pdo->prepare("SELECT a.*,p.patient_number,u.full_name FROM appointments a JOIN patients p ON p.id=a.patient_id JOIN users u ON u.id=p.user_id WHERE a.appointment_date=? ORDER BY a.appointment_time");
$stmt->execute([$today]); $appointments=$stmt->fetchAll();
include __DIR__.'/../partials/header.php';
?>
<div class="page-head"><div><h1>Nurse Dashboard</h1><p class="muted">Manage appointments and patient rescheduling requests.</p></div><a class="btn" href="appointment_form.php">+ Assign Appointment</a></div>
<div class="grid">
<div class="card"><div class="muted">Today's appointments</div><div class="stat"><?=$todayCount?></div></div>
<div class="card"><div class="muted">Pending requests</div><div class="stat"><?=$pending?></div></div>
<div class="card"><div class="muted">Registered patients</div><div class="stat"><?=$patients?></div></div>
<div class="card"><div class="muted">Completed appointments</div><div class="stat"><?=$completed?></div></div>
</div>
<br><div class="card"><h2>Today's Schedule</h2>
<div class="table-wrap"><table><tr><th>Time</th><th>Patient</th><th>Treatment</th><th>Status</th><th>Action</th></tr>
<?php foreach($appointments as $a): ?><tr>
<td><?=h(substr($a['appointment_time'],0,5))?></td><td><?=h($a['patient_number'])?> — <?=h($a['full_name'])?></td><td><?=h($a['treatment'])?></td>
<td><span class="badge <?=strtolower($a['status'])?>"><?=h($a['status'])?></span></td>
<td><a class="btn small secondary" href="appointment_form.php?id=<?=$a['id']?>">Manage</a></td>
</tr><?php endforeach; ?>
<?php if(!$appointments): ?><tr><td colspan="5" class="empty">No appointments scheduled for today.</td></tr><?php endif; ?>
</table></div></div>
<?php include __DIR__.'/../partials/footer.php'; ?>