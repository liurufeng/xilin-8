<html>
<head>
    <title>Chinese School Donation</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>

<table style="width: 980px; margin-top: 30px;">
    <tbody>
    <tr>
        <td style="width: 800px;"><strong style="margin-left: 40%; font-size: 1.5em;">Donation Receipt</strong></td>
        <td style="width: 180px;"><img style="float: right; position: relative; top: -12px; right: 5px;" src="<?php echo base_url();?>/<?=$_SESSION['tm']?>img/logo.png" alt="logo" width="110" height="110" /></td>
    </tr>
    </tbody>
</table>

<table style="width: 980px; border-bottom: 2px solid;">
    <tbody>
    <tr>
        <td style="width: 140px;">Invoice Date:</td>
        <td style="width: 288px;"><?= $invoice_date?></td>
        <td style="width: 275px; text-align: right;">&nbsp;Tax ID: 30-0332045</td>
    </tr>
    <?php if($parent_id){ ?>
    <tr>
        <td>Family ID:</td>
        <td><?= $parent_id ?></td>
        <td style="text-align: right;">&nbsp;4957 Oakton Street, Suite 292</td>
    </tr>
    <tr>
        <td>Payment Code:</td>
        <td><?= $pay_code ?></td>
        <td style="text-align: right;">&nbsp;Skokie, IL 60077</td>
    </tr>
    <?php } else { ?>
        <tr>
            <td> </td>
            <td> </td>
            <td style="text-align: right;">&nbsp;4957 Oakton Street, Suite 292</td>
        </tr>
        <tr>
            <td> </td>
            <td> </td>
            <td style="text-align: right;">&nbsp;Skokie, IL 60077</td>
        </tr>
    <?php } ?>
    <tr>
        <td> </td>
        <td> </td>
        <td style="text-align: right;">&nbsp;www.xilinnschinese.org</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    </tbody>
</table>

<table style="width: 980px; border-bottom: 2px solid;margin-top: 15px;margin-bottom: 15px;">
    <tbody>
    <tr>
        <td style="width: 45%;"><strong>Donor</strong></td>
        <td style="width: 45%; text-align: right;"><strong>Amount</strong></td>
    </tr>

    <tr>
        <td style="width: 45%;"><?= $name ?></td>
        <td style="width: 45%; text-align: right;"><?= $amount ?></td>
    </tr>

    </tbody>
</table>

</body>
</html>