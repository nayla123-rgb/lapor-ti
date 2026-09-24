<?php
global $hesk_settings, $hesklang;

/**
 * @var array $topArticles - Collection of top knowledgebase articles
 * @var array $latestArticles - Collection of newest/latest knowledgebase articles
 * @var array $serviceMessages - Collection of service messages to be displayed
 * @var array $messages - Collection of feedback messages to be displayed (such as "You have been logged out")
 * @var bool $accountRequired - `true` if an account is required to use the helpdesk, `false` otherwise
 * @var bool $customerLoggedIn - `true` if a customer is logged in, `false` otherwise
 * @var array $customerUserContext - User info for a customer if logged in.  `null` if a customer is not logged in.
 */
// This guard is used to ensure that users can't hit this outside of actual HESK code
if (!defined('IN_SCRIPT')) {
    die();
}
define('EXTRA_PAGE_CLASSES','page-index');

define('ALERTS',1);
define('KBSEARCH',1);
define('RATING',1);

define('OUTPUT_SEARCH_STYLING',1);
define('RENDER_COMMON_ELEMENTS',1);

define('OUTPUT_SEARCH_JAVASCRIPT',1);

global $BREADCRUMBS;
$BREADCRUMBS = array(
    array('url' => $hesk_settings['site_url'], 'title' => $hesk_settings['site_title']),
    array('title' => $hesk_settings['hesk_title'])
);

/* Print header */
require_once(TEMPLATE_PATH . 'customer/inc/header.inc.php');
?>

