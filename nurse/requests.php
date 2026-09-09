<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_role('NURSE'); $title='Reschedule Requests';
$stmt=$pdo->query("SELECT r.*,a.appointment_date,a.appointment_time,a.treatment,p.patient_number,u.full_name FROM reschedule_requests r JOIN appointments a ON a.id=r.appointment_id JOIN patients p ON p.id=r.patient_id JOIN users u ON u.id=p.user_id ORDER BY r.status='PENDING' DESC,r.requested_at DESC");
$rows=$stmt->fetchAll();
include __DIR__.'/../partials/header.php';?>
<div class="page-head"><div><h1>Reschedule Requests</h1><p class="muted">Patients request changes here; the nurse decides the new date.</p></div></div>
<div class="card"><div class="table-wrap"><table><tr><th>Patient</th><th>Current appointment</th><th>Reason</th><th>Request</th><th>Status</th><th>Action</th></tr>
<?php foreach($rows as $r):?><tr><td><?=h($r['patient_number'])?> — <?=h($r['full_name'])?></td><td><?=h($r['appointment_date'])?> <?=h(substr($r['appointment_time'],0,5))?><br><?=h($r['treatment'])?></td><td><?=nl2br(h($r['reason']))?></td><td><?=h($r['requested_at'])?></td><td><span class="badge <?=strtolower($r['status'])?>"><?=h($r['status'])?></span></td><td><?php if($r['status']==='PENDING'):?><a class="btn small" href="handle_request.php?id=<?=$r['id']?>">Handle</a><?php else:?><?=h($r['nurse_note']??'')?><?php endif;?></td></tr><?php endforeach;?>
<?php if(!$rows):?><tr><td colspan="6" class="empty">No rescheduling requests.</td></tr><?php endif;?></table></div></div>
<?php include __DIR__.'/../partials/footer.php'; ?>