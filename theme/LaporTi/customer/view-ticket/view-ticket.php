<?php
global $hesk_settings, $hesklang;
/**
 * @var array $customerUserContext - User info for the customer.
 * @var array $messages
 * @var array $serviceMessages
 * @var array $ticket
 * @var boolean $ticketJustReopened
 * @var string $trackingID
 * @var int $numberOfReplies
 * @var array $category
 * @var array $replies
 * @var string $email
 * @var array $customFieldsBeforeMessage
 * @var array $customFieldsAfterMessage
 */

if (!defined('IN_SCRIPT')) {
    die();
}
define('EXTRA_PAGE_CLASSES','page-view-ticket');
define('ALERTS',1);
define('CUSTOM_FIELDS',1);
define('ADD_REPLY',1);
define('LOAD_CSS_DROPZONE',1);
define('LOAD_CSS_ZEBRA',1);
define('LOAD_PRISM_OPTIONAL',1);
define('RENDER_COMMON_ELEMENTS',1);
define('RENDER_HIDDEN_TICKET_FIELDS',1);
define('LOAD_JS_DROPZONE',1);
define('LOAD_JS_ZEBRA',1);
define('FOOTER_DONT_CLOSE_HTML',1);

global $BREADCRUMBS;
$BREADCRUMBS = array(
    array('url' => $hesk_settings['site_url'], 'title' => $hesk_settings['site_title']),
    array('url' => $hesk_settings['hesk_url'], 'title' => $hesk_settings['hesk_title']),
    array('url' => ($customerUserContext !== null ? 'my_tickets.php' : 'ticket.php'), 'title' => $hesklang['customer_my_tickets_heading']),
    array('title' => ($hesk_settings['new_top'] && $ticket['replies'] ? $ticket['subject'] : $hesklang['your_ticket']))
);

