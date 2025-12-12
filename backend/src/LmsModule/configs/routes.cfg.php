<?php

return [
	'lms.Admin.Courses.Contents.REST' => [
		'type' => 'segment',
		'options' => [
			'route' => '/lms/api/v1/admin/courses/contents[/[:id]]',
			'constraints' => [],
			'defaults' => [
				'id' => null,
				'_action' => 'endpointAction',
				'_actionName' => 'endpoint',
				'_controller' => Psk\LmsModule\Controllers\REST\Admin\ContentController::class,
				'_controllerDir' => 'r-e-s-t/admin/content',
				'_moduleName' => 'LmsModule',
			],
		],
	],
	'lms.Admin.Courses.REST' => [
		'type' => 'segment',
		'options' => [
			'route' => '/lms/api/v1/admin/courses[/[:id]]',
			'constraints' => [],
			'defaults' => [
				'id' => null,
				'_action' => 'endpointAction',
				'_actionName' => 'endpoint',
				'_controller' => Psk\LmsModule\Controllers\REST\Admin\CourseController::class,
				'_controllerDir' => 'r-e-s-t/admin/course',
				'_moduleName' => 'LmsModule',
			],
		],
	],
	'lms.Courses.Contents.REST' => [
		'type' => 'segment',
		'options' => [
			'route' => '/lms/api/v1/courses/contents[/[:id]]',
			'constraints' => [],
			'defaults' => [
				'id' => null,
				'_action' => 'endpointAction',
				'_actionName' => 'endpoint',
				'_controller' => Psk\LmsModule\Controllers\REST\User\ContentController::class,
				'_controllerDir' => 'r-e-s-t/user/content',
				'_moduleName' => 'LmsModule',
			],
		],
	],
	'lms.Courses.REST' => [
		'type' => 'segment',
		'options' => [
			'route' => '/lms/api/v1/courses[/[:id]]',
			'constraints' => [],
			'defaults' => [
				'id' => null,
				'_action' => 'endpointAction',
				'_actionName' => 'endpoint',
				'_controller' => Psk\LmsModule\Controllers\REST\User\CourseController::class,
				'_controllerDir' => 'r-e-s-t/user/course',
				'_moduleName' => 'LmsModule',
			],
		],
	],
];
