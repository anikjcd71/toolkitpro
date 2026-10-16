<?php
require_once __DIR__ . '/../src/bootstrap.php';
$q = trim((string)($_GET['q'] ?? ''));
$cat = trim((string)($_GET['category'] ?? ''));
$sql = "SELECT t.*, c.name AS category_name, c.slug AS category_slug FROM tools t LEFT JOIN categories c ON c.id=t.category_id WHERE t.status='published'";
$args = [];
if ($q !== '') { $sql .= " AND (t.name LIKE ? OR t.description LIKE ?)"; $args[]="%$q%"; $args[]="%$q%"; }
if ($cat !== '') { $sql .= " AND c.slug=?"; $args[]=$cat; }
$sql .= " ORDER BY t.name";
$stmt = db()->prepare($sql); $stmt->execute($args); $tools=$stmt->fetchAll();
$categories=db()->query("SELECT name,slug FROM categories ORDER BY name")->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>ToolkitPro — Online Tools</title><meta name="description" content="A growing directory of practical online tools."><link rel="stylesheet" href="/assets/style.css"></head><body>
<header class="top"><a class="brand" href="/">ToolkitPro</a><a href="/admin/login.php">Admin</a></header>
<main class="wrap"><section class="hero"><p class="eyebrow">TOOLS FOR EVERYDAY WORK</p><h1>Get things done with ToolkitPro</h1><p>Discover useful utilities in one simple directory.</p>
<form class="search" method="get"><input name="q" value="<?=e($q)?>" placeholder="Search tools..." aria-label="Search tools"><button>Search</button></form></section>
<nav class="filters"><a href="/">All tools</a><?php foreach($categories as $c): ?><a href="/?category=<?=e($c['slug'])?>"><?=e($c['name'])?></a><?php endforeach; ?></nav>
<section class="cards"><?php foreach($tools as $t): ?><article class="tool"><span class="tag"><?=e($t['category_name'] ?? 'Tools')?></span><h2><?=e($t['name'])?></h2><p><?=e($t['description'])?></p><?php if(!empty($t['tool_url'])): ?><a class="go" href="<?=e($t['tool_url'])?>">Open tool →</a><?php else: ?><span class="muted">Coming soon</span><?php endif; ?></article><?php endforeach; ?></section>
<?php if(!$tools): ?><p>No matching tools yet.</p><?php endif; ?><footer>© <?=date('Y')?> ToolkitPro</footer></main></body></html>
