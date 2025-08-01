<?php
$pageName = "Wire Transfer";
include_once("layouts/header.php");
require_once("userPinfunction.php");

//List usa banks
$list_us_banks_sql = "SELECT * FROM list_banks";
$stmt = $conn->prepare($list_us_banks_sql);
$stmt->execute();
$list_us_banks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<style>
        /* Page specific styles */
        .wire-transfer-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        
        .wire-transfer-steps::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: rgba(16, 64, 66, 0.1);
            z-index: 0;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
            flex: 1;
        }
        
        .step-number {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #fff;
            border: 2px solid rgba(16, 64, 66, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: rgba(16, 64, 66, 0.6);
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        
        .step.active .step-number {
            background-color: #104042;
            border-color: #104042;
            color: #afff1a;
        }
        
        .step.completed .step-number {
            background-color: #afff1a;
            border-color: #afff1a;
            color: #104042;
        }
        
        .step-label {
            font-size: 14px;
            font-weight: 500;
            color: rgba(16, 64, 66, 0.6);
        }
        
        .step.active .step-label {
            color: #104042;
            font-weight: 600;
        }
        
        .step.completed .step-label {
            color: #104042;
        }
        
        .transfer-form-container {
            background-color: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(16, 64, 66, 0.08);
            border-left: 4px solid #FFD200;
            margin-bottom: 30px;
        }
        
        .form-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #104042;
            display: flex;
            align-items: center;
        }
        
        .form-title i {
            margin-right: 10px;
            color: #FFD200;
            background-color: #104042;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #104042;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(16, 64, 66, 0.2);
            border-radius: 8px;
            background-color: rgba(16, 64, 66, 0.02);
            color: #104042;
            font-size: 15px;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: #104042;
            box-shadow: 0 0 0 2px rgba(16, 64, 66, 0.1);
        }
        
        .btn-submit {
            background-color: #104042;
            color: #fff;
            padding: 14px 25px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        
        .btn-submit:hover {
            background-color: #165e61;
        }
        
        .form-section {
            margin-bottom: 30px;
        }
        
        .form-section-title {
            font-size: 18px;
            font-weight: 600;
            color: #104042;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(16, 64, 66, 0.1);
        }
        
        .transfer-info {
            margin-top: 30px;
            padding: 20px;
            background-color: rgba(255, 210, 0, 0.1);
            border-radius: 8px;
            border-left: 4px solid #FFD200;
        }
        
        .transfer-info h3 {
            color: #104042;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .transfer-info h3 i {
            margin-right: 10px;
        }
        
        .transfer-info ul {
            list-style: none;
            padding-left: 30px;
        }
        
        .transfer-info ul li {
            margin-bottom: 10px;
            position: relative;
            color: #104042;
        }
        
        .transfer-info ul li:before {
            content: '•';
            position: absolute;
            left: -15px;
            color: #FFD200;
            font-weight: bold;
        }
        
        .currency-info {
            display: flex;
            align-items: center;
            padding: 15px;
            background-color: rgba(16, 64, 66, 0.02);
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .currency-flag {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 15px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            background-color: #104042;
            color: white;
        }
        
        .currency-details {
            flex-grow: 1;
        }
        
        .currency-name {
            font-weight: 600;
            color: #104042;
            margin-bottom: 5px;
        }
        
        .currency-rate {
            font-size: 12px;
            color: rgba(16, 64, 66, 0.7);
        }
        
        .currency-amount {
            font-weight: 600;
            color: #104042;
        }
        
        @media (max-width: 992px) {
            .wire-transfer-steps {
                flex-wrap: wrap;
            }
            
            .step {
                flex-basis: 50%;
                margin-bottom: 20px;
            }
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .step {
                flex-basis: 100%;
            }
        }
    </style>
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <!-- Wire Transfer Steps -->
        <div class="wire-transfer-steps">
            <div class="step active">
                <div class="step-number">1</div>
                <div class="step-label">Transfer Details</div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-label">Review</div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-label">Confirm</div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-label">Complete</div>
            </div>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-md-8 offset-md-2">
                <div class="card component-card">
                    <div class="card-body">
                        <div class="user-profile">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    if($acct_stat === 'active'){
                                    ?>

                                    <?php
                                   
                                   if($page['transfer'] == '1'){
                                    ?>


                                    <?php
                                   
                                   if( $row['transfer'] == '1'){
                                    ?>

                                    <div class="transfer-form-container">
                                        <div class="form-title">
                                            <i class="fas fa-wifi"></i>
                                            International Wire Transfer
                                        </div>
                                        
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="form-section">
                                                <div class="form-section-title">Transfer Amount</div>
                                                <div class="form-row">
                                                    <div class="form-group">
                                                        <label for="amount">Amount</label>
                                                        <div class="input-group">
                                                            <input type="number" id="amount" name="amount" placeholder="Enter amount" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="acct_country">Select Country</label>
                                                        <div class="input-group">
                                                            <select name="acct_country" id="acct_country" class='form-control' required>
                                                                <option>Select Country</option>
                                                                <option value="Afganistan">Afghanistan</option>
                                                                <option value="Albania">Albania</option>
                                                                <option value="Algeria">Algeria</option>
                                                                <option value="American Samoa">American Samoa</option>
                                                                <option value="Andorra">Andorra</option>
                                                                <option value="Angola">Angola</option>
                                                                <option value="Anguilla">Anguilla</option>
                                                                <option value="Antigua & Barbuda">Antigua & Barbuda</option>
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
                                                                <option value="Bonaire">Bonaire</option>
                                                                <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                                                <option value="Botswana">Botswana</option>
                                                                <option value="Brazil">Brazil</option>
                                                                <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                                                <option value="Brunei">Brunei</option>
                                                                <option value="Bulgaria">Bulgaria</option>
                                                                <option value="Burkina Faso">Burkina Faso</option>
                                                                <option value="Burundi">Burundi</option>
                                                                <option value="Cambodia">Cambodia</option>
                                                                <option value="Cameroon">Cameroon</option>
                                                                <option value="Canada">Canada</option>
                                                                <option value="Canary Islands">Canary Islands</option>
                                                                <option value="Cape Verde">Cape Verde</option>
                                                                <option value="Cayman Islands">Cayman Islands</option>
                                                                <option value="Central African Republic">Central African Republic</option>
                                                                <option value="Chad">Chad</option>
                                                                <option value="Channel Islands">Channel Islands</option>
                                                                <option value="Chile">Chile</option>
                                                                <option value="China">China</option>
                                                                <option value="Christmas Island">Christmas Island</option>
                                                                <option value="Cocos Island">Cocos Island</option>
                                                                <option value="Colombia">Colombia</option>
                                                                <option value="Comoros">Comoros</option>
                                                                <option value="Congo">Congo</option>
                                                                <option value="Cook Islands">Cook Islands</option>
                                                                <option value="Costa Rica">Costa Rica</option>
                                                                <option value="Cote DIvoire">Cote DIvoire</option>
                                                                <option value="Croatia">Croatia</option>
                                                                <option value="Cuba">Cuba</option>
                                                                <option value="Curaco">Curacao</option>
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
                                                                <option value="Falkland Islands">Falkland Islands</option>
                                                                <option value="Faroe Islands">Faroe Islands</option>
                                                                <option value="Fiji">Fiji</option>
                                                                <option value="Finland">Finland</option>
                                                                <option value="France">France</option>
                                                                <option value="French Guiana">French Guiana</option>
                                                                <option value="French Polynesia">French Polynesia</option>
                                                                <option value="French Southern Ter">French Southern Ter</option>
                                                                <option value="Gabon">Gabon</option>
                                                                <option value="Gambia">Gambia</option>
                                                                <option value="Georgia">Georgia</option>
                                                                <option value="Germany">Germany</option>
                                                                <option value="Ghana">Ghana</option>
                                                                <option value="Gibraltar">Gibraltar</option>
                                                                <option value="Great Britain">Great Britain</option>
                                                                <option value="Greece">Greece</option>
                                                                <option value="Greenland">Greenland</option>
                                                                <option value="Grenada">Grenada</option>
                                                                <option value="Guadeloupe">Guadeloupe</option>
                                                                <option value="Guam">Guam</option>
                                                                <option value="Guatemala">Guatemala</option>
                                                                <option value="Guinea">Guinea</option>
                                                                <option value="Guyana">Guyana</option>
                                                                <option value="Haiti">Haiti</option>
                                                                <option value="Hawaii">Hawaii</option>
                                                                <option value="Honduras">Honduras</option>
                                                                <option value="Hong Kong">Hong Kong</option>
                                                                <option value="Hungary">Hungary</option>
                                                                <option value="Iceland">Iceland</option>
                                                                <option value="Indonesia">Indonesia</option>
                                                                <option value="India">India</option>
                                                                <option value="Iran">Iran</option>
                                                                <option value="Iraq">Iraq</option>
                                                                <option value="Ireland">Ireland</option>
                                                                <option value="Isle of Man">Isle of Man</option>
                                                                <option value="Israel">Israel</option>
                                                                <option value="Italy">Italy</option>
                                                                <option value="Jamaica">Jamaica</option>
                                                                <option value="Japan">Japan</option>
                                                                <option value="Jordan">Jordan</option>
                                                                <option value="Kazakhstan">Kazakhstan</option>
                                                                <option value="Kenya">Kenya</option>
                                                                <option value="Kiribati">Kiribati</option>
                                                                <option value="Korea North">Korea North</option>
                                                                <option value="Korea Sout">Korea South</option>
                                                                <option value="Kuwait">Kuwait</option>
                                                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                                <option value="Laos">Laos</option>
                                                                <option value="Latvia">Latvia</option>
                                                                <option value="Lebanon">Lebanon</option>
                                                                <option value="Lesotho">Lesotho</option>
                                                                <option value="Liberia">Liberia</option>
                                                                <option value="Libya">Libya</option>
                                                                <option value="Liechtenstein">Liechtenstein</option>
                                                                <option value="Lithuania">Lithuania</option>
                                                                <option value="Luxembourg">Luxembourg</option>
                                                                <option value="Macau">Macau</option>
                                                                <option value="Macedonia">Macedonia</option>
                                                                <option value="Madagascar">Madagascar</option>
                                                                <option value="Malaysia">Malaysia</option>
                                                                <option value="Malawi">Malawi</option>
                                                                <option value="Maldives">Maldives</option>
                                                                <option value="Mali">Mali</option>
                                                                <option value="Malta">Malta</option>
                                                                <option value="Marshall Islands">Marshall Islands</option>
                                                                <option value="Martinique">Martinique</option>
                                                                <option value="Mauritania">Mauritania</option>
                                                                <option value="Mauritius">Mauritius</option>
                                                                <option value="Mayotte">Mayotte</option>
                                                                <option value="Mexico">Mexico</option>
                                                                <option value="Midway Islands">Midway Islands</option>
                                                                <option value="Moldova">Moldova</option>
                                                                <option value="Monaco">Monaco</option>
                                                                <option value="Mongolia">Mongolia</option>
                                                                <option value="Montserrat">Montserrat</option>
                                                                <option value="Morocco">Morocco</option>
                                                                <option value="Mozambique">Mozambique</option>
                                                                <option value="Myanmar">Myanmar</option>
                                                                <option value="Nambia">Nambia</option>
                                                                <option value="Nauru">Nauru</option>
                                                                <option value="Nepal">Nepal</option>
                                                                <option value="Netherland Antilles">Netherland Antilles</option>
                                                                <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                                                <option value="Nevis">Nevis</option>
                                                                <option value="New Caledonia">New Caledonia</option>
                                                                <option value="New Zealand">New Zealand</option>
                                                                <option value="Nicaragua">Nicaragua</option>
                                                                <option value="Niger">Niger</option>
                                                                <option value="Nigeria">Nigeria</option>
                                                                <option value="Niue">Niue</option>
                                                                <option value="Norfolk Island">Norfolk Island</option>
                                                                <option value="Norway">Norway</option>
                                                                <option value="Oman">Oman</option>
                                                                <option value="Pakistan">Pakistan</option>
                                                                <option value="Palau Island">Palau Island</option>
                                                                <option value="Palestine">Palestine</option>
                                                                <option value="Panama">Panama</option>
                                                                <option value="Papua New Guinea">Papua New Guinea</option>
                                                                <option value="Paraguay">Paraguay</option>
                                                                <option value="Peru">Peru</option>
                                                                <option value="Phillipines">Philippines</option>
                                                                <option value="Pitcairn Island">Pitcairn Island</option>
                                                                <option value="Poland">Poland</option>
                                                                <option value="Portugal">Portugal</option>
                                                                <option value="Puerto Rico">Puerto Rico</option>
                                                                <option value="Qatar">Qatar</option>
                                                                <option value="Republic of Montenegro">Republic of Montenegro</option>
                                                                <option value="Republic of Serbia">Republic of Serbia</option>
                                                                <option value="Reunion">Reunion</option>
                                                                <option value="Romania">Romania</option>
                                                                <option value="Russia">Russia</option>
                                                                <option value="Rwanda">Rwanda</option>
                                                                <option value="St Barthelemy">St Barthelemy</option>
                                                                <option value="St Eustatius">St Eustatius</option>
                                                                <option value="St Helena">St Helena</option>
                                                                <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                                                <option value="St Lucia">St Lucia</option>
                                                                <option value="St Maarten">St Maarten</option>
                                                                <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                                                <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                                                <option value="Saipan">Saipan</option>
                                                                <option value="Samoa">Samoa</option>
                                                                <option value="Samoa American">Samoa American</option>
                                                                <option value="San Marino">San Marino</option>
                                                                <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                                                <option value="Saudi Arabia">Saudi Arabia</option>
                                                                <option value="Senegal">Senegal</option>
                                                                <option value="Seychelles">Seychelles</option>
                                                                <option value="Sierra Leone">Sierra Leone</option>
                                                                <option value="Singapore">Singapore</option>
                                                                <option value="Slovakia">Slovakia</option>
                                                                <option value="Slovenia">Slovenia</option>
                                                                <option value="Solomon Islands">Solomon Islands</option>
                                                                <option value="Somalia">Somalia</option>
                                                                <option value="South Africa">South Africa</option>
                                                                <option value="Spain">Spain</option>
                                                                <option value="Sri Lanka">Sri Lanka</option>
                                                                <option value="Sudan">Sudan</option>
                                                                <option value="Suriname">Suriname</option>
                                                                <option value="Swaziland">Swaziland</option>
                                                                <option value="Sweden">Sweden</option>
                                                                <option value="Switzerland">Switzerland</option>
                                                                <option value="Syria">Syria</option>
                                                                <option value="Tahiti">Tahiti</option>
                                                                <option value="Taiwan">Taiwan</option>
                                                                <option value="Tajikistan">Tajikistan</option>
                                                                <option value="Tanzania">Tanzania</option>
                                                                <option value="Thailand">Thailand</option>
                                                                <option value="Togo">Togo</option>
                                                                <option value="Tokelau">Tokelau</option>
                                                                <option value="Tonga">Tonga</option>
                                                                <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                                                <option value="Tunisia">Tunisia</option>
                                                                <option value="Turkey">Turkey</option>
                                                                <option value="Turkmenistan">Turkmenistan</option>
                                                                <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                                                <option value="Tuvalu">Tuvalu</option>
                                                                <option value="Uganda">Uganda</option>
                                                                <option value="United Kingdom">United Kingdom</option>
                                                                <option value="Ukraine">Ukraine</option>
                                                                <option value="United Arab Erimates">United Arab Emirates</option>
                                                                <option value="United States of America" selected id="trigUsSelected">United States of America</option>
                                                                <option value="Uraguay">Uruguay</option>
                                                                <option value="Uzbekistan">Uzbekistan</option>
                                                                <option value="Vanuatu">Vanuatu</option>
                                                                <option value="Vatican City State">Vatican City State</option>
                                                                <option value="Venezuela">Venezuela</option>
                                                                <option value="Vietnam">Vietnam</option>
                                                                <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                                                <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                                                <option value="Wake Island">Wake Island</option>
                                                                <option value="Wallis & Futana Is" class="trigNonUsSelected">Wallis & Futana Is</option>
                                                                <option value="Yemen" class="trigNonUsSelected">Yemen</option>
                                                                <option value="Zaire" class="trigNonUsSelected">Zaire</option>
                                                                <option value="Zambia" class="trigNonUsSelected">Zambia</option>
                                                                <option value="Zimbabwe" class="trigNonUsSelected">Zimbabwe</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-section">
                                                <div class="form-section-title">Bank Information</div>
                                                <div class="form-row">
                                                    <div class="form-group">
                                                        <label for="bank_name_us">Bank Name (For US Bank Only)</label>
                                                        <div class="input-group" id="UsSelected">
                                                            <select name="bank_name" id="bank_name_us" class='form-control' data-live-search="true">
                                                                <option>Select Bank</option>
                                                                <?php foreach ($list_us_banks as $bank) {
                                                                    $us_bank_name = $bank['acquiring_institution'];
                                                                    // Skip the first option
                                                                    if ($us_bank_name !== 'acquiring_institution') {
                                                                ?>
                                                                    <option value="<?= htmlspecialchars($us_bank_name) ?>"><?= htmlspecialchars($us_bank_name) ?></option>
                                                                <?php }
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="bank_name_non_us">Bank Name (Non-US)</label>
                                                        <div class="input-group" id="nonUsSelected">
                                                            <input type="text" class="form-control" id="bank_name_non_us" name="bank_name" placeholder="Bank Name">
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-row">
                                                    <div class="form-group">
                                                        <label for="acct_number">Beneficiary Account No</label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" id="acct_number" name="acct_number" placeholder="Beneficiary Account Number" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="acct_name">Beneficiary Account Name</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="acct_name" name="acct_name" placeholder="Beneficiary Account Name" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-row">
                                                    <div class="form-group">
                                                        <label for="acct_swift">Swift Code</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="acct_swift" name="acct_swift" placeholder="Swift Code" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="acct_routing">Routing Number</label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" id="acct_routing" name="acct_routing" placeholder="Routing Number" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-row">
                                                    <div class="form-group">
                                                        <label for="acct_type">Select Account Type</label>
                                                        <div class="input-group">
                                                            <select name="acct_type" id="acct_type" class='form-control' required>
                                                                <option value="">Select Account Type</option>
                                                                <option value="Savings">Savings Account</option>
                                                                <option value="Current">Current Account</option>
                                                                <option value="Checking">Checking Account</option>
                                                                <option value="Fixed Deposit">Fixed Deposit</option>
                                                                <option value="Non Resident">Non Resident Account</option>
                                                                <option value="Online Banking">Online Banking</option>
                                                                <option value="Domicilary Account">Domicilary Account</option>
                                                                <option value="Joint Account">Joint Account</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="acct_remarks">Narration/Purpose</label>
                                                    <div class="input-group">
                                                        <textarea class="form-control" rows="3" id="acct_remarks" name="acct_remarks" placeholder="Fund Description" style="resize: none"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <button type="submit" class="btn-submit" name="wire_transfer">
                                                <i class="fas fa-paper-plane"></i> Process Transfer
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Transfer Information -->
                                    <div class="transfer-info">
                                        <h3><i class="fas fa-info-circle"></i> Important Information</h3>
                                        <ul>
                                            <li>International wire transfers typically take 3-5 business days to process.</li>
                                            <li>A fee may be charged for this wire transfer.</li>
                                            <li>The recipient's bank may charge additional fees.</li>
                                            <li>For security reasons, first-time international transfers may require additional verification.</li>
                                        </ul>
                                    </div>
                                <?php
                                }else{
                                ?>

                                <div class="transfer-form-container">
                                    <div class="alert custom-alert-1 mb-4 bg-danger border-danger" role="alert">
                                        <div class="media">
                                            <div class="alert-icon">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="alert-text">
                                                    <strong>Warning! </strong><span> You can not Make<span class="text-uppercase"><b>Wire Transfer</b></span> contact support.</span>
                                                </div>
                                                <div class="alert-btn">
                                                    <a class="btn btn-default btn-dismiss" href="mailto:<?=$page['url_email'] ?>">Contact Us</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                                }
                                ?>

<?php
                                }else{
                                ?>

                                <div class="transfer-form-container">
                                    <div class="alert custom-alert-1 mb-4 bg-danger border-danger" role="alert">
                                        <div class="media">
                                            <div class="alert-icon">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="alert-text">
                                                    <strong>Warning! </strong><span> You can not Make<span class="text-uppercase"><b> Transfer</b></span> contact support.</span>
                                                </div>
                                                <div class="alert-btn">
                                                    <a class="btn btn-default btn-dismiss" href="mailto:<?=$page['url_email'] ?>">Contact Us</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                                }
                                ?>


                            <?php
                                }else{
                                ?>


                            <div class="transfer-form-container">
                                <div class="alert custom-alert-1 mb-4 bg-danger border-danger" role="alert">
                                    <div class="media">
                                        <div class="alert-icon">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </div>
                                        <div class="media-body">
                                            <div class="alert-text">
                                                <strong>Warning! </strong><span> Account on <span class="text-uppercase"><b>hold</b></span> contact support.</span>
                                            </div>
                                            <div class="alert-btn">
                                                <a class="btn btn-default btn-dismiss" href="mailto:<?=$page['url_email'] ?>">Contact Us</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                                }
                                ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php
include_once("layouts/footer.php");
?>

<script>
    // Show/hide US and non-US bank fields based on country selection
    document.addEventListener('DOMContentLoaded', function() {
        const countrySelect = document.getElementById('acct_country');
        const usBank = document.getElementById('UsSelected');
        const nonUsBank = document.getElementById('nonUsSelected');
        
        // Initial setup
        if(countrySelect.value === 'United States of America') {
            usBank.style.display = 'block';
            nonUsBank.style.display = 'none';
        } else {
            usBank.style.display = 'none';
            nonUsBank.style.display = 'block';
        }
        
        // On change event
        countrySelect.addEventListener('change', function() {
            if(this.value === 'United States of America') {
                usBank.style.display = 'block';
                nonUsBank.style.display = 'none';
            } else {
                usBank.style.display = 'none';
                nonUsBank.style.display = 'block';
            }
        });
    });
</script>