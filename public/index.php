<?php
require_once __DIR__ . '/../src/bootstrap.php';
$q = trim((string)($_GET['q'] ?? ''));
$cat = trim((string)($_GET['category'] ?? ''));
$sql = "SELECT t.*, c.name AS category_name, c.slug AS category_slug FROM tools t LEFT JOIN categories c ON c.id=t.category_id WHERE t.status='published'";
$args = [];
if ($q !== '') { $sql .= " AND (t.name LIKE ? OR t.description LIKE ?)"; $args[] = "%$q%"; $args[] = "%$q%"; }
if ($cat !== '') { $sql .= " AND c.slug=?"; $args[] = $cat; }
$sql .= " ORDER BY t.name";
$stmt = db()->prepare($sql); $stmt->execute($args); $tools = $stmt->fetchAll();
$categories = db()->query("SELECT name,slug FROM categories ORDER BY name")->fetchAll();
require_once __DIR__ . '/../src/catalog.php';
$toolIconFor = static function (string $name, string $category): array {
 $v = mb_strtolower($name . ' ' . $category);
 foreach ([
  ['pdf|document|ডকুমেন্ট|পিডিএফ','▤','pdf'],['image|photo|ছবি|ইমেজ','▧','image'],
  ['code|developer|json|html|css|javascript|প্রোগ্রাম','⌘','code'],['text|writing|word|লেখা|টেক্সট','T','text'],
  ['security|password|সিকিউরিটি|পাসওয়ার্ড','♙','security'],['seo|website|web|সাইট|এসইও','↗','web'],
  ['convert|converter|unit|রূপান্তর|কনভার্ট','⇄','convert'],['calculator|math|হিসাব|ক্যালকুলেটর','∑','math'],
 ] as [$pattern,$glyph,$kind]) { if (preg_match('/'.$pattern.'/u', $v)) return [$glyph,$kind]; }
 return ['✦','general'];
};
?>
<!doctype html>
<html lang="bn">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<title>ToolkitPro — সব টুলস এক জায়গায়</title>
<meta name="description" content="PDF, ছবি, লেখা ও দৈনন্দিন ডিজিটাল কাজের প্রয়োজনীয় অনলাইন টুলস—ToolkitPro-তে এক জায়গায় খুঁজুন ও ব্যবহার করুন।">
<meta name="theme-color" content="#315eea">
<meta property="og:type" content="website">
<meta property="og:site_name" content="ToolkitPro">
<meta property="og:title" content="ToolkitPro — সব টুলস এক জায়গায়">
<meta property="og:description" content="খুঁজুন, ক্যাটাগরি বেছে নিন এবং আপনার প্রয়োজনীয় অনলাইন টুল ব্যবহার করুন।">
<link rel="icon" type="image/svg+xml" href="/assets/favicon.svg">
<link rel="manifest" href="/assets/site.webmanifest">
<link rel="stylesheet" href="/assets/style.css">
</head>
<body id="top">
<header class="site-header">
 <div class="header-inner">
  <a class="brand" href="/" aria-label="ToolkitPro হোম"><span class="brand-mark" aria-hidden="true">⚒</span><span class="brand-name">Toolkit<span class="brand-pro">Pro</span><small>আপনার অনলাইন টুলস</small></span></a>
  <nav class="main-nav" id="primary-navigation" aria-label="প্রধান নেভিগেশন"><a class="nav-active" href="/">হোম</a><a href="#categories">ক্যাটাগরি</a><a href="#tools">সব টুলস</a></nav>
  <div class="header-actions">
   <a class="icon-button home-button" href="/" aria-label="হোম" title="হোম"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 10 9-7 9 7v10h-6v-7H9v7H3z"/></svg></a>
   <button class="icon-button theme-toggle" type="button" aria-label="ডার্ক মোড চালু/বন্ধ" title="ডার্ক মোড"><svg class="moon-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 15.2A8.7 8.7 0 0 1 8.8 3.5 9 9 0 1 0 20.5 15.2Z"/></svg><svg class="sun-icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"/></svg></button>
   <button class="icon-button mobile-menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" aria-label="মেনু খুলুন" title="মেনু"><span></span><span></span><span></span></button>
  </div>
 </div>
