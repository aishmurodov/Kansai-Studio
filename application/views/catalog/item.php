<?
	$cats_string = "";
	foreach ($post['cats'] as $cat) {
		$cats_string.= '<a href="/catalog/search/cats/'.$cat['id'].'" class="categorie">'.$cat['title'].'</a>, ';
	}
	$cats_string = substr($cats_string, 0, -2);
	$torrents_string = "";
	$post['torrent'] = json_decode($post['torrent']);
	if (!empty($post['torrent'])) {
		foreach ($post['torrent'] as $resolution => $url) {
			$torrents_string.= '<a style="display: inline-block;" class="torrentDownloadBTN" href="'.$url.'" download="'.$post['original_title'].'['.$resolution.'p].torrent" target="_blank"><i class="fa fa-download" aria-hidden="true" target="_blank"></i>&nbsp;'.$resolution.'p</a>, ';
		}
		$torrents_string = substr($torrents_string, 0, -2);
	}
?>
<input type="hidden" value="<?= $post['id'] ?>" id="itemID">
<div class="block">
	<div class="row">
		<div class="col-md-4">
			<img src="<?= $post['poster'] ?>" alt="<?= $post['title'] ?>" class="img-fluid">
			<?php if (isset($_SESSION['admin'])): ?>
				<br><hr>
			<div class="text-center">
				<a href="/admin/series/id/<?=$post['id']?>">Управление сериями</a><br>
				<a href="/admin/torrents/id/<?=$post['id']?>">Управление торрентами</a>
			</div>
			<?php endif ?>
		</div>
		<div class="col-md-8">
			<h1><?= $post['title'] ?> 

            <?php if ($post['totalseries'] > 1): ?> 
                [<?= count($post['series'])?> из <?= $post['totalseries'] ?>]
            <?php endif ?>
			</h1>
			<h6><?= $post['original_title'] ?></h6><hr>
			<div id="votes">
			    <div class="ARating-container">
			      <div data-total-value="<?= $post['rating'] ?>" class="ARatingBackGround" style="width: 0%;"></div>
			      <div class="ARatingStar"></div>
			    </div>
			</div><br>
			<div>
				<b>Жанры: </b>
					<?= $cats_string ?>
			</div>
			<div>
                <?php if ($post['season']): ?>
                    <b>Сезон: </b> <a href="/catalog/search/season/<?=$post['season']?>"><?= returnSeason($post['season'])?></a><br>
                <?php endif ?>
                <?
                    $infos = json_decode($post['additional'],true);
                    foreach ($infos as $info) {
						echo '<b>'.$info['key'].':</b> '.$info['value'].'<br>';
                    }
                ?>
			</div><br>
			<div>
				<b>Описание: </b><br><?= nl2br($post['description']) ?>
			</div>

			<hr>
			<div>
				<?php if ($post['torrent']): ?>
					<b>Скачать торрентом: </b>
					<?=$torrents_string?>
				<?php endif ?>
			</div><br>
			<?php if (isset($_SESSION['account']['id'])): ?>
			<div class="text-right">
				<?php if ($post['inFavorite'] == true): ?>
					<form action="/account/favorites/" method="post">
						<input type="hidden" name="delete-favorite" value="<?=$post['id']?>">
						<button class="btn main_bgc_color" type="submit">Удалить из избранных</button>
					</form>
				<?php else: ?>
					<form action="/account/favorites/" method="post">
						<input type="hidden" name="add-favorite" value="<?=$post['id']?>">
						<button class="btn main_bgc_color" type="submit">Добавить в избраные</button>
					</form>
				<?php endif ?>
			</div><br>
			<?php endif ?>
		</div>
		<div class="col-12" id="player_container">
			<iframe src="" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe>
			<video src="" poster="<?= $post['poster'] ?>" webkitAllowFullScreen mozallowfullscreen allowfullscreen playsinline controls></video>
			<?php foreach ($post['series'] as $serie): ?>
				<span <?php if (count($post['series']) < 2): ?> style="display: none;" <?php endif ?> class="target_serie <?php if ($serie['number'] == $post['last_serie'] || (!$post['last_serie'] && $serie['number'] == 1)): ?>active<?php endif ?>" data-serie-number="<?= $serie['number'] ?>" data-serie-id="<?= $serie['id'] ?>" data-is-frame="<?= $serie['isframe'] ?>" data-serie-url="<?= $serie['url'] ?>"><?= $serie['number'] ?></span>
			<?php endforeach ?>
		</div>
		<?php if (!empty($post['comments'])): ?>
			<div id="comments" class="col-12">
				<div class="text-center">
					<h3>Комментарии</h3>
				</div>
				<div class="comments-pages">
					<?php if (!empty($paginator->getPages())): ?>
						Страницы: 
					<?php endif ?>
					
				        <?php if ($paginator->
				        getPrevUrl()): ?>
				            <a href="<?php echo $paginator->
				                getPrevUrl(); ?>">
				                <span class="sr-only">Пред.</span>
				            </a>
				        <?php endif; ?>
				        <?php foreach ($paginator->getPages() as $page): ?>
					        <?php if ($page['url']): ?>
					            <a class="<?php echo $page['isCurrent'] ? ' active' : ''; ?>"  href="<?php echo $page['url']; ?>
					                ">
					                <?php echo $page['num']; ?></a>
					        <?php else: ?>
					            <span>
					                <?php echo $page['num']; ?></span>
					        <?php endif; ?>
				        <?php endforeach; ?>
				        <?php if ($paginator->
				        getNextUrl()): ?>
				            <a href="<?php echo $paginator->
				                getNextUrl(); ?>">
				                <span class="sr-only">След.</span>
				            </a>
				        <?php endif; ?>
				</div>
				<div id="comments-container">
					<?php foreach ($post['comments'] as $comment): ?>
						<?
							$comment['user']['avatar'] = isset($comment['user']['avatar']) ? $comment['user']['avatar'] : '/public/images/non-avatar.png';
						?>
						<div class="comment-item">
							<div class="row">
								<div class="col-2">
									<img src="<?=$comment['user']['avatar']?>" alt="<?=$comment['user']['login']?>">
								</div>
								<div class="col-10">
									<b><a href="/profile/<?=$comment['user']['login']?>"><?=$comment['user']['login']?></a></b><br>
									<i class="fas fa-user"></i> <i><?= returnRankValue($comment['user']['ugroup']) ?></i>
									<div class="date-of-comment">
										<?=$comment['date']?>
									</div>
									<div class="clear"></div>
									<div class="comment-text">
										<?
											$comment_text = nl2br(htmlspecialchars($comment['text']));
											if ($comment['spoiler'] == 1) {
												echo '<a data-toggle="collapse" href="#comment-id-'.$comment['id'].'" role="button" aria-expanded="false" aria-controls="comment-id-'.$comment['id'].'">Спойлер</a>';
												echo '<div id="comment-id-'.$comment['id'].'" class="collapse">';
													echo '<div class="card card-body">';
														echo $comment_text;
													echo '</div>';
												echo '</div>';
											}else
												echo $comment_text;
										?>

										<?php if (isset($_SESSION['admin'])): ?>
											<div>
												<form style="float:right;margin-left: 10px;" action="/admin/deleteComment" method="post">
													<input type="hidden" name="comment_id" value="<?=$comment['id']?>">
													<button class="btn btn-dangerous">Удалить комментарий</button>
												</form>
												<?php if ($comment['spoiler'] == 0): ?>
													<form style="float:right;" action="/admin/spoilerComment" method="post">
														<input type="hidden" name="comment_id" value="<?=$comment['id']?>">
														<button class="btn main_bgc_color">Отметить как спойлер</button>
													</form>
												<?php else: ?>
													<form style="float:right;" action="/admin/unspoilerComment" method="post">
														<input type="hidden" name="comment_id" value="<?=$comment['id']?>">
														<button class="btn main_bgc_color">Убрать из спама</button>
													</form>
												<?php endif ?>
											</div>
										<?php endif ?>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach ?>
				</div>
			</div>
		<?php endif ?>
		<?php if (isset($_SESSION['account'])): ?>
		<div class="col-12">
			<div id="new_comment">
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item active" aria-current="page">Напишите свой комментарий</li>
				  </ol>
				</nav>
				<form action="/account/addComment" method="post">
					<input type="hidden" name="item" value="<?=$post['id']?>">
					<div class="form-group">
						<textarea name="commentText" class="form-control" style="min-height: 150px;" required="" placeholder="Текст комментария"></textarea>
					</div>
					<div class="form-grop">
						<label>
							<input type="checkbox" value="1" name="isSpoiler">
							Спойлер
						</label>
						<button style="float:right;" class="btn main_bgc_color">Добавить</button>
					</div>
				</form>
			</div>
		</div>
		<?php endif ?>
	</div>
</div>