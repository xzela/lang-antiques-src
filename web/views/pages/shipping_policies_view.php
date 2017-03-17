<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>Payment and Shipping Policies - Lang Antiques</title>
    <?php echo snappy_script('browser.selector.js');?>
    <?php //global style sheets, browser independent; ?>
    <?php echo snappy_style('global.styles.css'); ?>
    <?php echo snappy_style('global.round.css'); ?>


    <?php echo snappy_style('firefox.main.css'); ?>
    <?php echo snappy_style('firefox.item.css'); ?>

    <meta name='keywords' content='' />
    <meta name='description' content='Shipping Statements for online and phone orders for Estate Jewelry at Lang Antiques' />

</head>
<body>
    <div id="container" >
        <span class="rtop">
            <b class="r1"></b>
            <b class="r2"></b>
            <b class="r3"></b>
            <b class="r4"></b>
        </span>
    <?php
        $this->load->view('components/header_view');
        $this->load->view('components/menu_view');
    ?>
        <div id="content">
            <div class="breadcrumb">
                <?php echo anchor('/', 'Home'); ?> &gt; Payment and Shipping Policies
            </div>
            <h2 id='top_h2'>Shipping Policy</h2>
            <p>
                Purchases over $2,500 include overnight shipping with insurance (Orders placed Friday through Sunday will be shipped on the following Monday for Tuesday delivery).
            </p>
            <h3>Shipping Charges (Including Insurance):</h3>
            <p>
                Purchases over $2,500 include overnight shipping with insurance (Orders placed Friday through Sunday will be shipped on the following Monday for Tuesday delivery).
                <br />
                FedEx Registered Mail (usually 2 - 5 days, not guaranteed)
            </p>
                <ul>
                    <li>Up to $ 1,000 - $25.</li>
                    <li>$1,001 to $1,500 - $30.</li>
                    <li>$1,501 to $2,000 - $35.</li>
                    <li>$2,001 to $2,499 - $40.</li>
                </ul>
                Overnight FedEx
                <ul>
                    <li>Up to $ 2,500 - $60.</li>
                    <li>$2,501 to $4,999 - $75.</li>
                    <li>$5,000 to $9,999 - $90.</li>
                    <li>$10,000 to $14,999 - $105.</li>
                    <li>$15,000 to $19,999 - $120.</li>
                    <li>$20,000 to $25,000 - $135.</li>
                </ul>

            <h3>International</h3>
            <p>
                Shipping charges apply to all international purchases.
                Price will be quoted based on value, destination and location availability by carrier.
                Not all locations have shipping available.
            </p>
            <h2 id='top_h2'>Return Policy</h2>
            <p>
                <strong>Internet Purchases</strong>: You have 10 days to return your purchase for any reason.
                All sales are final after 10 days.
                You <strong>MUST</strong> contact us immediately once you receive your package to notify us of any problem regarding the condition of the piece, such as damage or extreme wear.
                If you have not contacted us within this short time period we will assume that any damage upon return was caused by you.
                We take the utmost care to package our shipments and make sure that our jewelry is in the best condition possible.
                Exchange for store credit only within 30 days.
                Shipping and sizing are non refundable.
            </p>
            <p>
                <strong>In Store Purchases</strong>:
                No Refunds. Exchange for store credit only within 30 days.
                No refund for jewelry which has been worn, altered or damaged.
            </p>
            <p>
                <strong>Layaways</strong>:
                All sales final.
                Final payment is due no later than 90 days from the date of the initial down payment.
            </p>
            <h3>Instructions for Return:</h3>
            <p>
                You have 10 days from the time your package arrives to return your item.
                All returned jewelry items must be in the same condition as was sent to you.
                No jewelry will be accepted for return if it is damaged, altered or worn.
                Please contact us at 415-982-2213 or 1-800-924-2213 to arrange for your return.
                You must ship the package back to us registered insured through USPS, fully insured for the retail value, and pack the items safely and securely.
                Once the item is received by us, we will check the condition of the item and issue a refund, less any shipping or sizing charges, within 3 days of receipt.
                If you have any questions regarding these procedures please contact us at the numbers provided.
            </p>
            <h2 id='top_h2'>Payment and Shipping Policies</h2>
            <p>Here are our policies about payments and shipping:</p>
            <!--
            <p style="color:red;"><strong>HOLIDAY SHIPPING DEADLINES:</strong>
            </p>
            <p style="color:green;"><strong>Overnight air shipments: December 23rd
            LAST DAY TO SHIP</strong></p>
            <P style="color:blue;"><strong>2nd day air shipments: December 21st
            LAST DAY TO SHIP</strong></P>
            -->
          <h3>Domestic Payment Policy</h3>
            <p>
                We accept most major credit cards:
                <?php echo snappy_image('vendor_logos/amex_logo.jpg', 'American Express'); ?>
                <?php echo snappy_image('vendor_logos/discover_card_logo.jpg', 'Discover Card'); ?>
                <?php echo snappy_image('vendor_logos/master_card_logo.jpg', 'Master Card'); ?>
                <?php echo snappy_image('vendor_logos/visa_logo.jpg', 'VISA'); ?>
                <br />
                Credit cards require us to ship to your billing address.
                We verify your address before shipping.
                All credit card payments are processed manually so you can call us on our toll free number (800-924-2213) during our business hours (9:30 - 5:30 pst) to make your purchase.
                We also accept wire transfers.
                Once a wire transfer has been received we can ship the item to any address.
            </p>
            <h3>International Payment Policy:</h3>
            <p>We accept wire transfers for international payments. Please call or email to make arrangements.</p>

            <h3>Internet Purchases</h3>
            <p>
                You have 10 days to return your purchase for any reason.
                All sales are final after 10 days.
                You <strong>MUST</strong> contact us immediately once you receive your package to notify us of any problem regarding the condition of the piece, such as damage or extreme wear.
                If you have not contacted us within this short time period we will assume that any damage upon return was caused by you.
                We take the utmost care to package our shipments and make sure that our jewelry is in the best condition possible.
                Exchange for store credit only within 30 days.
                Shipping and sizing are non refundable.
            </p>

            <h3>Disclaimer</h3>
            <p>
                Unless otherwise stated, all gemstones are graded and evaluated in their mountings to the maximum extent that the mounting permits examination.
                Approximate weights are formulated by taking measurements and applying acceptable formulas; as such they are estimates only.
                Keen determination of color, clarity and proportions may be prevented by certain types of mountings, small fancy, round, full and single, and baguette cut diamonds (melee) are evaluated according to their approximate weights and average quality grades using Gemological Institute of America (GIA) grading scale.
            </p>
            <p>
                Major diamonds are graded with the use of pre-graded permanent master diamonds color comparison stones and the grading nomenclature prescribed by the GIA.
                Colored Gemstones are graded using the GIA prescribed colored stones grading system and nomenclature.
                Major colored stones are described using Gem Dialogue color grading system and/or GemeWizard.
            </p>
            <p>
                Because mountings prohibit full and accurate observation of gem quality and weight, all data pertaining to mounted gems can be considered as approximate unless accompanied by an independent laboratory certificate (AGL, EGL, GIA).
                Unless otherwise stated, all color gemstones and pearls are assumed to be subject to a relatively stable and possibly undetected color or clarity enhancement.
            </p>
            <p>
                Authenticity, antiquity, decorative periods, vintages and or circa dating are determined to the best of our ability based on materials incorporated, methods of manufacture, design, hallmarks and signatures, lapidary techniques, technological developments and consultation of our extensive reference library and materials.
                Dating and period attributions are not an exact science as decorative periods frequently overlap and borrow influences from one another.
            </p>
            <h2 id='top_h2'>Privacy Policy for Lang Antiques</h2>
            <p>
                We want our users to always be aware of any information we collect, how we use it, and under what circumstances, if any, we disclose it.
            </p>
            <h3>Website Administration</h3>
            <table>
                <tr>
                    <td>Business name:</td>
                    <td>Lang Antiques</td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td>323 Sutter Street</td>
                </tr>
                <tr>
                    <td>City State Zip:</td>
                    <td>San Francisco, CA 94108</td>
                </tr>
                <tr>
                    <td>Phone:</td>
                    <td>(415) 982-2213</td>
                </tr>
                <tr>
                    <td>Fax:</td>
                    <td>(415) 986-8855</td>
                </tr>
                <tr>
                    <td>Website URL:</td>
                    <td><a href="http://www.langantiques.com">http://www.langantiques.com</a></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><a href="mailto:info@langantiques.com">info@langantiques.com</a></td>
                </tr>
            </table>

            <h3>Information Collection and Use</h3>
            <p>
                We respect each site visitor's right to personal privacy.
                To that end, we collect and use information throughout our website only as disclosed in this Privacy Policy.
                This statement applies solely to information collected on this website.
            </p>
            <p>
                For each visitor to our website, our web server automatically recognizes only the visitor's domain name: not the e-mail address.
            </p>

            <h3>Information We Collect</h3>
            <p>
                Your information is never shared with other organizations for commercial purposes.
                We only use your information internally for contacting you about your orders.
            </p>
            <p>
                We do use and collects cookies.
                Please see <a href="http://en.wikipedia.org/wiki/HTTP_cookie">Definition of Cookies</a> for more information.
            </p>
            <p>
                In order to use this website, visitors and/or members are not required to complete any form or give us any information.
                All forms are optional.
                If you wish to order something from our website you may contact us via mail or over the phone.
            </p>
            <p>
                During online ordering a user must give certain contact information, we never sell or give away this information.
                It is only gathered to contact the user about products on our site for which s/he has expressed interest.
                Giving additional information helps provide a more personalized experience on our site, but is not required.
            </p>
            <p>
                We require information from the user on our online order form.
            </p>
            <p>
                A user must provide contact information (such as name, email, and shipping address) and possibly financial information (such as credit card number, expiration date).
                If we have trouble processing an order, we use the information to contact the user.
            </p>
            <p>
                This information is used exclusively to fill customer's orders.
            </p>

            <h3>Security</h3>
            <p>
                We currently and will always use industry-standard encryption technologies when transferring and receiving consumer data.
                We have appropriate security measures in place in our physical facilities to protect your data against being lost, misused, or altered.
                All ficancial information is permanently removed from our databases after each transaction.
            </p>

            <h3>Notification of Changes </h3>
            <p>If we decide to change our privacy policy, we will post those changes here, on this <a href='#privacy'>Privacy Policy</a> page.</p>

            <h3>Conflict Resolution </h3>
            <p>
                We are committed to resolving disputes within 24-48 hours.
                If a problem arises with an order, or any other transaction, users may contact us via phone, email or mail and we will respond within 24 hours.
                <br />
                <br />
                Disputes which cannot be settled directly between us, whether mediated, arbitrated or argued in a court of law, shall be conducted in the State of California.
            </p>

            <h3>Contact Information</h3>
            <p>If you have any questions or suggestions regarding our privacy policy, please contact us via phone, email, or mail</p>
            <p>
                Phone: 415.982.2213<br />
                Fax: 415.986.8855<br />
                Email: <a href="mailto:info@langantiques.com">info@langantiques.com</a>
            </p>
            <p>Mailing Address:</p>
            <p>
                Lang Antiques<br />
                323 Sutter Street<br />
                San Francisco, CA 94108
            </p>
            <p>Website URL:<a href="http://www.langantiques.com">http://www.langantiques.com</a></p>

            <h3><a name='privacy' >Privacy Affiliates</a></h3>
            <p>To further protect your privacy and ensure that we have kept our promises to you, this policy has been registered with Privacy Affiliates (a third party privacy advocate).</p>
            <p>
                Privacy Affiliates <br />
                318-19567 Fraser Highway<br />
                Surrey, British Columbia V3S 9A4 Canada <br />
                Email: <a href="mailto:support@privacyaffiliates.com">support@privacyaffiliates.com</a><br />
                Phone:   604-644-7024
            </p>

            <p>Privacy Statement has been validated by <a href="http://www.privacyaffiliates.com">PrivacyAffiliates.com</a></p>
            <p><img alt="Certified Member PrivacyAffiliates.com" src="http://www.privacyaffiliates.com/images/privacyaffiliates.gif" /></p>

        </div>
    <?php $this->load->view('components/footer_view.php'); ?>
        <span class="rbottom">
            <b class="r4"></b>
            <b class="r3"></b>
            <b class="r2"></b>
            <b class="r1"></b>
        </span>
    </div>
</body>
</html>
<?php
ob_flush();
?>
