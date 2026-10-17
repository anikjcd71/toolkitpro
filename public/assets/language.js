(function(){
'use strict';
const translations={
'Developer Tools':'ডেভেলপার টুলস','Text Tools':'টেক্সট টুলস','Website Tools':'ওয়েবসাইট টুলস','PDF Tools':'পিডিএফ টুলস','Image Tools':'ছবি সম্পাদনার টুলস','Security & Generators':'নিরাপত্তা ও জেনারেটর','SEO & Website':'এসইও ও ওয়েবসাইট','Calculators':'ক্যালকুলেটর',
'Password Generator':'পাসওয়ার্ড জেনারেটর','Password Strength Checker':'পাসওয়ার্ডের শক্তি যাচাই','QR Code Generator':'QR কোড জেনারেটর','UUID Generator':'UUID জেনারেটর',
'Base64 Encoder / Decoder':'Base64 এনকোডার / ডিকোডার','URL Encoder / Decoder':'URL এনকোডার / ডিকোডার','HTML Formatter':'HTML ফরম্যাটার','Regex Tester':'Regex টেস্টার','Word Counter':'শব্দ গণনা','Case Converter':'লেখার ধরন পরিবর্তন','Duplicate Line Remover':'ডুপ্লিকেট লাইন সরান','Lorem Ipsum Generator':'Lorem Ipsum জেনারেটর','PDF Merge':'PDF একত্র করুন','PDF Split':'PDF ভাগ করুন','PDF Compressor':'PDF কমপ্রেসর','PDF to Images':'PDF থেকে ছবি','Images to PDF':'ছবি থেকে PDF','Image Compressor':'ছবি কমপ্রেসর','Image Resizer':'ছবির আকার পরিবর্তন','Image Format Converter':'ছবির ফরম্যাট পরিবর্তন','Image to Base64':'ছবি থেকে Base64','Meta Tag Generator':'মেটা ট্যাগ জেনারেটর','Robots.txt Generator':'Robots.txt জেনারেটর','XML Sitemap Generator':'XML সাইটম্যাপ জেনারেটর','Website Health Check':'ওয়েবসাইটের অবস্থা যাচাই','Percentage Calculator':'শতকরা হিসাব','Age Calculator':'বয়স ক্যালকুলেটর','Unit Converter':'একক রূপান্তর','Date Difference Calculator':'তারিখের পার্থক্য হিসাব',
'CATEGORY':'ক্যাটাগরি','TOOLS FOR YOUR WORK':'আপনার কাজের জন্য','SEARCH RESULTS':'সার্চের ফলাফল','SELECTED CATEGORY':'নির্বাচিত ক্যাটাগরি','TOOLKIT PRO · TOOL ENGINE':'টুলকিট প্রো · টুল ইঞ্জিন','সব টুলস':'All tools','এক জায়গায়':'in one place','এক workspace':'One workspace','দরকারি টুলস এক জায়গায়':'Essential tools in one place','সহজে খুঁজুন':'Search easily','দ্রুত ব্যবহার':'Quick to use','সার্চ ও ক্যাটাগরি':'Search & categories','টুলে সরাসরি প্রবেশ':'Direct access to tools','ডিজিটাল কাজকে সহজ, দ্রুত ও গুছিয়ে করার স্মার্ট টুলবক্স।':'A smart toolbox to make digital work easier, faster, and more organized.','খুঁজুন, ক্যাটাগরি বেছে নিন এবং একই workspace-এ আপনার প্রয়োজনীয় অনলাইন টুল ব্যবহার করুন।':'Search, choose a category, and use the online tools you need in one workspace.','যেমন: JSON, Password, PDF…':'e.g. JSON, Password, PDF…','হোম':'Home','ক্যাটাগরি':'Categories','সব টুলস':'All tools','আপনার অনলাইন টুলস':'Your online tools','সব টুলস এক জায়গায়':'All your tools in one place','খুঁজুন':'Search','সব ক্যাটাগরি':'All categories','কাজ অনুযায়ী প্রয়োজনীয় টুল খুঁজে নিন。':'Find the right tool for your task.','কাজ অনুযায়ী প্রয়োজনীয় টুল খুঁজে নিন。':'Find the right tool for your task.','আপনার কাজের ধরন বেছে নিন':'Choose your workflow','টুলস এক্সপ্লোর করুন':'Explore tools','টুল বেছে নিয়ে কাজ শুরু করুন।':'Choose a tool and get started.','মিলে যাওয়া টুলস':'Matching tools','সার্চের সঙ্গে মিলে যাওয়া টুলগুলো দেখুন।':'Browse tools matching your search.','টি টুল':'tools','টুলটি ব্যবহার করুন':'Open tool','শীঘ্রই আসছে':'Coming soon','কোনো টুল পাওয়া যায়নি':'No tools found','অন্য শব্দ দিয়ে খুঁজুন অথবা সব ক্যাটাগরি দেখুন。':'Try another search term or browse all categories.','অন্য শব্দ দিয়ে খুঁজুন অথবা সব ক্যাটাগরি দেখুন。':'Try another search term or browse all categories.','সব টুলস দেখুন →':'Browse all tools →','দ্রুত লিংক':'Quick links','তথ্য ও সহায়তা':'Information & Support','আমাদের সম্পর্কে':'About us','যোগাযোগ':'Contact','গোপনীয়তা নীতি':'Privacy policy','ব্যবহারের শর্ত':'Terms of use','সাইটম্যাপ':'Sitemap','অ্যাকাউন্ট':'Account','অ্যাডমিন লগইন ↗':'Admin login ↗','পাতার শুরুতে ↑':'Back to top','সর্বস্বত্ব সংরক্ষিত।':'All rights reserved.','সহজ ডিজিটাল কাজের জন্য তৈরি':'Built for simpler digital work','বাংলা বা ইংরেজি লেখা পেস্ট করুন—শব্দ, অক্ষর ও বাক্যের সংখ্যা সঙ্গে সঙ্গে দেখুন。':'Paste Bengali or English text to count words, characters, and sentences instantly.','আপনার লেখা':'Your text','এখানে লেখা টাইপ বা পেস্ট করুন…':'Type or paste your text here…','শব্দ':'Words','অক্ষর (স্পেসসহ)':'Characters (including spaces)','বাক্য':'Sentences','লেখা কপি করুন':'Copy text','মুছে ফেলুন':'Clear','JSON পেস্ট করে যাচাই করুন, সুন্দরভাবে সাজান অথবা ছোট করে মিনিফাই করুন। তথ্য আপনার ব্রাউজারেই প্রসেস হয়。':'Paste JSON to validate, format, or minify it. Your data is processed in your browser.','JSON ইনপুট':'JSON input','ফলাফল':'Output','সাজানো JSON এখানে দেখাবে…':'Formatted JSON will appear here…','ফলাফল কপি':'Copy output','Format / Validate':'Format / Validate','Minify':'Minify','← সব টুলস':'← All tools','আপনার কাজ দ্রুত ও সহজে সম্পন্ন করুন。':'Complete your task quickly and easily.','এই টুলটি তৈরি হচ্ছে। প্রস্তুত হলে এখানে ব্যবহার করতে পারবেন。':'This tool is in development and will be available here soon.'
};
const storageKey='toolkitpro-language';
let lang='bn';
try{lang=localStorage.getItem(storageKey)||'bn';}catch(e){}
const toggle=document.querySelector('.language-toggle');
const originalNodes=new WeakMap();
const originalPlaceholders=new WeakMap();
const originalTitle=document.title;
function translateText(source,next){
 const map=next==='en'?translations:Object.fromEntries(Object.entries(translations).map(([a,b])=>[b,a]));
 let value=source;
 Object.keys(map).sort((a,b)=>b.length-a.length).forEach(key=>{value=value.split(key).join(map[key]);});
 return value;
}
function applyLanguage(next){
 lang=next;
 document.documentElement.lang=lang;
 document.documentElement.dataset.language=lang;
 const walker=document.createTreeWalker(document.body,NodeFilter.SHOW_TEXT);
 const nodes=[];
 while(walker.nextNode())nodes.push(walker.currentNode);
 nodes.forEach(node=>{
  if(!originalNodes.has(node))originalNodes.set(node,node.nodeValue);
  const source=originalNodes.get(node);
  if(source&&source.trim())node.nodeValue=translateText(source,lang);
 });
 document.querySelectorAll('[placeholder]').forEach(el=>{
  if(!originalPlaceholders.has(el))originalPlaceholders.set(el,el.getAttribute('placeholder')||'');
  el.setAttribute('placeholder',translateText(originalPlaceholders.get(el),lang));
 });
 if(toggle){
  toggle.textContent=lang==='bn'?'EN':'বাংলা';
  toggle.setAttribute('aria-label',lang==='bn'?'Switch to English':'বাংলায় পরিবর্তন করুন');
  toggle.title=lang==='bn'?'Switch to English':'Switch to Bengali';
 }
 if(originalTitle.includes('ToolkitPro'))document.title=lang==='en'?originalTitle.replace('ফ্রি অনলাইন টুলস','Free Online Tools').replace('সব টুলস এক জায়গায়','All Tools in One Place'):originalTitle;
}
if(toggle)toggle.addEventListener('click',()=>{const next=lang==='bn'?'en':'bn';try{localStorage.setItem(storageKey,next);}catch(e){}applyLanguage(next);});
applyLanguage(lang);
})();