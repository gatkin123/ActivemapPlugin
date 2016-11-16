<?php
function tooka(){
	$test = "test";
}
function render_foo_single_posts($content){
   return $content;
}


function fooblahinit()
{
    //is this a post display page? If so, then filter the content\
    if( is_single() ){
        add_filter( 'the_content', 'render_foo_single_posts' );
	}
}

$doa = "doa";
  	 
	
	fooblahinit();

?>