$hesk_settings['hidden_data'] = array(
    'ticket' => $ticket,
    'email' => $email
);
require_once(TEMPLATE_PATH . 'customer/inc/header.inc.php');
?>
        <div class="main__content">
            <div class="contr">
                <div style="margin-bottom: 20px;">
                    <?php hesk3_show_messages($serviceMessages); ?>
                    <?php hesk3_show_messages($messages); ?>
                </div>
                
                <div class="max-w-5xl mx-auto space-y-8 font-sans antialiased pb-12">

                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 p-6 sm:p-8">
                        <div class="flex flex-wrap justify-between items-center mb-6 border-b border-slate-100 pb-5">
                            <h2 class="text-xl font-extrabold flex items-center gap-2">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-blue-500">Detail Laporan</span>
                            </h2>
                            <div class="px-5 py-2 rounded-full text-sm font-bold bg-slate-50 border border-slate-200 shadow-sm">
                                <span class="text-slate-500 font-medium">Status:</span> 
                                <span class="text-blue-600 ml-1 tracking-wide"><?php echo hesk_get_ticket_status($ticket['status']); ?></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <p class="text-xs font-semibold text-slate-400 mb-1 uppercase tracking-wider">Nomor Laporan</p>
                                <p class="text-lg font-bold text-slate-800"><?php echo $trackingID; ?></p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <p class="text-xs font-semibold text-slate-400 mb-1 uppercase tracking-wider">Kategori Laporan</p>
                                <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-bold">
                                    <?php echo $category['name']; ?>
                                </span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <p class="text-xs font-semibold text-slate-400 mb-1 uppercase tracking-wider">Tanggal Masuk</p>
                                <p class="text-sm text-slate-700 font-semibold"><?php echo hesk_date($ticket['dt'], true); ?></p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <p class="text-xs font-semibold text-slate-400 mb-1 uppercase tracking-wider">Prioritas</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <?php $data_style = 'background-color:'.$hesk_settings['priorities'][$ticket['priority']]['color'].';' ?>
                                    <div class="w-3.5 h-3.5 rounded-full shadow-sm" style="<?php echo $data_style; ?>"></div>
                                    <span class="text-sm font-bold text-slate-700"><?php echo $hesk_settings['priorities'][$ticket['priority']]['name']; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($ticket['status'] == 3 || $hesk_settings['custclose']): ?>
                        <div class="mt-6 pt-5 border-t border-slate-100 flex justify-end">
                            <?php
                            if ($ticket['status'] == 3 && $ticket['locked'] != 1 && $hesk_settings['custopen']) {
                                echo '<a class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-50 text-blue-700 font-bold rounded-xl hover:bg-teal-100 hover:shadow-sm transition-all" href="change_status.php?track='.$trackingID.$hesk_settings['e_query'].'&amp;s=2&amp;Refresh='.rand(10000,99999).'&amp;token='.hesk_token_echo(0).'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Buka Kembali Tiket Ini</a>';
                            } elseif ($ticket['status'] != 3 && $hesk_settings['custclose']) {
                                echo '<a class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-50 text-red-600 font-bold rounded-xl hover:bg-red-100 hover:text-red-700 hover:shadow-sm transition-all ring-1 ring-inset ring-red-100" href="change_status.php?track='.$trackingID.$hesk_settings['e_query'].'&amp;s=3&amp;Refresh='.rand(10000,99999).'&amp;token='.hesk_token_echo(0).'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Tandai Laporan Selesai</a>';
                            }
                            ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php
                    $status_id = $ticket['status'];
                    $step = 1; 

                    if ($status_id == 3) {
                        $step = 3; 
                    } elseif ($status_id != 0) {
                        $step = 2; 
                    }

                    $time_created = hesk_date($ticket['dt'], true);
                    $time_updated = ($step >= 2) ? hesk_date($ticket['lastchange'], true) : '-';
                    $time_resolved = ($step == 3) ? hesk_date($ticket['lastchange'], true) : '-';
                    $real_status_name = strip_tags(hesk_get_ticket_status($ticket['status']));
                    ?>
                    
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 p-6 sm:p-8 overflow-hidden">
                        <h3 class="text-xl font-extrabold text-slate-800 flex items-center gap-2 mb-10">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Progress Penanganan
                        </h3>

                        <div class="relative max-w-3xl mx-auto hidden sm:block">
                            <div class="absolute top-6 left-[16%] right-[16%] h-1.5 bg-slate-100 rounded-full z-0"></div>
                            
                            <div class="absolute top-6 left-[16%] h-1.5 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full z-0 transition-all duration-1000 ease-out" 
                                 style="width: <?php echo ($step == 1) ? '0%' : (($step == 2) ? '34%' : '68%'); ?>;"></div>

                            <div class="flex justify-between relative z-10">
                                <div class="flex flex-col items-center w-1/3 text-center group">
                                    <?php if ($step > 1): ?>
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 border-4 border-white flex items-center justify-center text-white mb-4 shadow-md">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    <?php else: ?>
                                        <div class="w-12 h-12 rounded-full bg-white border-4 border-yellow-400 flex items-center justify-center mb-4 shadow-[0_0_15px_rgba(250,204,21,0.4)]">
                                            <div class="w-4 h-4 rounded-full bg-yellow-400 animate-pulse"></div>
                                        </div>
                                    <?php endif; ?>
                                    <h4 class="font-extrabold text-slate-800 text-sm tracking-wide">Laporan Diterima</h4>
                                    <p class="text-xs text-slate-500 mt-1.5 mb-1 px-4">Laporan Anda telah kami terima.</p>
                                    <span class="text-xs font-semibold text-slate-400 bg-slate-50 px-2 py-1 rounded-md mt-1"><?php echo $time_created; ?></span>
                                </div>

                                <div class="flex flex-col items-center w-1/3 text-center group">
                                    <?php if ($step > 2): ?>
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 border-4 border-white flex items-center justify-center text-white mb-4 shadow-md">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    <?php elseif ($step == 2): ?>
                                        <div class="w-12 h-12 rounded-full bg-white border-4 border-yellow-400 flex items-center justify-center mb-4 shadow-[0_0_15px_rgba(250,204,21,0.4)]">
                                            <div class="w-4 h-4 rounded-full bg-yellow-400 animate-pulse"></div>
                                        </div>
                                    <?php else: ?>
                                        <div class="w-12 h-12 rounded-full bg-slate-100 border-4 border-white flex items-center justify-center text-slate-400 font-bold text-lg mb-4 shadow-sm group-hover:bg-slate-200 transition-colors">2</div>
                                    <?php endif; ?>
                                    <h4 class="font-extrabold <?php echo ($step >= 2) ? 'text-slate-800' : 'text-slate-400'; ?> text-sm tracking-wide">Sedang Diproses</h4>
                                    
                                    <p class="text-xs <?php echo ($step >= 2) ? 'text-teal-700 font-bold' : 'text-slate-400'; ?> mt-1.5 mb-1 px-4 <?php echo ($step == 2) ? 'bg-teal-50 py-1.5 rounded-lg border border-teal-100 shadow-sm' : ''; ?>">
                                        <?php echo ($step == 2) ? $real_status_name : 'Menunggu respon dari tim.'; ?>
                                    </p>
                                    
                                    <span class="text-xs font-semibold text-slate-400 <?php echo ($step >= 2) ? 'bg-slate-50 px-2 py-1 rounded-md mt-1' : ''; ?>"><?php echo ($step >= 2) ? $time_updated : '&nbsp;'; ?></span>
                                </div>

                                <div class="flex flex-col items-center w-1/3 text-center group">
                                    <?php if ($step == 3): ?>
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-teal-400 to-teal-600 border-4 border-white flex items-center justify-center text-white mb-4 shadow-md">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    <?php else: ?>
                                        <div class="w-12 h-12 rounded-full bg-slate-100 border-4 border-white flex items-center justify-center text-slate-400 font-bold text-lg mb-4 shadow-sm group-hover:bg-slate-200 transition-colors">3</div>
                                    <?php endif; ?>
                                    <h4 class="font-extrabold <?php echo ($step == 3) ? 'text-slate-800' : 'text-slate-400'; ?> text-sm tracking-wide">Selesai</h4>
                                    <p class="text-xs <?php echo ($step == 3) ? 'text-slate-500' : 'text-slate-400'; ?> mt-1.5 mb-1 px-4">Laporan telah ditutup.</p>
                                    <span class="text-xs font-semibold text-slate-400 <?php echo ($step == 3) ? 'bg-slate-50 px-2 py-1 rounded-md mt-1' : ''; ?>"><?php echo ($step == 3) ? $time_resolved : '&nbsp;'; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="sm:hidden space-y-5">
                            <div class="flex items-center gap-4 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="w-10 h-10 rounded-full <?php echo ($step >= 1) ? 'bg-gradient-to-br from-teal-400 to-teal-600 shadow-md' : 'bg-slate-200'; ?> text-white flex items-center justify-center shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                                <div><h4 class="font-extrabold text-sm text-slate-800">Laporan Diterima</h4><p class="text-xs text-slate-500 font-medium"><?php echo $time_created; ?></p></div>
                            </div>
                            <div class="w-1 h-6 bg-slate-200 ml-7 rounded-full"></div>
                            <div class="flex items-center gap-4 <?php echo ($step == 2) ? 'bg-teal-50 p-3 rounded-xl border border-teal-100 shadow-sm' : 'px-3'; ?>">
                                <div class="w-10 h-10 rounded-full <?php echo ($step > 2) ? 'bg-gradient-to-br from-teal-400 to-teal-600 shadow-md' : (($step == 2) ? 'bg-yellow-400 shadow-[0_0_10px_rgba(250,204,21,0.5)]' : 'bg-slate-200'); ?> text-white flex items-center justify-center shrink-0"><?php if($step > 2) echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>'; else echo '<span class="text-sm font-bold">2</span>'; ?></div>
                                <div><h4 class="font-extrabold text-sm <?php echo ($step >= 2) ? 'text-slate-800' : 'text-slate-400'; ?>">Sedang Diproses</h4><p class="text-xs <?php echo ($step == 2) ? 'text-teal-700 font-bold' : 'text-slate-500 font-medium'; ?>"><?php echo ($step >= 2) ? $real_status_name : 'Menunggu...'; ?></p></div>
                            </div>
                            <div class="w-1 h-6 bg-slate-200 ml-7 rounded-full"></div>
                            <div class="flex items-center gap-4 <?php echo ($step == 3) ? 'bg-teal-50 p-3 rounded-xl border border-teal-100 shadow-sm' : 'px-3'; ?>">
                                <div class="w-10 h-10 rounded-full <?php echo ($step == 3) ? 'bg-gradient-to-br from-teal-400 to-teal-600 shadow-md' : 'bg-slate-200'; ?> text-white flex items-center justify-center shrink-0"><?php if($step == 3) echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>'; else echo '<span class="text-sm font-bold">3</span>'; ?></div>
                                <div><h4 class="font-extrabold text-sm <?php echo ($step == 3) ? 'text-slate-800' : 'text-slate-400'; ?>">Selesai</h4><p class="text-xs text-slate-500 font-medium"><?php echo ($step == 3) ? $time_resolved : '-'; ?></p></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 p-6 sm:p-8">
                        <div class="flex justify-between items-start mb-6">
                            <h3 class="text-2xl font-extrabold text-slate-800"><?php echo $ticket['subject']; ?></h3>
                            <a title="<?php echo $hesklang['btn_print']; ?>" href="print.php?track=<?php echo $ticket['trackid'].$hesk_settings['e_query']; ?>" target="_blank" class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            </a>
                        </div>

                        <div class="block--head mb-6 pb-6 border-b border-slate-100">
                            <div class="d-flex">
                                <div class="contact grid">
                                    <div class="requester-header">
                                        <span class="font-semibold text-slate-500"><?php echo $hesklang['m_from']; ?>:</span>
                                    </div>
                                    <div class="requester">
                                        <?php
                                        $requesters = array_filter($ticket['customers'], function($customer) { return $customer['customer_type'] === 'REQUESTER'; });
                                        $requesters = array_values($requesters);
                                        if (count($requesters) && $requesters[0]['email'] === ''): ?>
                                            <div class="dropdown customer left out-close">
                                                <label class="bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm cursor-pointer hover:bg-slate-100 transition-colors">
                                                    <svg class="icon icon-person"><use xlink:href="<?php echo TEMPLATE_PATH; ?>customer/img/sprite.svg#icon-person"></use></svg>
                                                    <span class="font-bold text-slate-700"><?php echo $requesters[0]['name']; ?></span>
                                                </label>
                                            </div>
                                        <?php else:
                                            if (count($requesters)):
                                                $requester = $requesters[0];
                                                ?>
                                                <div class="dropdown customer left out-close">
                                                    <label class="bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm cursor-pointer hover:bg-slate-100 transition-colors">
                                                        <svg class="icon icon-person"><use xlink:href="<?php echo TEMPLATE_PATH; ?>customer/img/sprite.svg#icon-person"></use></svg>
                                                        <span class="font-bold text-slate-700"><?php echo (strlen($requester['name']) ? $requester['name'] : ( ! empty($requester['email']) ? $requester['email'] : $hesklang['pde'] )) ; ?></span>
                                                        <svg class="icon icon-chevron-down"><use xlink:href="<?php echo TEMPLATE_PATH; ?>customer/img/sprite.svg#icon-chevron-down"></use></svg>
                                                    </label>
                                                    <ul class="dropdown-list shadow-lg rounded-xl border border-slate-100 overflow-hidden">
                                                        <li class="noclose hover:bg-slate-50 transition-colors p-3">
                                                            <span class="title text-xs font-semibold text-slate-400 uppercase"><?php echo $hesklang['email']; ?>:</span><br>
                                                            <span class="value font-medium text-teal-600"><a href="mailto:<?php echo $requester['email']; ?>"><?php echo $requester['email']; ?></a></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <span class="text-slate-300 mx-2">&bull;</span> <time class="timeago tooltip text-sm font-medium text-slate-500" datetime="<?php echo date("c", strtotime($ticket['dt'])) ; ?>" title="<?php echo hesk_date($ticket['dt'], true); ?>"><?php echo hesk_date($ticket['dt'], true); ?></time>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php hesk3_output_custom_fields_for_display($customFieldsBeforeMessage); ?>
                        
                        <?php if ($ticket['message_html'] != ''): ?>
                        <div class="relative bg-slate-50 rounded-xl p-6 border-l-4 border-teal-500 shadow-inner">
                            <svg class="absolute top-4 right-4 w-8 h-8 text-slate-200 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                            <div class="prose prose-slate max-w-none text-slate-700 font-medium relative z-10">
                                <?php echo $ticket['message_html']; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php hesk3_output_custom_fields_for_display($customFieldsAfterMessage); ?>
                        
                        <div class="mt-6">
                            <?php listAttachments($ticket['attachments'], $trackingID); ?>
                        </div>
                    </div>

                    <?php
                    if ($hesk_settings['new_top']) {
                        if (($ticket['locked'] != 1 && $ticket['status'] != 3 && $hesk_settings['reply_top'] == 1) || $ticketJustReopened) {
                            echo '<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 mb-8">';
                            showReplyForm($trackingID, $email, $ticketJustReopened);
                            echo '</div>';
                        }
                        displayReplies($replies, $trackingID);
                    } else {
                        displayReplies($replies, $trackingID);
                        
                        if ($ticket['locked'] != 1 && $ticket['status'] != 3 && !$hesk_settings['reply_top'] && !$ticketJustReopened) {
                            echo '<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 mt-8">';
                            showReplyForm($trackingID, $email, false);
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
                </div>
        </div>
<?php
require_once(TEMPLATE_PATH . 'customer/inc/footer.inc.php');

if ($hesk_settings['time_display']): ?>
    <script src="./js/timeago/jquery.timeago.js?<?php echo $hesk_settings['hesk_version']; ?>"></script>
    <?php if ($hesklang['TIMEAGO_LANG_FILE'] != 'jquery.timeago.en.js'): ?>
        <script type="text/javascript" src="./js/timeago/locales/<?php echo $hesklang['TIMEAGO_LANG_FILE']; ?>?<?php echo $hesk_settings['hesk_version']; ?>"></script>
    <?php endif; ?>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            $("time.timeago").timeago();
        });
    </script>
