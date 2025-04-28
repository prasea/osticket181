<?php

/*********************************************************************
    open.php

    New tickets handle.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
 **********************************************************************/
require('client.inc.php');
define('SOURCE', 'Web'); //Ticket source.
$ticket = null;
$errors = array();
if ($_POST) {
    $vars = $_POST;
    $vars['deptId'] = $vars['emailId'] = 0; //Just Making sure we don't accept crap...only topicId is expected.
    if ($thisclient) {
        $vars['uid'] = $thisclient->getId();
    } elseif ($cfg->isCaptchaEnabled()) {
        if (!$_POST['captcha'])
            $errors['captcha'] = __('Enter text shown on the image');
        elseif (strcmp($_SESSION['captcha'], md5(strtoupper($_POST['captcha']))))
            $errors['captcha'] = sprintf('%s - %s', __('Invalid'), __('Please try again!'));
    }

    $tform = TicketForm::objects()->one()->getForm($vars);
    $messageField = $tform->getField('message');
    $attachments = $messageField->getWidget()->getAttachments();
    if (!$errors) {
        $vars['message'] = $messageField->getClean();
        if ($messageField->isAttachmentsEnabled())
            $vars['files'] = $attachments->getFiles();
    }

    // Drop the draft.. If there are validation errors, the content
    // submitted will be displayed back to the user
    Draft::deleteForNamespace('ticket.client.' . substr(session_id(), -12));
    //Ticket::create...checks for errors..
    if (($ticket = Ticket::create($vars, $errors, SOURCE))) {
        $msg = __('Support ticket request created');
        // Drop session-backed form data
        unset($_SESSION[':form-data']);
        //Logged in...simply view the newly created ticket.
        if ($thisclient && $thisclient->isValid()) {
            // Regenerate session id
            $thisclient->regenerateSession();
            @header('Location: tickets.php?id=' . $ticket->getId());
        } else
            $ost->getCSRF()->rotate();
    } else {
        $errors['err'] = $errors['err'] ?: sprintf(
            '%s %s',
            __('Unable to create a ticket.'),
            __('Correct any errors below and try again.')
        );
    }
}

//page
$nav->setActiveNav('new');
if ($cfg->isClientLoginRequired()) {
    if ($cfg->getClientRegistrationMode() == 'disabled') {
        Http::redirect('view.php');
    } elseif (!$thisclient) {
        require_once 'secure.inc.php';
    } elseif ($thisclient->isGuest()) {
        require_once 'login.php';
        exit();
    }
}

