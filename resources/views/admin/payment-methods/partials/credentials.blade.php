@if($paymentMethod->isRazorpay())
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="razorpay_key_id" class="form-label">Razorpay Key ID *</label>
                <input type="text" class="form-control" id="razorpay_key_id" name="razorpay_key_id"
                       value="{{ old('razorpay_key_id', $paymentMethod->razorpay_key_id) }}" required>
                <div class="form-text">Your Razorpay Key ID from the dashboard</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="razorpay_key_secret" class="form-label">Razorpay Key Secret *</label>
                <input type="password" class="form-control" id="razorpay_key_secret" name="razorpay_key_secret"
                       value="{{ old('razorpay_key_secret', $paymentMethod->razorpay_key_secret) }}" required>
                <div class="form-text">Your Razorpay Key Secret from the dashboard</div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="razorpay_test_mode" name="razorpay_test_mode"
                   {{ old('razorpay_test_mode', $paymentMethod->razorpay_test_mode) ? 'checked' : '' }}>
            <label class="form-check-label" for="razorpay_test_mode">
                Test Mode
            </label>
            <div class="form-text">Enable test mode for development and testing</div>
        </div>
    </div>

@elseif($paymentMethod->isPayU())
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="payu_merchant_key" class="form-label">Merchant Key *</label>
                <input type="text" class="form-control" id="payu_merchant_key" name="payu_merchant_key"
                       value="{{ old('payu_merchant_key', $paymentMethod->payu_merchant_key) }}" required>
                <div class="form-text">Your PayU merchant key</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="payu_merchant_salt" class="form-label">Merchant Salt *</label>
                <input type="password" class="form-control" id="payu_merchant_salt" name="payu_merchant_salt"
                       value="{{ old('payu_merchant_salt', $paymentMethod->payu_merchant_salt) }}" required>
                <div class="form-text">Your PayU merchant salt</div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="payu_authorization_header" class="form-label">Authorization Header</label>
        <input type="text" class="form-control" id="payu_authorization_header" name="payu_authorization_header"
               value="{{ old('payu_authorization_header', $paymentMethod->payu_authorization_header) }}">
        <div class="form-text">Optional authorization header for API calls</div>
    </div>

    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="payu_test_mode" name="payu_test_mode"
                   {{ old('payu_test_mode', $paymentMethod->payu_test_mode) ? 'checked' : '' }}>
            <label class="form-check-label" for="payu_test_mode">
                Test Mode
            </label>
            <div class="form-text">Enable test mode for development and testing</div>
        </div>
    </div>

@elseif($paymentMethod->isCOD())
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="cod_minimum_amount" class="form-label">Minimum Amount (₹)</label>
                <input type="number" class="form-control" id="cod_minimum_amount" name="cod_minimum_amount"
                       value="{{ old('cod_minimum_amount', $paymentMethod->cod_minimum_amount) }}"
                       step="0.01" min="0">
                <div class="form-text">Minimum order amount for COD (leave empty for no limit)</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="cod_maximum_amount" class="form-label">Maximum Amount (₹)</label>
                <input type="number" class="form-control" id="cod_maximum_amount" name="cod_maximum_amount"
                       value="{{ old('cod_maximum_amount', $paymentMethod->cod_maximum_amount) }}"
                       step="0.01" min="0">
                <div class="form-text">Maximum order amount for COD (leave empty for no limit)</div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="cod_instructions" class="form-label">COD Instructions</label>
        <textarea class="form-control" id="cod_instructions" name="cod_instructions" rows="3">{{ old('cod_instructions', $paymentMethod->cod_instructions) }}</textarea>
        <div class="form-text">Special instructions for COD orders (optional)</div>
    </div>
@endif
