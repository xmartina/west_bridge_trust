<?php include 'layouts/header.php'; ?>
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

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <h1>Wire Transfer</h1>
                <div class="search-notification">
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search">
                    </div>
                    <div class="user-profile">
                        <div class="notification">
                            <i class="far fa-bell"></i>
                        </div>
                        <div class="avatar">
                            <img src="https://via.placeholder.com/40" alt="User avatar">
                        </div>
                        <div class="user-name">Sanjoy_R</div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Wire Transfer Steps -->
                <div class="wire-transfer-steps">
                    <div class="step completed">
                        <div class="step-number">1</div>
                        <div class="step-label">Sender Details</div>
                    </div>
                    <div class="step active">
                        <div class="step-number">2</div>
                        <div class="step-label">Recipient Details</div>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-label">Transfer Details</div>
                    </div>
                    <div class="step">
                        <div class="step-number">4</div>
                        <div class="step-label">Review & Confirm</div>
                    </div>
                </div>

                <!-- Transfer Form -->
                <div class="transfer-form-container">
                    <div class="form-title">
                        <i class="fas fa-wifi"></i>
                        International Wire Transfer
                    </div>
                    
                    <div class="form-section">
                        <div class="form-section-title">Recipient Information</div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="recipient-name">Recipient Full Name</label>
                                <input type="text" id="recipient-name" placeholder="Enter recipient's full name">
                            </div>
                            <div class="form-group">
                                <label for="recipient-email">Recipient Email (Optional)</label>
                                <input type="email" id="recipient-email" placeholder="Enter recipient's email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="recipient-address">Recipient Address</label>
                            <input type="text" id="recipient-address" placeholder="Enter recipient's address">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="recipient-city">City</label>
                                <input type="text" id="recipient-city" placeholder="City">
                            </div>
                            <div class="form-group">
                                <label for="recipient-country">Country</label>
                                <select id="recipient-country">
                                    <option value="">Select a country</option>
                                    <option value="uk">United Kingdom</option>
                                    <option value="fr">France</option>
                                    <option value="de">Germany</option>
                                    <option value="jp">Japan</option>
                                    <option value="ca">Canada</option>
                                    <option value="au">Australia</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <div class="form-section-title">Bank Information</div>
                        <div class="form-group">
                            <label for="bank-name">Bank Name</label>
                            <input type="text" id="bank-name" placeholder="Enter bank name">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="swift-code">SWIFT/BIC Code</label>
                                <input type="text" id="swift-code" placeholder="Enter SWIFT/BIC code">
                            </div>
                            <div class="form-group">
                                <label for="account-number">Account Number / IBAN</label>
                                <input type="text" id="account-number" placeholder="Enter account number or IBAN">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bank-address">Bank Address</label>
                            <input type="text" id="bank-address" placeholder="Enter bank address">
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <div class="form-section-title">Transfer Amount</div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="text" id="amount" placeholder="Enter amount">
                            </div>
                            <div class="form-group">
                                <label for="currency">Currency</label>
                                <select id="currency">
                                    <option value="usd">USD - US Dollar</option>
                                    <option value="eur">EUR - Euro</option>
                                    <option value="gbp">GBP - British Pound</option>
                                    <option value="jpy">JPY - Japanese Yen</option>
                                    <option value="cad">CAD - Canadian Dollar</option>
                                    <option value="aud">AUD - Australian Dollar</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="currency-info">
                            <div class="currency-flag">€</div>
                            <div class="currency-details">
                                <div class="currency-name">EUR - Euro</div>
                                <div class="currency-rate">1 USD = 0.92 EUR</div>
                            </div>
                            <div class="currency-amount">€920.00</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="transfer-purpose">Purpose of Transfer</label>
                            <select id="transfer-purpose">
                                <option value="personal">Personal Transfer</option>
                                <option value="business">Business Payment</option>
                                <option value="gift">Gift</option>
                                <option value="bills">Bill Payment</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">Description / Note</label>
                            <textarea id="description" placeholder="Add a note for the recipient (optional)"></textarea>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">Continue to Review</button>
                </div>

                <!-- Transfer Information -->
                <div class="transfer-info">
                    <h3><i class="fas fa-info-circle"></i> Important Information</h3>
                    <ul>
                        <li>International wire transfers typically take 3-5 business days to process.</li>
                        <li>A fee of $25 will be charged for this wire transfer.</li>
                        <li>The recipient's bank may charge additional fees.</li>
                        <li>Exchange rates are subject to change and are updated every hour.</li>
                        <li>For security reasons, first-time international transfers may require additional verification.</li>
                    </ul>
                </div>
            </div>
        </main>

        <!-- Right Sidebar -->
        <aside class="right-sidebar">
            <div class="balance-section">
                <h3>Balance</h3>
                <div class="balance-amount">$23,240,000</div>
                <div class="balance-actions">
                    <button class="btn-send">Send</button>
                    <button class="btn-withdraw">Withdraw</button>
                </div>
            </div>

            <div class="cards-section">
                <div class="section-header">
                    <h3>Recent Wire Transfers</h3>
                    <button class="more-options"><i class="fas fa-ellipsis-v"></i></button>
                </div>
                
                <div class="message-list">
                    <div class="message-item">
                        <div class="message-avatar">
                            <div style="width: 30px; height: 30px; background-color: #104042; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">UK</div>
                        </div>
                        <div class="message-content">
                            <div class="message-sender">HSBC Bank - London</div>
                            <div class="message-preview">$5,000.00 - Completed</div>
                            <div class="message-time">May 20, 2023</div>
                        </div>
                    </div>
                    <div class="message-item">
                        <div class="message-avatar">
                            <div style="width: 30px; height: 30px; background-color: #104042; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">FR</div>
                        </div>
                        <div class="message-content">
                            <div class="message-sender">BNP Paribas - Paris</div>
                            <div class="message-preview">$3,500.00 - Completed</div>
                            <div class="message-time">April 15, 2023</div>
                        </div>
                    </div>
                    <div class="message-item">
                        <div class="message-avatar">
                            <div style="width: 30px; height: 30px; background-color: #104042; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">DE</div>
                        </div>
                        <div class="message-content">
                            <div class="message-sender">Deutsche Bank - Berlin</div>
                            <div class="message-preview">$7,200.00 - Completed</div>
                            <div class="message-time">March 28, 2023</div>
                        </div>
                    </div>
                </div>
                
                <div class="view-all-transfers">
                    <button class="btn-view-all">View All Wire Transfers</button>
                </div>
            </div>

            <div class="transfer-limits">
                <div class="section-header">
                    <h3>Wire Transfer Fees</h3>
                </div>
                <div style="padding: 15px; background-color: rgba(16, 64, 66, 0.02); border-radius: 8px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: #104042; font-weight: 500;">Transfer Amount</span>
                        <span style="color: #104042; font-weight: 600;">$1,000.00</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: #104042; font-weight: 500;">Wire Transfer Fee</span>
                        <span style="color: #104042; font-weight: 600;">$25.00</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-top: 1px solid rgba(16, 64, 66, 0.1); padding-top: 10px; margin-top: 10px;">
                        <span style="color: #104042; font-weight: 600;">Total Amount</span>
                        <span style="color: #104042; font-weight: 700;">$1,025.00</span>
                    </div>
                </div>
                <div style="font-size: 12px; color: rgba(16, 64, 66, 0.7);">
                    * Recipient bank may charge additional fees
                </div>
            </div>
        </aside>
    </div>

   <?php include 'layouts/footer.php'; ?>