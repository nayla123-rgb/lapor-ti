<?php
global $hesk_settings, $hesklang;
/**
 * @var array $customerUserContext - User info for the customer.
 * @var string $trackingId
 * @var string $email
 * @var boolean $rememberEmail
 * @var boolean $displayForgotTrackingIdForm
 * @var boolean $submittedForgotTrackingIdForm
 */

if (!defined('IN_SCRIPT')) {
    die();
}
define('EXTRA_PAGE_CLASSES','page-form');
define('ALERTS',1);
define('RENDER_COMMON_ELEMENTS',1);
define('LOAD_CSS_MODAL',1);
define('LOAD_JS_JQUERY_MODAL',1);

global $BREADCRUMBS;
$BREADCRUMBS = array(
    array('url' => $hesk_settings['site_url'], 'title' => $hesk_settings['site_title']),
    array('url' => $hesk_settings['hesk_url'], 'title' => $hesk_settings['hesk_title']),
    array('title' => $hesklang['view_ticket'])
);

require_once(TEMPLATE_PATH . 'customer/inc/header.inc.php');
?>

<div class="main__content status-cek-bg">
    <div class="contr" style="max-width: 900px; margin: 0 auto; position: relative;">
        
        <div style="margin-bottom: 20px;">
            <?php hesk3_show_messages($serviceMessages); ?>
            <?php if (!$submittedForgotTrackingIdForm) { hesk3_show_messages($messages); } ?>
        </div>

        <div style="text-align: center;">
            <div class="status-cek-header-icon">
                <svg class="status-cek-main-svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <circle cx="10" cy="13" r="2"></circle>
                    <line x1="11.4" y1="14.4" x2="14" y2="17"></line>
                </svg>
                <div class="status-cek-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
            </div>
            <h1 class="status-cek-title">Cek Status Pelaporan</h1>
            <p class="status-cek-subtitle">Masukkan nomor pelaporan dan email Anda untuk melihat perkembangan penanganannya.</p>
        </div>

        <div class="status-cek-card">
            <form action="ticket.php" method="get" name="form2" id="formNeedValidation" style="box-shadow:none; padding:0; background:transparent; margin:0;" novalidate>
                
                <div style="margin-bottom: 24px;">
                    <label class="status-cek-label" for="track">Nomor Pelaporan <span>*</span></label>
                    <div class="status-cek-input-group">
                        <div class="status-cek-icon-box">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </div>
                        <input type="text" id="track" name="track" maxlength="20" placeholder="Contoh: INV-ABC-Z3JP" class="status-cek-input" value="<?php echo $trackingId; ?>" required>
                    </div>
                </div>

                <?php
                $tmp = '';
                if ($hesk_settings['email_view_ticket'])
                {
                    $tmp = 'document.form1.email.value=document.form2.e.value;';
                ?>
                <div style="margin-bottom: 20px;">
                    <label class="status-cek-label" for="e">Email <?php if ($hesk_settings['require_email']) echo '<span>*</span>'; ?></label>
                    <div class="status-cek-input-group">
                        <div class="status-cek-icon-box">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                        <input type="email" id="e" name="e" size="35" placeholder="contoh@email.com" class="status-cek-input" value="<?php echo $email; ?>" <?php if ($hesk_settings['require_email']) echo 'required'; ?>>
                    </div>
                </div>

                <div style="margin-bottom: 30px;">
                    <label class="status-cek-checkbox-wrap">
                        <input type="hidden" name="f" value="1">
                        <input type="checkbox" name="r" value="Y" id="inputRememberMyEmail" <?php if ($rememberEmail) { ?>checked<?php } ?>>
                        <span class="status-cek-checkbox-text">Ingat email saya</span>
                    </label>
                </div>
                <?php } ?>

                <div style="text-align: center; margin-bottom: 24px;">
                    <button type="submit" class="status-cek-btn">Cek Status Pelaporan</button>
                </div>

                <div style="text-align: center; border-top: 1px solid #f1f5f9; padding-top: 24px;">
                    <a href="ticket.php?forgot=1#modal-contents" data-modal="#forgot-modal" style="color: #0284c7; font-weight: 600; text-decoration: underline; font-size: 14px;"><?php echo $hesklang['forgot_tid']; ?></a>
                </div>
            </form>
        </div>

        <div id="forgot-modal" role="dialog" aria-modal="true" aria-label="<?php echo $hesklang['forgot_tid']; ?>" class="<?php echo !$displayForgotTrackingIdForm ? 'modal' : ''; ?>">
            <div id="modal-contents" class="<?php echo !$displayForgotTrackingIdForm ? '' : 'notification orange'; ?>" style="padding-bottom:15px">
                <?php
                if ($submittedForgotTrackingIdForm) {
                    hesk3_show_messages($messages);
                }
                ?>
                <b><?php echo $hesklang['forgot_tid']; ?></b><br><br>
                <?php echo $hesklang['tid_mail']; ?>
                <form action="index.php" method="post" name="form1" id="form1" class="form">
                    <div class="form-group">
                        <label class="label screen-reader-text skiplink" for="forgot-email"><?php echo $hesklang['email']; ?></label>
                        <input id="forgot-email" type="email" class="form-control" name="email" value="<?php echo $email; ?>">
                    </div>
                    <div class="form-group">
                        <div class="radio-custom">
                            <input type="radio" name="open_only" id="open_only1" value="1" <?php echo $hesk_settings['open_only'] ? 'checked' : ''; ?>>
                            <label for="open_only1"><?php echo $hesklang['oon1']; ?></label>
                        </div>
                        <div class="radio-custom">
                            <input type="radio" name="open_only" id="open_only0" value="0" <?php echo !$hesk_settings['open_only'] ? 'checked' : ''; ?>>
                            <label for="open_only0"><?php echo $hesklang['oon2']; ?></label>
                        </div>
                    </div>

                    <?php
                    if ($hesk_settings['secimg_use'] && $hesk_settings['recaptcha_use'] == 1) {
                        define('RECAPTCHA',1);
                        ?>
                        <div class="g-recaptcha" data-sitekey="<?php echo $hesk_settings['recaptcha_public_key']; ?>" data-bind="forgot-tid-submit" data-callback="recaptcha_submitForm"></div>
                    <?php
                    } elseif ($hesk_settings['secimg_use']) {
                    ?>
                    <div class="captcha-remind">
                        <div class="form-group">
                            <?php
                            if ($hesk_settings['recaptcha_use'] == 2) {
                                define('RECAPTCHA',1);
                                ?>
                                <div class="g-recaptcha" data-sitekey="<?php echo $hesk_settings['recaptcha_public_key']; ?>"></div>
                            <?php } else { ?>
                                <img name="secimg" src="print_sec_img.php?<?php echo rand(10000,99999); ?>" width="150" height="40" alt="<?php echo $hesklang['sec_img']; ?>" title="<?php echo $hesklang['sec_img']; ?>" style="vertical-align:text-bottom">
                                <a class="btn btn-refresh" href="javascript:void(0)" onclick="javascript:document.form1.secimg.src='print_sec_img.php?'+ ( Math.floor((90000)*Math.random()) + 10000);">
                                    <svg class="icon icon-refresh"><use xlink:href="<?php echo TEMPLATE_PATH; ?>customer/img/sprite.svg#icon-refresh"></use></svg>
                                </a>
                                <label class="required" for="mysecnum"><?php echo $hesklang['sec_enter']; ?></label>
                                <input type="text" id="mysecnum" name="mysecnum" size="20" maxlength="5" autocomplete="off" class="form-control">
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                    <input type="hidden" name="a" value="forgot_tid">
                    <input type="hidden" id="js" name="forgot" value="<?php echo (hesk_GET('forgot') ? '1' : '0'); ?>">
                    <button id="forgot-tid-submit" type="submit" class="btn btn-full"><?php echo $hesklang['tid_send']; ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('#select_category').selectize();
        $('a[data-modal]').on('click', function() {
            $($(this).data('modal')).modal();
            return false;
        });
        <?php if ($submittedForgotTrackingIdForm) { ?>
        $('#forgot-modal').modal();
        $('#forgot-email').select();
        <?php } ?>
    });
</script>

<?php
require_once(TEMPLATE_PATH . 'customer/inc/footer.inc.php');
?>