<div class="row">
	<div class="span8 offset2">
		<h1>Shortened URLs</h1>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Code</th>
					<th>URL</th>
					<th>Created at</th>
				</tr>
			</thead>
			<tbody>
<?php foreach($list as $url): ?>
				<tr>
					<td><?=$url['code'];?></td>
					<td><a href="<?=$url['url'];?>"><?=$url['url'];?></a></td>
					<td><?=$url['created_at'];?></td>
				</tr>
<?php endforeach; ?>
			</tbody>
		</table>
		<ul class="pager">
<?php if(1 == $currentPage): ?>
			<li class="previous disabled"><a>Newer</a></li>
<?php else: ?>
			<li class="previous">
				<a href="/list/<?=$currentPage - 1;?>">Newer</a>
			</li>
<?php endif; ?>
<?php if($numPages <= $currentPage): ?>
			<li class="next disabled"><a>Older</a></li>
<?php else: ?>
			<li class="next">
				<a href="/list/<?=$currentPage + 1;?>">Older</a>
			</li>
<?php endif; ?>
		</ul>
	</div>
</div>

