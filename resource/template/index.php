<div class="row">
	<div class="span6 offset3">
		<h1>Welcome to UrlShortener</h1>
		<h2>A simple URL shortener based on Flight</h2>
		<p>To add a new URL to be shortened, use the following form.</p>
		<form class="form-horizontal well" action="add" method="post">
			<div class="control-group">
				<label class="control-label" for="input01">URL</label>
				<div class="controls">
					<input type="text" class="input-xlarge" id="input01">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="input02">Custom code</label>
				<div class="controls">
					<input type="text" class="input-xlarge" id="input02">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="input03">Result</label>
				<div class="controls">
					<input type="text" class="input-xlarge" id="input03" value="<?=$_SERVER['PHP_SELF'] . '/code'?>">
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn">Save</button>
			</div>
		</form>
	</div>
</div>

