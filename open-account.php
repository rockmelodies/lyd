<?php

include('header.php');

require_once('account/include/entryPoint.php');

if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) )

{

	redirect('account/index.php');

}

?>

      <div class="clr"></div>

  

  <div class="content">

    <div class="content_resize">

      <div class="mainbar">

        <div class="article">

         <h2>Online Account Form</h2>

        

         <?php

		 

							if(isset($_REQUEST['_submit']))

							{

								

								if(empty($_POST['emailid']) || empty($_POST['fname']) || empty($_POST['lname']) ){

									$_SESSION['error']  = 'Invalid Data.';

									redirect('open-account.php');

									exit;

								}

								

								

								$parameters = array(

									'user_auth'=> array('user_name' => $crm_username, 'password' => md5($crm_password)),

								);

								$authenticate = doRESTCALL($crm_url, 'login', $parameters);

								if(!isset($authenticate->id) || empty($authenticate->id)){

									$_SESSION['error']  = 'Something is Wrong. Please try again.';

									redirect('open-account.php');

									exit;

								}else{

									$sessionId = $authenticate->id;

									/*$parameters = array(

										'session' => $sessionId,

										'module' => 'Contacts',

										'query'=>  " contacts.id in (

											SELECT eabr.bean_id FROM email_addr_bean_rel eabr 

											JOIN email_addresses ea

											ON (ea.id = eabr.email_address_id) 

											WHERE eabr.deleted=0 and ea.email_address = '".$_POST['emailid']."') ",

										'order_by' => '',

										'offset' => 0,

										'select_fields' => array('id'),

										'link_name_to_fields_array' => array(),

										'max_results' => '1',

									);

									$check_existing_record = doRESTCALL($crm_url, 'get_entry_list', $parameters);

									if(isset($check_existing_record->entry_list[0]->name_value_list->id->value) 

											&& !empty($check_existing_record->entry_list[0]->name_value_list->id->value) ){

										//header("Location:login.php?e_exist=true");

										$_SESSION['error'] = 'Email already exists. Please login with the email address or request to reset password from Login Page';

										redirect('open-account.php');

										exit;

									}else{ */

										$parameters = array(

										'session' => $sessionId,

										'module' => 'Contacts',

										'name_value_list' => array(

											'salutation' => $_POST['ntitle'],

											'first_name' => $_POST['fname'],

											'last_name' => $_POST['lname'],

											'house_number_c' => $_POST['houseno'],

											'primary_address_street' => $_POST['street'],

											'po_box_c' => $_POST['pobox'],

											'primary_address_city' => $_POST['cityname'],

											'primary_address_postalcode' => $_POST['postcode'],

											'primary_address_country' => $_POST['country'],

											'n_house_number_c' => $_POST['n_houseno'],

											'alt_address_street' => $_POST['n_street'],

											'n_po_box_c' => $_POST['n_pobox'],

											'alt_address_city' => $_POST['n_cityname'],

											'alt_address_postalcode' => $_POST['n_postcode'],

											'alt_address_country' => $_POST['n_country'],

											'birthdate' => $_POST['year'].'-'.$_POST['month'].'-'.$_POST['date'],

											'place_of_birth_c' => $_POST['placebirth'],

											'nationality_c' => $_POST['nationality'],

											'phone_work' => $_POST['daytimephone'],

											'phone_home' => $_POST['evetimephone'],

											'phone_mobile' => $_POST['mobile'],
                                            
                                            'passport_number_c' => $_POST['passport_number'],
                                            
                                            'profession_c' => $_POST['profession'],

											'email1' => $_POST['emailid'],

											),

										);

										$contact_record = doRESTCALL($crm_url, 'set_entry_portal', $parameters, true);

										//echo '<pre>'; print_r($contact_record); echo '</pre>'; die;

										if(!empty($contact_record->id)){

											/*$_SESSION['message']  = 'We have got your details. You will shortly receive login details after verification';

											redirect('open-account.php');

											exit;*/
											$_SESSION['new_contact_id'] = $contact_record->id;
											redirect('open-account-payment.php');
											exit;

										}else{

											$_SESSION['error'] ='There must be some problem. Please try again.';

											redirect('open-account.php');

											exit;

										}

									//}

								}

							}

						?>



<div class="custom" style="padding:20px 15px; border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px; width:130%; margin:10px auto 0 auto;">



<h3 style="font-size:13.5pt;padding:0px 20px;"> LydorMarkets Online Account Opening Form</h3>



