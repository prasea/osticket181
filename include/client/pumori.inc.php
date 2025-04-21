<?php
if (!defined('OSTCLIENTINC')) die('Access Denied!');
$info = array();
if ($thisclient && $thisclient->isValid()) {
  $info = array(
    'name' => $thisclient->getName(),
    'email' => $thisclient->getEmail(),
    'phone' => $thisclient->getPhoneNumber()
  );
}

$info = ($_POST && $errors) ? Format::htmlchars($_POST) : $info;

$form = null;
if (!$info['topicId']) {
  if (array_key_exists('topicId', $_GET) && preg_match('/^\d+$/', $_GET['topicId']) && Topic::lookup($_GET['topicId']))
    $info['topicId'] = intval($_GET['topicId']);
  else
    $info['topicId'] = $cfg->getDefaultTopicId();
}

$forms = array();
if ($info['topicId'] && ($topic = Topic::lookup($info['topicId']))) {
  foreach ($topic->getForms() as $F) {
    if (!$F->hasAnyVisibleFields())
      continue;
    if ($_POST) {
      $F = $F->instanciate();
      $F->isValidForClient();
    }
    $forms[] = $F->getForm();
  }
}

?>


<?php if ($success): ?>
  <h1 class="success">Form submitted successfully!</h1>
