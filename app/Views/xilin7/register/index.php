<?php echo view($_SESSION['tm'].'uc/header.php')?>
<?php
// after a failed sign-up (Register::registerinfo): what was typed, and the problem of each field
$errors = $errors ?? array();
$old = $old ?? array();
$val = static fn (string $name, string $default = ''): string => esc($old[$name] ?? $default, 'attr');

// one row of the form: label, input, and its error under it
$row = static function (string $label, string $name, bool $required, string $input) use ($errors): string {
  $html = '<tr class="' . (isset($errors[$name]) ? 'has-reg-error' : '') . '">'
    . '<td class="reg-label">' . ($required ? '<span style="color:red;">*</span>' : '') . $label . ':</td>'
    . '<td class="reg-input">' . $input;
  if (isset($errors[$name])) {
    $html .= '<div class="reg-error">' . esc($errors[$name]) . '</div>';
  }
  return $html . '</td></tr>';
};
$text = static fn (string $name, string $type = 'text', string $extra = ''): string
  => '<input type="' . $type . '" name="' . $name . '" value="' . $val($name) . '" ' . $extra . '/>';

$states = array('Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware',
  'District of Columbia', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky',
  'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana',
  'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio',
  'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah',
  'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming');
$state = $old['state'] ?? 'Illinois';
$state_select = '<select name="state">';
foreach ($states as $s) {
  $state_select .= '<option value="' . esc($s, 'attr') . '"' . ($s === $state ? ' selected' : '') . '>' . esc($s) . '</option>';
}
$state_select .= '</select>';
?>

  <style>
    .register-form .reg-label { text-align: right; vertical-align: top; padding-top: 8px; }
    .register-form .reg-input { padding: 3px 3px 3px 20px; }
    .register-form .reg-input input[type=text], .register-form .reg-input input[type=email],
    .register-form .reg-input input[type=password] { width: 300px; }
    .register-form .reg-hint { color: #777; font-size: 12px; margin-top: 2px; max-width: 300px; }
    .register-form .has-reg-error .reg-label { color: #a94442; }
    .register-form .has-reg-error input, .register-form .has-reg-error select { border: 2px solid #d9534f; background: #fff8f8; }
    .register-form .reg-error { color: #a94442; font-size: 13px; margin-top: 3px; max-width: 320px; }
    .register-form .reg-summary { margin: 10px 0 15px; padding: 10px 14px; max-width: 560px; color: #a94442;
      background: #f2dede; border: 1px solid #ebccd1; border-radius: 4px; }
    .register-form .reg-summary ul { margin: 6px 0 0 18px; padding: 0; }
  </style>

  <script src="/common/js/jquery-input-mask-phone-number.min.js"></script>
  <script>
    $(function(){

      // this page's phone fields (the sign-up popup in the header uses the same ids). An error here would stop the
      // other start-up scripts (script.js removes the loading spinner), hence the check.
      if ($.fn.usPhoneFormat) {
        $('.register-form input[name=primary_phone], .register-form input[name=alter_phone]').usPhoneFormat({
          format: 'xxx-xxx-xxxx'
        });
      }

      // the first field with a problem
      $('.has-reg-error input, .has-reg-error select').first().focus();
    });
  </script>

  <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>

                    <div class="register-form">
                        <h3>&nbsp;&nbsp;&nbsp;REGISTER AN ACCOUNT</h3>

                        <?php if ($errors) { ?>
                        <div class="reg-summary" role="alert">
                            <b>Please fix the <?= count($errors) === 1 ? 'field' : count($errors) . ' fields' ?> marked in red below:</b>
                            <ul>
                                <?php foreach ($errors as $msg) { ?><li><?= esc($msg) ?></li><?php } ?>
                            </ul>
                        </div>
                        <?php } ?>

                        <!-- novalidate: every problem is shown on the page at once (Register::registerinfo) -->
                        <form name="contact-form" method="POST" action="/register/registerinfo?REGISTERTYPE=Parents" id="form1" novalidate>
                            <table id="Parents">
                                <?= $row('Email', 'email', true, $text('email', 'email', 'autocomplete="email"')) ?>
                                <?= $row('Re-type Email', 're_email', true, $text('re_email', 'email', 'autocomplete="email"')) ?>
                                <?= $row('Password', 'passwd', true,
                                    '<input type="password" name="passwd" value="" autocomplete="new-password"'
                                    . ' placeholder="8+ characters: A-Z, a-z, 0-9 and a symbol"/>'
                                    . '<div class="reg-hint">' . esc(\App\Libraries\Passwords::RULES) . '</div>') ?>
                                <?= $row('Re-type Password', 'passwd2', true,
                                    '<input type="password" name="passwd2" value="" autocomplete="new-password" placeholder="Type the same password again"/>') ?>
                                <?= $row('Primary Contact English Name', 'primary_en_name', true, $text('primary_en_name')) ?>
                                <?= $row('Chinese Name', 'primary_cn_name', false, $text('primary_cn_name')) ?>
                                <?= $row('Phone', 'primary_phone', true, $text('primary_phone', 'text', 'autocomplete="tel"')) ?>
                                <?= $row('Relationship', 'primary_relationship', true, $text('primary_relationship', 'text', 'placeholder="e.g. Mother, Father"')) ?>
                                <?= $row('Alternative Contact Email', 'alter_contact_email', false, $text('alter_contact_email', 'email')) ?>
                                <?= $row('Alternative Contact English Name', 'alter_en_name', false, $text('alter_en_name')) ?>
                                <?= $row('Chinese Name', 'alter_cn_name', false, $text('alter_cn_name')) ?>
                                <?= $row('Phone', 'alter_phone', false, $text('alter_phone')) ?>
                                <?= $row('Relationship', 'alter_relationship', false, $text('alter_relationship')) ?>
                                <?= $row('City', 'city', true, $text('city', 'text', 'autocomplete="address-level2"')) ?>
                                <?= $row('State', 'state', true, $state_select) ?>
                                <?= $row('Zip Code', 'zip', true, $text('zip', 'text', 'autocomplete="postal-code"')) ?>
                                <?= $row('Where did you know this School?', 'heard_from', false, $text('heard_from')) ?>
                                <?= $row('Referrer ID Number', 'referrer_id', false, $text('referrer_id', 'text', 'inputmode="numeric"')) ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="reg-input">
                                        <input type="submit" value="register" />
                                        <input type="reset" name="reset" value="reset" style="margin-left:20px;"/>
                                    </td>
                                </tr>
                            </table>
                        </form>

                    </div>

<?php echo view($_SESSION['tm'].'uc/footer.php')?>
