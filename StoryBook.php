<?php
// ===== STORY DATA (5 LONGER CHAPTERS, DRAGON VERSION) =====
$chapters = [
    "Ember soared above the SMOKING peaks at dawn, her crimson scales glinting as the first sunbeams pierced the clouds. 
    Her heart thundered with both fear and wonder, for tonight marked the first full moon of the ancient prophecy.",

    "Beside her glided Pyra (once called Pyxis), a clever sapphire dragon whose golden eyes gleamed with secrets older than the mountain caves. 
    They followed a trail of faintly glowing crystals, each wingbeat echoing a soft hum of forgotten songs.",

    "The mountain itself seemed alive. Elder Stone, veins deep as memory, spoke in riddles carried on the wind: 
    'Seek the valley where moon and shadow meet.' 
    Ember listened, the words etching themselves into her mind like shimmering runes.",

    "From the darkest cavern emerged SHADOW the ANCIENT wyrm, his scales streaked with starlight. 
    A thunderous roar rolled across the cliffs, shaking the stones. 
    Yet within his eyes burned an old sorrow. 
    Ember raised her talon, whispering a promise of peace as Pyra bristled beside her.",

    "The moonlit valley shimmered awake as Luma, spirit of flame, rose from its heart. 
    Gentle winds reflected galaxies, and the prophecy unfolded: 
    Ember’s courage had united friend and guardian alike, weaving their fates into a new legend that would be sung for generations."
];

// ===== STRING MANIPULATIONS =====
$chapters[0] = str_replace("SMOKING", strtoupper("smoking"), $chapters[0]);
$chapters[3] = str_replace("ANCIENT", strtoupper("ancient"), $chapters[3]);
$chapters[3] .= " " . str_repeat("ROAR! ", 2);
$nameLength  = strlen("Ember");
$teaser      = substr($chapters[0], 0, 80) . "...";
$chapters[2] = ucfirst($chapters[2]);
$secret      = strrev("MoonValley");
foreach ($chapters as &$ch) { $ch = wordwrap($ch, 60, "<br>"); }
$words = explode(" ", $chapters[0]);
foreach ($words as &$w) {
    if (stripos($w, "Ember") !== false) $w = "<span class='highlight'>$w</span>";
}
$chapters[0] = implode(" ", $words);
$bonusLine   = lcfirst("And so a legend began, whispered by stars and carried on every dawn breeze.");

// ===== BACKGROUND IMAGES =====
$valleyPics = [
    'valley1.png', // Chapter 1
    'valley2.png', // Chapter 2
    'valley3.png', // Chapter 3
    'valley4.png', // Chapter 4
    'valley5.png'  // Chapter 5
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Moon Valley Flipbook</title>
<style>
body {
    position: relative;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    overflow:hidden;
    font-family:'Georgia',serif;
    transition:background-image 0.6s;
    background: #1a0d0d;
    background-size:cover;
    background-position:center;
}
#flame-bg-canvas {
    position: fixed;
    top: 0; left: 0;
    width: 100vw; height: 100vh;
    z-index: 0;
    pointer-events: none;
    display: block;
}
.book {
    position:relative;
    width:700px; height:500px;
    perspective:2000px;
    z-index: 1;
}
.page {
    position:absolute;
    width:100%; height:100%;
    color:#1b1b1b;
    padding:40px;
    box-sizing:border-box;
    box-shadow:0 0 20px rgba(158, 0, 0, 0.3);
    transform-origin:left;
    transition:transform 1s;
    backface-visibility:hidden;
    line-height:1.6em;
    z-index:5;
}
.page::before {
    content:'';
    position:absolute;
    top:0; left:0; right:0; bottom:0;
    background:rgba(255,255,255,0.75); /* white overlay for readability */
    opacity: 80%;
    z-index:-1;
}

.page.no-overlay::before {
    display: none;
}

