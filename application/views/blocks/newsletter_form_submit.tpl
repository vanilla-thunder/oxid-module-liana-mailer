<div class="form-group [{if $smarty.post.gdprcheckbox == '0'}]oxInValid text-danger[{/if}]">
    <label class="req control-label col-lg-2"></label>

    <div class="col-lg-5">
        <div class="alert alert-warning">
            [{oxcontent ident="newsletter_gdpr"}]
        </div>

        <input type="hidden" name="gdprcheckbox" value="0">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="gdprcheckbox" value="1" required data-validation-required-message='[{oxmultilang ident="LIANA_NEWSLETTER_PLEASE_ACCEPT_GDPR"}]'>
                <b>[{oxmultilang ident="LIANA_NEWSLETTER_I_ACCEPT_GDPR"}]</b>
            </label>
            <div class="help-block"></div>
        </div>
    </div>
</div>

[{$smarty.block.parent}]