<?php else: ?>
  <h1><?php echo __('Fill Pumori Right Form'); ?></h1>
  <p><?php echo __('Please fill in the form below to open get pumori right.'); ?></p>
  <?php if (!empty($errors)): ?>
    <div class="error">
      <ul>
        <?php foreach ($errors as $field => $error): ?>
          <li><strong><?= ucfirst($field) ?>:</strong> <?= $error ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>


  <form id="pumoriRightForm" method="post" action="test.php" enctype="multipart/form-data">
    <?php csrf_token(); ?>

    <div class="form-user-information">
      <div class="form-group">
        <label for="date">Date</label>
        <input type="date" name="date" id="date">
      </div>
      <div class="form-group">
        <label for="requestType">Request Type</label>
        <select name="requestType" id="requestType">
          <option value="">Choose Request Type</option>
          <option value="amend" selected>Amend</option>
          <option value="new">New</option>
        </select>
      </div>

      <div class="form-group">
        <label for="staffName">Staff Name</label>
        <input type="text" name="staffName" id="staffName">
      </div>
      <div class="form-group">
        <label for="branchSelect">Branch Name</label>
        <select id="branchSelect" name="branchName" style="width: 100%">
          <option></option>
          <option value="CENTRAL OFFICE">CENTRAL OFFICE</option>
          <option value="4 No. JEETPUR BRANCH">4 No. JEETPUR BRANCH</option>
          <option value="ANBUKHAIRENI BRANCH">ANBUKHAIRENI BRANCH</option>
          <option value="ARUNKHOLA BRANCH">ARUNKHOLA BRANCH</option>
          <option value="ASON BRANCH">ASON BRANCH</option>
          <option value="ATTARIYA BRANCH">ATTARIYA BRANCH</option>
          <option value="BAGAR BRANCH">BAGAR BRANCH</option>
          <option value="BAGBAZAR BRANCH">BAGBAZAR BRANCH</option>
          <option value="BAGLUNG BRANCH">BAGLUNG BRANCH</option>
          <option value="BAITESHWOR BRANCH">BAITESHWOR BRANCH</option>
          <option value="Baiteshwor Extension Counter">Baiteshwor Extension Counter</option>
          <option value="BAJHAPATAN BRANCH">BAJHAPATAN BRANCH</option>
          <option value="BALAJU BRANCH">BALAJU BRANCH</option>
          <option value="Balaju Extension Counter">Balaju Extension Counter</option>
          <option value="BANASTHALI BRANCH">BANASTHALI BRANCH</option>
          <option value="BANEPA BRANCH">BANEPA BRANCH</option>
          <option value="BANGEMUDA BRANCH">BANGEMUDA BRANCH</option>
          <option value="BANIYATAR BRANCH">BANIYATAR BRANCH</option>
          <option value="BARDAGHAT BRANCH">BARDAGHAT BRANCH</option>
          <option value="BARDIBAS BRANCH">BARDIBAS BRANCH</option>
          <option value="BATTAR BRANCH">BATTAR BRANCH</option>
          <option value="BELAHA BRANCH">BELAHA BRANCH</option>
          <option value="BENI BRANCH">BENI BRANCH</option>
          <option value="BESISAHAR BRANCH">BESISAHAR BRANCH</option>
          <option value="Bhadrabas Extension Counter">Bhadrabas Extension Counter</option>
          <option value="BHAIRAHAWA BRANCH">BHAIRAHAWA BRANCH</option>
          <option value="BHALWARI BRANCH">BHALWARI BRANCH</option>
          <option value="BHANDARA BRANCH">BHANDARA BRANCH</option>
          <option value="BHARATPUR BRANCH">BHARATPUR BRANCH</option>
          <option value="BHIMPHEDI BRANCH">BHIMPHEDI BRANCH</option>
          <option value="BHORLETAR BRANCH">BHORLETAR BRANCH</option>
          <option value="BIBLYATE BRANCH">BIBLYATE BRANCH</option>
          <option value="BIRATCHOWK BRANCH">BIRATCHOWK BRANCH</option>
          <option value="BIRAUTA BRANCH">BIRAUTA BRANCH</option>
          <option value="BIRENDRANAGAR BRANCH">BIRENDRANAGAR BRANCH</option>
          <option value="BIRGUNJ BRANCH">BIRGUNJ BRANCH</option>
          <option value="BIRTAMOD BRANCH">BIRTAMOD BRANCH</option>
          <option value="BP CHOWK BRANCH">BP CHOWK BRANCH</option>
          <option value="BUDHABARE BRANCH">BUDHABARE BRANCH</option>
          <option value="BUDIBAZAR BRANCH">BUDIBAZAR BRANCH</option>
          <option value="CHABAHIL BRANCH">CHABAHIL BRANCH</option>
          <option value="CHAKRAGHATTI BRANCH">CHAKRAGHATTI BRANCH</option>
          <option value="CHANDRAGADI BRANCH">CHANDRAGADI BRANCH</option>
          <option value="CHAPALI BRANCH">CHAPALI BRANCH</option>
          <option value="CHAURAHA BUTWAL BRANCH">CHAURAHA BUTWAL BRANCH</option>
          <option value="CHAURAHA DHANGADI BRANCH">CHAURAHA DHANGADI BRANCH</option>
          <option value="CHHINCHU BRANCH">CHHINCHU BRANCH</option>
          <option value="CHYAMHASINGH BRANCH">CHYAMHASINGH BRANCH</option>
          <option value="DAMAK BRANCH">DAMAK BRANCH</option>
          <option value="DAMAULI BRANCH">DAMAULI BRANCH</option>
          <option value="DAMKADA BRANCH">DAMKADA BRANCH</option>
          <option value="DEWANGANJ BRANCH">DEWANGANJ BRANCH</option>
          <option value="DHADINGBESI BRANCH">DHADINGBESI BRANCH</option>
          <option value="DHANGADI BRANCH">DHANGADI BRANCH</option>
          <option value="DHAPAKHEL BRANCH">DHAPAKHEL BRANCH</option>
          <option value="DHARAN BRANCH">DHARAN BRANCH</option>
          <option value="DHUMBARAHI BRANCH">DHUMBARAHI BRANCH</option>
          <option value="DULEGAUNDA BRANCH">DULEGAUNDA BRANCH</option>
          <option value="DUMRE BRANCH">DUMRE BRANCH</option>
          <option value="FIKKAL BRANCH">FIKKAL BRANCH</option>
          <option value="GANJBHAWANIPUR BRANCH">GANJBHAWANIPUR BRANCH</option>
          <option value="GATTHAGHAR BRANCH">GATTHAGHAR BRANCH</option>
          <option value="GAURADAHA BRANCH">GAURADAHA BRANCH</option>
          <option value="GAURIGUNJ BRANCH">GAURIGUNJ BRANCH</option>
          <option value="GAUSHALA KATHMANDU BRANCH">GAUSHALA KATHMANDU BRANCH</option>
          <option value="GAUSHALA MOHOTTARI BRANCH">GAUSHALA MOHOTTARI BRANCH</option>
          <option value="Gokarneshwor Extension Counter">Gokarneshwor Extension Counter</option>
          <option value="GOLDHUNGA BRANCH">GOLDHUNGA BRANCH</option>
          <option value="GORKHA BRANCH">GORKHA BRANCH</option>
          <option value="GRANDE BRANCH">GRANDE BRANCH</option>
          <option value="GWALDUBBA BRANCH">GWALDUBBA BRANCH</option>
          <option value="GWARKO LALITPUR BRANCH">GWARKO LALITPUR BRANCH</option>
          <option value="GYANECHOWK BRANCH">GYANECHOWK BRANCH</option>
          <option value="HAKIMCHOWK Branch">HAKIMCHOWK Branch</option>
          <option value="HANUMANDAS ROAD BRANCH">HANUMANDAS ROAD BRANCH</option>
          <option value="HEMJA BRANCH">HEMJA BRANCH</option>
          <option value="HETAUDA BRANCH">HETAUDA BRANCH</option>
          <option value="HORIZONCHOWK BRANCH">HORIZONCHOWK BRANCH</option>
          <option value="HOSPITALCHOWK BRANCH">HOSPITALCHOWK BRANCH</option>
          <option value="IMADOL BRANCH">IMADOL BRANCH</option>
          <option value="INARUWA BRANCH">INARUWA BRANCH</option>
          <option value="ITAHARI BRANCH">ITAHARI BRANCH</option>
          <option value="ITTABHATTA BRANCH">ITTABHATTA BRANCH</option>
          <option value="JANAKPUR BRANCH">JANAKPUR BRANCH</option>
          <option value="JANTE BRANCH">JANTE BRANCH</option>
          <option value="JHILJHILE BRANCH">JHILJHILE BRANCH</option>
          <option value="JIRI BRANCH">JIRI BRANCH</option>
          <option value="JITPUR BARA BRANCH">JITPUR BARA BRANCH</option>
          <option value="JORPATI BRANCH">JORPATI BRANCH</option>
          <option value="KAKARBHITTA BRANCH">KAKARBHITTA BRANCH</option>
          <option value="KALANKI BRANCH">KALANKI BRANCH</option>
          <option value="KALI GANDAKI BRANCH">KALI GANDAKI BRANCH</option>
          <option value="KALIKA BRANCH">KALIKA BRANCH</option>
          <option value="KALIMATI BRANCH">KALIMATI BRANCH</option>
          <option value="KALYANPUR BRANCH">KALYANPUR BRANCH</option>
          <option value="KANCHANBARI BRANCH">KANCHANBARI BRANCH</option>
          <option value="KAPAN BRANCH">KAPAN BRANCH</option>
          <option value="KAPURDHARA BRANCH">KAPURDHARA BRANCH</option>
          <option value="KAWASOTI BRANCH">KAWASOTI BRANCH</option>
          <option value="KHANDBARI BRANCH">KHANDBARI BRANCH</option>
          <option value="KHAPTADCHHANA BRANCH">KHAPTADCHHANA BRANCH</option>
          <option value="KHORSHANE BRANCH">KHORSHANE BRANCH</option>
          <option value="KHUMALTAR BRANCH">KHUMALTAR BRANCH</option>
          <option value="KIRTIPUR BRANCH">KIRTIPUR BRANCH</option>
          <option value="KOHALPUR BRANCH">KOHALPUR BRANCH</option>
          <option value="KOTESHWOR BRANCH">KOTESHWOR BRANCH</option>
          <option value="KUSMA BRANCH">KUSMA BRANCH</option>
          <option value="LAHAN BRANCH">LAHAN BRANCH</option>
          <option value="LAKESIDE BRANCH">LAKESIDE BRANCH</option>
          <option value="LAMACHOUR BRANCH">LAMACHOUR BRANCH</option>
          <option value="LAMAHI BRANCH">LAMAHI BRANCH</option>
          <option value="LAMKI BRANCH">LAMKI BRANCH</option>
          <option value="LAXMIPUR BRANCH">LAXMIPUR BRANCH</option>
          <option value="LEK BESI BRANCH">LEK BESI BRANCH</option>
          <option value="LETANG BRANCH">LETANG BRANCH</option>
          <option value="MAHABOUDHA BRANCH">MAHABOUDHA BRANCH</option>
          <option value="MAHALAXMISTHAN BRANCH">MAHALAXMISTHAN BRANCH</option>
          <option value="MAHENDRANAGAR BRANCH">MAHENDRANAGAR BRANCH</option>
          <option value="MALANGAWA BRANCH">MALANGAWA BRANCH</option>
          <option value="MANAKAMANA BRANCH">MANAKAMANA BRANCH</option>
          <option value="MANEBHANJYANG BRANCH">MANEBHANJYANG BRANCH</option>
          <option value="MANGALBARE,ILAM BRANCH">MANGALBARE,ILAM BRANCH</option>
          <option value="MANGALBARE,JHAPA BRA">MANGALBARE,JHAPA BRA</option>
          <option value="MANGALBARE,MORANG BRANCH">MANGALBARE,MORANG BRANCH</option>
          <option value="MANGALBAZAR BRANCH">MANGALBAZAR BRANCH</option>
          <option value="MAYA DEVI BRANCH">MAYA DEVI BRANCH</option>
          <option value="MITRAPARK BRANCH">MITRAPARK BRANCH</option>
          <option value="NARAYANGADH BRANCH">NARAYANGADH BRANCH</option>
          <option value="NECHA SALYAN BRANCH">NECHA SALYAN BRANCH</option>
          <option value="NEPALGUNJ BRANCH">NEPALGUNJ BRANCH</option>
          <option value="NEW BANESHWOR BRANCH">NEW BANESHWOR BRANCH</option>
          <option value="NEWPLAZA BRANCH">NEWPLAZA BRANCH</option>
          <option value="NEWROAD BRANCH">NEWROAD BRANCH</option>
          <option value="NEWROAD POKHARA BRANCH">NEWROAD POKHARA BRANCH</option>
          <option value="NIJGADH BRANCH">NIJGADH BRANCH</option>
          <option value="OLD BANESHWOR BRANCH">OLD BANESHWOR BRANCH</option>
          <option value="PAKKALI BRANCH">PAKKALI BRANCH</option>
          <option value="PALPAROAD BUTWAL BRANCH">PALPAROAD BUTWAL BRANCH</option>
          <option value="PANAUTI BRANCH">PANAUTI BRANCH</option>
          <option value="PANMARA BRANCH">PANMARA BRANCH</option>
          <option value="PATAN BRANCH">PATAN BRANCH</option>
          <option value="PATHARI BRANCH">PATHARI BRANCH</option>
          <option value="PEPSICOLA BRANCH">PEPSICOLA BRANCH</option>
          <option value="PHALELUNG BRANCH">PHALELUNG BRANCH</option>
          <option value="PHEDI BRANCH">PHEDI BRANCH</option>
          <option value="POKHARIYA BRANCH">POKHARIYA BRANCH</option>
          <option value="PRITHVICHOWK BRANCH">PRITHVICHOWK BRANCH</option>
          <option value="PULCHOWK BRANCH">PULCHOWK BRANCH</option>
          <option value="PURTIGHAT BRANCH">PURTIGHAT BRANCH</option>
          <option value="PUSPALAL BRANCH">PUSPALAL BRANCH</option>
          <option value="RAJGADH BRANCH">RAJGADH BRANCH</option>
          <option value="RAMAPUR BRANCH">RAMAPUR BRANCH</option>
          <option value="RANKE BRANCH">RANKE BRANCH</option>
          <option value="RATNACHOWK BRANCH">RATNACHOWK BRANCH</option>
          <option value="RUDRABENI BRANCH">RUDRABENI BRANCH</option>
          <option value="SABHAPOKHARI BRANCH">SABHAPOKHARI BRANCH</option>
          <option value="SAKHUWA MAHENDRANAGA BRANCH">SAKHUWA MAHENDRANAGA BRANCH</option>
          <option value="SALLAGHARI BRANCH">SALLAGHARI BRANCH</option>
          <option value="SAMAKUSHI BRANCH">SAMAKUSHI BRANCH</option>
          <option value="SANDHIKHARKA BRANCH">SANDHIKHARKA BRANCH</option>
          <option value="SANKHAMUL BRANCH">SANKHAMUL BRANCH</option>
          <option value="SARAWAL BRANCH">SARAWAL BRANCH</option>
          <option value="SARUMARANI BRANCH">SARUMARANI BRANCH</option>
          <option value="SHAHID LAKHAN BRANCH">SHAHID LAKHAN BRANCH</option>
          <option value="SHIVANAGAR RAUTAHAT">SHIVANAGAR RAUTAHAT</option>
          <option value="Shivapur Extension Counter">Shivapur Extension Counter</option>
          <option value="SIDHUWA BRANCH">SIDHUWA BRANCH</option>
          <option value="SINAMANGAL BRANCH">SINAMANGAL BRANCH</option>
          <option value="SINDHULI BRANCH">SINDHULI BRANCH</option>
          <option value="SIRAHABAZAR BRANCH">SIRAHABAZAR BRANCH</option>
          <option value="SOMBARE BRANCH">SOMBARE BRANCH</option>
          <option value="SORAKHUTTE BRANCH">SORAKHUTTE BRANCH</option>
          <option value="SUDHODHAN BRANCH">SUDHODHAN BRANCH</option>
          <option value="SUNDARBAZAR BRANCH">SUNDARBAZAR BRANCH</option>
          <option value="SUNDHARA BRANCH">SUNDHARA BRANCH</option>
          <option value="SURUNGA BRANCH">SURUNGA BRANCH</option>
          <option value="SURYABINAYAK BRANCH">SURYABINAYAK BRANCH</option>
          <option value="SYANGJA BRANCH">SYANGJA BRANCH</option>
          <option value="TAHACHAL BRANCH">TAHACHAL BRANCH</option>
          <option value="TALCHOWK BRANCH">TALCHOWK BRANCH</option>
          <option value="TANDI BRANCH">TANDI BRANCH</option>
          <option value="TAPLEJUNG BRANCH">TAPLEJUNG BRANCH</option>
          <option value="TAPLI BRANCH">TAPLI BRANCH</option>
          <option value="TARKESHWOR BRANCH">TARKESHWOR BRANCH</option>
          <option value="THAMEL BRANCH">THAMEL BRANCH</option>
          <option value="THANKOT BRANCH">THANKOT BRANCH</option>
          <option value="THANTIPOKHARI BRANCH">THANTIPOKHARI BRANCH</option>
          <option value="THIMI BRANCH">THIMI BRANCH</option>
          <option value="TINGHARE BRANCH">TINGHARE BRANCH</option>
          <option value="TRAFFIC CHOWK BRANCH">TRAFFIC CHOWK BRANCH</option>
          <option value="TULSIPUR BRANCH">TULSIPUR BRANCH</option>
          <option value="TUMBEWA BRANCH">TUMBEWA BRANCH</option>
          <option value="TUMLINGTAR BRANCH">TUMLINGTAR BRANCH</option>
          <option value="WALING BRANCH">WALING BRANCH</option>

        </select>

      </div>
      <div class="form-group">
        <label for="staffId">Staff Id</label>
        <input type="number" name="staffId" id="staffId">
      </div>
      <div class="form-group">
        <label for="designation">Designation</label>
        <input type="text" name="designation" id="designation">
      </div>
      <div class="form-group">
        <label for="pumLoginId">Pumori Login Id</label>
        <input type="number" name="pumLoginId" id="pumLoginId">
      </div>
      <div class="form-group">
        <label for="existingPrevileges">Existing Previleges</label>
        <input type="text" name="existingPrevileges" id="existingPrevileges">
      </div>
    </div>
    <hr>

    <fieldset>
      <legend>Checker Privilege</legend>
      <div class="checker-group">
        <label class="full-width border-bottom">
          <input type="checkbox" name="checkerPrivilege[]" value="branchManage" data-group="singlePrivilege"> Branch Manager
        </label>
        <label>
          <input type="checkbox" name="checkerPrivilege[]" value="creditTradeFinanceChecker" data-group="singlePrivilege">
          Branch Credit & Trade Finance Checker
        </label>
        <label>
          <input type="checkbox" name="checkerPrivilege[]" value="operationInCharge" data-group="singlePrivilege">
          Branch Operation In-charge
        </label>
      </div>
    </fieldset>


    <fieldset>
      <legend>Maker Privilege</legend>
      <div class="maker-group">
        <div class="maker-left">
          <label>
            <input type="checkbox" name="makerPrivilege[]" value="creditTradeFinanceMaker" data-group="singlePrivilege">
            Branch Credit & Trade Finance Maker
          </label>
        </div>
        <div class="maker-right">
          <label>
            <input type="checkbox" name="makerPrivilege[]" value="operationMakerL1" data-group="singlePrivilege">
            Branch Operation Maker L1
          </label>
          <label>
            <input type="checkbox" name="makerPrivilege[]" value="operationMakerL2" data-group="singlePrivilege">
            Branch Operation Maker L2
          </label>
          <label>
            <input type="checkbox" name="makerPrivilege[]" value="operationMakerL3" data-group="singlePrivilege">
            Branch Operation Maker L3
          </label>
        </div>

        <label class="full-width border-top">
          <input type="checkbox" name="goldLoan" data-group="singlePrivilege"> Gold Loan
        </label>
      </div>
    </fieldset>


    <hr>
    <p class="buttons" style="text-align:center;">
      <input type="submit" name="submit" value="<?php echo __('Submit'); ?>">
      <input type="reset" name="reset" value="<?php echo __('Reset'); ?>">
      <input type="button" name="cancel" value="<?php echo __('Cancel'); ?>" onclick="javascript:
            $('.richtext').each(function() {
                var redactor = $(this).data('redactor');
                if (redactor && redactor.opts.draftDelete)
                    redactor.plugin.draft.deleteDraft();
            });
            window.location.href='index.php';">
    </p>
  </form>

  <div class="transaction-info">
    <p><strong>Transaction Limit:</strong></p>
    <table>
      <tr>
        <th></th>
        <th>Dr</th>
        <th>Cr</th>
      </tr>
      <tr>
        <td>Branch Operation Maker L1</td>
        <td>50,000.00</td>
        <td>1,000,000.00</td>
      </tr>
      <tr>
        <td>Branch Operation Maker L2</td>
        <td>100,000.00</td>
        <td>2,000,000.00</td>
      </tr>
      <tr>
        <td>Branch Operation Maker L3</td>
        <td>200,000.00</td>
        <td>4,000,000.00</td>
      </tr>
    </table>
  </div>
<?php endif; ?>


<script>
  document.addEventListener("DOMContentLoaded", function() {
    document.addEventListener("change", function(e) {
      console.log(e.target)
      if (
        e.target.matches('input[type="checkbox"][data-group="singlePrivilege"]')
      ) {
        console.log("Matched")
        const allCheckboxes = document.querySelectorAll(
          'input[type="checkbox"][data-group="singlePrivilege"]'
        );

        allCheckboxes.forEach((box) => {
          if (box !== e.target) box.checked = false;
        });
      }
    });
  });
</script>

<script>
  $(document).ready(function() {
    $('#branchSelect').select2({
      placeholder: 'Select a branch',
      allowClear: true
    });
  });
</script>
