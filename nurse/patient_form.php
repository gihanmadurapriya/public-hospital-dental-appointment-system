<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_role('NURSE'); $title='Register Patient';
$id=(int)($_GET['id']??0); $error='';
$data=['full_name'=>'','username'=>'','phone'=>'','patient_number'=>'','date_of_birth'=>'','address'=>''];

if($id){
 $stmt=$pdo->prepare("SELECT p.*,u.full_name,u.username,u.phone FROM patients p JOIN users u ON u.id=p.user_id WHERE p.id=?");$stmt->execute([$id]);$data=$stmt->fetch() ?: $data;
}
if($_SERVER['REQUEST_METHOD']==='POST'){
 $full=trim($_POST['full_name']);$username=trim($_POST['username']);$phone=trim($_POST['phone']);
 $pn=trim($_POST['patient_number']);$dob=$_POST['date_of_birth']?:null;$address=trim($_POST['address']);$password=$_POST['password']??'';
 try{
  $pdo->beginTransaction();
  if($id){
   $stmt=$pdo->prepare("SELECT user_id FROM patients WHERE id=?");$stmt->execute([$id]);$uid=$stmt->fetchColumn();
   if($password!==''){
    $stmt=$pdo->prepare("UPDATE users SET full_name=?,username=?,phone=?,password_hash=? WHERE id=?");
    $stmt->execute([$full,$username,$phone,password_hash($password,PASSWORD_DEFAULT),$uid]);
   }else{$stmt=$pdo->prepare("UPDATE users SET full_name=?,username=?,phone=? WHERE id=?");$stmt->execute([$full,$username,$phone,$uid]);}
   $stmt=$pdo->prepare("UPDATE patients SET patient_number=?,date_of_birth=?,address=? WHERE id=?");$stmt->execute([$pn,$dob,$address,$id]);
  }else{
   if($password==='') throw new Exception('Password is required for a new patient.');
   $stmt=$pdo->prepare("INSERT INTO users(username,password_hash,role,full_name,phone) VALUES(?,?,?,?,?)");
   $stmt->execute([$username,password_hash($password,PASSWORD_DEFAULT),'PATIENT',$full,$phone]);$uid=(int)$pdo->lastInsertId();
   $stmt=$pdo->prepare("INSERT INTO patients(user_id,patient_number,date_of_birth,address) VALUES(?,?,?,?)");$stmt->execute([$uid,$pn,$dob,$address]);
  }
  $pdo->commit();header('Location: patients.php');exit;
 }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$error=$e->getMessage();}
}
include __DIR__.'/../partials/header.php';?>
<div class="page-head"><h1><?=$id?'Edit':'Register'?> Patient</h1></div>
<div class="card"><?php if($error):?><div class="alert error"><?=h($error)?></div><?php endif;?>
<form method="post"><div class="form-row">
<label>Full name<input name="full_name" value="<?=h($data['full_name'])?>" required></label>
<label>Patient number<input name="patient_number" value="<?=h($data['patient_number'])?>" required></label>
<label>Username<input name="username" value="<?=h($data['username'])?>" required></label>
<label>Phone<input name="phone" value="<?=h($data['phone'])?>"></label>
<label>Date of birth<input type="date" name="date_of_birth" value="<?=h($data['date_of_birth'])?>"></label>
<label>Password <span class="muted">(leave blank when editing to keep current)</span><input type="password" name="password" <?=$id?'':'required'?>></label>
</div><label>Address<textarea name="address"><?=h($data['address'])?></textarea></label>
<div class="form-actions"><button class="btn">Save Patient</button><a class="btn secondary" href="patients.php">Cancel</a></div></form></div>
<?php include __DIR__.'/../partials/footer.php'; ?>