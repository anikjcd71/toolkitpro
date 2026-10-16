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
<!doctype html>
<html lang="bn">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>ToolkitPro — আপনার কাজের অনলাইন টুলবক্স</title>
<meta name="description" content="দৈনন্দিন কাজ সহজ করতে দরকারি অনলাইন টুলস এক জায়গায় খুঁজুন ও ব্যবহার করুন।">
<meta name="theme-color" content="#f7f8fc"><link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header class="site-header"><div class="header-inner">
<a class="brand" href="/" aria-label="ToolkitPro হোম"><span class="brand-mark">T</span><span>Toolkit<span class="brand-pro">Pro</span><small>SMART TOOLS, SIMPLE WORK</small></span></a>
<nav class="main-nav" aria-label="প্রধান নেভিগেশন"><a class="nav-active" href="/">হোম</a><a href="#categories">ক্যাটাগরি</a><a href="#tools">সব টুলস</a></nav>
<a class="admin-link" href="/admin/login.php">অ্যাডমিন <span aria-hidden="true">↗</span></a>
</div></header>
<main>
<section class="hero"><div class="hero-glow hero-glow-one"></div><div class="hero-glow hero-glow-two"></div>
<div class="hero-content">
<span class="hero-kicker"><span class="pulse-dot"></span> আপনার কাজের স্মার্ট টুলবক্স</span>
<h1>কাজ হোক সহজ,<br><span>সময় বাঁচুক বেশি।</span></h1>
<p class="hero-copy">দৈনন্দিন ডিজিটাল কাজের দরকারি টুলস—এক জায়গায়, সহজে খুঁজুন, দ্রুত ব্যবহার করুন।</p>
<form class="search-form" method="get" action="/" role="search"><span class="search-icon" aria-hidden="true">⌕</span>
<input name="q" value="<?=e($q)?>" placeholder="কোন টুল খুঁজছেন? যেমন: PDF, ছবি, লেখা…" aria-label="টুল খুঁজুন">
<?php if ($cat !== ''): ?><input type="hidden" name="category" value="<?=e($cat)?>"><?php endif; ?>
<button type="submit">খুঁজুন <span aria-hidden="true">→</span></button></form>
<div class="hero-note"><span aria-hidden="true">✦</span> সহজ ইন্টারফেস <span class="note-separator">·</span> দ্রুত ব্যবহার <span class="note-separator">·</span> এক জায়গায় প্রয়োজনীয় টুলস</div>
</div>
<div class="hero-art" aria-hidden="true"><div class="orbit orbit-a"></div><div class="orbit orbit-b"></div><div class="tool-tile tile-main">✳<small>TOOLS</small></div><div class="tool-tile tile-pdf">PDF<span>↗</span></div><div class="tool-tile tile-image">▧<small>IMAGE</small></div><div class="tool-tile tile-code">&lt;/&gt;</div><div class="art-spark spark-a">✦</div><div class="art-spark spark-b">✧</div></div>
</section>
<section class="trust-strip" aria-label="ToolkitPro বৈশিষ্ট্য">
<div><span class="trust-icon">⌕</span><span><b>সহজে খুঁজুন</b><small>সার্চ ও ক্যাটাগরি</small></span></div>
<div><span class="trust-icon">↗</span><span><b>দ্রুত শুরু করুন</b><small>প্রয়োজনীয় টুলে সরাসরি</small></span></div>
<div><span class="trust-icon">▦</span><span><b>এক জায়গায়</b><small>বিভিন্ন কাজের টুলস</small></span></div>
</section>
<section class="content-section" id="categories">
<div class="section-heading"><div><span class="section-kicker">বেছে নিন আপনার প্রয়োজন</span><h2>ক্যাটাগরি অনুযায়ী খুঁজুন</h2><p>আপনার কাজের ধরন অনুযায়ী টুলস দেখুন।</p></div><a class="text-link" href="#tools">সব টুলস দেখুন <span>→</span></a></div>
<div class="category-list"><a class="category-chip <?= $cat === '' ? 'selected' : '' ?>" href="/"><span class="chip-symbol">✦</span> সব ক্যাটাগরি</a>
<?php foreach($categories as $c): ?><a class="category-chip <?= $cat === $c['slug'] ? 'selected' : '' ?>" href="/?category=<?=e($c['slug'])?>"><span class="chip-symbol">▦</span><?=e($c['name'])?></a><?php endforeach; ?>
</div></section>
<section class="content-section tools-section" id="tools">
<div class="section-heading tools-heading"><div><span class="section-kicker"><?= $q !== '' ? 'সার্চের ফলাফল' : ($cat !== '' ? 'নির্বাচিত ক্যাটাগরি' : 'আপনার কাজের জন্য') ?></span><h2><?= $q !== '' ? 'মিলে যাওয়া টুলস' : 'জনপ্রিয় ও প্রয়োজনীয় টুলস' ?></h2><p><?= $q !== '' ? 'আপনার সার্চের সঙ্গে মিলে যাওয়া টুলগুলো এখানে আছে।' : 'টুল বেছে নিয়ে কাজ শুরু করুন।' ?></p></div><span class="result-count"><?=count($tools)?> টি টুল</span></div>
<?php if($tools): ?><div class="tool-grid">
<?php foreach($tools as $i=>$t): ?><article class="tool-card">
<div class="tool-card-top"><span class="tool-icon icon-<?=($i%5)+1?>" aria-hidden="true"><?=['✦','▤','⌘','▧','↗'][$i%5]?></span><span class="tool-category"><?=e($t['category_name'] ?? 'ToolkitPro')?></span></div>
<h3><?=e($t['name'])?></h3><p><?=e($t['description'])?></p>
<div class="tool-card-bottom"><?php if(!empty($t['tool_url'])): ?><a class="tool-open" href="<?=e($t['tool_url'])?>">টুলটি ব্যবহার করুন <span aria-hidden="true">→</span></a><?php else: ?><span class="coming-soon">শীঘ্রই আসছে</span><?php endif; ?></div>
</article><?php endforeach; ?></div>
<?php else: ?><div class="empty-state"><div class="empty-icon">⌕</div><h3><?= $q !== '' ? 'কোনো টুল পাওয়া যায়নি' : 'টুলস যোগ করা হচ্ছে' ?></h3><p><?= $q !== '' ? 'অন্য শব্দ দিয়ে সার্চ করুন অথবা সব ক্যাটাগরি দেখুন।' : 'নতুন টুল যুক্ত হলে এখানে দেখা যাবে।' ?></p><?php if($q !== '' || $cat !== ''): ?><a class="empty-action" href="/">সব টুলস দেখুন <span>→</span></a><?php endif; ?></div><?php endif; ?>
</section></main>
<footer class="site-footer"><div class="footer-inner"><a class="brand footer-brand" href="/"><span class="brand-mark">T</span><span>Toolkit<span class="brand-pro">Pro</span><small>SMART TOOLS, SIMPLE WORK</small></span></a><p>আপনার ডিজিটাল কাজকে সহজ করার ছোট্ট উদ্যোগ।</p><span class="copyright">© <?=date('Y')?> ToolkitPro. সর্বস্বত্ব সংরক্ষিত।</span></div></footer>
</body></html>