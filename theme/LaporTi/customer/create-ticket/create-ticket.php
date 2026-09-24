<?php
global $hesk_settings, $hesklang;
/**
 * @var string $categoryName
 * @var int $categoryId
 * @var array $visibleCustomFieldsBeforeMessage
 * @var array $visibleCustomFieldsAfterMessage
 * @var array $customFieldsBeforeMessage
 * @var array $customFieldsAfterMessage
 * @var bool $customerLoggedIn - `true` if a customer is logged in, `false` otherwise
 * @var array $customerUserContext - User info for a customer if logged in.  `null` if a customer is not logged in.
 */

// This guard is used to ensure that users can't hit this outside of actual HESK code
if (!defined('IN_SCRIPT')) {
    die();
}
define('EXTRA_PAGE_CLASSES', 'page-create-ticket');

define('ALERTS', 1);
define('CUSTOM_FIELDS', 1);
define('ATTACHMENTS', 1);
define('PRIORITIES', 1);

define('LOAD_CSS_DROPZONE', 1);
define('RENDER_COMMON_ELEMENTS', 1);

define('TMP_TITLE', 1); // TODO absolutelyRework

define('LOAD_JS_DATEPICKER', 1);
define('LOAD_JS_DROPZONE', 1);

define('FOOTER_DONT_CLOSE_HTML', 1);

global $BREADCRUMBS;
$BREADCRUMBS = array(
    array('url' => $hesk_settings['site_url'], 'title' => $hesk_settings['site_title']),
    array('url' => 'index.php', 'title' => $hesk_settings['hesk_title']),
    array('url' => 'index.php?a=add', 'title' => $hesklang['submit_ticket']),
    array('title' => $categoryName)
);

