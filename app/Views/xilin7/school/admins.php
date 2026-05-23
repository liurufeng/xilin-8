<?php echo view($_SESSION['tm'].'uc/header.php') ?>
  <div class="Section1">

    <p><b><span style="font-size:16.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:blue">School Board of Directors</span></b></p>

    <table style="margin-left:5.4pt;border-collapse:collapse;" width="98%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td style="border:solid windowtext 1.0pt; padding:0in 5.4pt 0in 5.4pt" width="20%" valign="top">
          <b><span style="font-size:14.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;">Position</span></b>
        </td>
        <td style="border:solid windowtext 1.0pt; padding:0in 5.4pt 0in 5.4pt" width="30%" valign="top">
          <b><span style="font-size:14.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;">Name</span></b>
        </td>
        <td style="border:solid windowtext 1.0pt; padding:0in 5.4pt 0in 5.4pt" width="50%" valign="top">
          <b><span style="font-size:14.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;">Email address</span></b>
        </td>
      </tr>
      <?php foreach ($bods as $bod) { ?>
        <tr style="height:.3in">
          <td style="border:solid windowtext 1.0pt;border-top: none;padding:0in 5.4pt 0in 5.4pt;height:.3in" width="20%">
            <b><span style="color:#7d1a1ae6;"><?= $bod['desc'] ?></span></b>
          </td>
          <td style="border:solid windowtext 1.0pt;border-top: none;padding:0in 5.4pt 0in 5.4pt;height:.3in" width="30%">
            <p class="MsoNormal"><b><span style="color:#7d1a1ae6;"><?= $bod['name'] ?></span></b>
            </p></td>
          <td style="border:solid windowtext 1.0pt;border-top: none;padding:0in 5.4pt 0in 5.4pt;height:.3in" width="50%">
            <b><a href="mailto:<?= $bod['email'] ?>"><?= $bod['email'] ?></a></b>
          </td>
        </tr>
      <?php } ?>
      </tbody></table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>

  <p><b><span style="font-size:16.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:blue">School Executive Committee</span></b></p>
  <p>&nbsp;</p>

  <table style="margin-left:5.4pt;border-collapse:collapse;" width="98%" cellspacing="0" cellpadding="0" border="0">
    <tbody><tr>
      <td style="border:solid windowtext 1.0pt; padding:0in 5.4pt 0in 5.4pt" width="20%" valign="top">
        <b><span style="font-size:14.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;">Position</span></b>
      </td>
      <td style="border:solid windowtext 1.0pt; padding:0in 5.4pt 0in 5.4pt" width="30%" valign="top">
        <b><span style="font-size:14.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;">Name</span></b>
      </td>
      <td style="border:solid windowtext 1.0pt; padding:0in 5.4pt 0in 5.4pt" width="50%" valign="top">
        <b><span style="font-size:14.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;">Email &amp; Phone</span></b>
      </td>
    </tr>
    <?php foreach ($stuffs as $stuff) { ?>
  <tr style="height:.3in">
    <td style="border:solid windowtext 1.0pt; padding:0in 5.4pt 0in 5.4pt" width="20%" valign="top">
      <b><span style="color:#7d1a1ae6;"><?= $stuff['desc'] ?></span></b>
    </td>
    <td style="border:solid windowtext 1.0pt; padding:0in 5.4pt 0in 5.4pt" width="30%" valign="top">
      <b><span style="color:#7d1a1ae6;"><?= $stuff['name'] ?></span></b>
    </td>
    <td style="border:solid windowtext 1.0pt; padding:0in 5.4pt 0in 5.4pt" width="50%" valign="top">
      <b><a href="mailto:<?= $stuff['email'] ?>"><?= $stuff['email'] ?></a></b>
      <br><?= $stuff['phone'] ?>
    </td>
  </tr>
    <?php } ?>

    </tbody></table>

    <p>&nbsp;</p>

    <p>&nbsp;</p>

  </div>

<?php echo view($_SESSION['tm'].'uc/footer.php') ?>