<?php endif;

function displayReplies($replies, $trackingId) {
    global $hesklang, $hesk_settings;

    foreach ($replies as $reply) {
        if ($reply['staffid'] && !$reply['read']) {
            $unread_replies[] = $reply['id'];
        }
        ?>
        <article class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 p-6 sm:p-8 mb-6 relative overflow-hidden <?php if ($reply['staffid']) { echo 'border-l-4 border-l-teal-500'; } ?>">
            <?php if ($reply['staffid']): ?>
                <div class="absolute top-0 right-0 bg-teal-50 text-teal-600 text-xs font-bold px-3 py-1 rounded-bl-lg">Tanggapan Tim</div>
            <?php endif; ?>
            
            <div class="flex justify-between items-center mb-4 pb-4 border-b border-slate-100 mt-2">
                <div class="text-sm text-slate-500 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold uppercase">
                        <?php echo substr($reply['name'], 0, 1); ?>
                    </div>
                    <div>
                        <b class="text-slate-800 text-base"><?php echo $reply['name']; ?></b><br>
                        <time class="timeago text-xs font-medium text-slate-400" datetime="<?php echo date("c", strtotime($reply['dt'])) ; ?>" title="<?php echo hesk_date($reply['dt'], true); ?>"><?php echo hesk_date($reply['dt'], true); ?></time>
                    </div>
                </div>
            </div>
            <div class="prose prose-slate max-w-none text-slate-700 font-medium leading-relaxed">
                <?php echo $reply['message_html']; ?>
            </div>
            
            <div class="mt-4">
                <?php listAttachments($reply['attachments'], $trackingId); ?>
            </div>

            <?php if ($hesk_settings['rating'] && $reply['staffid']): ?>
            <div class="mt-6 pt-4 border-t border-slate-100 text-sm text-slate-500 flex items-center justify-between bg-slate-50 p-3 rounded-xl">
                <span class="font-semibold"><?php echo $hesklang['r']; ?></span>
                <?php
                if ($reply['rating'] == 1) {
                    echo '<span class="text-slate-400 font-bold">'.$hesklang['rnh'].'</span>';
                } elseif ($reply['rating'] == 5) {
                    echo '<span class="text-teal-600 font-bold">'.$hesklang['rh'].'</span>';
                } else { ?>
                    <div id="rating<?php echo $reply['id']; ?>" class="flex items-center gap-3">
                        <a href="javascript:" onclick="HESK_FUNCTIONS.rate('rate.php?rating=5&amp;id=<?php echo $reply['id']; ?>&amp;track=<?php echo $trackingId; ?>','rating<?php echo $reply['id']; ?>')" class="flex items-center gap-1 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-teal-600 hover:bg-teal-50 hover:border-teal-200 font-bold transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                            Ya
                        </a>
                        <a href="javascript:" onclick="HESK_FUNCTIONS.rate('rate.php?rating=1&amp;id=<?php echo $reply['id']; ?>&amp;track=<?php echo $trackingId; ?>','rating<?php echo $reply['id']; ?>')" class="flex items-center gap-1 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-red-600 hover:bg-red-50 hover:border-red-200 font-bold transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.714.211-1.412.608-2.006L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"></path></svg>
                            Tidak
                        </a>
                    </div>
                <?php }
                ?>
            </div>
            <?php endif; ?>
        </article>
        <?php
    }
}

function listAttachments($attachments, $trackingId) {
    global $hesk_settings, $hesklang;

    if (!$hesk_settings['attachments']['use'] || ! strlen($attachments) ) {
        return false;
    }
    ?>
    <div class="block--uploads mt-3 flex flex-wrap gap-3">
    <?php
    $att=explode(',',substr($attachments, 0, -1));
    foreach ($att as $myatt) {
        list($att_id, $att_name) = explode('#', $myatt);
        ?>
        <a title="<?php echo $hesklang['dnl']; ?>" href="download_attachment.php?att_id=<?php echo $att_id; ?>&amp;track=<?php echo $trackingId.$hesk_settings['e_query']; ?>" class="group inline-flex items-center gap-3 bg-white border border-slate-200 px-4 py-2.5 rounded-xl hover:border-teal-300 hover:shadow-md transition-all">
            <div class="w-8 h-8 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center group-hover:bg-teal-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            </div>
            <span class="text-sm font-bold text-slate-700 group-hover:text-teal-700 truncate max-w-[200px]"><?php echo $att_name; ?></span>
        </a>
        <?php
    }
    ?>
    </div>
    <?php
    return true;
}