/* Print header */
require_once(TEMPLATE_PATH . 'customer/inc/header.inc.php');
?>
        <style>
            .page-create-ticket .laporan-shell {
                display: grid;
                grid-template-columns: minmax(0, 1.7fr) minmax(280px, 0.9fr);
                gap: 24px;
                align-items: start;
                margin-top: 24px;
                margin-bottom: 60px;
            }

            .page-create-ticket .laporan-hero {
                position: relative;
                overflow: hidden;
                margin-bottom: 28px;
                padding: 32px;
                border-radius: 20px;
                background: linear-gradient(135deg, #f4fbff 0%, #ffffff 55%, #eef8f3 100%);
                box-shadow: 0 18px 40px rgba(20, 55, 90, 0.08);
            }

            .page-create-ticket .laporan-hero::after {
                content: '';
                position: absolute;
                inset: auto -60px -60px auto;
                width: 220px;
                height: 220px;
                border-radius: 999px;
                background: radial-gradient(circle, rgba(0, 168, 150, 0.18) 0%, rgba(0, 168, 150, 0) 72%);
                pointer-events: none;
            }

            .page-create-ticket .laporan-hero__eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(20, 90, 150, 0.08);
                color: #145a96;
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .page-create-ticket .laporan-hero__title {
                margin: 16px 0 10px;
                font-size: clamp(28px, 4vw, 40px);
                line-height: 1.1;
                color: #17324d;
            }

            .page-create-ticket .laporan-hero__desc {
                max-width: 700px;
                margin: 0;
                color: #5a6f85;
                font-size: 15px;
                line-height: 1.7;
            }

            .page-create-ticket .laporan-card {
                padding: 28px;
                border: 1px solid rgba(20, 90, 150, 0.1);
                border-radius: 20px;
                background: var(--surface);
                box-shadow: 0 18px 40px rgba(20, 55, 90, 0.08);
            }

            .page-create-ticket .laporan-card--form {
                padding: 0;
                overflow: hidden;
            }

            .page-create-ticket .laporan-card__header {
                padding: 28px 28px 0;
            }

            .page-create-ticket .laporan-card__title {
                margin: 0;
                font-size: 26px;
                line-height: 1.2;
                color: #17324d;
            }

            .page-create-ticket .laporan-card__desc {
                margin: 10px 0 0;
                color: #60758a;
                line-height: 1.7;
            }

            .page-create-ticket .laporan-form {
                margin-bottom: 0;
                padding: 28px;
                border-radius: 0;
                box-shadow: none;
                background: transparent;
            }

            .page-create-ticket .laporan-form .form-group,
            .page-create-ticket .laporan-form .param {
                max-width: none;
            }

            .page-create-ticket .laporan-row {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 18px;
            }

            .page-create-ticket .laporan-row .form-group {
                margin-bottom: 24px;
            }

            .page-create-ticket .laporan-row .captcha-block,
            .page-create-ticket .laporan-row .param {
                max-width: none;
            }

            .page-create-ticket .laporan-row--single {
                grid-template-columns: minmax(0, 1fr);
            }

            .page-create-ticket .laporan-divider {
                height: 1px;
                margin: 8px 0 28px;
                background: linear-gradient(90deg, rgba(20, 90, 150, 0.18), rgba(20, 90, 150, 0));
            }

            .page-create-ticket .laporan-form .divider {
                display: none;
            }

            .page-create-ticket .laporan-form textarea.form-control {
                min-height: 210px;
                height: auto;
            }

            .page-create-ticket .laporan-form .kb-suggestions {
                margin-top: 18px;
            }

            .page-create-ticket .laporan-form .captcha-block {
                max-width: none;
                margin: 0;
                text-align: left;
            }

            .page-create-ticket .laporan-form .captcha-block h2 {
                margin-bottom: 18px;
            }

            .page-create-ticket .laporan-form .attach {
                display: block;
            }

            .page-create-ticket .laporan-form .attach .attach-tooltype {
                margin-top: 12px;
            }

            .page-create-ticket .laporan-form .dropdown-select select,
            .page-create-ticket .laporan-form .selectize-input,
            .page-create-ticket .laporan-form input.form-control,
            .page-create-ticket .laporan-form textarea.form-control {
                border-radius: 14px;
            }

            .page-create-ticket .laporan-side {
                display: grid;
                gap: 20px;
            }

            .page-create-ticket .laporan-aside__title {
                margin: 0 0 14px;
                font-size: 20px;
                color: #17324d;
            }

            .page-create-ticket .laporan-checklist {
                display: grid;
                gap: 14px;
                padding: 0;
                margin: 0;
                list-style: none;
            }

            .page-create-ticket .laporan-checklist li {
                display: grid;
                grid-template-columns: 20px minmax(0, 1fr);
                gap: 12px;
                align-items: start;
                color: #4d6277;
                line-height: 1.6;
            }

            .page-create-ticket .laporan-checklist li::before {
                content: '';
                width: 20px;
                height: 20px;
                margin-top: 2px;
                border-radius: 999px;
                background: radial-gradient(circle at 50% 50%, #00a896 0 35%, rgba(0, 168, 150, 0.18) 36% 100%);
            }

            .page-create-ticket .laporan-contact {
                padding: 22px;
                border-radius: 16px;
                background: linear-gradient(135deg, #17324d 0%, #145a96 100%);
                color: #f7fbff;
            }

            .page-create-ticket .laporan-contact p {
                margin: 0;
            }

            .page-create-ticket .laporan-contact__label {
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: rgba(247, 251, 255, 0.76);
            }

            .page-create-ticket .laporan-contact__value {
                margin-top: 10px;
                font-size: 24px;
                font-weight: 700;
                line-height: 1.2;
            }

            .page-create-ticket .laporan-contact__note {
                margin-top: 12px;
                color: rgba(247, 251, 255, 0.78);
                line-height: 1.6;
            }

            .page-create-ticket .laporan-submit {
                display: flex;
                flex-wrap: wrap;
                gap: 14px;
                justify-content: space-between;
                align-items: center;
                margin-top: 34px;
                padding-top: 24px;
                border-top: 1px solid rgba(20, 90, 150, 0.12);
            }

            .page-create-ticket .laporan-submit__meta {
                color: #60758a;
                font-size: 13px;
                line-height: 1.6;
            }

            .page-create-ticket .laporan-submit__actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                justify-content: flex-end;
            }

            /* Menyamakan ukuran semua tombol di area aksi (Batalkan & Kirim Tiket) */
            .page-create-ticket .laporan-submit__actions .btn {
                min-width: 190px !important;
                height: 52px !important; /* Tinggi absolut agar presisi 100% sama */
                border-radius: 8px !important; /* Silakan ubah ke 16px jika ingin lebih membulat */
                padding: 0 24px !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                box-sizing: border-box !important;
                font-size: 15px !important;
                text-transform: uppercase !important; /* Seragamkan teks jadi kapital semua */
            }

            /* 1. KTERSIER: Tombol BATALKAN (Ghost Button) 
               Dibuat paling tidak mencolok agar tidak tidak sengaja terklik */
            .page-create-ticket .laporan-submit__actions .btn--ghost {
                background: transparent !important;
                border: 1px solid rgba(20, 90, 150, 0.3) !important;
                color: #145a96 !important;
                box-shadow: none !important;
                transition: all 0.2s ease !important;
            }
            
            .page-create-ticket .laporan-submit__actions .btn--ghost:hover {
                background: rgba(20, 90, 150, 0.08) !important;
                border-color: rgba(20, 90, 150, 0.5) !important;
                color: #0f4675 !important;
            }
            
            /* 2. SEKUNDER: Tombol ADD FILE (Outline/Dashed Button)
               Menargetkan tombol di dalam area dropzone bawaan sistem */
            .page-create-ticket .laporan-form .attach .btn,
            .page-create-ticket .laporan-form .attach button {
                background: #ffffff !important;
                border: 1px dashed rgba(20, 90, 150, 0.5) !important;
                color: #145a96 !important;
                box-shadow: none !important;
                font-weight: 600 !important;
                transition: all 0.2s ease !important;
                padding-inline: 8px;
            }
            
            .page-create-ticket .laporan-form .attach .btn:hover,
            .page-create-ticket .laporan-form .attach button:hover {
                background: #f4fbff !important; /* Latar biru sangat muda saat hover */
                border: 1px solid #145a96 !important; /* Berubah jadi solid saat hover */
            }
            
            /* 3. PRIMER: Tombol KIRIM TIKET (Solid Button)
               Titik fokus utama halaman. Memastikan style bawaan tampil maksimal */
            .page-create-ticket .laporan-submit__actions .btn-full {
                background: #145a96 !important;
                color: #ffffff !important;
                border: none !important;
                font-weight: 700 !important;
                box-shadow: 0 4px 14px rgba(20, 90, 150, 0.25) !important;
                transition: all 0.2s ease !important;
            }
            
            .page-create-ticket .laporan-submit__actions .btn-full:hover {
                background: #0f4675 !important;
                box-shadow: 0 6px 20px rgba(20, 90, 150, 0.35) !important;
                transform: translateY(-1px) !important; /* Sedikit efek terangkat saat hover */
}
            

            @media screen and (max-width: 992px) {
                .page-create-ticket .laporan-shell {
                    grid-template-columns: minmax(0, 1fr);
                }

                .page-create-ticket .laporan-side {
                    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
                }
            }

            @media screen and (max-width: 768px) {
                /**.page-create-ticket .laporan-hero,
                *.page-create-ticket .laporan-card,
                *.page-create-ticket .laporan-card__header,
                *.page-create-ticket .laporan-form {
                *   padding-left: 20px;
                *   padding-right: 20px;
                *}
                */

                .page-create-ticket .laporan-row {
                    grid-template-columns: minmax(0, 1fr);
                    gap: 0;
                }

                .page-create-ticket .laporan-submit {
                    align-items: stretch;
                }

                .page-create-ticket .laporan-submit__actions {
                    width: 100%;
                }

                .page-create-ticket .laporan-submit__actions .btn {
                    width: 100%;
                }
            }
        </style>
        <div class="main__content">
            <div class="contr">
                <div style="margin-bottom: 20px;">
                    <?php hesk3_show_messages($serviceMessages); ?>
                    <?php hesk3_show_messages($messages); ?>
                </div>

                <section class="laporan-hero">
                    <span class="laporan-hero__eyebrow"><?php echo $hesklang['submit_ticket']; ?></span>
                    <h1 class="laporan-hero__title"><?php echo $hesklang['submit_a_support_request']; ?></h1>
                    <p class="laporan-hero__desc">Sampaikan laporan Anda dengan jelas agar tim dapat memproses, memverifikasi, dan menindaklanjuti dengan lebih cepat tanpa kehilangan detail penting.</p>
                </section>

                <div class="laporan-shell">
                    <div class="laporan-card laporan-card--form">
                        <div class="laporan-card__header">
                            <h2 class="laporan-card__title">Detail Laporan</h2>
                        </div>

                        <form class="form form-submit-ticket ticket-create laporan-form <?php echo count($_SESSION['iserror']) ? 'invalid' : ''; ?>" method="post" action="submit_ticket.php?submit=1" aria-label="<?php echo $hesklang['create_a_ticket']; ?>" name="form1" id="form1" enctype="multipart/form-data" onsubmit="<?php if ($hesk_settings['submitting_wait']): ?>hesk_showLoadingMessage('recaptcha-submit');<?php endif; ?>" <?php echo $hesk_settings['disable_autofill_customer'] ? 'autocomplete="off" aria-autocomplete="none"' : ''; ?>>
                            <?php if (!$customerLoggedIn) { ?>
                            <div class="laporan-row">
                                <div class="form-group">
                                    <label class="label required" for="name"><?php echo $hesklang['name']; ?>:</label>
                                    <?php
                                    $input_css = 'form-control';
                                    if (in_array('name', $_SESSION['iserror'])) {
                                        $input_css .= ' isError';
                                    }
                                    ?>
                                    <input type="text" id="name" name="name"
                                           class="<?php echo $input_css; ?>"
                                           maxlength="50"
                                           placeholder="Nama lengkap Anda"
                                           value="<?php
                                           if (isset($_SESSION['c_name'])) {
                                               echo stripslashes(hesk_input($_SESSION['c_name']));
                                           } ?>"
                                           <?php echo $hesk_settings['disable_autofill_customer'] ? 'autocomplete="off" aria-autocomplete="none"' : ''; ?>
                                           required>
                                </div>
                                <div class="form-group">
                                    <label class="label <?php if ($hesk_settings['require_email']) { ?>required<?php } ?>" for="email"><?php echo $hesklang['email']; ?>:</label>
                                    <?php
                                    $input_css = 'form-control';
                                    if (in_array('email', $_SESSION['iserror'])) {
                                        $input_css .= ' isError';
                                    }
                                    if (in_array('email', $_SESSION['isnotice'])) {
                                        $input_css .= ' isNotice';
                                    }
                                    ?>
                                    <input type="email"
                                           class="<?php echo $input_css; ?>"
                                           name="email" id="email" maxlength="1000"
                                           placeholder="Alamat email aktif"
                                           value="<?php
                                           if (isset($_SESSION['c_email'])) {
                                               echo stripslashes(hesk_input($_SESSION['c_email']));
                                           } ?>"
                                           <?php echo $hesk_settings['disable_autofill_customer'] ? 'autocomplete="off" aria-autocomplete="none"' : ''; ?>
                                           <?php if ($hesk_settings['detect_typos']) { echo ' onblur="HESK_FUNCTIONS.suggestEmail(\'email\', \'email_suggestions\', 0)"'; } ?>
                                           <?php if ($hesk_settings['require_email']) { ?>required<?php } ?>>
                                    <div id="email_suggestions" class="email-suggestion"></div>
                                </div>
                            </div>
                            <?php
                            if ($hesk_settings['confirm_email']):
                                $input_css = 'form-control';
                                if (in_array('email2', $_SESSION['iserror'])) {
                                    $input_css .= ' isError';
                                }
                                if (in_array('email2', $_SESSION['isnotice'])) {
                                    $input_css .= ' isNotice';
                                }
                                if ($customerLoggedIn) {
                                    $input_css .= ' as-text';
                                }
                                ?>
                                <div class="laporan-row laporan-row--single">
                                    <div class="form-group">
                                        <label class="label <?php if ($hesk_settings['require_email']) { ?>required<?php } ?>" for="email2"><?php echo $hesklang['confemail']; ?>:</label>
                                        <input type="<?php echo $hesk_settings['multi_eml'] ? 'text' : 'email'; ?>"
                                               class="<?php echo $input_css; ?>"
                                               name="email2" id="email2" maxlength="1000"
                                               <?php echo $hesk_settings['disable_autofill_customer'] ? 'autocomplete="off" aria-autocomplete="none"' : ''; ?>
                                               <?php if ($customerLoggedIn) { echo 'readonly'; } ?>
                                               value="<?php if (isset($_SESSION['c_email2'])) {echo stripslashes(hesk_input($_SESSION['c_email2']));} ?>"
                                               <?php if ($hesk_settings['require_email']) { ?>required<?php } ?>>
                                    </div>
                                </div>
                                <?php
                            endif;
                            }

                            if ($hesk_settings['multi_eml'] && !isset($_SESSION['c_followers'])): ?>
                            <div class="form-group" id="cc-link">
                                <a href="#" onclick="HESK_FUNCTIONS.toggleLayerDisplay('cc-div');HESK_FUNCTIONS.toggleLayerDisplay('cc-link')">
                                    <?php echo $hesklang['add_cc']; ?>
                                </a>
                            </div>
                            <?php endif;

                            if ($hesk_settings['multi_eml']):
                                $display = isset($_SESSION['c_followers']) ? 'block' : 'none';
                            ?>
                            <div class="form-group" id="cc-div" style="display: <?php echo $display; ?>">
                                <label class="label" for="follower_email"><?php echo $hesklang['cc']; ?>:</label>
                                <?php
                                $input_css = 'form-control';
                                if (in_array('followers', $_SESSION['iserror'])) {
                                    $input_css .= ' isError';
                                }
                                if (in_array('followers', $_SESSION['isnotice'])) {
                                    $input_css .= ' isNotice';
                                }
                                ?>
                                <input type="text"
                                       class="<?php echo $input_css; ?>"
                                       <?php echo $hesk_settings['disable_autofill_customer'] ? 'autocomplete="off" aria-autocomplete="none"' : ''; ?>
                                       name="follower_email" id="follower_email" maxlength="1000"
                                       value="<?php
                                       if (isset($_SESSION['c_followers'])) {
                                           echo stripslashes(hesk_input($_SESSION['c_followers']));
                                       } ?>" <?php if ($hesk_settings['detect_typos']) { echo ' onblur="HESK_FUNCTIONS.suggestEmail(\'follower_email\', \'follower_email_suggestions\', 0)"'; } ?>>
                                <div id="follower_email_suggestions" class="email-suggestion"></div>
                                <p><?php if ($hesk_settings['customer_accounts'] && $hesk_settings['customer_accounts_required']) echo $hesklang['only_verified_cc'] . ' '; echo $hesklang['cc_help']; ?></p>
                            </div>
                            <?php
                            endif;

                            if ($hesk_settings['cust_urgency']): ?>
                                <section class="param">
                                    <span class="label required <?php if (in_array('priority', $_SESSION['iserror'])) echo 'isErrorStr'; ?>"><?php echo $hesklang['priority']; ?>:</span>
                                    <div class="dropdown-select center out-close priority select-priority">
                                        <select name="priority" aria-label="<?php echo $hesklang['priority']; ?>">
                                            <?php if ($hesk_settings['select_pri']): ?>
                                                <option value=""><?php echo $hesklang['select']; ?></option>
                                            <?php endif; ?>
                                            <?php echo hesk_get_priority_select('', 0, $_SESSION['c_priority']); ?>
                                        </select>
                                    </div>
                                </section>
                            <?php endif; ?>

                            <?php if (count($visibleCustomFieldsBeforeMessage) > 0): ?>
                                <div class="laporan-divider"></div>
                            <?php endif; ?>

                            <?php hesk3_output_custom_fields($customFieldsBeforeMessage); ?>

                            <?php if ($hesk_settings['require_subject'] != -1 || $hesk_settings['require_message'] != -1): ?>
                                <div class="laporan-divider"></div>
                                <?php if ($hesk_settings['require_subject'] != -1): ?>
                                    <div class="form-group">
                                        <label class="label <?php if ($hesk_settings['require_subject']) { ?>required<?php } ?>" for="subject">
                                            <?php echo $hesklang['subject']; ?>:
                                        </label>
                                        <input type="text" id="subject" class="form-control <?php if (in_array('subject', $_SESSION['iserror'])) {echo 'isError';} ?>"
                                               name="subject" maxlength="70"
                                               placeholder="Contoh: Masalah koneksi internet"
                                               value="<?php if (isset($_SESSION['c_subject'])) {echo stripslashes(hesk_input($_SESSION['c_subject']));} ?>"
                                               <?php echo $hesk_settings['disable_autofill_customer'] ? 'autocomplete="off" aria-autocomplete="none"' : ''; ?>
                                               <?php if ($hesk_settings['require_subject']) { ?>required<?php } ?>>
                                    </div>
                                <?php endif;

                                if ($hesk_settings['require_message'] != -1): ?>
                                    <div class="form-group">
                                        <label class="label <?php if ($hesk_settings['require_message']) { ?>required<?php } ?>" for="message">
                                            <?php echo $hesklang['message']; ?>:
                                        </label>
                                        <textarea class="form-control <?php if (in_array('message', $_SESSION['iserror'])) {echo 'isError';} ?>"
                                                  id="message" name="message" rows="12" cols="60"
                                                  placeholder="Jelaskan detail laporan Anda..."
                                                  <?php if ($hesk_settings['require_message']) { ?>required<?php } ?>><?php if (isset($_SESSION['c_message'])) {echo stripslashes(hesk_input($_SESSION['c_message']));} ?></textarea>
                                        <?php if (has_public_kb() && $hesk_settings['kb_recommendanswers'] && !isset($_REQUEST['do_not_suggest'])): ?>
                                            <div class="kb-suggestions">
                                                <h2><?php echo $hesklang['sc']; ?>:</h2>
                                                <ul id="kb-suggestion-list" class="type--list">
                                                </ul>
                                                <div id="suggested-article-hidden-inputs" style="display: none">
                                                    <?php // Will be populated with the list sent to the create ticket logic ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if (count($visibleCustomFieldsAfterMessage) > 0): ?>
                                <div class="laporan-divider"></div>
                            <?php endif; ?>

                            <?php hesk3_output_custom_fields($customFieldsAfterMessage); ?>

                            <?php if ($hesk_settings['attachments']['use']): ?>
                                <div class="laporan-divider"></div>
                                <section class="param param--attach">
                                    <span class="label"><?php echo $hesklang['attachments']; ?>:</span>
                                    <div class="attach">
                                        <div>
                                            <?php hesk3_output_drag_and_drop_attachment_holder(); ?>
                                        </div>
                                        <div class="attach-tooltype">
                                            <a class="link" href="file_limits.php" onclick="HESK_FUNCTIONS.openWindow('file_limits.php',250,500);return false;">
                                                <?php echo $hesklang['ful']; ?>
                                            </a>
                                        </div>
                                    </div>
                                </section>
                            <?php endif; ?>

                            <?php if ($hesk_settings['question_use'] || ($hesk_settings['secimg_use'] && $hesk_settings['recaptcha_use'] !== 1)): ?>
                                <div class="laporan-divider"></div>
                                <div class="captcha-block">
                                    <h2><?php echo $hesklang['verify_header']; ?></h2>

                                    <?php if ($hesk_settings['question_use']): ?>
                                    <div class="form-group">
                                        <label class="required" for="question"><?php echo $hesk_settings['question_ask']; ?></label>
                                        <?php
                                        $value = '';
                                        if (isset($_SESSION['c_question'])) {
                                            $value = stripslashes(hesk_input($_SESSION['c_question']));
                                        }
                                        ?>
                                        <input type="text" id="question" class="form-control <?php echo in_array('question', $_SESSION['iserror']) ? 'isError' : ''; ?>"
                                               name="question" size="20" value="<?php echo $value; ?>"
                                               <?php echo $hesk_settings['disable_autofill_customer'] ? 'autocomplete="off" aria-autocomplete="none"' : ''; ?>>
                                    </div>
                                    <?php endif;

                                    if ($hesk_settings['secimg_use'] && $hesk_settings['recaptcha_use'] != 1) {
                                        ?>
                                        <div class="form-group">
                                            <?php
                                            if (isset($_SESSION['img_verified'])) {
                                                echo $hesklang['vrfy'];
                                            } elseif ($hesk_settings['recaptcha_use'] == 2) {
                                                ?>
                                                <div class="g-recaptcha" data-sitekey="<?php echo $hesk_settings['recaptcha_public_key']; ?>"></div>
                                                <?php
                                            } else {
                                                $cls = in_array('mysecnum', $_SESSION['iserror']) ? 'isError' : '';
                                                ?>
                                                <div class="form-captcha">
                                                    <img name="secimg" src="print_sec_img.php?<?php echo rand(10000,99999); ?>" width="150" height="40" alt="<?php echo $hesklang['sec_img']; ?>" title="<?php echo $hesklang['sec_img']; ?>" style="vertical-align:text-bottom">
                                                    <a class="btn btn-refresh" href="javascript:void(0)" onclick="javascript:document.form1.secimg.src='print_sec_img.php?'+ ( Math.floor((90000)*Math.random()) + 10000);">
                                                        <svg class="icon icon-refresh">
                                                            <use xlink:href="<?php echo TEMPLATE_PATH; ?>customer/img/sprite.svg#icon-refresh"></use>
                                                        </svg>
                                                    </a>
                                                </div>
                                                <label class="required" for="mysecnum"><?php echo $hesklang['sec_enter']; ?></label>
                                                <input type="text" id="mysecnum" name="mysecnum" size="20" maxlength="5" autocomplete="off" aria-autocomplete="none" class="form-control <?php echo $cls; ?>">
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($hesk_settings['submit_notice']): ?>
                                <div class="laporan-divider"></div>
                                <div class="alert browser-default">
                                    <div class="alert__inner">
                                        <b class="font-weight-bold"><?php echo $hesklang['before_submit']; ?>:</b>
                                        <ul>
                                            <li><?php echo $hesklang['all_info_in']; ?>.</li>
                                            <li><?php echo $hesklang['all_error_free']; ?>.</li>
                                        </ul>
                                        <br>
                                        <b class="font-weight-bold"><?php echo $hesklang['we_have']; ?>:</b>
                                        <ul>
                                            <li><?php echo hesk_htmlspecialchars(hesk_getClientIP()) . ' ' . $hesklang['recorded_ip']; ?></li>
                                            <li><?php echo $hesklang['recorded_time']; ?></li>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="laporan-submit">
                                <div class="laporan-submit__meta">
                                    <span><?php echo $hesklang['req_marked_with']; ?> <span class="label required"></span></span>
                                </div>
                                <div class="laporan-submit__actions" style="align-items: center; display: flex;">
                                    <button type="button" class="btn btn--ghost" onclick="window.history.back();">Batalkan</button>
                                    <input type="hidden" name="token" value="<?php hesk_token_echo(); ?>">
                                    <input type="hidden" name="category" value="<?php echo $categoryId; ?>">
                                    <button type="submit" class="btn btn-full" ripple="ripple" id="recaptcha-submit">
                                        <?php echo $hesklang['sub_ticket']; ?>
                                    </button>
                                </div>
                                <!-- Do not delete or modify the code below, it is used to detect simple SPAM bots -->
                                <input type="hidden" name="hx" value="3" /><input type="hidden" name="hy" value="">
                                <!-- >
                                <input type="text" name="phone" value="3">
                                < -->
                            </div>

                            <?php
                            if ($hesk_settings['secimg_use'] && $hesk_settings['recaptcha_use'] == 1 && !isset($_SESSION['img_verified'])) {
                                ?>
                                <div class="g-recaptcha" data-sitekey="<?php echo $hesk_settings['recaptcha_public_key']; ?>" data-bind="recaptcha-submit" data-callback="recaptcha_submitForm"></div>
                                <?php
                            }
                            ?>
                        </form>
                    </div>

                    <aside class="laporan-side">
                        <div class="laporan-card">
                            <h3 class="laporan-aside__title">Tips Pelaporan</h3>
                            <ul class="laporan-checklist">
                                <li><span><strong>Gunakan judul yang jelas.</strong><br>Ringkas, spesifik, dan menggambarkan inti masalah.</span></li>
                                <li><span><strong>Tuliskan kronologi seperlunya.</strong><br>Sebutkan apa yang terjadi, kapan terjadi, dan dampaknya.</span></li>
                                <li><span><strong>Lampirkan bukti bila ada.</strong><br>Foto, dokumen, atau tangkapan layar membantu percepatan verifikasi.</span></li>
                            </ul>
                        </div>

                        <div class="laporan-contact">
                            <p class="laporan-contact__label">Butuh bantuan cepat?</p>
                            <p class="laporan-contact__value">089525958857 - ARIFIN</p>
                            <p class="laporan-contact__note">Gunakan kontak ini jika Anda memerlukan bantuan saat mengisi formulir atau perlu tindak lanjut yang lebih cepat.</p>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
        <div id="loading-overlay" class="loading-overlay">
            <div id="loading-message" class="loading-message">
                <div class="spinner"></div>
                <p><?php echo $hesklang['sending_wait']; ?></p>
            </div>
        </div>
<?php
    function hesk_jsString($str)
    {
        $str  = addslashes($str);
        $str  = str_replace('<br />' , '' , $str);
        $from = array("/\r\n|\n|\r/", '/\<a href="mailto\:([^"]*)"\>([^\<]*)\<\/a\>/i', '/\<a href="([^"]*)" target="_blank"\>([^\<]*)\<\/a\>/i');
        $to   = array("\\r\\n' + \r\n'", "$1", "$1");
        return preg_replace($from,$to,$str);
    } // END hesk_jsString()
?>
<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function() {

        $('#select_category').selectize();
        hesk_loadNoResultsSelectizePlugin('<?php echo hesk_jsString($hesklang['no_results_found']); ?>');
        <?php

        foreach ($customFieldsBeforeMessage as $customField)
        {
            if ($customField['type'] == 'select')
            {
                if ($customField['value']['is_searchable'] == 1) {
                    echo "$('#{$customField['name']}').addClass('read-write').attr('placeholder', '".$hesklang["search_by_pattern"]."').selectize({
                        delimiter: ',',
                        valueField: 'id',
                        labelField: 'displayName',
                        searchField: ['displayName'],
                        create: false,
                        copyClassesToDropdown: true,
                        plugins: ['no_results'],
                    });";
                } else {
                    echo "$('#{$customField['name']}').selectize();";
                }
            }
        }
        foreach ($customFieldsAfterMessage as $customField)
        {
            if ($customField['type'] == 'select')
            {
                if ($customField['value']['is_searchable'] == 1) {
                    echo "$('#{$customField['name']}').addClass('read-write').attr('placeholder', '".$hesklang["search_by_pattern"]."').selectize({
                        delimiter: ',',
                        valueField: 'id',
                        labelField: 'displayName',
                        searchField: ['displayName'],
                        create: false,
                        copyClassesToDropdown: true,
                        plugins: ['no_results'],
                    });";
                } else {
                    echo "$('#{$customField['name']}').selectize();";
                }
            }
        }
        ?>
    });