</header>
<main>
 <section class="hero">
  <div class="hero-content">
   <span class="hero-kicker"><span class="pulse-dot"></span> TOOLKIT PRO · TOOL ENGINE</span>
   <h1>সব টুলস <span>এক জায়গায়</span></h1>
   <p class="hero-copy">খুঁজুন, ক্যাটাগরি বেছে নিন এবং একই workspace-এ আপনার প্রয়োজনীয় অনলাইন টুল ব্যবহার করুন।</p>
   <form class="search-form" method="get" action="/" role="search">
    <span class="search-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/></svg></span>
    <input name="q" value="<?=e($q)?>" placeholder="যেমন: JSON, Password, PDF…" aria-label="টুল খুঁজুন">
    <?php if ($cat !== ''): ?><input type="hidden" name="category" value="<?=e($cat)?>"><?php endif; ?>
    <button type="submit">খুঁজুন <span aria-hidden="true">→</span></button>
   </form>
  </div>
  <div class="hero-art"><img src="/assets/toolkit-hero.svg" alt="ToolkitPro ওয়েব, ডেভেলপার ও PDF টুলসের চিত্র" width="560" height="390" fetchpriority="high"></div>
 </section>
 <section class="benefits" aria-label="ToolkitPro সুবিধা">
  <div class="benefit"><span class="benefit-icon">⌕</span><span><b>সহজে খুঁজুন</b><small>সার্চ ও ক্যাটাগরি</small></span></div>
  <div class="benefit"><span class="benefit-icon">↗</span><span><b>দ্রুত ব্যবহার</b><small>টুলে সরাসরি প্রবেশ</small></span></div>
  <div class="benefit"><span class="benefit-icon">▦</span><span><b>এক workspace</b><small>দরকারি টুলস এক জায়গায়</small></span></div>
 </section>
 <section class="content-section" id="categories">
  <div class="section-heading"><div><span class="section-kicker">আপনার কাজের ধরন বেছে নিন</span><h2>ক্যাটাগরি</h2><p>কাজ অনুযায়ী প্রয়োজনীয় টুল খুঁজে নিন।</p></div><a class="text-link" href="#tools">সব টুলস <span>→</span></a></div>
  <div class="category-list"><a class="category-chip <?= $cat === '' ? 'selected' : '' ?>" href="/"><span class="chip-symbol">✦</span> সব ক্যাটাগরি</a>
   <?php foreach ($categories as $c): ?><a class="category-chip <?= $cat === $c['slug'] ? 'selected' : '' ?>" href="/?category=<?=e($c['slug'])?>"><span class="chip-symbol" aria-hidden="true">▦</span><?=e($c['name'])?></a><?php endforeach; ?>
  </div>
 </section>
 <section class="content-section tools-section" id="tools">
  <div class="section-heading tools-heading"><div><span class="section-kicker"><?= $q !== '' ? 'সার্চের ফলাফল' : ($cat !== '' ? 'নির্বাচিত ক্যাটাগরি' : 'আপনার কাজের জন্য') ?></span><h2><?= $q !== '' ? 'মিলে যাওয়া টুলস' : 'টুলস এক্সপ্লোর করুন' ?></h2><p><?= $q !== '' ? 'সার্চের সঙ্গে মিলে যাওয়া টুলগুলো দেখুন।' : 'টুল বেছে নিয়ে কাজ শুরু করুন।' ?></p></div><span class="result-count"><?=count($tools)?> টি টুল</span></div>
  <?php if ($tools):
   $toolGroups = [];
   foreach ($tools as $t) { $group = (string)($t['category_name'] ?? 'অন্যান্য টুলস'); $toolGroups[$group][] = $t; }
   ksort($toolGroups, SORT_NATURAL | SORT_FLAG_CASE);
  ?>
   <div class="tool-groups">
    <?php foreach ($toolGroups as $groupName=>$groupTools): ?>
     <section class="tool-group" aria-label="<?=e($groupName)?>">
      <div class="tool-group-heading"><div><span class="group-eyebrow">CATEGORY</span><h3><?=e($groupName)?></h3></div><span class="group-count"><?=count($groupTools)?> টুল</span></div>
      <div class="tool-grid">
       <?php foreach ($groupTools as $t): [$glyph,$kind] = $toolIconFor((string)$t['name'],(string)($t['category_name'] ?? '')); ?>
        <article class="tool-card"><div class="tool-card-top"><span class="tool-icon tool-icon-<?=e($kind)?>" aria-hidden="true"><?=$glyph?></span><span class="tool-category"><?=e($t['category_name'] ?? 'ToolkitPro')?></span></div><h3><?=e($t['name'])?></h3><p><?=e($t['description'])?></p><div class="tool-card-bottom"><?php
         $liveToolRoutes = ['json-formatter'=>'/tools/json-formatter.php','word-counter'=>'/tools/word-counter.php'];
         $destination = !empty($t['tool_url']) ? (string)$t['tool_url'] : ($liveToolRoutes[(string)$t['slug']] ?? '');
        ?><?php if ($destination !== ''): ?><a class="tool-open" href="<?=e($destination)?>">টুলটি ব্যবহার করুন <span aria-hidden="true">↗</span></a><?php else: ?><span class="coming-soon">শীঘ্রই আসছে</span><?php endif; ?></div></article>
       <?php endforeach; ?>
      </div>
     </section>
    <?php endforeach; ?>
   </div>
  <?php else: ?><div class="empty-state"><span class="empty-icon">⌕</span><h3><?= $q !== '' ? 'কোনো টুল পাওয়া যায়নি' : 'এখনো টুল যুক্ত হয়নি' ?></h3><p><?= $q !== '' ? 'অন্য শব্দ দিয়ে খুঁজুন অথবা সব ক্যাটাগরি দেখুন।' : 'অ্যাডমিন প্যানেল থেকে টুল যোগ করা যাবে।' ?></p><?php if ($q !== '' || $cat !== ''): ?><a class="empty-action" href="/">সব টুলস দেখুন →</a><?php endif; ?></div><?php endif; ?>
 </section>