require(CLIENTINC_DIR . 'header.inc.php');
if (
    $ticket
    && (
        (($topic = $ticket->getTopic()) && ($page = $topic->getPage()))
        || ($page = $cfg->getThankYouPage())
    )
) {
    // Thank the user and promise speedy resolution!
    echo Format::viewableImages(
        $ticket->replaceVars(
            $page->getLocalBody()
        ),
        ['type' => 'P']
    );
} else {
    require(CLIENTINC_DIR . 'open.inc.php');
}
require(CLIENTINC_DIR . 'footer.inc.php');
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Listen for changes on the Help Topic Select (topicId)
        document.getElementById('topicId').addEventListener("change", function() {
            const selectedHelpTopicValue = this.value;
            const data = $(':input[name]', '#dynamic-form').serialize();

            $.ajax('ajax.php/form/help-topic/' + selectedHelpTopicValue, {
                data: data,
                dataType: 'json',
                success: function(json) {
                    // Update the dynamic form content after AJAX response
                    $('#dynamic-form').empty().append(json.html);
                    $(document.head).append(json.media);

                    setTimeout(() => {


                        /*                    
                        <tr style="">
                            <td colspan="2" style="padding-top:10px;">
                                <label for="e73239e8148745"><span class="">
                                        Gold Loan </span> <br>
                                    <label class="checkbox">
                                        <input id="_e73239e8148745" type="checkbox" name="_field-checkboxes[]" value="48">
                                    </label>
                                </label>
                            </td>
                        </tr>
                        
                        <tr style="">
                            <td colspan="2" style="padding-top: 10px;">
                                <label class="checkbox-label" for="_e73239e8148745">
                                    <input type="checkbox" id="_e73239e8148745" name="_field-checkboxes[]" value="48"> Gold Loan
                                </label>
                            </td>
                        </tr>
                        */

                        // Fix invalid nested labels styling and align checkbox with text
                        document.querySelectorAll('#dynamic-form tr').forEach(row => {
                            const outerLabel = row.querySelector('label[for]');
                            const innerLabel = row.querySelector('label.checkbox');
                            const checkbox = innerLabel?.querySelector('input');

                            // Adding custom class to date input picker
                            const input = row.querySelector('input[type="text"].dp');
                            let count = 0;
                            if (input) {
                                input.classList.add(`custom-date-${count++}`);
                            }

                            if (outerLabel && checkbox) {
                                const text = outerLabel.querySelector('span')?.innerText || 'Checkbox';
                                const newLabel = document.createElement('label');
                                newLabel.className = 'checkbox-label';
                                newLabel.setAttribute('for', checkbox.id);
                                newLabel.innerHTML = `<input type="checkbox" id="${checkbox.id}" name="${checkbox.name}" value="${checkbox.value}"> ${text}`;

                                // Replace the old HTML
                                row.innerHTML = '';
                                const td = document.createElement('td');
                                td.colSpan = 2;
                                td.style.paddingTop = '10px';
                                td.appendChild(newLabel);
                                row.appendChild(td);
                            }
                        });

                        // Set today's date as default in yyyy-mm-dd format
                        const today = new Date();
                        const yyyy = today.getFullYear();
                        const mm = String(today.getMonth() + 1)
                        const dd = String(today.getDate());
                        const formattedDate = `${yyyy}-${mm}-${dd}`;

                        const dateField = document.querySelector('input.custom-date-0');
                        if (dateField) {
                            dateField.value = formattedDate;
                        }

                        // After dynamic form content is loaded, handle Privilege Type selection
                        if (selectedHelpTopicValue == 12) {
                            const selectedPrivilegeType = document.querySelector('select[data-placeholder="Choose Request Privilege Type"]');
                            const allDynamicFormRows = document.querySelectorAll('#dynamic-form tr');
                            const branchManager = allDynamicFormRows[7],
                                creditChecker = allDynamicFormRows[8],
                                operationChecker = allDynamicFormRows[9],
                                creditMaker = allDynamicFormRows[10],
                                operationMakerL1 = allDynamicFormRows[11],
                                operationMakerL2 = allDynamicFormRows[12],
                                operationMakerL3 = allDynamicFormRows[13],
                                goldLoanMaker = allDynamicFormRows[14];
                            // Initially hide all the privileges
                            branchManager.style.display = 'none';
                            creditChecker.style.display = 'none';
                            operationChecker.style.display = 'none';
                            creditMaker.style.display = 'none';
                            operationMakerL1.style.display = 'none';
                            operationMakerL2.style.display = 'none';
                            operationMakerL3.style.display = 'none';
                            goldLoanMaker.style.display = 'none';

                            // Listen for changes on the Privilege Type Select
                            selectedPrivilegeType.addEventListener("change", function() {
                                const selectedPrivilegeTypeValue = this.value.split(':')[0]; // Extract the part before the colon (checker or maker)
                                console.log('Selected Privilege Type:', selectedPrivilegeTypeValue);

                                // Handle "Checker" selection
                                if (selectedPrivilegeTypeValue === 'checker') {
                                    branchManager.style.display = '';
                                    creditChecker.style.display = '';
                                    operationChecker.style.display = '';

                                    // Hide Maker-related rows
                                    creditMaker.style.display = 'none';
                                    operationMakerL1.style.display = 'none';
                                    operationMakerL2.style.display = 'none';
                                    operationMakerL3.style.display = 'none';
                                    goldLoanMaker.style.display = 'none'
                                }

                                // Handle "Maker" selection
                                if (selectedPrivilegeTypeValue === 'maker') {
                                    creditMaker.style.display = '';
                                    operationMakerL1.style.display = '';
                                    operationMakerL2.style.display = '';
                                    operationMakerL3.style.display = '';
                                    goldLoanMaker.style.display = '';

                                    // Hide Checker-related rows
                                    branchManager.style.display = 'none';
                                    creditChecker.style.display = 'none';
                                    operationChecker.style.display = 'none';
                                }
                            });
                        }

                        // Uncheck if multiple checkboxes are checked
                        document.querySelectorAll('#dynamic-form input[type="checkbox"]').forEach((checkbox) => {
                            checkbox.addEventListener('change', function(e) {
                                if (this.checked) {
                                    document.querySelectorAll('#dynamic-form input[type="checkbox"]').forEach((box) => {
                                        if (box !== this) box.checked = false;
                                    });
                                }
                            });
                        });

                        // Adding Select 2 on Branch Name choices
                        $('#dynamic-form select[data-placeholder="Select"]').select2({
                            placeholder: 'Select a branch',
                            allowClear: true
                        });


                        // To dynamically wrap the datepicker input and its associated button inside a .datepicker-wrapper div
                        $(function() {
                            $('#dynamic-form input.hasDatepicker').each(function() {
                                const $input = $(this);
                                const $button = $input.next('.ui-datepicker-trigger');
                                const $timezone = $button.next('.faded');

                                // Wrap input + button (and optionally timezone info)
                                const $wrapper = $('<div class="datepicker-wrapper"></div>');
                                $input.add($button).add($timezone).wrapAll($wrapper);
                            });
                        });
                    }, 0)

                }
            });

        });
    });
</script>
