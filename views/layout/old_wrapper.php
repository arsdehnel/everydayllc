<script type="text/javascript">
	$(function(){
		$("#openBtn").click(function(){
			$("#panel").animate({height: "107px", marginTop: "-130px"}, "slow");
			$(".panelBtn").toggle();
			return false;
		});
		$("#closeBtn").click(function(){
			$("#panel").animate({height: "0px", marginTop: "-23px"}, "slow");
			$(".panelBtn").toggle();
			return false;
		});
	});
	
	$(function() {
		$("#tabs").tabs({
			select: function(ui) { 
				//alert( ui.index );
				//$('#header_photo').attr('src', '/library/image/DSCF0188.jpg').attr('alt', 'newattribute');
		    } 	
		});
		
		$('#tabs').bind('tabsselect', function(event, ui) {
			
			var content_detail_id = ui.tab;
			content_detail_id = content_detail_id.toString();
			//alert( content_detail_id.substring( content_detail_id.lastIndexOf("#") + 1 ) );
			content_detail_id = content_detail_id.substring( content_detail_id.lastIndexOf("#") + 1 );
			
			$.post("/_ajax/header_photo.php", { content_detail_id: content_detail_id },
			  	function(data){
			  		if( data != '' ){
				  		$('#header_photo').attr('src', data).attr('alt', data);
				  	}
  				});
			return true;

		});
				
	});
</script>

</head>
<body onload="<?=$onload;?>">
<div id="page_wrapper">
<?=$body;?>
</div>
<script src="/scripts/modal.js"></script>
<?
	if( $_SESSION['cart_ind'] == 'Y' ){
		if( $_SESSION['cart_notice_ind'] == 'N' ){
			$_SESSION['cart_notice_ind'] = 'Y';
			?>
			<a href="/catalog/cart_notice.cmpnt.php" id="cart_notice" class="modal" style="display: none;">Find Your Cart</a>
			<script type="text/javascript">
				$(document).ready(function(){
					$('#cart_notice').trigger('click');
				});
			</script>
			<?
		}
		?>
		<form method="post" action="http://ww5.aitsafe.com/cf/review.cfm" id="cart_form">
		<input type="hidden" name="userid" value="72326820" />
		<input type="hidden" name="return" value="http://www.everyday-everywhere.org/catalog/return.prcs.php" />
		<input type="hidden" name="ref" value="<?=$_SERVER['HTTP_REFERER'];?>" />
		</form>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#view_cart').click(function(){
					$('#cart_form').submit();
					return false;
				});
			});
		</script>
		<?
	}
?>
</body>
</html>