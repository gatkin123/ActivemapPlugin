<?php
 
/**
 * The main plugin controller
 *
 * @package MVC Example
 * @subpackage Main Plugin Controller
 * @since 0.1
 */
class fooController
{
   function __construct() {
	    if( !is_admin() ):
	        add_action( 'wp', array( $this, 'init' ) );
		endif;
	}
	public function init()
	{
	   if( is_single() ):
	        add_filter( 'the_content', array(&$this, 'render_foo_single_post' ) );
		endif;
	}
	 public function render_foo_single_post( $content ){
		   require_once( 'models/foo-model.php' );
		   $fooModel = new fooModel;
		    $message = $fooModel->get_message();
		   require_once( 'views/foo-single-post-html.php' );
		   $content = fooSinglePostHtmlView::render( $message ) . $content ;
		   return $content;
		}	
}
$foo = new fooController;
?>