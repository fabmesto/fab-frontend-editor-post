

  function fab_ajax_submit(form){
    jQuery('.ajax_message').css('display', 'block');
    jQuery('.ajax_message').html('Sto salvando...');
    var data = jQuery(form).serialize();
    data += '&security='+fab_obj.security;
    jQuery.ajax({
      url : fab_obj.ajax_url,
      type : 'post',
      data : data,
      error: function(){
        jQuery('.ajax_message').html("Errore di connessione!");
      },
      success : function( json_string ) {
        var response = jQuery.parseJSON(json_string);
        console.log('response', response);
        if(response!=0 && response.errors==false){
          // salvato
          jQuery("#primary_key").val(response.id);
          jQuery('.ajax_message').html(response.message);
        }else{
          // errore
          jQuery('.ajax_message').html((response.message? response.message : 'ERRORE'));
        }
      }
    });
    return false;
  }

  function fab_ajax_delete(post_id, action){
    jQuery('.ajax_message').css('display', 'block');
    jQuery('.ajax_message').html('Sto eliminando...');
    var data = 'action='+action+'&post_id='+post_id+'&security='+fab_obj.security;
    jQuery.ajax({
      url : fab_obj.ajax_url,
      type : 'post',
      data : data,
      error: function(){
        jQuery('.ajax_message').html("Errore di connessione!");
      },
      success : function( json_string ) {
        var response = jQuery.parseJSON(json_string);
        console.log('response', response);
        if(response!=0 && response.errors==false){
          // salvato
          jQuery("#primary_key").val(response.id);
          jQuery('.ajax_message').html(response.message);
        }else{
          // errore
          jQuery('.ajax_message').html((response.message? response.message : 'ERRORE'));
        }
      }
    });
    return false;
  }

  jQuery(document).ready( function(){

    /* FORM SUBMIT */
    jQuery('form#fab_post_form').submit(function(e){

    });


  });
