@extends('merchant_template')

@section('main')
<div class="container container-pad">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 sort nopad mar-bot-20">
<h2 class="find-head">
<b>{{ trans('messages.header.settings') }}</b>
</h2>
</div>
<div class="csv col-lg-12 col-md-12 col-sm-12 col-xs-12 nopad">
@include('common.settings_subheader_logged')
<div  class="col-lg-9 col-md-9 col-sm-9 col-xs-12 nopad right-sett">
<div class="back-white csv_right col-md-12 nopad getting_paid">
<h3 class="csv_tit">Getting Paid
<div class="history"><a href="/merchant/settings/payment/transfers">View Payout History</a></div>
</h3>
<div class="notice disabled col-md-12 ">
        <p><b>Verification Status:</b> Unverified - <em>TRANSFER DISABLED</em></p>
        <ul>
            
            <li><span class="dot">•</span> Transfer may be blocked if your account is not verified.</li>
            <li><span class="dot">•</span> Please enter following fields &ndash; bank account, date of birth, first name, last name</li>
            
        </ul>
    </div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopad bor-bot-ash " style="padding-bottom:25px !important;margin-bottom:15px;padding-top: 43px !important;overflow: unset;">
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5">
<h2 class="stit">Account</h2>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 ">
<div class="set-paid">
             <p class="col-md-6 p-l-0"><label class="label" for="stripe_country">Country <span class="tooltip-custom"><i class="icon"></i><em>The country the account holder resides in or that the business is legally established in.</em></span></label>
                    <span class="readonly">USA</span>
                    </p>
                    <p class="col-md-6 nopad"><label class="label" for="stripe_type">Account type</label>
                    <select name="stripe_type">
                        
                        <option value="individual" selected="">Individual</option>
                        <option value="company">Business</option>
                        
                        
                    </select></p>
                    <p class="clear col-md-6 p-l-0"><label class="label" for="minimum_payout">Minimum Payout Amount (USD) <span class="tooltip-custom"><i class="icon"></i><em>Once set, we will hold your funds until you reach a payout amount over the minimum you set. Use it to avoid any transaction fee from your bank. This field is optional.</em></span></label>
                    <input class="text" name="minimum_payout" value="" placeholder="0.00" type="text"></p>
                    <p class="col-md-6 nopad"></p>
               
           
            

</div>

</div>

</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopad bor-bot-ash " style="padding-bottom:25px !important;margin-bottom:15px;padding-top: 43px !important;overflow: unset;">
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5">
<h2 class="stit">Bank information</h2>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 ">
<div class="set-paid">
             <p class="bank-account col-md-12 nopad"><label for="bank_account" class="label">Selected bank account</label>
                    <select name="bank_account">
                        
                        <option value="">Add new bank account...</option>
                    </select></p>
                    <p class="col-md-6 p-l-0"><label for="bank_country" class="label">Bank country</label>
                    <select name="bank_country" data-default-country="US">
                        
                        <option value="US" selected="">USA</option>
                        
                    </select></p>
                    <p class="col-md-6 nopad"><label for="bank_currency" class="label">Currency</label>
                    <select name="bank_currency" data-default-currency="usd"><option value="usd" data-country="US">USD - U.S. dollars</option></select></p>
                    <p class="col-md-6 p-l-0">
                    <label for="bank_routing" class="label"><span>Routing Number</span> <span class="tooltip-custom"><i class="icon"></i><em>ABA routing number or SWIFT code of your bank.</em></span></label>
                    <input class="text" name="bank_routing" placeholder="111000000" type="text"></p>
                    <p class="col-md-6 nopad"><label for="bank_number" class="label">Account Number</label>
                    <input class="text" name="bank_number" placeholder="" type="text"></p>
             
               
           
            

</div>

</div>

</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopad bor-bot-ash " style="padding-bottom:25px !important;margin-bottom:15px;padding-top: 43px !important;overflow: unset;">
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5">
<h2 class="stit">Personal information</h2>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 ">
<div class="set-paid">
               <p class="col-md-6 p-l-0"><label for="first_name" class="label">First name</label>
                    <input class="text" name="first_name" value="" type="text"></p>
                    <p class="col-md-6 nopad"><label for="last_name" class="label">Last name</label>
                    <input class="text" name="last_name" value="" type="text"></p>
                    
                    <p class="col-md-6 p-l-0"><label for="dob" class="label">Date of birth</label>
                    <input class="text birthday hasDatepicker" placeholder="yyyy-mm-dd" name="dob" id="dp1484127311894" type="text"></p>
                    
                    <p class="col-md-6 nopad"><label for="ssn_last_4" class="label">Last 4 digits of SSN</label>
                    <input class="text" name="ssn_last_4" value="" type="text"></p>
                    
             
               
           
            

