<?php
function smarty_function_widget_faq_data($params, $smarty){
    $collection = new plugins_faq_public();
    $smarty->assign('page',$collection->getPageContent());
    $smarty->assign('QAs',$collection->getQAs());
}