</script>

<?php if (has_public_kb() && $hesk_settings['kb_recommendanswers']): ?>
    <script type="text/javascript">
        var noArticlesFoundText = <?php echo json_encode($hesklang['nsfo']); ?>;

        document.addEventListener("DOMContentLoaded", function() {
            HESK_FUNCTIONS.getKbTicketSuggestions($('input[name="subject"]'),
                $('textarea[name="message"]'),
                function(data) {
                    $('.kb-suggestions').show();
                    var $suggestionList = $('#kb-suggestion-list');
                    var $suggestedArticlesHiddenInputsList = $('#suggested-article-hidden-inputs');
                    $suggestionList.html('');
                    $suggestedArticlesHiddenInputsList.html('');
                    var format = '<a href="knowledgebase.php?article={0}" class="suggest-preview" target="_blank">' +
                        '<span class="icon-in-circle" aria-hidden="true">' +
                        '<svg class="icon icon-knowledge">' +
                        '<use xlink:href="./theme/hesk3/customer/img/sprite.svg#icon-knowledge"></use>' +
                        '</svg>' +
                        '</span>' +
                        '<div class="suggest-preview__text">' +
                        '<p class="suggest-preview__title">{1}</p>' +
                        '<p>{2}</p>' +
                        '</div>' +
                        '</a>';
                    var hiddenInputFormat = '<input type="hidden" name="suggested[]" value="{0}">';
                    var results = false;
                    $.each(data, function() {
                        results = true;
                        $suggestionList.append(format.replace('{0}', this.id).replace('{1}', this.subject).replace('{2}', this.contentPreview));
                        $suggestedArticlesHiddenInputsList.append(hiddenInputFormat.replace('{0}', this.hiddenInputValue));
                    });

                    if (!results) {
                        $suggestionList.append('<li class="no-articles-found">' + noArticlesFoundText + '</li>');
                    }
                }
            );
        });
    </script>
<?php endif;

if (isset($hesk_settings['datepicker'])):
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const myDP = {};
            <?php
            foreach ($hesk_settings['datepicker'] as $selector => $data) {
                echo "
                myDP['{$selector}'] = $('{$selector}').datepicker(".((isset($data['position']) && is_string($data['position'])) ? "{position: '{$data['position']}'}" : "").");
            ";
                if (isset($data['timestamp']) && ($ts = intval($data['timestamp']))) {
                    echo "
                    myDP['{$selector}'].data('datepicker').selectDate(new Date({$ts} * 1000));
                ";
                }
            }
            ?>
        });
    </script>
<?php
endif;
?>
<?php
/* Print Footer */
require_once(TEMPLATE_PATH . 'customer/inc/footer.inc.php');

/*
 * Note: In this case, we have to make sure we load all footer scripts first, as otherwise it breaks some of the custom JS page code
 */
?>
<?php hesk3_output_drag_and_drop_script('c_attachments'); ?>
    </body>
</html>
