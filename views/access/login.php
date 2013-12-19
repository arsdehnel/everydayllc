<form action="/access/login" method="post" name="login_form" style="width: 300px;">
	<label>Username:&nbsp;</label><input type="text" name="username" id="username" autocomplete="off" value="" /><br />
	<label>Password:&nbsp;</label><input type="password" name="password" autocomplete="off" value="" /><br />
	<div class="btn-toolbar">
		<div class="btn-group">
			<button type="submit" class="btn btn-primary btn-mini">Login</button>
		</div>
		<div class="btn-group">
			<a class="btn btn-mini" data-toggle="modal-external" href="/access/request">need a login?</a>
			<a href="/access/frgt_pwd" class="btn btn-mini">forgot password?</a>
		</div>
	</div>
</form>