</div>

</div>

</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopad  " style="padding-bottom:25px !important;margin-bottom:15px;padding-top: 43px !important;overflow: unset;">
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5">
<h2 class="stit">Address</h2>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 ">
<div class="set-paid">
                 <p class="col-md-6 p-l-0"><label for="line1" class="label">Address line 1</label>
                    <input class="text" name="line1" value="" type="text"></p>
                    <p class="col-md-6 nopad"><label for="line2" class="label">Address line 2 <small>Optional</small></label>
                    <input class="text" name="line2" value="" type="text"></p>
                    <p class="col-md-6 p-l-0"><label for="city" class="label">City</label>
                    <input class="text" name="city" value="" type="text"></p>
                    <p class="col-md-6 nopad"><label for="postal_code" class="label">Postal code</label>
                    <input class="text" name="postal_code" value="" type="text"></p>
                    <p class="col-md-6 p-l-0"><label for="country" class="label">Country</label>
                    <select name="country" class="country">
                        
                        <option value="AX">Aaland Islands</option>
                        
                        <option value="AF">Afghanistan</option>
                        
                        <option value="AL">Albania</option>
                        
                      
                        
                    </select></p>
                    <p class="col-md-6 nopad"><label for="state" class="label">State</label>
                    <select class="state" name="state">
                        <option value="">Select State</option>
                        
                        <option value="AL">Alabama</option>
                        
                        <option value="AK">Alaska</option>
                        
                        <option value="AS">American Samoa</option>
                        
                        <option value="AZ">Arizona</option>
                        
                        <option value="AR">Arkansas</option>
                        
                        
                        
                        <option value="WY">Wyoming</option>
                        
                    </select>                                
                   
                    </p>
             
               
           
            

</div>

</div>

</div>
<div class="btn-area">
<p class="comments">
            
                By submitting, you agree to the <a href="https://stripe.com/connect/account-terms" target="_blank">Stripe Connected Account Agreement</a>.<br>
            
                We may request more information to verify your account in the future.
            </p>
        <button class="blue-btn " type="button">Save Changes</button>
    </div>
</div>
</div>
<div id="popup_container" class="" style="display: none; opacity: 0; top: 0px;">

