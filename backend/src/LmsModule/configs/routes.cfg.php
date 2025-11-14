<?php

return [
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
];