</main>
<footer class="site-footer"><div class="footer-inner">
 <div class="footer-about"><a class="brand footer-brand" href="/"><span class="brand-mark" aria-hidden="true">⚒</span><span class="brand-name">Toolkit<span class="brand-pro">Pro</span><small>আপনার অনলাইন টুলস</small></span></a><p>ডিজিটাল কাজকে সহজ, দ্রুত ও গুছিয়ে করার স্মার্ট টুলবক্স।</p></div>
 <div class="footer-nav"><strong>দ্রুত লিংক</strong><a href="/">হোম</a><a href="#categories">ক্যাটাগরি</a><a href="#tools">সব টুলস</a></div>
 <div class="footer-nav"><strong>ToolkitPro</strong><a href="/admin/login.php">অ্যাডমিন প্যানেল ↗</a><a href="#top">পাতার শুরুতে ↑</a></div>
 <div class="footer-bottom"><span>© <?=date('Y')?> ToolkitPro. সর্বস্বত্ব সংরক্ষিত।</span><span>Made for simpler digital work</span></div>
</div></footer>
<script>
(function(){
 const menu=document.querySelector('.mobile-menu-toggle'),nav=document.querySelector('.main-nav');
 if(menu&&nav){menu.addEventListener('click',()=>{const opened=menu.getAttribute('aria-expanded')==='true';menu.setAttribute('aria-expanded',String(!opened));nav.classList.toggle('is-open',!opened);menu.setAttribute('aria-label',opened?'মেনু খুলুন':'মেনু বন্ধ করুন');});nav.addEventListener('click',e=>{if(e.target.closest('a')){nav.classList.remove('is-open');menu.setAttribute('aria-expanded','false');}});}
 const toggle=document.querySelector('.theme-toggle');
 const setTheme=dark=>{document.documentElement.classList.toggle('dark',dark);if(toggle)toggle.setAttribute('aria-pressed',String(dark));};
 try{setTheme(localStorage.getItem('toolkitpro-theme')==='dark');}catch(e){}
 if(toggle)toggle.addEventListener('click',()=>{const dark=!document.documentElement.classList.contains('dark');setTheme(dark);try{localStorage.setItem('toolkitpro-theme',dark?'dark':'light');}catch(e){}});
})();
</script>
</body></html>