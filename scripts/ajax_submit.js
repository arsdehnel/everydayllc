	$(function(){
	
		$('button.btn-ajax-submit').click(function(){
		
			var $this		= $(this);
			var form_id		= $this.attr('data-target-form-id');
			if( form_id == undefined ){
				alert('no target form defined');
				return false;
			}
			var $form		= $('#'+form_id);
			var dataString 	= $form.serialize();
			var action		= $form.attr('action');
			var method		= $form.attr('method');
			if( action == undefined ){
				alert('no action defined');
				return false;
			}
			if( method == undefined ){
				alert('no method defined');
				return false;
			}
			var $buttons	= $this.closest('.btn-toolbar').children('.btn-group');
			var $spinner	= $this.closest('.btn-toolbar').children('.spinner');
			var $errors		= $form.closest('.ajax-results');

			if( $errors.size() == 0 ){
				alert('no ajax results div defined');
				return false;
			}
			
			$buttons.hide();
			$spinner.removeClass('hide').spinner();
			
			$.ajax({
				type: method,
				url: action,
				data:  dataString,
				complete: function( jqXHR, textStatus ){
					if( ( jqXHR.responseText ).length == 0 ){
						var success_url = $this.attr('data-success-url');
						var success_id	= $this.attr('data-success-id');
						if( success_url == undefined ){
							window.location.reload();
						}else{
							$.ajax({
								type: "GET",
								url: '/system/setbreadcrumbid/'+success_id,
								complete: function(){
							    	window.location.href = success_url;
							    }
							});//ajax call
						}
					}else{
						$errors.html(jqXHR.responseText);
						window.clearTimeout(spinTimer);
						$spinner.empty().addClass('hide');
						$buttons.show();			
					}
				}
			});//ajax call

			return false;
			
		});
	
	});