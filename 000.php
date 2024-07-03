<form name="order" method="post">
    <fieldset class="mb-3">
        <legend class="col-form-label required">Adresse : Choisissez votre adresse.</legend>
        <div id="order_addresses" placeholder="Choisissez votre adresse de livraison">
            <div class="form-check"><input type="radio" id="order_addresses_6" name="order[addresses]" required="required" class="form-check-input" value="6">
                <label class="form-check-label required" for="order_addresses_6">BOUAZZA Jamel<br>17 rue Paul Fort<br>77330 Ozoir la ferrière - FR</label>
            </div>
        </div>
    </fieldset>
    <a href="/account/address/add" class="btn btn-dark small"><i class="bi bi-house-add-fill"></i></a>
    <hr class="my-5">
    <fieldset class="mb-3">
        <legend class="col-form-label required">Adresse : Choisissez votre transporteur.</legend>
        <div id="order_carriers" placeholder="Choisissez votre transporteur de livraison">
            <div class="form-check"><input type="radio" id="order_carriers_1" name="order[carriers]" required="required" class="form-check-input" value="1">
                <label class="form-check-label required" for="order_carriers_1">DHL : 3,90 €<br>Lorem ipsum</label>
            </div>
            <div class="form-check"><input type="radio" id="order_carriers_2" name="order[carriers]" required="required" class="form-check-input" value="2">
                <label class="form-check-label required" for="order_carriers_2">fedex : 10,90 €<br>fedex transporteur livraison</label>
            </div>
        </div>
    </fieldset>
    <a href="#" class="btn btn-dark small mb-5"><i class="bi bi-truck"></i></a>

    <div class="mb-3"><button type="submit" id="order_submit" name="order[submit]" class="btn btn-dark btn">Valider</button></div><input type="hidden" id="order__token" name="order[_token]" value="095024af944cdd83dead.UyPqUwtn981FCz2zHS26Xeb4VdwtDIGWCtlzhzQdNFQ.AxuTEVQRxr8zWVvfWGb7EYTLL48ebemnboMr9mt4cRwBbYJrQSKFhSB6Ug">
</form>