.page h1.title {
    text-align:center;
    font-size:2.5em;
    margin-top:100px;
    color:#c0392b;
}
.page h2 {margin-top:0;}
.page.flipped {
    transform:rotateY(-180deg);
    z-index:1;
}
.highlight { color:#c0392b; font-weight:bold; }
.controls {
    position:absolute;
    bottom:20px;
    left:50%;
    transform:translateX(-50%);
    z-index:10;
}
button {
    background-color:maroon; color:#fff;
    border:none; padding:10px 20px;
    margin:0 10px; border-radius:6px;
    cursor:pointer; font-size:1em;
}
button:hover { background:red; }
.end-page {
    text-align:center;
    font-size:2em;
    font-weight:bold;
    color:red;
}
</style>
</head>
<body>
<canvas id="flame-bg-canvas"></canvas>
<div class="book" id="book">
<?php
// ---- Cover / Title Page ----
echo "<div class='page no-overlay' style=\" opacity:100%;
        z-index:7;
        background:url('cover-valley.jpg') center/cover no-repeat;
      \" data-page='0'>
      </div>";

// ---- Chapters with individual backgrounds ----
$total = count($chapters);
for ($i=0; $i<$total; $i++) {
    $pageIndex = $i+1; // shift because of cover
    $bg = isset($valleyPics[$i]) ? $valleyPics[$i] : $valleyPics[0]; // fallback
    $extra = '';
    if ($i==0) {
        $extra = "<p><em>Teaser:</em> $teaser</p>
                  <p>Ember's name has $nameLength letters.</p>
                  <p>Secret Moon Valley code (reversed): $secret</p>";
    }
    if ($i==$total-1) { $extra = "<p>$bonusLine</p>"; }
    echo "<div class='page' style=\"
            z-index:".($total+1-$i).";
            background:url('$bg') center/cover no-repeat;
          \" data-page='$pageIndex'>
            <h2>Chapter ".($i+1)."</h2>
            <p>{$chapters[$i]} $extra</p>
          </div>";
}

// ---- Final END page ----

$endIndex = $total + 1;
echo "<div  class='page no-overlay' style=\"
        z-index:1;
        background:url('end-valley.jpg') center/cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
      \" data-page='$endIndex'>
        <h1 style='text-align:center; width:100%;color:white;'>✨ The End ✨</h1>
      </div>";
?>
<div class="controls">
    <button id="prev">⬅ Previous</button>
    <button id="next">Next ➡</button>
</div>
</div>
<script>
// --- MAGMA ANIMATION BACKGROUND ONLY ---
const canvas = document.getElementById('flame-bg-canvas');
const ctx = canvas.getContext('2d');
let w = window.innerWidth, h = window.innerHeight;
canvas.width = w;
canvas.height = h;

function resizeMagmaCanvas() {
    w = window.innerWidth;
    h = window.innerHeight;
    canvas.width = w;
    canvas.height = h;
}
window.addEventListener('resize', resizeMagmaCanvas);

// Magma effect: full screen, animated, non-interrupting
const magmaParticles = [];
const MAGMA_COLORS = ['#ff4500','#ff8c00','#ffd700','#b22222','#ff6347'];

function randomBetween(a,b){ return a + Math.random()*(b-a); }

function spawnMagma() {
    magmaParticles.push({
        x: randomBetween(0, w),
        y: h - randomBetween(10, 120),
        vx: randomBetween(-0.3,0.3),
        vy: -randomBetween(0.5,1.5),
        size: randomBetween(30, 90),
        life: randomBetween(60, 140),
        color: MAGMA_COLORS[Math.floor(Math.random()*MAGMA_COLORS.length)]
    });
}

function drawMagma(p) {
    let grad = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.size);
    grad.addColorStop(0, p.color);
    grad.addColorStop(0.5, 'rgba(255,80,0,0.5)');
    grad.addColorStop(1, 'rgba(255,0,0,0)');
    ctx.globalAlpha = 0.7;
    ctx.beginPath();
    ctx.arc(p.x, p.y, p.size, 0, 2*Math.PI);
    ctx.fillStyle = grad;
    ctx.fill();
    ctx.globalAlpha = 1;
}

function drawParticles(arr, drawFn) {
    for(let i=arr.length-1; i>=0; i--) {
        let p = arr[i];
        drawFn(p);
        p.x += p.vx;
        p.y += p.vy;
        p.life--;
        if(p.life <= 0) arr.splice(i,1);
    }
}

function animateMagma() {
    ctx.clearRect(0,0,w,h);

    // Magma at bottom, rising up
    if(magmaParticles.length < 40) spawnMagma();
    drawParticles(magmaParticles, drawMagma);

    requestAnimationFrame(animateMagma);
}
animateMagma();
// --- END MAGMA ANIMATION ---

// --- PAGE FLIP CODE ---
const pages = document.querySelectorAll('.page');
let current = 0;
const nextBtn = document.getElementById('next');
const prevBtn = document.getElementById('prev');
const controls = document.querySelector('.controls');

function showPage(n){
    pages.forEach((p,i)=>{
        if(i < n){ p.classList.add('flipped'); }
        else     { p.classList.remove('flipped'); }
    });

    // Hide controls on cover (first page)
    if (current === 0) {
        controls.style.display = 'none';
        // Enable click-to-continue on cover
        pages[0].style.cursor = 'pointer';
    } else {
        controls.style.display = '';
        pages[0].style.cursor = '';
    }

    // Hide next button on end page, show otherwise
    if (current === pages.length - 1) {
        nextBtn.style.display = 'none';
    } else {
        nextBtn.style.display = '';
    }
}

showPage(current);

// Click anywhere on cover to go to chapter 1
pages[0].onclick = function() {
    if(current === 0){
        current = 1;
        showPage(current);
    }
};

nextBtn.onclick = ()=>{
    if(current < pages.length - 1){
        current++;
        showPage(current);
    }
};
prevBtn.onclick = ()=>{
    if(current > 0){
        current--;
        showPage(current);
    }
};

document.getElementById('next').onclick = ()=>{
    if(current < pages.length){ current++; showPage(current); }
};
document.getElementById('prev').onclick = ()=>{
    if(current > 0){ current--; showPage(current); }
};
</script>
</body>
</html>