<div id="content-pop" class="popup content-pop" style="margin-top: 5%; margin-left: 20%; display: none;"><p class="ltit">Import Products</p>
    <dl class="docs">
        <dt>You can upload a CSV file to import products. Download and view a sample product CSV file. You can use this as a template for creating your CSV file. Just remember to remove the example products.</dt>
        <dd>
            <div class="table">
                <table class="tb-type2 head">
                    <colgroup>
                        <col width="370">
                        <col width="136">
                        <col width="*">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Requirement</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                </table>
                <div class="scroll"><table class="tb-type2">
                    <colgroup>
                        <col width="370">
                        <col width="136">
                        <col width="*">
                    </colgroup>
                    <tbody>
                        <tr>
                            <td class="para"><code>title</code></td>
                            <td class="require">Required</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="para"><code>description</code></td>
                            <td class="require">Required</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="para"><code>quantity</code></td>
                            <td class="require"></td>
                            <td>Default: Unlimited</td>
                        </tr>
                        <tr>
                            <td class="para"><code>price</code></td>
                            <td class="require">Required</td>
                            <td>in USD</td>
                        </tr>
                        <tr>
                            <td class="para"><code>retail_price</code></td>
                            <td class="require">Optional</td>
                            <td>in USD</td>
                        </tr>
                        <tr>
                            <td class="para"><code>image1_url</code>,<code>image2_url</code>,<code>image3_url</code></td>
                            <td class="require">Required</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="para"><code>categories</code></td>
                            <td class="require">Required</td>
                            <td>Separated by vertical bar "|"<br>
                                <span class="tooltip">View categories</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="para"><code>option_name1</code>,<code>option_quantity1</code>,<code>option_price1</code><br>
                            <code>option_name2</code>,<code>option_quantity2</code>,<code>option_price2</code><br>
                            <code>option_name3</code>,<code>option_quantity3</code>,<code>option_price3</code><br>
                            <code>option_name4</code>,<code>option_quantity4</code>,<code>option_price4</code><br>
                            <code>option_name5</code>,<code>option_quantity5</code>,<code>option_price5</code></td>
                            <td class="require"></td>
                            <td>Options are optional. Name and quantity must have value, or it will be ignored. Price can be omitted and it will use the item price.</td>
                        </tr>
                        <tr>
                            <td class="para"><code>charge_shipping</code></td>
                            <td class="require">True / False</td>
                            <td>Charge domestic shipping fee (default: true)</td>
                        </tr>
                        <tr>
                            <td class="para"><code>charge_international_shipping</code></td>
                            <td class="require">True / False</td>
                            <td>Charge international shipping fee (default: true)</td>
                        </tr>
                        <tr>
                            <td class="para"><code>international_shipping</code></td>
                            <td class="require">True / False</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="para"><code>us_window_start</code></td>
                            <td class="require">Required</td>
                            <td>Domestic shipping minimum window</td>
                        </tr>
                        <tr>
                            <td class="para"><code>us_window_end</code></td>
                            <td class="require">Required</td>
                            <td>Domestic shipping maximum window</td>
                        </tr>
                        <tr>
                            <td class="para"><code>intl_start</code></td>
                            <td class="require">Required</td>
                            <td>International shipping minimum window</td>
                        </tr>
                        <tr>
                            <td class="para"><code>intl_end</code></td>
                            <td class="require">Required</td>
                            <td>International shipping maximum window</td>
                        </tr>
                        
                        <tr>
                            <td class="para"><code>sale_start_date</code></td>
                            <td class="require">Optional</td>
                            <td>YYYY-MM-DD HH:MM:SS<br>Default is upload time.</td>
                        </tr>
                        <tr>
                            <td class="para"><code>prod_colors</code></td>
                            <td class="require">Optional</td>
                            <td>Colors separated by comma</td>
                        </tr>
                        <tr>
                            <td class="para"><code>prod_country_of_origin</code></td>
                            <td class="require">Optional</td>
                            <td>Country of origin (2-letter country code)</td>
                        </tr>
                        <tr>
                            <td class="para"><code>prod_length</code></td>
                            <td class="require">Optional</td>
                            <td>Length of the product in inch</td>
                        </tr>
                        <tr>
                            <td class="para"><code>prod_height</code></td>
                            <td class="require">Optional</td>
                            <td>Height of the product in inch</td>
                        </tr>
                        <tr>
                            <td class="para"><code>prod_width</code></td>
                            <td class="require">Optional</td>
                            <td>Width of the product in inch</td>
                        </tr>
                        <tr>
                            <td class="para"><code>prod_weight</code></td>
                            <td class="require">Optional</td>
                            <td>Weight of the product in pound</td>
                        </tr>
                        <tr>
                            <td class="para"><code>seller_sku</code></td>
                            <td class="require">Optional</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="para"><code>is_active</code></td>
                            <td class="require">True / False</td>
                            <td>Default: Activated</td>
                        </tr>
                    </tbody>
                </table></div>
                <span class="tooltip trick" style="top: 281.133px; left: 516px;"><small>View category IDs</small><em>
                    1 - Men<br>
                    2 - Women<br>
                    3 - Kids<br>
                    4 - Pets<br>
                    5 - Home<br>
                    6 - Gadgets<br>
                    7 - Art<br>
                    8 - Food<br>
                    9 - Media<br>
                    10 - Other<br>
                    11 - Architecture<br>
                    12 - Travel &amp; Destinations<br>
                    13 - Sports &amp; Outdoors<br>
                    14 - DIY &amp; Crafts<br>
                    15 - Workspace<br>
                    16 - Cars &amp; Vehicles
                </em></span>
            </div>
        </dd>
    </dl>
    <button class="ly-close"><i class="ic-del-black"></i></button>
</div>

        <div class="popup download_sellapp">
                <div class="inner"><h3>Sell on the go <small>New</small></h3>
                <p>Never miss a sale with the {{ $site_name }} Seller app.<br>
                Remotely manage inventory, customers and orders.<br>
                <a href="/about/merchants/mobile" target="_blank">Learn more about the {{ $site_name }} Seller app</a></p>
                <p class="download">
                <a href="https://itunes.apple.com/us/app/Spiffy-seller/id961870454?ls=1&amp;mt=8" class="btn-ios" target="_blank">Download on the App Store</a><br>
                Or, <a href="#" onclick="$.dialog('send_app').open();return false;">send a link</a> to your iPhone to download the app.</p></div>
        </div>
        <div class="popup send_app">
        <p class="tit">Send App Link</p>
        <fieldset class="frm">
            <p>Get the {{ $site_name }} Seller app sent to your phone.</p>
            <p class="country"><select name="country_code" class="select-country">
                          <option value="AX">Aaland Islands</option>
                          <option value="AF">Afghanistan</option>
                          <option value="AL">Albania</option>
                          <option value="DZ">Algeria</option>
                         
            </select></p>
            <p class="phone"><input class="text input-phone" placeholder="Enter your Phone number" type="text"></p>
            <button class="btn-green btn-send">Send</button>
        </fieldset>
        <p class="notify">Data rates may apply. We will <b>not</b> store your number.</p>
    </div>
    
</div>
</div>
</main>
@stop