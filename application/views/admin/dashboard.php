<div class="row">
	<div class="col-12 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2">Колличество посещений за неделю</h1>
	</div>
	<div class="col-12">
		<canvas class="my-4 w-100" id="statistic" width="900" height="380"></canvas>
	</div>

	<div class="col-12">
		<div class="row">
			<div class="col-md-6">
				<h4 class="h4">Последние 10 добавленные категрии</h4>
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Название</th>
                <th scope="col">Дата добавления</th>
              </tr>
            </thead>
            <tbody>
              <?php $count = 1; foreach ($tags as $tag): ?>
                <tr>
                  <th scope="row"><?= $count ?></th>
                  <td><?=$tag['tag']?></td>
                  <td><?=$tag['created_at']?></td>
                </tr>
              <?php $count++; endforeach ?>
            </tbody>
          </table>
			</div>
			<div class="col-md-6">
				<h4 class="h4">Последние 10 обнавлённых тайтлов</h4>
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Название</th>
                <th scope="col">Дата обновления/добавления</th>
              </tr>
            </thead>
            <tbody>
              <?php $count = 1; foreach ($posts as $post): ?>
                <tr>
                  <th scope="row"><?= $count ?></th>
                  <td><a href="/catalog/item/<?=$post['url']?>"><?=$post['title']?></a></td>
                  <td><?=$post['updated_at']?></td>
                </tr>
              <?php $count++; endforeach ?>
            </tbody>
          </table>
			</div>
      <div class="col-12">
        <h4 class="h4">Последние 50 комментариев</h4>
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Пользователь</th>
                <th scope="col">Пост</th>
                <th scope="col">Текст</th>
                <th scope="col">Дата</th>
              </tr>
            </thead>
            <tbody>
              <?php $count = 1; foreach ($comments as $comment): ?>
                <tr>
                  <th scope="row"><?= $count ?></th>
                  <td><a href="/profile/<?=$comment['user']['login']?>"><?=$comment['user']['login']. '(<b>' .returnRankValue($comment['user']['ugroup'])?></b>)</a></td>
                  <td><a href="/catalog/item/<?=$comment['post']['url']?>"><?=$comment['post']['title']?></a></td>
                  <td><?=$comment['text']?></td>
                  <td><?=$comment['date']?></td>
                </tr>
              <?php $count++; endforeach ?>
            </tbody>
          </table>
      </div>
		</div>
	</div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
<script>
  var ctx = document.getElementById("statistic");
  var statistic = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ["Понидельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье"],
      datasets: [{
        data: [
          <?php 
            foreach ($activityes as $activity) {
              echo $activity['count'].',';
            }
          ?>
        ],
        lineTension: 0,
        backgroundColor: 'transparent',
        borderColor: '#383838',
        borderWidth: 4,
        pointBackgroundColor: '#282829'
      }]
    },
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: false
          }
        }]
      },
      legend: {
        display: false,
      }
    }
  });
</script>