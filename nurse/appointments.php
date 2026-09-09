<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_role('NURSE'); $title='Appointments';
if(isset($_GET['action'],$_GET['id']) && in_array($_GET['action'],['complete','absent','cancel'],true)){
 $status=['complete'=>'COMPLETED','absent'=>'ABSENT','cancel'=>'CANCELLED'][$_GET['action']];
 $stmt=$pdo->prepare("UPDATE appointments SET status=? WHERE id=?");$stmt->execute([$status,(int)$_GET['id']]);
 header('Location: appointments.php');exit;
}
$date=$_GET['date']??'';$params=[];$where='';
if($date){$where='WHERE a.appointment_date=?';$params[]=$date;}
$stmt=$pdo->prepare("SELECT a.*,p.patient_number,u.full_name FROM appointments a JOIN patients p ON p.id=a.patient_id JOIN users u ON u.id=p.user_id $where ORDER BY a.appointment_date,a.appointment_time");$stmt->execute($params);$rows=$stmt->fetchAll();
include __DIR__.'/../partials/header.php';?>
<div class="page-head"><div><h1>Appointments</h1><p class="muted">Only nurses can assign or change appointment dates.</p></div><a class="btn" href="appointment_form.php">+ Assign Appointment</a></div>
<div class="card"><form method="get" class="form-row"><label>Filter by date<input type="date" name="date" value="<?=h($date)?>"></label><div class="form-actions"><button class="btn">Filter</button><a class="btn secondary" href="appointments.php">Clear</a></div></form></div><br>
<div class="card"><div class="table-wrap"><table><tr><th>Date</th><th>Time</th><th>Patient</th><th>Treatment</th><th>Status</th><th>Actions</th></tr>
<?php foreach($rows as $a):?><tr><td><?=h($a['appointment_date'])?></td><td><?=h(substr($a['appointment_time'],0,5))?></td><td><?=h($a['patient_number'])?> — <?=h($a['full_name'])?></td><td><?=h($a['treatment'])?></td><td><span class="badge <?=strtolower($a['status'])?>"><?=h($a['status'])?></span></td>
<td><div class="actions"><a class="btn small secondary" href="appointment_form.php?id=<?=$a['id']?>">Edit</a><?php if($a['status']==='SCHEDULED'):?><a class="btn small success" data-confirm="Mark this appointment completed?" href="?action=complete&id=<?=$a['id']?>">Complete</a><a class="btn small danger" data-confirm="Mark this patient absent?" href="?action=absent&id=<?=$a['id']?>">Absent</a><?php endif;?></div></td></tr><?php endforeach;?>
<?php if(!$rows):?><tr><td colspan="6" class="empty">No appointments found.</td></tr><?php endif;?></table></div></div>
<?php include __DIR__.'/../partials/footer.php'; ?>