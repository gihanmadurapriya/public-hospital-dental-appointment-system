<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_role('PATIENT'); $title='My Appointment';
$stmt=$pdo->prepare("SELECT p.*,u.full_name,u.phone FROM patients p JOIN users u ON u.id=p.user_id WHERE p.user_id=?");$stmt->execute([$_SESSION['user_id']]);$patient=$stmt->fetch();
if(!$patient) exit('Patient profile not found.');
$message='';$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $aid=(int)$_POST['appointment_id'];$reason=trim($_POST['reason']);
 $stmt=$pdo->prepare("SELECT * FROM appointments WHERE id=? AND patient_id=? AND status IN ('SCHEDULED','RESCHEDULED')");$stmt->execute([$aid,$patient['id']]);$a=$stmt->fetch();
 if(!$a)$error='Appointment not found or cannot be rescheduled.';
 elseif($reason==='')$error='Please enter a reason.';
 else{
  $stmt=$pdo->prepare("SELECT COUNT(*) FROM reschedule_requests WHERE appointment_id=? AND status='PENDING'");$stmt->execute([$aid]);
  if($stmt->fetchColumn())$error='A rescheduling request is already pending for this appointment.';
  else{$stmt=$pdo->prepare("INSERT INTO reschedule_requests(appointment_id,patient_id,reason) VALUES(?,?,?)");$stmt->execute([$aid,$patient['id'],$reason]);$message='Your rescheduling request has been sent to the nurse.';}
 }
}
$stmt=$pdo->prepare("SELECT * FROM appointments WHERE patient_id=? ORDER BY appointment_date DESC,appointment_time DESC");$stmt->execute([$patient['id']]);$appointments=$stmt->fetchAll();
$stmt=$pdo->prepare("SELECT r.*,a.appointment_date,a.appointment_time FROM reschedule_requests r JOIN appointments a ON a.id=r.appointment_id WHERE r.patient_id=? ORDER BY r.requested_at DESC");$stmt->execute([$patient['id']]);$requests=$stmt->fetchAll();
include __DIR__.'/../partials/header.php';?>
<div class="page-head"><div><h1>My Dental Appointment</h1><p class="muted">You can request a change, but only the nurse can assign the new date.</p></div></div>
<?php if($message):?><div class="alert success-alert"><?=h($message)?></div><?php endif;?><?php if($error):?><div class="alert error"><?=h($error)?></div><?php endif;?>
<div class="grid-2">
<div class="card"><h2>Patient Information</h2><p><strong>Name:</strong> <?=h($patient['full_name'])?></p><p><strong>Patient No:</strong> <?=h($patient['patient_number'])?></p><p><strong>Phone:</strong> <?=h($patient['phone'])?></p></div>
<div class="card"><h2>Current Appointment</h2>
<?php $current=null;foreach($appointments as $x){if(in_array($x['status'],['SCHEDULED','RESCHEDULED'])){$current=$x;break;}}?>
<?php if($current):?><p><strong>Date:</strong> <?=h($current['appointment_date'])?></p><p><strong>Time:</strong> <?=h(substr($current['appointment_time'],0,5))?></p><p><strong>Treatment:</strong> <?=h($current['treatment'])?></p><p><span class="badge <?=strtolower($current['status'])?>"><?=h($current['status'])?></span></p>
<button class="btn" onclick="document.getElementById('request').scrollIntoView()">I Cannot Attend</button>
<?php else:?><p class="empty">No current appointment.</p><?php endif;?>
</div></div><br>
<div class="card" id="request"><h2>Request Rescheduling</h2><p class="muted">Enter your reason. The nurse will review the request and assign the new date/time.</p>
<?php if($current):?><form method="post"><input type="hidden" name="appointment_id" value="<?=$current['id']?>"><label>Reason<textarea name="reason" required placeholder="Example: I cannot attend because of work/travel/another appointment."></textarea></label><button class="btn">Send Request to Nurse</button></form><?php endif;?></div><br>
<div class="card"><h2>Rescheduling Requests</h2><div class="table-wrap"><table><tr><th>Requested</th><th>Appointment</th><th>Reason</th><th>Status</th><th>Nurse note</th></tr>
<?php foreach($requests as $r):?><tr><td><?=h($r['requested_at'])?></td><td><?=h($r['appointment_date'])?> <?=h(substr($r['appointment_time'],0,5))?></td><td><?=nl2br(h($r['reason']))?></td><td><span class="badge <?=strtolower($r['status'])?>"><?=h($r['status'])?></span></td><td><?=nl2br(h($r['nurse_note']??''))?></td></tr><?php endforeach;?>
<?php if(!$requests):?><tr><td colspan="5" class="empty">No rescheduling requests.</td></tr><?php endif;?></table></div></div><br>
<div class="card"><h2>Appointment History</h2><div class="table-wrap"><table><tr><th>Date</th><th>Time</th><th>Treatment</th><th>Status</th></tr>
<?php foreach($appointments as $a):?><tr><td><?=h($a['appointment_date'])?></td><td><?=h(substr($a['appointment_time'],0,5))?></td><td><?=h($a['treatment'])?></td><td><span class="badge <?=strtolower($a['status'])?>"><?=h($a['status'])?></span></td></tr><?php endforeach;?>
</table></div></div>
<?php include __DIR__.'/../partials/footer.php'; ?>
