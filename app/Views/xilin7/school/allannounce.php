<?php echo view($_SESSION['tm'].'uc/header.php') ?>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="left">
    <tr>
        <td width="800" align="left" valign="top">

            <table border="1" cellpadding="0" cellspacing="0" width="800" bordercolor="#D8E2E2">
                <tr bgcolor="#D8E2E2">
                    <td height="20">
                        <font class=boldBlueText>&nbsp;All Announcements</font>
                    </td>
                </tr>
            </table>


            <table width="800" border="0" cellspacing="0" cellpadding="0" align="left">
              <?php foreach ($announcements as $item) { ?>
                  <tr>
                    <td width="600" align="left" valign="top" class="plainBlackText">
                      <div align=left style="width: 798px; border: 1px solid #999999; background: #FFFFFF;
                          margin: 0px; padding-left: 5px;">
                        <span class=smallBoldBlackText><?=$item['lastupdate']?></span>
                        <br>
                        <?= html_entity_decode($item['body']) ?>

                      </div>
                    </td>
                  </tr>
                <?php
              }
              ?>
            </table>
        </td>
    </tr>
</table>


<?php echo view($_SESSION['tm'].'uc/footer.php') ?>