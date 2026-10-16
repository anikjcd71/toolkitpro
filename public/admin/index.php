<?php
require_once __DIR__ . '/../../src/bootstrap.php'; require_admin();
$notice='';
if($_SERVER['REQUEST_METHOD']==='POST') {
 csrf_check(); $action=$_POST['action']??'';
 if($action==='delete') { $id=(int)($_POST['id']??0); db()->prepare('DELETE FROM tools WHERE id=?')->execute([$id]); $notice='Tool deleted.'; }
 if($action==='save') {
  $id=(int)($_POST['id']??0); $name=trim((string)($_POST['name']??'')); $slug=trim((string)($_POST['slug']??'')); $desc=trim((string)($_POST['description']??'')); $cat=(int)($_POST['category_id']??0); $url=trim((string)($_POST['tool_url']??'')); $status=($_POST['status']??'draft')==='published'?'published':'draft';
  if($name && preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/',$slug) && $desc) {
   if($id) db()->prepare('UPDATE tools SET category_id=?,name=?,slug=?,description=?,tool_url=?,status=? WHERE id=?')->execute([$cat?:null,$name,$slug,$desc,$url?:null,$status,$id]);
   else db()->prepare('INSERT INTO tools(category_id,name,slug,description,tool_url,status) VALUES(?,?,?,?,?,?)')->execute([$cat?:null,$name,$slug,$desc,$url?:null,$status]);
   $notice='Saved.'; 
  } else $notice='Name, valid slug and description are required.';
 }
}
$edit=null; if(isset($_GET['edit'])) { $s=db()->prepare('SELECT * FROM tools WHERE id=?');$s->execute([(int)$_GET['edit']]);$edit=$s->fetch()?:null; }
$tools=db()->query('SELECT t.*,c.name category_name FROM tools t LEFT JOIN categories c ON c.id=t.category_id ORDER BY t.created_at DESC')->fetchAll();
$cats=db()->query('SELECT id,name FROM categories ORDER BY name')->fetchAll();
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>ToolkitPro Admin</title><link rel="stylesheet" href="/assets/style.css"></head><body><header class="top"><a class="brand" href="/">ToolkitPro Admin</a><form method="post" action="/admin/logout.php"><?=csrf_field()?><button class="secondary">Log out</button></form></header><main class="wrap"><h1>Manage tools</h1><?php if($notice):?><p class="notice"><?=e($notice)?></p><?php endif;?>
<section class="panel"><h2><?=$edit?'Edit tool':'Add a tool'?></h2><form method="post"><?=csrf_field()?><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?=e((string)($edit['id']??''))?>">
<div class="formgrid"><label>Name<input name="name" required value="<?=e($edit['name']??'')?>"></label><label>Slug<input name="slug" required pattern="[a-z0-9]+(-[a-z0-9]+)*" value="<?=e($edit['slug']??'')?>"></label><label>Category<select name="category_id"><option value="">Uncategorized</option><?php foreach($cats as $c):?><option value="<?=$c['id']?>" <?=((int)($edit['category_id']??0)===(int)$c['id'])?'selected':''?>><?=e($c['name'])?></option><?php endforeach;?></select></label><label>Tool URL (optional)<input type="url" name="tool_url" value="<?=e($edit['tool_url']??'')?>"></label><label class="wide">Description<textarea name="description" required><?=e($edit['description']??'')?></textarea></label><label>Status<select name="status"><option value="published" <?=($edit['status']??'published')==='published'?'selected':''?>>Published</option><option value="draft" <?=($edit['status']??'')==='draft'?'selected':''?>>Draft</option></select></label></div><button>Save tool</button><?php if($edit):?> <a href="/admin/index.php">Cancel</a><?php endif;?></form></section>
<section class="panel"><h2>Existing tools (<?=count($tools)?>)</h2><div class="tablewrap"><table><thead><tr><th>Tool</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead><tbody><?php foreach($tools as $t):?><tr><td><?=e($t['name'])?><small><?=e($t['slug'])?></small></td><td><?=e($t['category_name']??'—')?></td><td><?=e($t['status'])?></td><td><a href="?edit=<?=$t['id']?>">Edit</a><form class="inline" method="post" onsubmit="return confirm('Delete this tool?')"><?=csrf_field()?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$t['id']?>"><button class="danger">Delete</button></form></td></tr><?php endforeach;?></tbody></table></div></section></main></body></html>
