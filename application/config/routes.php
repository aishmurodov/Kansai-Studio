<?php

return [
	//MainController
	'' => [
		'controller' => 'main',
		'action' => 'index',
	],

	'catalog/sort/alphabit' => [
		'controller' => 'main',
		'action' => 'sortbyalphabit',
	],
	'catalog/sort/alphabit/page/{page:\w+}' => [
		'controller' => 'main',
		'action' => 'sortbyalphabit',
	],

	'catalog/sort/season/{item:\S+}' => [
		'controller' => 'main',
		'action' => 'sortbyseason',
	],

	'catalog/search/cats/{item:\S+}' => [
		'controller' => 'main',
		'action' => 'searchbycats',
	],

	'catalog/search/query/{item:\S+}' => [
		'controller' => 'main',
		'action' => 'searchbywords',
	],

	'page/{page:\w+}' => [
		'controller' => 'main',
		'action' => 'index',
	],

	'profile/{login:\w+}' => [
		'controller' => 'main',
		'action' => 'profile',
	],

	//Catalog controller

	'catalog/item/{item:\S+}' => [
		'controller' => 'catalog',
		'action' => 'item',
	],

	'catalog/plusviewtoserie' => [
		'controller' => 'catalog',
		'action' => 'plusviewtoserie',
	],

	//AdminController
	'admin' => [
		'controller' => 'admin',
		'action' => 'dashboard',
	],
	'admin/siteheader' => [
		'controller' => 'admin',
		'action' => 'siteheader',
	],
	'admin/siteheader/delete/{deletepostid:\w+}' => [
		'controller' => 'admin',
		'action' => 'siteheader',
	],
	'admin/users' => [
		'controller' => 'admin',
		'action' => 'users',
	],
	'admin/users/page/{page:\w+}' => [
		'controller' => 'admin',
		'action' => 'users',
	],

	'admin/users/add' => [
		'controller' => 'admin',
		'action' => 'useradd',
	],

	'admin/catalog/add' => [
		'controller' => 'admin',
		'action' => 'addCatalog',
	],

	'admin/catalog/edit' => [
		'controller' => 'admin',
		'action' => 'editCatalog',
	],

	'admin/catalog/updateStatusOfPost' => [
		'controller' => 'admin',
		'action' => 'updateStatusOfPost',
	],

	'admin/catalog/edit/page/{page:\w+}' => [
		'controller' => 'admin',
		'action' => 'editCatalog',
	],

	'admin/catalog/edit/id/{postid:\w+}' => [
		'controller' => 'admin',
		'action' => 'editCatalogWithId',
	],

	'admin/cats' => [
		'controller' => 'admin',
		'action' => 'cats',
	],
	'admin/cats/delete/{deleteid:\d+}' => [
		'controller' => 'admin',
		'action' => 'cats',
	],

	'admin/spoilerComment' => [
		'controller' => 'admin',
		'action' => 'spoilerComment',
	],

	'admin/unspoilerComment' => [
		'controller' => 'admin',
		'action' => 'unspoilerComment',
	],
	
	'admin/deleteComment' => [
		'controller' => 'admin',
		'action' => 'deleteComment',
	],

	'admin/series/id/{postid:\w+}' => [
		'controller' => 'admin',
		'action' => 'series',
	],

	'admin/series/delete/{deleteserieid:\w+}' => [
		'controller' => 'admin',
		'action' => 'series',
	],

	'admin/torrents/id/{postid:\w+}' => [
		'controller' => 'admin',
		'action' => 'torrents',
	],
	//AccountController
	'account/login' => [
		'controller' => 'account',
		'action' => 'login',
	],

	'account/register' => [
		'controller' => 'account',
		'action' => 'register',
	],

	'account/register/{ref:\w+}' => [
		'controller' => 'account',
		'action' => 'register',
	],

	'account/recovery' => [
		'controller' => 'account',
		'action' => 'recovery',
	],

	
	'account/reset/{token:\w+}' => [
		'controller' => 'account',
		'action' => 'reset',
	],
	
	'account/confirm/{token:\w+}' => [
		'controller' => 'account',
		'action' => 'confirm',
	],

	'account/profile' => [
		'controller' => 'account',
		'action' => 'profile',
	],

	'account/logout' => [
		'controller' => 'account',
		'action' => 'logout',
	],

	'account/addComment' => [
		'controller' => 'account',
		'action' => 'addComment',
	],

	'account/ratePost' => [
		'controller' => 'account',
		'action' => 'ratePost',
	],

	'account/favorites' => [
		'controller' => 'account',
		'action' => 'favorites',
	],

	'account/favorites/page/{page:\w+}' => [
		'controller' => 'account',
		'action' => 'favorites',
	],

	'account/getLastViewedSerie' => [
		'controller' => 'account',
		'action' => 'getLastViewedSerie',
	],


];