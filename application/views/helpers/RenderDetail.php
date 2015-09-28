<?php

class          Zend_View_Helper_RenderDetail
extends        Zend_View_Helper_Abstract {

protected $_count = 0;

public function renderDetail( $view, $title, $action, $form_action, $buttons ) {
     $contr = get_session( 'contr' );
     $html  = '';

     $html .= NL . '<link type="text/css" rel="stylesheet" href="/css/' . $contr . '_detail.css?20101025" />';

//     $html .= NL . ' <div class="clear"></div>';

     $style = ( $action == 'index' ) ? ' style="display:none"' : '';

     if(  $action != 'index' ) {
          $html .= NL . '      <!-- Content header -->';
          $html .= NL . '      <div id="cont_header" class="padding">';

          $html .= NL . '           <div class="float_left">';
          $html .= NL . '               <span class="black">' . $title . '</span>';
          $html .= NL . '          </div>';

          if(  $action == 'show' ) {
               $html .= NL . '      <div id="cont_controls">';
               $html .= NL . '          <a href=' . $view->previous . ' ><< Previous</a>&nbsp; | &nbsp;';
               $html .= NL . '          <a href=' . $view->next     . ' >Next >></a>';
               $html .= NL . '     </div id="cont_controls">';
          }

          $html .= NL . '           <div class="clear"></div>';
          $html .= NL . '     </div id="cont_header">';
     }

     
     $html .= NL . ' <div id="content_body">';
     $html .= NL . ' <!-- Content details -->';
     $html .= NL . ' <div id="content_details">';
     $html .= NL . ' <form id="im_form" action="/' . $contr . "/" . $form_action . '" method="post" enctype="multipart/form-data"' . $style . '>';

     $html .= NL . $view->render( $contr . '/detail.phtml' );

     $html .= NL . '     <!-- Save Changes -->';
     $html .= NL . '     <div id="save_changes" class="bg_gray">';
     
     $my_buttons = explode( ',', $buttons );
     foreach( $my_buttons as $my_button ) {
          $html .= NL . '          <a class="all_buttons button_details" onclick="javascript:submit_form_name( \'im_form\', \'' . $my_button . '\' )"><span>' . $my_button . '</span></a>';
     }     
     
     if(  $action != 'index' ) {
          $html .= NL . '          <div class="or_anchor_enabled">or&nbsp;&nbsp;<a class="enabled_link" onclick="javascript:submit_form_name( \'im_form\', \'Cancel\' )">Cancel</a></div>';
     }

     $html .= NL . '     </div>';
     $html .= NL . '<div>' . put_img( 'round_corner_gray_bottom.jpg' ) . '</div>';
     $html .= NL . '</form id="im_form">';
     $html .= NL . '</div id="content_details">';
     $html .= NL . '</div id="content_body">';

     return $html;
}
}