<style>
/* Hero Section */
.hero-wrapper {
        background-image: linear-gradient(rgba(14, 38, 74, 0.85), rgba(14, 38, 74, 0.85)), url('https://commons.wikimedia.org/wiki/Special:FilePath/TB-10_Tobago_PK-AGL_Budiarto_260126_(1).jpg');
        background-size: cover;
        background-position: center;
        
        padding: 100px 20px 180px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .hero-title { 
        font-size: 38px; 
        color: #ffffff !important; /* !important memaksa warna putih agar tidak ditimpa CSS bawaan */
        font-weight: 800; 
        letter-spacing: 0.5px; 
        margin-bottom: 15px;
        text-transform: uppercase;
        text-shadow: 0 4px 10px rgba(0, 0, 0, 0.4); /* Efek bayangan agar teks terpisah dari background */
        position: relative;
        z-index: 10;
    }
    .hero-subtitle { color: #cbd5e1; font-size: 18px; font-weight: 400; margin-bottom: 30px; }
    .hero-divider { width: 60px; border: 2px solid #3b82f6; margin: 0 auto 30px; border-radius: 4px; }    
</style>


        <div class="main__content modern-home" style="background-color: #f4f7f9; padding-bottom: 50px;">
            
            <div class="hero-wrapper">
                <div style="position: absolute; top: -50px; left: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
                <div style="position: absolute; bottom: -20px; right: -20px; width: 400px; height: 400px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
        
                <div class="contr" style="max-width: 900px; margin: 0 auto; position: relative; z-index: 5;">
                    <div style="margin-bottom: 20px;">
                        <?php hesk3_show_messages($messages); ?>
                        <?php hesk3_show_messages($serviceMessages); ?>
                    </div>
                    
                    <h1 class="hero-title">Layanan Aspirasi dan Pengaduan</h1>
                    <p class="hero-subtitle">Sampaikan laporan Anda langsung kepada tim dukungan teknis kami dengan mudah dan transparan.</p>
                    <hr class="hero-divider">
                </div>
            </div>

            <div class="contr" style="max-width: 850px; margin: -100px auto 40px auto; position: relative; z-index: 10;">
                <div class="lapor-card" style="background: #fff; border-radius: 10px; box-shadow: 0 20px 40px rgba(0,0,0,0.12); overflow: hidden;">
                    
                    <div style="display: flex; background: #fff; border-bottom: 1px solid #eaeaea;">
                        <button type="button" id="tab-buat" onclick="switchTab('buat')" style="flex: 1; padding: 22px; font-weight: bold; color: #1d4ed8; background: #fff; border: none; border-bottom: 3px solid #1d4ed8; cursor: pointer; transition: 0.3s; font-size: 15px; letter-spacing: 0.5px;">
                            PENGADUAN
                        </button>
                        <button type="button" id="tab-cek" onclick="switchTab('cek')" style="flex: 1; padding: 22px; font-weight: bold; color: #888; background: #fdfdfd; border: none; border-bottom: 3px solid transparent; cursor: pointer; transition: 0.3s; font-size: 15px; letter-spacing: 0.5px;">
                            CEK STATUS LAPORAN
                        </button>
                    </div>

                    <div style="padding: 0;"> 
                        
                        <div id="content-buat" style="display: block; width: 100%;">
                            <iframe id="iframe-buat" src="index.php?a=add" style="width: 100%; border: none; min-height: 800px; display: block;" onload="cleanIframe('iframe-buat')"></iframe>
                        </div>

                        <div id="content-cek" style="display: none; width: 100%;">
                            <iframe id="iframe-cek" src="ticket.php" style="width: 100%; border: none; min-height: 400px; display: block;" onload="cleanIframe('iframe-cek')"></iframe>
                        </div>

                    </div>
                </div>
                
                <div class="hero-search-box" style="margin-top: 40px; display: block; width: 100%; text-align: center;">
                    <?php displayKbSearch(); ?>
                </div>
            </div>

            <div class="contr">
                <div class="alur-pelaporan-wrapper">
                    <h2 class="alur-title">Alur Pelaporan</h2>

                    <div class="stepper-container">
                        <div class="stepper-item">
                            <div class="step-indicator">
                                <div class="step-number active">1</div>
                                <div class="step-line active"></div> 
                            </div>
                            <div class="step-content-wrapper">
                                <div class="step-icon-box icon-blue">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                </div>
                                <h3 class="step-title">Isi Formulir</h3>
                                <p class="step-desc">Lengkapi data dan jelaskan masalah Anda.</p>
                            </div>
                        </div>

                        <div class="stepper-item">
                            <div class="step-indicator">
                                <div class="step-number">2</div>
                                <div class="step-line"></div>
                            </div>
                            <div class="step-content-wrapper">
                                <div class="step-icon-box icon-grey">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                </div>
                                <h3 class="step-title">Kirim Laporan</h3>
                                <p class="step-desc">Laporan Anda akan kami terima.</p>
                            </div>
                        </div>

                        <div class="stepper-item">
                            <div class="step-indicator">
                                <div class="step-number">3</div>
                                <div class="step-line"></div>
                            </div>
                            <div class="step-content-wrapper">
                                <div class="step-icon-box icon-grey">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H3z"></path><path d="M21 11h-3a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h3z"></path><path d="M3 11V9a9 9 0 0 1 18 0v2"></path><path d="M21 16v2a4 4 0 0 1-4 4h-5"></path></svg>
                                </div>
                                <h3 class="step-title">Diproses Tim</h3>
                                <p class="step-desc">Tim kami akan meninjau dan menindaklanjuti.</p>
                            </div>
                        </div>

                        <div class="stepper-item">
                            <div class="step-indicator">
                                <div class="step-number">4</div>
                            </div>
                            <div class="step-content-wrapper">
                                <div class="step-icon-box icon-green">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="9 12 11 14 15 10"></polyline></svg>
                                </div>
                                <h3 class="step-title">Selesai</h3>
                                <p class="step-desc">Anda akan mendapatkan balasan dan masalah terselesaikan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($hesk_settings['kb_enable']): ?>
            <div class="contr">
                <article class="article original-kb-section">
                    <h2 class="article__heading">
                        <a href="knowledgebase.php">
                            <span class="icon-in-circle" aria-hidden="true">
                                <svg class="icon icon-knowledge">
                                    <use xlink:href="<?php echo TEMPLATE_PATH; ?>customer/img/sprite.svg#icon-knowledge"></use>
                                </svg>
                            </span>
                            <span><?php echo $hesklang['kb_text']; ?></span>
                        </a>
                    </h2>
                    <div class="tabbed__head">
                        <ul class="tabbed__head_tabs">
                            <?php if (count($topArticles) > 0): ?>
                            <li class="current" data-link="tab1">
                                <span><?php echo $hesklang['popart']; ?></span>
                            </li>
                            <?php endif; if (count($latestArticles) > 0): ?>
                            <li data-link="tab2">
                                <span><?php echo $hesklang['latart']; ?></span>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="tabbed__tabs">
                        <?php if (count($topArticles) > 0): ?>
                        <div class="tabbed__tabs_tab is-visible" data-tab="tab1">
                            <?php foreach ($topArticles as $article): ?>
                            <a href="knowledgebase.php?article=<?php echo $article['id']; ?>" class="preview">
                                <span class="icon-in-circle" aria-hidden="true">
                                    <svg class="icon icon-knowledge">
                                        <use xlink:href="<?php echo TEMPLATE_PATH; ?>customer/img/sprite.svg#icon-knowledge"></use>
                                    </svg>
                                </span>
                                <div class="preview__text">
                                    <h3 class="preview__title"><?php echo $article['subject'] ?></h3>
                                    <p>
                                        <span class="lightgrey"><?php echo $hesklang['kb_cat']; ?>:</span>
                                        <span class="ml-1"><?php echo $article['category']; ?></span>
                                    </p>
                                    <p class="navlink__descr">
                                        <?php echo $article['content_preview']; ?>
                                    </p>
                                </div>
                                <?php if ($hesk_settings['kb_views'] || $hesk_settings['kb_rating']): ?>
                                    <div class="rate">
                                        <?php if ($hesk_settings['kb_views']): ?>
                                            <div style="margin-right: 10px; display: flex;">
                                                <svg class="icon icon-eye-close">
                                                    <use xlink:href="<?php echo TEMPLATE_PATH; ?>customer/img/sprite.svg#icon-eye-close"></use>
                                                </svg>
                                                <span class="lightgrey"><?php echo $article['views_formatted']; ?></span>
                                            </div>
                                        <?php endif; if ($hesk_settings['kb_rating']): ?>
                                            <?php echo hesk3_get_customer_rating($article['rating']); ?>
                                            <?php if ($hesk_settings['kb_views']) echo '<span class="lightgrey">('.$article['votes_formatted'].')</span>'; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; if (count($latestArticles) > 0): ?>
                        <div class="tabbed__tabs_tab <?php echo count($topArticles) === 0 ? 'is-visible' : ''; ?>" data-tab="tab2">
                            <?php foreach ($latestArticles as $article): ?>
                                <a href="knowledgebase.php?article=<?php echo $article['id']; ?>" class="preview">
                                    <span class="icon-in-circle" aria-hidden="true">
                                        <svg class="icon icon-knowledge">
                                            <use xlink:href="<?php echo TEMPLATE_PATH; ?>customer/img/sprite.svg#icon-knowledge"></use>
                                        </svg>
                                    </span>
                                    <div class="preview__text">
                                        <h3 class="preview__title"><?php echo $article['subject'] ?></h3>
                                        <p>
                                            <span class="lightgrey"><?php echo $hesklang['kb_cat']; ?>:</span>
                                            <span class="ml-1"><?php echo $article['category']; ?></span>
                                        </p>
                                        <p class="navlink__descr">
                                            <?php echo $article['content_preview']; ?>
                                        </p>
                                    </div>
                                    <?php if ($hesk_settings['kb_views'] || $hesk_settings['kb_rating']): ?>
                                        <div class="rate">
                                            <?php if ($hesk_settings['kb_views']): ?>
                                                <div style="margin-right: 10px; display: flex;">
                                                    <svg class="icon icon-eye-close">
                                                        <use xlink:href="<?php echo TEMPLATE_PATH; ?>customer/img/sprite.svg#icon-eye-close"></use>
                                                    </svg>
                                                    <span class="lightgrey"><?php echo $article['views_formatted']; ?></span>
                                                </div>
                                            <?php endif; if ($hesk_settings['kb_rating']): ?>
                                                <?php echo hesk3_get_customer_rating($article['rating']); ?>
                                                <?php if ($hesk_settings['kb_views']) echo '<span class="lightgrey">('.$article['votes_formatted'].')</span>'; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="article__footer">
                        <a href="knowledgebase.php" class="btn btn--blue-border" ripple="ripple"><?php echo $hesklang['viewkb']; ?></a>
                    </div>
                </article>
            </div>
            <?php endif; if (!$customerLoggedIn && $hesk_settings['alink']): ?>
            <div class="contr">
                <div class="article__footer admin-link-wrapper">
                    <a href="<?php echo $hesk_settings['admin_dir']; ?>/" class="link admin-link-button"><?php echo $hesklang['ap']; ?></a>
                </div>
            </div>
            <?php endif; ?>
            
        </div>

        <script>
            // Fungsi Khusus untuk Mengukur Tinggi Iframe Secara Akurat (Trik Reset)
            function adjustIframeHeight(iframeId) {
                var iframe = document.getElementById(iframeId);
                if (!iframe) return;
                
                try {
                    var doc = iframe.contentWindow.document;
                    if (doc && doc.documentElement) {
                        // KUNCI UTAMA: Reset dulu tingginya jadi kecil supaya body-nya ikut menciut
                        iframe.style.height = '10px'; 
                        
                        // Hitung tinggi murni setelah di-reset
                        var tinggiPas = Math.max(
                            doc.body.scrollHeight, 
                            doc.documentElement.scrollHeight,
                            doc.body.offsetHeight, 
                            doc.documentElement.offsetHeight
                        );
                        
                        // Kembalikan tinggi iframe sesuai isi aslinya + buffer 30px biar aman dari bayangan/border
                        iframe.style.height = (tinggiPas + 30) + 'px';
                    }
                } catch(e) {
                    console.log("Iframe Blocked.", e);
                }
            }

            // Fungsi Pindah Tab
            function switchTab(tab) {
                var contentBuat = document.getElementById('content-buat');
                var contentCek = document.getElementById('content-cek');
                var btnBuat = document.getElementById('tab-buat');
                var btnCek = document.getElementById('tab-cek');

                if (tab === 'buat') {
                    contentBuat.style.display = 'block';
                    contentCek.style.display = 'none';
                    btnBuat.style.color = '#1d4ed8';
                    btnBuat.style.background = '#fff';
                    btnBuat.style.borderBottom = '3px solid #1d4ed8';
                    btnCek.style.color = '#888';
                    btnCek.style.background = '#fdfdfd';
                    btnCek.style.borderBottom = '3px solid transparent';
                    
                    setTimeout(function() { adjustIframeHeight('iframe-buat'); }, 100);
                    
                } else {
                    contentBuat.style.display = 'none';
                    contentCek.style.display = 'block';
                    btnCek.style.color = '#1d4ed8';
                    btnCek.style.background = '#fff';
                    btnCek.style.borderBottom = '3px solid #1d4ed8';
                    btnBuat.style.color = '#888';
                    btnBuat.style.background = '#fdfdfd';
                    btnBuat.style.borderBottom = '3px solid transparent';
                    
                    setTimeout(function() { adjustIframeHeight('iframe-cek'); }, 100);
                }
            }

            // Fungsi Merombak Iframe (Dipanggil otomatis tiap iframe nge-load/refresh)
            function cleanIframe(iframeId) {
                var iframe = document.getElementById(iframeId);
                try {
                    var doc = iframe.contentWindow.document;

                    var inputs = doc.querySelectorAll('input, textarea, select');
                    inputs.forEach(function(input) { input.blur(); });

                    var footers = doc.querySelectorAll('footer, .footer');
                    footers.forEach(function(footer) {
                        footer.style.setProperty('display', 'none', 'important');
                        footer.style.opacity = '0';
                        footer.style.visibility = 'hidden';
                        footer.style.height = '0px';
                        footer.style.position = 'absolute';
                        footer.style.zIndex = '-9999';
                        footer.innerHTML = '';
                    });

                    var style = doc.createElement('style');
                    style.innerHTML = `
                        .header, header, .navbar, .breadcrumbs, .hero-wrapper { 
                            display: none !important; 
                        }
                        
                        /* Kembalikan settingan CSS body ke natural biar form panjang ga kepotong */
                        body, html { 
                            background: transparent !important; 
                            padding: 0 !important; 
                            margin: 0 !important; 
                            overflow-y: hidden !important; /* Cegah scrollbar dobel */
                        }
                        
                        .contr { 
                            width: 100% !important; 
                            max-width: 100% !important; 
                            padding: 20px 20px 30px 20px !important; 
                            margin: 0 !important; 
                            box-sizing: border-box !important;
                        }
                        
                        @media screen and (max-width: 768px) {
                            .contr { padding: 10px 10px 20px 10px !important; }
                        }

                        .ticket-view-form, .ticket-view-form > div, .ticket-view-form__body {
                            background: transparent !important; border: none !important; box-shadow: none !important; padding: 0 !important; margin: 0 !important;
                        }

                        h3.navlink__title, .page-title, h2, .h2, .icon-in-circle, .ticket-view-form > p { 
                            display: none !important; 
                        }
                        
                        label, .form-label, .control-label {
                            font-weight: 600 !important; color: #4b5563 !important; margin-bottom: 8px !important; display: inline-block !important; font-size: 14px !important; font-family: inherit !important;
                        }

                        input[type="text"], input[type="email"], input[type="password"], select, textarea, .form-control {
                            width: 100% !important; padding: 14px 16px !important; border: 1px solid #cbd5e1 !important; border-radius: 6px !important; box-sizing: border-box !important; font-family: inherit !important; font-size: 14px !important; background-color: #f8fafc !important; color: #333 !important; transition: all 0.3s ease !important; box-shadow: none !important;
                        }

                        input:focus, textarea:focus, select:focus {
                            border-color: #1d4ed8 !important; outline: none !important; background-color: #fff !important; box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.15) !important;
                        }

                        input[type="submit"], button[type="submit"], .btn, .btn-primary {
                            background-color: #1d4ed8 !important; color: white !important; padding: 14px 45px !important; border: none !important; border-radius: 6px !important; font-weight: bold !important; font-size: 15px !important; cursor: pointer !important; float: right !important; margin-top: 10px !important; margin-bottom: 0px !important; transition: background 0.3s !important; text-transform: uppercase !important;
                        }
                        
                        input[type="submit"]:hover, button[type="submit"]:hover, .btn:hover, .btn-primary:hover {
                            background-color: #1e40af !important;
                        }

                        .clearfix::after { content: ""; clear: both; display: table; }
                    `;
                    doc.head.appendChild(style);

                    // Pasang observer biar dinamis saat error validasi muncul / form diketik
                    if (iframe.contentWindow.ResizeObserver) {
                        const resizeObserver = new iframe.contentWindow.ResizeObserver(function() {
                            // Cek jika tinggi belum di-set jadi 10px (mencegah loop tak terhingga)
                            if (iframe.style.height !== '10px') {
                                adjustIframeHeight(iframeId);
                            }
                        });
                        resizeObserver.observe(doc.body);
                    }
                    
                    // Trigger penyesuaian awal saat onload selesai
                    setTimeout(function() { adjustIframeHeight(iframeId); }, 200);
                    setTimeout(function() { adjustIframeHeight(iframeId); }, 1000);

                } catch(e) {
                    console.log("Iframe Blocked.", e);
                }
            }
        </script>

<?php
/* Print Footer */
require_once(TEMPLATE_PATH . 'customer/inc/footer.inc.php');
?>