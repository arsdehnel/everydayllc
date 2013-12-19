$(function() {
	
	//load the section homepage frame	
	if( window.location.hash ){
		var hash = window.location.hash;
		$(window.location.hash).parent().addClass('active');
	}else{
		$.get('/system/getbreadcrumbid', function(data){
			if( data.length > 0 && $('#'+data).size() > 0 ){
				$('#'+data).parent().addClass('active');
			}else{
				$('.subnav_wrapper li a:first').parent().addClass('active');
			}		
		});
	}

	//well title dropdown	
	$('.well-title .well-options-link').click(function(){
		var $this	= $(this);
		var target	= $('#'+$this.attr('data-target-id'));
		if( target.hasClass('well-options-opened') ){
			target.addClass('well-options-closed').removeClass('well-options-opened');
		}else{
			target.removeClass('well-options-closed').addClass('well-options-opened');
		}
		return false;
	});
			
	$('*[data-toggle=tab-external]').live('click',function(e) {
	    e.preventDefault();
	    var href 	= $(e.target).attr('href');
	    var id 		= $(e.target).attr('id'); 
	    window.location.hash = id;
	    document.body.scrollTop = 0;
	    $('#'+id).parent().siblings().removeClass('active');
	    $('#'+id).parent().addClass('active');
		$.ajax({
			type: "GET",
			url: '/system/setbreadcrumbid/'+id,
			complete: function(){
		    	location.href = href;
		    }
		});//ajax call
	});

	if( $('#result_wrapper').size() ){
		setTimeout(function() { $('#result_wrapper').fadeOut(3000, function(){
			$(this).remove()
		}); }, 5000);
	}
	
	$('body').on('focus','*[data-toggle=datepicker]',function(){
		$(this).attr('readonly',true).datepicker()
	});
	
	//modal
	$('*[data-toggle=modal-external]').live('click',function(e) {
		$this			= $(this);
	    e.preventDefault();
	    var href 		= $(e.target).attr('href');
	    var title 		= $(e.target).attr('data-modal-title'); 
	    if( href == undefined ){
	    	var href 	= $(e.target).parent().attr('href');
		    var title 	= $(e.target).parent().attr('data-modal-title'); 
	    }
	    if (href.indexOf('#') == 0) {
	        $(href).modal('open');
	    } else if (href.indexOf('jpg') >= 0 ) {
	    	var $modal = $('.modal-external-load');
			$modal.children('.modal-header').remove()
			$modal.prepend('<div class="modal-header"><button class="close" data-dismiss="modal"><i class="icon-remove"></i></button><h3>'+title+'</h3></div>').modal('show');
	    	$('.modal-loading-wrapper').html('<div class="modal-image-viewer"><img src="'+href+'" /></div>');
	    } else {
	    	var $modal = $('.modal-external-load');
	    	if( $this.hasClass('modal-wide') ){
	    		$modal.addClass('modal-wide');
	    	}
	    	$modal.empty().append('<div class="modal-loading-wrapper"><div class="modal-body"><div class="modal-spinner spinner" id="modal-spinner"></div></div></div>');
		    var $spinner	= $('.spinner', $modal);
	    	$('.modal-body .modal-spinner',$modal).spinner();
		    if( title == undefined ){
		    	$modal.load(href, function( responseText, textStatus, jqXHR ){
					$(this).html(jqXHR.responseText);
		    	}).modal('show');
		    }else{
		    	$modal.prepend('<div class="modal-header"><button class="close" data-dismiss="modal"><i class="icon-remove"></i></button><h3>'+title+'</h3></div>').modal('show');
		    	$('.modal-loading-wrapper',$modal).load(href, function( responseText, textStatus, jqXHR ){
					$(this).html(jqXHR.responseText);
		    	});
		    }
	    }
	});
	
	if( $('.scrollcheck').size() > 0 ){
		
		var scrolltop, height, e_offset, e_vert_offset;
		$(window).scroll(function (){ 
			forum_scroll_check( 'discussion_thread_id' );			
		});					
		
	}//scrollcheck
	
	$('*[data-toggle=ajax-action]').live('click',function(e) {
		$this	= $(this);
		e.preventDefault();
		href	= $this.attr('href');
		if( $this.attr('data-error_div_id') === undefined ){
			alert('no error div defined');
			return false;		
		}else{
			$errors	= $('#'+$this.attr('data-error_div_id'));
		}
		$this.addClass('spinner').spinner({'loadingText':''});
		$.ajax({
			type: "POST",
			url: href,
			complete: function( jqXHR, textStatus ){
				if( ( jqXHR.responseText ).length == 0 ){
					window.location.reload();
				}else{
					$errors.html(jqXHR.responseText);
				}
	    	}
		});//ajax call
	});
	
	xOffset = 10;
	yOffset = 30;
	
	// these 2 variable determine popup's distance from the cursor
	// you might want to adjust to get the right result
			
	/* END CONFIG */
	$(".image-hover-preview").hover(function(e){
		$this = $(this);
		var image_master_id	= $this.attr('data-image_master_id');
		var preview_size	= $this.attr('data-preview_size');
		$("body").append("<p id='image-hover-preview'><img src='/upload/img/"+preview_size+"/"+image_master_id+".jpg' alt='Image preview' /></p>");
		$("#image-hover-preview")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px")
			.fadeIn("slow");						
    },
	function(){
		this.title = this.t;	
		$("#image-hover-preview").remove();
    });	
	$(".image-hover-preview").mousemove(function(e){
		$("#image-hover-preview")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px");
	});	
	
	$('form select.inline-add').change(function(e){
		$this = $(this);
		if( $this.val() == 'other' ){
		    var href 		= $(e.target).attr('data-href');
		    var title 		= $(e.target).attr('data-modal-title'); 
	    	var $modal 		= $('.modal-external-load');
	    	$modal.empty().append('<div class="modal-loading-wrapper"><div class="modal-body"><div class="modal-spinner spinner" id="modal-spinner"></div></div></div>');
		    var $spinner	= $('.spinner', $modal);
	    	$('.modal-body .modal-spinner',$modal).spinner();
	    	console.log(href);
	    	console.log(title);
		    if( title == undefined || href == undefined ){
		    	alert('no title and/or href defined');
		    	return false;
		    }
	    	$modal.prepend('<div class="modal-header"><button class="close" data-dismiss="modal"><i class="icon-remove"></i></button><h3>'+title+'</h3></div>').modal('show');
	    	$('.modal-loading-wrapper',$modal).load(href, function( responseText, textStatus, jqXHR ){
				$(this).html(jqXHR.responseText);
	    	});
		}
	})		
	
	$('*[data-toggle=inline-add-cancel]').live('click',function(e) {
		$('#'+$(this).attr('data-target-id')).val('').show();
		$('input[name="'+$(this).attr('data-name')+'"]').remove();
		$(this).remove();
		return false;
	});
});
function forum_scroll_check( rec_type ){

	var window_top		= $(window).scrollTop();
	var window_bottom 	= ( window_top ) + ( $(window).height() );
	
	$('.forum-list tbody tr.forum_unread').each(function(){
	
		e_offset		= $(this).offset();
		e_id			= $(this).attr('id');
		e_top			= e_offset.top;
		e_bottom		= e_top + $(this).height();
		var dataString	= "rec_type="+rec_type+"&id="+e_id+"&status_code=A&results_ind=N";
		
		if( e_top >= window_top && e_bottom <= window_bottom ){
			$('#'+e_id).removeClass('forum_unread');
			$.ajax({
				type: "POST",
				url: "/common/user_detail",
				data: dataString,
				success: function(data){
					if( data.length != 0 ){
						$('#'+e_id).addClass('forum_unread');								
					}
				}//success
			});//ajax call
		}
	
	});

}

function jqEsc(myid) { 
   return '#' + myid.replace(/(:|\.)/g,'\\$1');
 }
