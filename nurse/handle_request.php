<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_role('NURSE'); $id=(int)($_GET['id']??0);$error='';
$stmt=$pdo->prepare("SELECT r.*,a.appointment_date,a.appointment_time,a.treatment,p.patient_number,u.full_name FROM reschedule_requests r JOIN appointments a ON a.id=r.appointment_id JOIN patients p ON p.id=r.patient_id JOIN users u ON u.id=p.user_id WHERE r.id=?");$stmt->execute([$id]);$r=$stmt->fetch();
if(!$r) exit('Request not found.');
if($_SERVER['REQUEST_METHOD']==='POST'){
 $action=$_POST['action'];$note=trim($_POST['nurse_note']);
 try{
  $pdo->beginTransaction();
  if($action==='APPROVE'){
   $date=$_POST['new_date'];$time=$_POST['new_time'];
   if(!$date||!$time)throw new Exception('New appointment date and time are required.');
   $stmt=$pdo->prepare("SELECT COUNT(*) FROM appointments WHERE appointment_date=? AND appointment_time=? AND status NOT IN ('CANCELLED','ABSENT') AND id<>?");
   $stmt->execute([$date,$time,$r['appointment_id']]);
   if($stmt->fetchColumn()>0)throw new Exception('That new slot is already occupied.');
   $stmt=$pdo->prepare("UPDATE appointments SET appointment_date=?,appointment_time=?,status='RESCHEDULED',notes=CONCAT(COALESCE(notes,''),?) WHERE id=?");
   $stmt->execute([$date,$time,"\nRescheduled by nurse: ".$note,$r['appointment_id']]);
   $stmt=$pdo->prepare("UPDATE reschedule_requests SET status='APPROVED',nurse_note=?,handled_at=NOW(),handled_by=? WHERE id=?");$stmt->execute([$note,$_SESSION['user_id'],$id]);
  }else{
   $stmt=$pdo->prepare("UPDATE reschedule_requests SET status='REJECTED',nurse_note=?,handled_at=NOW(),handled_by=? WHERE id=?");$stmt->execute([$note,$_SESSION['user_id'],$id]);
  }
  $pdo->commit();header('Location: requests.php');exit;
 }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$error=$e->getMessage();}
}
include __DIR__.'/../partials/header.php';?>
<div class="page-head"><h1>Handle Rescheduling Request</h1></div>
<div class="card">
<?php if($error):?><div class="alert error"><?=h($error)?></div><?php endif;?>
<p><strong>Patient:</strong> <?=h($r['patient_number'])?> — <?=h($r['full_name'])?></p>
<p><strong>Current appointment:</strong> <?=h($r['appointment_date'])?> at <?=h(substr($r['appointment_time'],0,5))?></p>
<p><strong>Reason:</strong><br><?=nl2br(h($r['reason']))?></p>
<hr>
<form method="post">
<div class="form-row"><label>New date (nurse decides)<input type="date" name="new_date"></label><label>New time<input type="time" name="new_time"></label></div>
<label>Nurse note<textarea name="nurse_note" placeholder="Optional explanation or instruction"></textarea></label>
<div class="form-actions"><button class="btn success" name="action" value="APPROVE">Approve & Assign New Date</button><button class="btn danger" name="action" value="REJECT" data-confirm="Reject this rescheduling request?">Reject Request</button><a class="btn secondary" href="requests.php">Back</a></div>
</form></div>
<?php include __DIR__.'/../partials/footer.php'; ?>