<p style="padding:0px 20px;">Open an Account for just 75 Euros today !</p>



<p style="padding:0px 20px;">Complete the account opening form in just 3 simple steps to start your relationship with us. Make sure to answer all fields but note that there are mandatory fields that will need to be answered, otherwise your application will not be processed.</p>



<p style="padding:0px 20px;">As soon as you have registered your personal details, you will be sent a verification email which will confirm your new User-name and Password. Keep these safe. You will then be required to submit your KYC – (Know your customer ) documentation which verifies your identity and address. You will need to attach a copy of your passport, preferably colour scanned and a copy of your most recent utility bill (make sure it is your current bill – no longer than 3 months old) for proof of address. As soon as your KYC has been approved your account will be activated.</p>



<p style="padding:0px 20px;">If you have problems completing this form, please email us at <a href="mailto:info@lydormarkets.com">info@lydormarkets.com</a></p>





<script type="text/javascript">

$(document).ready(function(){

	$('#open_account').validationEngine();

});

</script>



<img src="images/step1.png" class="steps"/>



	<form action="" method="post" name="open_account" id="open_account">

		<table cellpadding="5" cellspacing="6" width="100%">

			<tr><td id="err" colspan="4" style="color:#FF0000;"><?php if(isset($_SESSION['error'])){   echo $_SESSION['error']; unset($_SESSION['error'] ); } ?></td></tr>

			<tr><td id="success" colspan="4" style="color:#00FF00;"><?php if(isset($_SESSION['message'])){   echo $_SESSION['message']; unset($_SESSION['message'] ); } ?></td></tr>

			<tr><td colspan="3"><span class="mandatory">*</span> Mandatory, MUST be answered</td></tr>

			<tr><td colspan="3"><b>Overview</b><hr /></td></tr>

			<tr><td align="right" width="20%">Title<span class="mandatory">*</span></td><td>:</td><td>

			<input type="radio" checked="checked" name="ntitle" id="ntitle_0"  class="validate[required] radio" value="Mr." />Mr.&nbsp;

			<input type="radio" name="ntitle" id="ntitle_1" class="validate[required] radio" value="Mrs." />Mrs.&nbsp;

			<input type="radio" name="ntitle" id="ntitle_2" class="validate[required] radio" value="Ms." />Ms.&nbsp;

			<input type="radio" name="ntitle" id="ntitle_3" class="validate[required] radio" value="Dr." />Dr.</td></tr>

			<tr><td align="right">First Name<span class="mandatory">*</span></td><td>:</td><td><input type="text"  name="fname" id="fname"  class="validate[required] inputbox" /></td></tr>

			<tr><td align="right">Last Name<span class="mandatory">*</span></td><td>:</td><td><input type="text"  name="lname" id="lname"  class="validate[required] inputbox"/></td></tr>

			<!--

			<tr><td>Verified<span class="mandatory">*</span></td><td>:</td><td><input type="radio" name="isverified" value="1" />Yes&nbsp;<input checked="checked" type="radio" name="isverified" value="0" />No</td></tr>

			<tr><td>Status<span class="mandatory">*</span></td><td>:</td>

				<td>

					<select name="status"  style="width:auto;">

						<option value="0">Inactive</option>

						<option value="1">Registered</option>

						<option value="2">Process</option>

						<option value="3">Pending</option>

						<option value="4">Blocked</option>

						<option value="5">Active</option>

					</select>

				</td>

			</tr>

			<tr><td>Number of Shares</td><td>:</td><td><input type="text"  name="noofshares"/></td></tr>

			<tr><td>Price</td><td>:</td><td><input type="text"  name="price"/></td></tr>

			<tr><td>Total Price</td><td>:</td><td><input type="text"  name="totalprice"/></td></tr>

			<tr><td>Account Number</td><td>:</td><td><input type="text"  name="accno"/></td></tr>

			<tr><td>Password<span class="mandatory">*</span></td><td>:</td><td><input type="password"  name="password"/></td></tr>

			-->

			<tr><td colspan="3"><b>Address Details (English)</b><hr /></td></tr>

			<tr><td align="right">House Name / No<span class="mandatory">*</span></td><td>:</td><td><input type="text"  name="houseno" id="houseno"   class="validate[required] inputbox"/></td></tr>

			<tr><td align="right">Street<span class="mandatory">*</span></td><td>:</td><td><input type="text"  name="street" id="street"  class="validate[required] inputbox"/></td></tr>

			<tr><td align="right">PO Box<span class="mandatory">*</span></td><td>:</td><td><input type="text"  name="pobox" id="pobox"   class="validate[required] inputbox"/></td></tr>

			<tr><td align="right">Town/City<span class="mandatory">*</span></td><td>:</td><td><input type="text"  name="cityname" id="cityname" class="validate[required] inputbox"/></td></tr>

			<tr><td align="right">Postal Code<span class="mandatory">*</span></td><td>:</td><td><input type="text"  name="postcode" id="postcode" class="validate[required] inputbox" style="width:120px;"/></td></tr>

			<tr><td align="right">Country<span class="mandatory">*</span></td><td>:</td>

				<td>

					<select name="country" id="country" class="inputbox validate[required]" style="width:auto;">

						<option value="" selected="selected">Select</option>

						<option value="Afghanistan">Afghanistan</option>

						<option value="Albania">Albania</option>

						<option value="Algeria">Algeria</option>

						<option value="American Samoa">American Samoa</option>

						<option value="Andorra">Andorra</option>

						<option value="Angola">Angola</option>

						<option value="Anguilla">Anguilla</option>

						<option value="Antartica">Antarctica</option>

						<option value="Antigua and Barbuda">Antigua and Barbuda</option>

						<option value="Argentina">Argentina</option>

						<option value="Armenia">Armenia</option>

						<option value="Aruba">Aruba</option>

						<option value="Australia">Australia</option>

						<option value="Austria">Austria</option>

						<option value="Azerbaijan">Azerbaijan</option>

						<option value="Bahamas">Bahamas</option>

						<option value="Bahrain">Bahrain</option>

						<option value="Bangladesh">Bangladesh</option>

						<option value="Barbados">Barbados</option>

						<option value="Belarus">Belarus</option>

						<option value="Belgium">Belgium</option>

						<option value="Belize">Belize</option>

						<option value="Benin">Benin</option>

						<option value="Bermuda">Bermuda</option>

						<option value="Bhutan">Bhutan</option>

						<option value="Bolivia">Bolivia</option>

						<option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>

						<option value="Botswana">Botswana</option>

						<option value="Bouvet Island">Bouvet Island</option>

						<option value="Brazil">Brazil</option>

						<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>

						<option value="Brunei Darussalam">Brunei Darussalam</option>

						<option value="Bulgaria">Bulgaria</option>

						<option value="Burkina Faso">Burkina Faso</option>

						<option value="Burundi">Burundi</option>

						<option value="Cambodia">Cambodia</option>

						<option value="Cameroon">Cameroon</option>

						<option value="Canada">Canada</option>

						<option value="Cape Verde">Cape Verde</option>

						<option value="Cayman Islands">Cayman Islands</option>

						<option value="Central African Republic">Central African Republic</option>

						<option value="Chad">Chad</option>

						<option value="Chile">Chile</option>

						<option value="China">China</option>

						<option value="Christmas Island">Christmas Island</option>

						<option value="Cocos Islands">Cocos (Keeling) Islands</option>

						<option value="Colombia">Colombia</option>

						<option value="Comoros">Comoros</option>

						<option value="Congo">Congo</option>

						<option value="Congo">Congo, the Democratic Republic of the</option>

						<option value="Cook Islands">Cook Islands</option>

						<option value="Costa Rica">Costa Rica</option>

						<option value="Cota D'Ivoire">Cote d'Ivoire</option>

						<option value="Croatia">Croatia (Hrvatska)</option>

						<option value="Cuba">Cuba</option>

						<option value="Cyprus">Cyprus</option>

						<option value="Czech Republic">Czech Republic</option>

						<option value="Denmark">Denmark</option>

						<option value="Djibouti">Djibouti</option>

						<option value="Dominica">Dominica</option>

						<option value="Dominican Republic">Dominican Republic</option>

						<option value="East Timor">East Timor</option>

						<option value="Ecuador">Ecuador</option>

						<option value="Egypt">Egypt</option>

						<option value="El Salvador">El Salvador</option>

						<option value="Equatorial Guinea">Equatorial Guinea</option>

						<option value="Eritrea">Eritrea</option>

						<option value="Estonia">Estonia</option>

						<option value="Ethiopia">Ethiopia</option>

						<option value="Falkland Islands">Falkland Islands (Malvinas)</option>

						<option value="Faroe Islands">Faroe Islands</option>

						<option value="Fiji">Fiji</option>

						<option value="Finland">Finland</option>

						<option value="France">France</option>

						<option value="France Metropolitan">France, Metropolitan</option>

						<option value="French Guiana">French Guiana</option>

						<option value="French Polynesia">French Polynesia</option>

						<option value="French Southern Territories">French Southern Territories</option>

						<option value="Gabon">Gabon</option>

						<option value="Gambia">Gambia</option>

						<option value="Georgia">Georgia</option>

						<option value="Germany">Germany</option>

						<option value="Ghana">Ghana</option>

						<option value="Gibraltar">Gibraltar</option>

						<option value="Greece">Greece</option>

						<option value="Greenland">Greenland</option>

						<option value="Grenada">Grenada</option>

						<option value="Guadeloupe">Guadeloupe</option>

						<option value="Guam">Guam</option>

						<option value="Guatemala">Guatemala</option>

						<option value="Guinea">Guinea</option>

						<option value="Guinea-Bissau">Guinea-Bissau</option>

						<option value="Guyana">Guyana</option>

						<option value="Haiti">Haiti</option>

						<option value="Heard and McDonald Islands">Heard and Mc Donald Islands</option>

						<option value="Holy See">Holy See (Vatican City State)</option>

						<option value="Honduras">Honduras</option>

						<option value="Hong Kong">Hong Kong</option>

						<option value="Hungary">Hungary</option>

						<option value="Iceland">Iceland</option>

						<option value="India">India</option>

						<option value="Indonesia">Indonesia</option>

						<option value="Iran">Iran (Islamic Republic of)</option>

						<option value="Iraq">Iraq</option>

						<option value="Ireland">Ireland</option>

						<option value="Israel">Israel</option>

						<option value="Italy">Italy</option>

						<option value="Jamaica">Jamaica</option>

						<option value="Japan">Japan</option>

						<option value="Jordan">Jordan</option>

						<option value="Kazakhstan">Kazakhstan</option>

						<option value="Kenya">Kenya</option>

						<option value="Kiribati">Kiribati</option>

						<option value="Democratic People's Republic of Korea">Korea, Democratic People's Republic of</option>

						<option value="Korea">Korea, Republic of</option>

						<option value="Kuwait">Kuwait</option>

						<option value="Kyrgyzstan">Kyrgyzstan</option>

						<option value="Lao">Lao People's Democratic Republic</option>

						<option value="Latvia">Latvia</option>

						<option value="Lebanon">Lebanon</option>

						<option value="Lesotho">Lesotho</option>

						<option value="Liberia">Liberia</option>

						<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>

						<option value="Liechtenstein">Liechtenstein</option>

						<option value="Lithuania">Lithuania</option>

						<option value="Luxembourg">Luxembourg</option>

						<option value="Macau">Macau</option>

						<option value="Macedonia">Macedonia, The Former Yugoslav Republic of</option>

						<option value="Madagascar">Madagascar</option>

						<option value="Malawi">Malawi</option>

						<option value="Malaysia">Malaysia</option>

						<option value="Maldives">Maldives</option>

						<option value="Mali">Mali</option>

						<option value="Malta">Malta</option>

						<option value="Marshall Islands">Marshall Islands</option>

						<option value="Martinique">Martinique</option>

						<option value="Mauritania">Mauritania</option>

						<option value="Mauritius">Mauritius</option>

						<option value="Mayotte">Mayotte</option>

						<option value="Mexico">Mexico</option>

						<option value="Micronesia">Micronesia, Federated States of</option>

						<option value="Moldova">Moldova, Republic of</option>

						<option value="Monaco">Monaco</option>

						<option value="Mongolia">Mongolia</option>

						<option value="Montserrat">Montserrat</option>

						<option value="Morocco">Morocco</option>

						<option value="Mozambique">Mozambique</option>

						<option value="Myanmar">Myanmar</option>

						<option value="Namibia">Namibia</option>

						<option value="Nauru">Nauru</option>

						<option value="Nepal">Nepal</option>

						<option value="Netherlands">Netherlands</option>

						<option value="Netherlands Antilles">Netherlands Antilles</option>

						<option value="New Caledonia">New Caledonia</option>

						<option value="New Zealand">New Zealand</option>

						<option value="Nicaragua">Nicaragua</option>

						<option value="Niger">Niger</option>

						<option value="Nigeria">Nigeria</option>

						<option value="Niue">Niue</option>

						<option value="Norfolk Island">Norfolk Island</option>

						<option value="Northern Mariana Islands">Northern Mariana Islands</option>

						<option value="Norway">Norway</option>

						<option value="Oman">Oman</option>

						<option value="Pakistan">Pakistan</option>

						<option value="Palau">Palau</option>

						<option value="Panama">Panama</option>

						<option value="Papua New Guinea">Papua New Guinea</option>

						<option value="Paraguay">Paraguay</option>

						<option value="Peru">Peru</option>

						<option value="Philippines">Philippines</option>

						<option value="Pitcairn">Pitcairn</option>

						<option value="Poland">Poland</option>

						<option value="Portugal">Portugal</option>

						<option value="Puerto Rico">Puerto Rico</option>

						<option value="Qatar">Qatar</option>

						<option value="Reunion">Reunion</option>

						<option value="Romania">Romania</option>

						<option value="Russia">Russian Federation</option>

						<option value="Rwanda">Rwanda</option>

						<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option> 

						<option value="Saint LUCIA">Saint LUCIA</option>

						<option value="Saint Vincent">Saint Vincent and the Grenadines</option>

						<option value="Samoa">Samoa</option>

						<option value="San Marino">San Marino</option>

						<option value="Sao Tome and Principe">Sao Tome and Principe</option> 

						<option value="Saudi Arabia">Saudi Arabia</option>

						<option value="Senegal">Senegal</option>

						<option value="Seychelles">Seychelles</option>

						<option value="Sierra">Sierra Leone</option>

						<option value="Singapore">Singapore</option>

						<option value="Slovakia">Slovakia (Slovak Republic)</option>

						<option value="Slovenia">Slovenia</option>

						<option value="Solomon Islands">Solomon Islands</option>

						<option value="Somalia">Somalia</option>

						<option value="South Africa">South Africa</option>

						<option value="South Georgia">South Georgia and the South Sandwich Islands</option>

						<option value="Span">Spain</option>

						<option value="SriLanka">Sri Lanka</option>

						<option value="St. Helena">St. Helena</option>

						<option value="St. Pierre and Miguelon">St. Pierre and Miquelon</option>

						<option value="Sudan">Sudan</option>

						<option value="Suriname">Suriname</option>

						<option value="Svalbard">Svalbard and Jan Mayen Islands</option>

						<option value="Swaziland">Swaziland</option>

						<option value="Sweden">Sweden</option>

						<option value="Switzerland">Switzerland</option>

						<option value="Syria">Syrian Arab Republic</option>

						<option value="Taiwan">Taiwan, Province of China</option>

						<option value="Tajikistan">Tajikistan</option>

						<option value="Tanzania">Tanzania, United Republic of</option>

						<option value="Thailand">Thailand</option>

						<option value="Togo">Togo</option>

						<option value="Tokelau">Tokelau</option>

						<option value="Tonga">Tonga</option>

						<option value="Trinidad and Tobago">Trinidad and Tobago</option>

						<option value="Tunisia">Tunisia</option>

						<option value="Turkey">Turkey</option>

						<option value="Turkmenistan">Turkmenistan</option>

						<option value="Turks and Caicos">Turks and Caicos Islands</option>

						<option value="Tuvalu">Tuvalu</option>

						<option value="Uganda">Uganda</option>

						<option value="Ukraine">Ukraine</option>

						<option value="United Arab Emirates">United Arab Emirates</option>

						<option value="United Kingdom">United Kingdom</option>

						<option value="United States">United States</option>

						<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>

						<option value="Uruguay">Uruguay</option>

						<option value="Uzbekistan">Uzbekistan</option>

						<option value="Vanuatu">Vanuatu</option>

						<option value="Venezuela">Venezuela</option>

						<option value="Vietnam">Viet Nam</option>

						<option value="Virgin Islands (British)">Virgin Islands (British)</option>

						<option value="Virgin Islands (U.S)">Virgin Islands (U.S.)</option>

						<option value="Wallis and Futana Islands">Wallis and Futuna Islands</option>

						<option value="Western Sahara">Western Sahara</option>

						<option value="Yemen">Yemen</option>

						<option value="Yugoslavia">Yugoslavia</option>

						<option value="Zambia">Zambia</option>

						<option value="Zimbabwe">Zimbabwe</option>

					</select>

				</td>

			</tr>

            <tr><td colspan="3"><b>Address Details (Native Language)</b><hr /></td></tr>

			<tr><td align="right">House Name / No</td><td>:</td><td><input type="text"  name="n_houseno" id="n_houseno"  class="inputbox"/></td></tr>

			<tr><td align="right">Street</td><td>:</td><td><input type="text"  name="n_street" id="n_street"  class="inputbox"/></td></tr>

			<tr><td align="right">PO Box</td><td>:</td><td><input type="text"  name="n_pobox" id="n_pobox"   class="inputbox"/></td></tr>

			<tr><td align="right">Town/City</td><td>:</td><td><input type="text"  name="n_cityname" id="n_cityname" class="inputbox"/></td></tr>

			<tr><td align="right">Postal Code</td><td>:</td><td><input type="text"  name="n_postcode" id="n_postcode" class="inputbox" style="width:120px;"/></td></tr>

			<tr><td align="right">Country</td><td>:</td><td><input type="text"  name="n_country" id="n_country" class="inputbox"/></td></tr>

			<tr><td colspan="3"><b>Personal Profile</b><hr /></td></tr>

			<tr><td align="right">DOB (mm/dd/yyyy)<span class="mandatory">*</span></td><td>:</td>

				<td>

					<select name="month" id="month" class="inputbox validate[required]" style="width:auto;">

						<option value="">Select</option>

						<option value="1">January</option>

						<option value="2">February</option>

						<option value="3">March</option>

						<option value="4">April</option>

						<option value="5">May</option>

						<option value="6">June</option>

						<option value="7">July</option>

						<option value="8">August</option>

						<option value="9">September</option>

						<option value="10">October</option>

						<option value="11">November</option>

						<option value="12">December</option>

					</select>

					&nbsp;/&nbsp;

					<select name="date" id="date" class="inputbox validate[required]" style="width:auto;">

						<option value="">Select</option>

						<?php for($i=1;$i<=31;$i++)

						{?>

						<option value="<?php echo $i;?>"><?php echo $i;?></option>

						<?php }?>

					</select>

					&nbsp;/&nbsp;

					<select name="year" id="year" class="inputbox validate[required]" style="width:auto;">

						<option value="">Select</option>

						<?php for($i=2013;$i>=1900;$i--)

						{?>

						<option value="<?php echo $i;?>"><?php echo $i;?></option>

						<?php }?>

					</select>

				</td>

			</tr>

			<tr><td align="right">Place of Birth</td><td>:</td><td><input type="text"  name="placebirth" id="placebirth"  class="inputbox"/></td></tr>

			<tr><td align="right">Nationality<span class="mandatory">*</span></td><td>:</td><td><input type="text"  name="nationality" id="nationality"  class="validate[required] inputbox"/></td></tr>
            
            <tr><td align="right">Passport Number<span class="mandatory">*</span></td><td>:</td><td><input type="text"  name="passport_number" id="passport_number"  class="validate[required] inputbox"/></td></tr>

            <tr><td align="right">Profession<span class="mandatory">*</span></td><td>:</td><td><input type="text"  name="profession" id="profession"  class="validate[required] inputbox"/></td></tr>


			<tr><td colspan="3"><b>Contact Details</b><hr /></td></tr>

			<tr><td align="right">Daytime Telephone</td><td>:</td><td><input type="text"  name="daytimephone" id="daytimephone" class="validate[custom[phone]] inputbox"/></td></tr>

			<tr><td align="right">Evening Telephone</td><td>:</td><td><input type="text"  name="evetimephone" id="evetimephone" class="validate[custom[phone]] inputbox"/></td></tr>

			<tr><td align="right">Mobile<span class="mandatory">*</span></td><td>:</td><td><input type="text"  name="mobile" id="mobile"  class="validate[required,custom[phone]] inputbox"/></td></tr>

			<tr><td align="right">Email Address<span class="mandatory">*</span></td><td>:</td><td><input type="text"  name="emailid" id="emailid" class="validate[required,custom[email]] inputbox"/></td></tr>

			

			<!--

			<tr><td colspan="3"><b>Other</b><hr /></td></tr>

			<tr><td>Assigned to</td><td>:</td><td><input type="text"  name="assignto"/></td></tr>

			<tr><td>Date Verified</td><td>:</td><td><input type="text"  name="dateverified"/></td></tr>

			<tr><td>Date Created</td><td>:</td><td><input type="text"  name="detecreated"/></td></tr>

			-->

			<tr><td align="right" colspan="3"><input type="image" value="Next" src="images/Step2a.jpg" name="submit"/>

		</table>
	<input type="hidden" value="_Next" name="_submit"/>
	
	</form>

	

	</div>

            

       

          <div class="clr"></div>

        </div>

      </div>

      <div class="clr"></div>

    </div>

  </div>

  <?php include('footer.php');?>