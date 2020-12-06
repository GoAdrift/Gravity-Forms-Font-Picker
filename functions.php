<?php

// GRAVITY FORMS CODE TO ADD A DROPDOWN SELECT A FONT FROM A LIST
// USE FIELD CLASS 'gaws-font-picker' TO ASSIGN CODE TO A TEXT FIELD



add_filter( 'gform_pre_render', 'gaws_font_picker_head', 10, 5 );
function gaws_font_picker_head( $form ) {

//Change to Form ID to target form
	if( $form["id"] != 0 ){
		return $form;
	}

?>
<script>
	jQuery(document).ready( function($){
		
		if( $( 'li.gaws-font-picker.gfield' ).length ){
			var url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyCMWyuTstme3omnOoCjUSVNY2NIf8gstqw&sort=popularity';
			$.getJSON( url, function(  ){
			}).done( function( data ){
				$('li.gaws-font-picker').each( function(){
					let this_id = $(this).attr('id');
					$( this ).append('<div class="gaws_font_box" for="'+this_id+'" style="display: none;"></div>');
					$( document ).on( 'focus click', function(){
						$( 'div.gaws_font_box' ).hide( );
					});
					
					$( document ).on( 'focus click change keyup',  '#' + this_id + ' input', function(){
						append_font_box( this );
					});
					
					function append_font_box( el ){
						
						var input_value = $( el ).val();
						var font_box = $( 'div.gaws_font_box[for="'+this_id+'"]' );
						font_box.empty();
						var results = [];
						if( input_value ){
							var font_cat;
							var font_fam;
							var push_this;

							for (var i=0 ; i < data.items.length; i++){
								var fam_test;
								var cat_test;
								font_cat = data.items[i].category;
								font_fam = data.items[i].family;
								if( font_fam.includes( input_value ) != null ){
									fam_test = font_fam.includes( input_value ).length;
								}
								if( font_cat.includes( input_value ) != null ){
									cat_test = font_cat.includes( input_value ).length;
								}
								if ( cat_test || fam_test ) {
									push_this = data.items[i];
									results.push(push_this);
								}else if( font_fam.match( input_value ) ){
									push_this = data.items[i];
									results.push(push_this);
								}
								if( results.length > 9 ){
									break;
								}
							}
						} else if( !input_value ){

							for (var i=0 ; 9 < data.items.length; i++){
								push_this = data.items[i];
								results.push(push_this);
								if( results.length > 9 ){
									break;
								}
							}
						}
						
						for (var i=0 ; i < results.length; i++){
							var str = $('head')[0].outerHTML;
							var a = results[i];
							if( str.search('<link rel="stylesheet" href="https://fonts.googleapis.com/css?family='+a.family+'">' == -1 ) ){
								$('head').append('<link rel="stylesheet" href="https://fonts.googleapis.com/css?family='+a.family+'">');
							}
							font_box.append( '<div style="font-family: '+a.family+'" class="gaws_fontbox_option" value="'+a.family+' -- '+a.category+'">'+a.family+' -- '+a.category+'</div>' );
						}
						
						console.log( 'finding', font_box.find('div.gaws_fontbox_option') );
						if( !font_box.find('div.gaws_fontbox_option').length ){
							font_box.append( '<div class="gaws_fontbox_option empty"><i>No Results</i></div>' );
						}
						
						let pos = el.getBoundingClientRect();
						$( font_box ).show('fast');
						font_box.mouseleave( function() {
						});
						font_box.mouseenter( function() {
							font_box.stop();
							font_box.fadeIn("fast");
						});
					}
					

				
				});
			
				$(document).on('click', '.gaws_fontbox_option', function(){
					var getLabel = $(this).closest('li.gfield').find('label').text();
					var str = $(this).attr('value');
					$(this).closest('li.gfield').find('input').val(str);
					var family = str.split(' -- ');
					$(this).closest('li.gfield').find('input').css('font-family', family[0]);
					if( getLabel.includes("Body") ){
						$('.gaws-color-section p').css('font-family', family[0]);
					} else if( getLabel.includes("Header") ){
						$('.gaws-color-section h1, .gaws-color-section h2, .gaws-color-section h3, .gaws-color-section h4').css('font-family', family[0]);
					}
				});
				
			});
		}
	});
</script>
<style>
.gaws_font_box {
    position: absolute;
    background: #0c1a00;
    padding: 20px 15px;
    margin: 5px;
    border-radius: 3px;
    border: 4px solid;
	z-index: 1000000;
}
	
.gaws_fontbox_option {
    padding: 10px 0px;
	z-index: 1000;
    /* text-decoration: underline; */
}
.gaws_fontbox_option:hover {
    text-decoration: underline;
	color: white;
	font-weight: 700px;
}

}

</style>
<?php

	return $form;
}
