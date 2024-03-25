<?php
  $company_name = get_field('company_name','option');
  $company_address = get_field('company_address','option');
  $company_phone = get_field('company_phone','option');
?>

<div class="container">
  <div class="row">

    {{-- Left Side - Unit details --}}
    <div class="col-12 col-lg-3 col-xl-4 mb-4">
      <div class="bordered-box background-lightest-gray p-3 p-xl-4 border-radius-8px">
        @if($company_name)
          <h2 class="h4 mb-1 font-weight-700">
            {!! $company_name !!}
          </h2>
        @endif

        @if($company_address)
          <h3 class="h6 font-weight-400">
            {!! $company_address !!}
          </h3>
        @endif

        <div class="text-size-tiny-14 text-uppercase font-weight-700 border-top pt-3 pt-xl-4 mt-3 mt-xl-4">
          Selected Unit
        </div>

        <div class="d-flex align-items-start justify-content-between">
          <div class="pr-3">
            <h4 class="text-size-medium-32 font-weight-700 mb-1">
              6x6x7
            </h4>
            <div class="font-weight-400 text-size-small-16 text-color-grey line-height-1-2">
              Indoor, Climate Controlled
            </div>
          </div>

          <div class="flex-grow-0 flex-shrink-0">
            <h4 class="text-size-medium-24 font-weight-700 text-color-red">
              $124
            </h4>
          </div>
        </div>
      </div>
    </div>
    {{-- Left Side end --}}


    {{-- Right Side - Booking Process --}}
    <div class="col-12 col-lg-9 col-xl-8 mb-4">
      <div class="bordered-box p-3 p-xl-4 border-radius-8px">

        {{-- Form --}}
        <form>
          <div class="row">

            {{-- Reservation info --}}
            <div class="col-12 mb-4">
              <h3>
                Reservation Information
              </h3>
            </div>

            <div class="col-12 col-md-6 mb-4">
              <label for="First Name">First Name</label>
              <input type="text" class="form-control" id="FirstName" aria-describedby="FirstName" placeholder="First Name">
            </div>

            <div class="col-12 col-md-6 mb-4">
              <label for="Last Name">Last Name</label>
              <input type="text" class="form-control" id="LastName" aria-describedby="LastName" placeholder="Last Name">
            </div>

            <div class="col-12 col-md-6 mb-4">
              <label for="Email Address">Email Address</label>
              <input type="email" class="form-control" id="Email" aria-describedby="Email" placeholder="Email Address">
            </div>

            <div class="col-12 col-md-6 mb-4">
              <label for="Phone Number">Phone Number</label>
              <input type="tel" class="form-control" id="Phone" aria-describedby="Phone" placeholder="Phone Number">
            </div>

            <div class="col-12 col-md-6 mb-4">
              <label for="Move In Date">Expected move in date</label>
              <select class="form-control" id="Expected Move In Date">
                <option value="">Please Select</option>
                <option value="2024-03-22">Friday, March 22, 2024</option>
                <option value="2024-03-22">Friday, March 22, 2024</option>
                <option value="2024-03-22">Friday, March 22, 2024</option>
                <option value="2024-03-22">Friday, March 22, 2024</option>
                <option value="2024-03-22">Friday, March 22, 2024</option>
                <option value="2024-03-22">Friday, March 22, 2024</option>
                <option value="2024-03-22">Friday, March 22, 2024</option>
              </select>
            </div>

            <div class="col-12 col-md-6 mb-4">
              <label for="Move In Date">How long do you plan to store?</label>
              <select class="form-control" id="Expected Move In Date">
                <option value="">Please Select</option>
                <option value="1">1 Month or Less</option>
                <option value="2">2 Months</option>
                <option value="3">3 Months</option>
                <option value="4">4-6 Months</option>
                <option value="5">7-11 Months</option>
                <option value="6">12+ Months</option>
                <option value="7">Not Sure</option>
              </select>
            </div>
            {{-- Reservation Info end --}}


            {{-- Protection Plan --}}
            <div class="col-12 my-4">
              <h3>
                Protection Plan
              </h3>
            </div>

            <div class="col-12 mb-4">
              <p>
                SecureSpace does not provide any type of insurance to protect your belongings and is not responsible for the stored items in your unit. Having insurance for your personal property is a requirement of the lease agreement.
              </p>

              <p>
                You may purchase a tenant protection plan by SecureLease (see below) to satisfy this requirement, however, you are not required to purchase this plan. If you have homeowner’s or renter’s insurance that covers storage, you may bring proof of this policy to our local store manager instead.
              </p>

              <div class="background-lightest-gray border-radius-8px text-size-tiny-14 line-height-12 p-3">
                <strong>Prohibited items:</strong> food, perishable goods, hazardous or toxic materials, e-bikes, lithium batteries, or substances under any local, state, or federal law or regulation.
              </div>
            </div>

            <fieldset class="col-12 form-group mb-4">
              <div class="row">

                <legend class="col-form-label col-12 pb-4">
                  <strong class="blue text-size-regular-20">Choose Your Plan</strong><br />
                  Protects your stored goods from loss like fire, theft, vandalism, and more. <a href="#">Click here</a> for full details.
                </legend>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4">
                  <div class="border-light-gray background-lightest-gray border-radius-8px text-center p-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-center">
                      <label class="text-size-regular-18 w-100 text-lowercase" for="2000Coverage">
                        $2000 in Coverage<br />
                        <span class="font-weight-400">$12/month</span>
                      </label>
                      <input class="form-check-input" type="radio" name="coverage" id="2000Coverage" value="2000coverage">
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4">
                  <div class="border-light-gray background-lightest-gray border-radius-8px text-center p-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-center">
                      <label class="text-size-regular-18 w-100 text-lowercase" for="2000Coverage">
                        $2000 in Coverage<br />
                        <span class="font-weight-400">$12/month</span>
                      </label>
                      <input class="form-check-input" type="radio" name="coverage" id="2000Coverage" value="2000coverage">
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4">
                  <div class="border-light-gray background-lightest-gray border-radius-8px text-center p-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-center">
                      <label class="text-size-regular-18 w-100 text-lowercase" for="2000Coverage">
                        $2000 in Coverage<br />
                        <span class="font-weight-400">$12/month</span>
                      </label>
                      <input class="form-check-input" type="radio" name="coverage" id="2000Coverage" value="2000coverage">
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4">
                  <div class="border-light-gray background-lightest-gray border-radius-8px text-center p-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-center">
                      <label class="text-size-regular-18 w-100 text-lowercase" for="2000Coverage">
                        $2000 in Coverage<br />
                        <span class="font-weight-400">$12/month</span>
                      </label>
                      <input class="form-check-input" type="radio" name="coverage" id="2000Coverage" value="2000coverage">
                    </div>
                  </div>
                </div>

              </div>
            </fieldset>
            {{-- Protection Plan end --}}


            {{-- E-Sign --}}
            <div class="col-12 my-4">
              <h3>
                Payment
              </h3>
            </div>

            <div class="col-12 col-md-6 mb-4">
              <label for="Name On Card">Name On Card</label>
              <input type="text" class="form-control" id="NameOnCard" aria-describedby="NameOnCard" placeholder="Name On Card">
            </div>

            <div class="col-12 col-md-6 mb-4">
              <label for="CreditCardNumber">Credit Card Number</label>
              <input type="text" class="form-control" id="CreditCardNumber" aria-describedby="CreditCardNumber" placeholder="Credit Card Number">
            </div>

            <div class="col-6 col-lg-4 mb-4">
              <label for="ExpirationDate">Expiration</label>
              <input type="text" class="form-control" id="ExpirationDate" aria-describedby="ExpirationDate" placeholder="MM/YY">
            </div>

            <div class="col-6 col-lg-4 mb-4">
              <label for="CVCcode">CVC Code</label>
              <input type="text" class="form-control" id="CVCcode" aria-describedby="CVCcode" placeholder="ex. 918">
            </div>

            <div class="col-12 mb-4">
              <label for="Address">Address</label>
              <input type="text" class="form-control" id="Address" aria-describedby="Address" placeholder="9321 Main Street">
            </div>

            <div class="col-12 mb-4">
              <label for="Address2">Address 2</label>
              <input type="text" class="form-control" id="Address2" aria-describedby="Address2" placeholder="Apartment, studio, floor, etc">
            </div>

            <div class="col-12 col-md-6 mb-4">
              <label for="City">City</label>
              <input type="text" class="form-control" id="City" aria-describedby="City" placeholder="City">
            </div>

            <div class="col-12 col-md-6 col-xl-3 mb-4">
              <label for="State">State</label>
              <select class="form-control" id="State">
              	<option value="AL">Alabama</option>
              	<option value="AK">Alaska</option>
              	<option value="AZ">Arizona</option>
              	<option value="AR">Arkansas</option>
              	<option value="CA">California</option>
              	<option value="CO">Colorado</option>
              	<option value="CT">Connecticut</option>
              	<option value="DE">Delaware</option>
              	<option value="DC">District Of Columbia</option>
              	<option value="FL">Florida</option>
              	<option value="GA">Georgia</option>
              	<option value="HI">Hawaii</option>
              	<option value="ID">Idaho</option>
              	<option value="IL">Illinois</option>
              	<option value="IN">Indiana</option>
              	<option value="IA">Iowa</option>
              	<option value="KS">Kansas</option>
              	<option value="KY">Kentucky</option>
              	<option value="LA">Louisiana</option>
              	<option value="ME">Maine</option>
              	<option value="MD">Maryland</option>
              	<option value="MA">Massachusetts</option>
              	<option value="MI">Michigan</option>
              	<option value="MN">Minnesota</option>
              	<option value="MS">Mississippi</option>
              	<option value="MO">Missouri</option>
              	<option value="MT">Montana</option>
              	<option value="NE">Nebraska</option>
              	<option value="NV">Nevada</option>
              	<option value="NH">New Hampshire</option>
              	<option value="NJ">New Jersey</option>
              	<option value="NM">New Mexico</option>
              	<option value="NY">New York</option>
              	<option value="NC">North Carolina</option>
              	<option value="ND">North Dakota</option>
              	<option value="OH">Ohio</option>
              	<option value="OK">Oklahoma</option>
              	<option value="OR">Oregon</option>
              	<option value="PA">Pennsylvania</option>
              	<option value="RI">Rhode Island</option>
              	<option value="SC">South Carolina</option>
              	<option value="SD">South Dakota</option>
              	<option value="TN">Tennessee</option>
              	<option value="TX">Texas</option>
              	<option value="UT">Utah</option>
              	<option value="VT">Vermont</option>
              	<option value="VA">Virginia</option>
              	<option value="WA">Washington</option>
              	<option value="WV">West Virginia</option>
              	<option value="WI">Wisconsin</option>
              	<option value="WY">Wyoming</option>
              </select>
            </div>

            <div class="col-12 col-md-6 col-xl-3 mb-4">
              <label for="Zip">Zipcode</label>
              <input type="text" class="form-control" id="Zip" aria-describedby="Zip" placeholder="Zipcode">
            </div>

            <div class="col-12 mb-4">
              <button class="button">
                PAY NOW
              </button>
            </div>


            {{-- E-Sign --}}
            <div class="col-12 my-4">
              <h3>
                E-Sign Documents
              </h3>
            </div>

            <div class="col-12 iframe-container" id="hellosign-container">
              <iframe id='hellosign-embed' src="{url from API}"></iframe>
            </div>

            <div class="signed">
              <p>Signed that iframe</p>
            </div>

            <script type="text/javascript">
              $('.signed').hide();

              window.addEventListener('message', e => {
                let a = e.data.type
                if(a==='sign')
                {
                  $('#hellosign-container').hide();
                  $('#hellosign-embed').remove();
                  $('.signed').show();
                }
              });
            </script>
            {{-- E-sign End --}}


            {{-- Complete --}}
            <div class="col-12 my-4">
              <h3>
                Finish up!
              </h3>
            </div>

          </div>
        </form>
        {{-- Form end --}}

      </div>
    </div>
    {{-- Right Side End --}}

  </div>
</div>
