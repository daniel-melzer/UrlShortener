<div class="row">
	<div class="span4 offset4">
		<h1>Welcome to UrlShortener</h1>
<?php if(!empty($errors)): ?>
		<div class="alert alert-error">
			<b>Oops, something went wrong!</b><br>
			<?=implode(', ', $errors)?>
		</div>
<?php endif; ?>
		<form class="form well" action="" method="post">
			<div class="control-group<?php if(isset($errors['url'])): ?> error<?php endif; ?>">
				<label class="control-label" for="url">Paste your long URL here:</label>
				<div class="controls">
					<input type="text" name="url" class="input-xlarge" id="url" value="<?=$url?>">
				</div>
			</div>
			<div class="control-group<?php if(isset($errors['code'])): ?> error<?php endif; ?>">
				<label class="control-label" for="code">Optional custom shortcode:</label>
				<div class="controls">
					<input type="text" name="code" class="input-xlarge" id="code" value="<?=$code?>">
					<p class="help-block">Minimum 4 characters.</p>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Shorten URL</button>
			</div>
		</form>
		<p>All URLs are public and can be accessed by anyone.</p>
	</div>
</div>

