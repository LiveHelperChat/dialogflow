<form action="" method="post" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <?php if (isset($updated) && $updated == 'done') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('dialogflow/module','Settings updated'); ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
    <?php endif; ?>

    <h3 class="attr-header"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('dialogflow/module','Service Credentials Content')?></h3>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('dialogflow/module','Enter content of service-account.json file which you will download from google.')?></label>
        <textarea class="form-control form-control-sm" placeholder="<?php echo htmlspecialchars('{
  "type": "service_account",
  "project_id": "xxxx",
  "private_key_id": "....",
  "private_key": "-----BEGIN PRIVATE KEY.....-----END PRIVATE KEY-----\n",
  "client_email": "xxx",
  "client_id": "xxx",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "xxxx",
  "universe_domain": "googleapis.com"
}
')?>" name="service_credentials" rows="15"><?php echo (isset($dialog_options['service_credentials'])) ? htmlspecialchars($dialog_options['service_credentials']) : ''?></textarea>
    </div>

    <div class="form-group">
        <label><input type="checkbox" value="on" name="reset">&nbsp;<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('dialogflow/module','Reset present bearer token')?></label>
    </div>

    <input type="submit" class="btn btn-secondary" name="StoreOptions" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Save'); ?>" />&nbsp;

</form>
