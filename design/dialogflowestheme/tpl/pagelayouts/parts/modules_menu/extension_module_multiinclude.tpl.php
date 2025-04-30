<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhdialogflowes','use_options')) : ?>
    <li class="nav-item"><a class="nav-link" href="<?php echo erLhcoreClassDesign::baseurl('dialogflowes/index')?>"><i class="material-icons">network_node</i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('dialogflow/module','Dialogflow');?></a></li>
<?php endif; ?>