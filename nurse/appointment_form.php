<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_role('NURSE'); $title='Assign Appointment';
$id=(int)($_GET['id']??0);$error='';
$data=['patient_id'=>'','appointment_date'=>'','appointment_time'=>'','treatment'=>'','notes'=>'','status'=>'SCHEDULED'];
if($id){$stmt=$pdo->prepare("SELECT * FROM appointments WHERE id=?");$stmt->execute([$id]);$data=$stmt->fetch() ?: $data;}
$patients=$pdo->query("SELECT p.id,p.patient_number,u.full_name FROM patients p JOIN users u ON u.id=p.user_id ORDER BY u.full_name")->fetchAll();

if($_SERVER['REQUEST_METHOD']==='POST'){
 $pid=(int)$_POST['patient_id'];$date=$_POST['appointment_date'];$time=$_POST['appointment_time'];$treatment=trim($_POST['treatment']);$notes=trim($_POST['notes']);$status=$_POST['status'];
 try{
  // Prevent accidental double booking for the same time.
  $sql="SELECT COUNT(*) FROM appointments WHERE appointment_date=? AND appointment_time=? AND status NOT IN ('CANCELLED','ABSENT')".($id?" AND id<>?":"");
  $params=[$date,$time];if($id)$params[]=$id;$stmt=$pdo->prepare($sql);$stmt->execute($params);
  if($stmt->fetchColumn()>0) throw new Exception('That date and time is already assigned to another appointment.');
  if($id){$stmt=$pdo->prepare("UPDATE appointments SET patient_id=?,appointment_date=?,appointment_time=?,treatment=?,notes=?,status=? WHERE id=?");$stmt->execute([$pid,$date,$time,$treatment,$notes,$status,$id]);}
  else{$stmt=$pdo->prepare("INSERT INTO appointments(patient_id,appointment_date,appointment_time,treatment,notes,status,assigned_by) VALUES(?,?,?,?,?,?,?)");$stmt->execute([$pid,$date,$time,$treatment,$notes,$status,$_SESSION['user_id']]);}
  header('Location: appointments.php');exit;
 }catch(Throwable $e){$error=$e->getMessage();}
}
include __DIR__.'/../partials/header.php';?>
<div class="page-head"><h1><?=$id?'Manage':'Assign'?> Appointment</h1></div>
<div class="card"><?php if($error):?><div class="alert error"><?=h($error)?></div><?php endif;?>
<form method="post"><div class="form-row">
<label>Patient<select name="patient_id" required><option value="">Select patient</option><?php foreach($patients as $p):?><option value="<?=$p['id']?>" <?=$data['patient_id']==$p['id']?'selected':''?>><?=h($p['patient_number'].' — '.$p['full_name'])?></option><?php endforeach;?></select></label>
<label>Treatment / reason<input name="treatment" value="<?=h($data['treatment'])?>" placeholder="Check-up, extraction, filling..." required></label>
<label>Appointment date<input type="date" name="appointment_date" value="<?=h($data['appointment_date'])?>" required></label>
<label>Appointment time<input type="time" name="appointment_time" value="<?=h(substr($data['appointment_time'],0,5))?>" required></label>
<label>Status<select name="status"><?php foreach(['SCHEDULED','COMPLETED','ABSENT','CANCELLED','RESCHEDULED'] as $s):?><option <?=$data['status']===$s?'selected':''?>><?=$s?></option><?php endforeach;?></select></label>
</div><label>Nurse notes<textarea name="notes"><?=h($data['notes'])?></textarea></label>
<div class="form-actions"><button class="btn">Save Appointment</button><a class="btn secondary" href="appointments.php">Cancel</a></div></form></div>
<?php include __DIR__.'/../partials/footer.php'; ?>
