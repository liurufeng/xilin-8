<?php echo view($_SESSION['tm'].'uc/header.php')?>

  <script>
    $(function(){

      $('#prim-phone').usPhoneFormat({
        format: 'xxx-xxx-xxxx'
      });

      $('#alt-phone').usPhoneFormat({
        format: 'xxx-xxx-xxxx'
      });

    });
  </script>

  <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>

                    <div class="register-form">
                        <h3>&nbsp;&nbsp;&nbsp;REGISTER AN ACCOUNT</h3>

                        <form name="contact-form" method="POST" action="/register/registerinfo?REGISTERTYPE=Parents" id="form1">
                            <table id="Parents">
                                <tr>
                                    <td style="text-align:right;"><span style="color:red;">*</span>Email:</td>
                                    <td style="padding:3px;"><input type="email" value="" name="email"
                                                                    style="width:300px; margin-left:20px;"/></td>
                                </tr>
                              <tr>
                                <td style="text-align:right;"><span style="color:red;">*</span>Re-type Email:</td>
                                <td style="padding:3px;"><input type="email" value="" name="re_email"
                                                                style="width:300px; margin-left:20px;"/></td>
                              </tr>
                                <tr>
                                    <td style="text-align:right;"><span style="color:red;">*</span>Password:</td>
                                    <td style="padding:3px;"><input type="password" value="" name="passwd"
                                                                    style="width:300px; margin-left:20px;"/></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;"><span style="color:red;">*</span>Primary Contact
                                        English Name:
                                    </td>
                                    <td style="padding:3px;"><input type="text" value="" name="primary_en_name"
                                                                    style="width:300px; margin-left:20px;"/></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;">Chinese Name:</td>
                                    <td style="padding:3px;"><input type="text" value="" name="primary_cn_name"
                                                                    style="width:300px; margin-left:20px;"/></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;"><span style="color:red;">*</span>Phone:</td>
                                    <td style="padding:3px;"><input type="text" id="prim-phone" value="" name="primary_phone"
                                                                    style="width:300px; margin-left:20px;"/></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;"><span style="color:red;">*</span>Relationship:</td>
                                    <td style="padding:3px;"><input type="text" value="" name="primary_relationship"
                                                                    style="width:300px; margin-left:20px;"/></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;">Alternative Contact Email:</td>
                                    <td style="padding:3px;"><input type="text" value="" name="alter_contact_email"
                                                                    style="width:300px; margin-left:20px;"/></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;">Alternative Contact English Name:</td>
                                    <td style="padding:3px;"><input type="text" value="" name="alter_en_name"
                                                                    style="width:300px; margin-left:20px;"/></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;">Chinese Name:</td>
                                    <td style="padding:3px;"><input type="text" value="" name="alter_cn_name"
                                                                    style="width:300px; margin-left:20px;"/></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;">Phone:</td>
                                    <td style="padding:3px;"><input type="text" id="alt-phone" value="" name="alter_phone"
                                                                    style="width:300px; margin-left:20px;"/></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;">Relationship:</td>
                                    <td style="padding:3px;"><input type="text" value="" name="alter_relationship"
                                                                    style="width:300px; margin-left:20px;"/></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;"><span style="color:red;">*</span>Street Address:</td>
                                    <td style="padding:3px;"><input type="text" value="" name="address"
                                                                    style="width:300px; margin-left:20px;"/></td>
                                </tr>
                              <tr>
                                <td style="text-align:right;"><span style="color:red;">*</span>City:</td>
                                <td style="padding:3px;"><input type="text" value="" name="city"
                                                                style="width:300px; margin-left:20px;"/></td>
                              </tr>
                              <tr>
                                <td style="text-align:right;"><span style="color:red;">*</span>State:</td>
                                <td style="padding:3px;"><select name="state" style="margin-left: 20px;">
                                    <option value="Alabama">Alabama</option>
                                    <option value="Alaska">Alaska</option>
                                    <option value="Arizona">Arizona</option>
                                    <option value="Arkansas">Arkansas</option>
                                    <option value="California">California</option>
                                    <option value="Colorado">Colorado</option>
                                    <option value="Connecticut">Connecticut</option>
                                    <option value="Delaware">Delaware</option>
                                    <option value="District of Columbia">District of Columbia</option>
                                    <option value="Florida">Florida</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Hawaii">Hawaii</option>
                                    <option value="Idaho">Idaho</option>
                                    <option value="Illinois" selected="">Illinois</option>
                                    <option value="Indiana">Indiana</option>
                                    <option value="Iowa">Iowa</option>
                                    <option value="Kansas">Kansas</option>
                                    <option value="Kentucky">Kentucky</option>
                                    <option value="Louisiana">Louisiana</option>
                                    <option value="Maine">Maine</option>
                                    <option value="Maryland">Maryland</option>
                                    <option value="Massachusetts">Massachusetts</option>
                                    <option value="Michigan">Michigan</option>
                                    <option value="Minnesota">Minnesota</option>
                                    <option value="Mississippi">Mississippi</option>
                                    <option value="Missouri">Missouri</option>
                                    <option value="Montana">Montana</option>
                                    <option value="Nebraska">Nebraska</option>
                                    <option value="Nevada">Nevada</option>
                                    <option value="New Hampshire">New Hampshire</option>
                                    <option value="New Jersey">New Jersey</option>
                                    <option value="New Mexico">New Mexico</option>
                                    <option value="New York">New York</option>
                                    <option value="North Carolina">North Carolina</option>
                                    <option value="North Dakota">North Dakota</option>
                                    <option value="Ohio">Ohio</option>
                                    <option value="Oklahoma">Oklahoma</option>
                                    <option value="Oregon">Oregon</option>
                                    <option value="Pennsylvania">Pennsylvania</option>
                                    <option value="Rhode Island">Rhode Island</option>
                                    <option value="South Carolina">South Carolina</option>
                                    <option value="South Dakota">South Dakota</option>
                                    <option value="Tennessee">Tennessee</option>
                                    <option value="Texas">Texas</option>
                                    <option value="Utah">Utah</option>
                                    <option value="Vermont">Vermont</option>
                                    <option value="Virginia">Virginia</option>
                                    <option value="Washington">Washington</option>
                                    <option value="West Virginia">West Virginia</option>
                                    <option value="Wisconsin">Wisconsin</option>
                                    <option value="Wyoming">Wyoming</option>
                                  </select></td>
                              </tr>
                              <tr>
                                <td style="text-align:right;"><span style="color:red;">*</span>Zip Code:</td>
                                <td style="padding:3px;"><input type="text" value="" name="zip"
                                                                style="width:300px; margin-left:20px;"/></td>
                              </tr>
                                <tr>
                                    <td style="text-align:right;">Where did you know this School?</td>
                                    <td style="padding:3px;"><input type="text" value="" name="heard_from"
                                                                    style="width:300px; margin-left:20px;"/></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;">&nbsp;</td>
                                    <td style="padding:3px;">
                                        <input type="submit" value="register" />
                                        <input type="reset" name="reset" value="reset" style="margin-left:20px;"/>
                                    </td>
                                </tr>
                            </table>
                        </form>

                    </div>

<?php echo view($_SESSION['tm'].'uc/footer.php')?>