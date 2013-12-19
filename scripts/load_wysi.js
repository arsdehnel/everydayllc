$(document).ready(function() {
	$('.wysiwyg').each(function(){
		if( $(this).attr('id') == undefined){
			$(this).attr('id',Math.round((Math.random())*100000));
			$('#'+$(this).attr('id')).wysihtml5();
		}else{
			$('#'+$(this).attr('id')).wysihtml5();
		}
	});
});