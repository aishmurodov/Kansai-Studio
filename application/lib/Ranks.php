<?

	function returnRankValue ($rank) {
		if ($rank == 1)
			return 'Зритель';
		else if ( $rank == 2 )
			return 'Админ';
	}

	function returnSeason ($int) {
		switch ($int) {
			case 1:
				return 'Замний';
				break;
			case 2:
				return 'Весенний';
				break;
			case 3:
				return 'Летний';
				break;
			case 4:
				return 'Осенний';
				break;
			default:
				// code...
				break;
		}
	}