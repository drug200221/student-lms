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
	'lms.Admin.Courses.QuestionCategories.REST' => [
		'type' => 'segment',
		'options' => [
			'route' => '/lms/api/v1/admin/courses/question-categories[/[:id]]',
			'constraints' => [],
			'defaults' => [
				'id' => null,
				'_action' => 'endpointAction',
				'_actionName' => 'endpoint',
				'_controller' => Psk\LmsModule\Controllers\REST\Admin\Questions\QuestionCategoryController::class,
				'_controllerDir' => 'r-e-s-t/admin/questions/question-category',
				'_moduleName' => 'LmsModule',
			],
		],
	],
	'lms.Admin.Courses.Questions.REST' => [
		'type' => 'segment',
		'options' => [
			'route' => '/lms/api/v1/admin/courses/questions[/[:id]]',
			'constraints' => [],
			'defaults' => [
				'id' => null,
				'_action' => 'endpointAction',
				'_actionName' => 'endpoint',
				'_controller' => Psk\LmsModule\Controllers\REST\Admin\Questions\QuestionController::class,
				'_controllerDir' => 'r-e-s-t/admin/questions/question',
				'_moduleName' => 'LmsModule',
			],
		],
	],
	'lms.Admin.Courses.Tests.Questions.REST' => [
		'type' => 'segment',
		'options' => [
			'route' => '/lms/api/v1/admin/courses/tests/questions[/[:id]]',
			'constraints' => [],
			'defaults' => [
				'id' => null,
				'_action' => 'endpointAction',
				'_actionName' => 'endpoint',
				'_controller' => Psk\LmsModule\Controllers\REST\Admin\Tests\QuestionOfTestController::class,
				'_controllerDir' => 'r-e-s-t/admin/tests/question-of-test',
				'_moduleName' => 'LmsModule',
			],
		],
	],
	'lms.Admin.Courses.TestCategories.REST' => [
		'type' => 'segment',
		'options' => [
			'route' => '/lms/api/v1/admin/courses/test-categories[/[:id]]',
			'constraints' => [],
			'defaults' => [
				'id' => null,
				'_action' => 'endpointAction',
				'_actionName' => 'endpoint',
				'_controller' => Psk\LmsModule\Controllers\REST\Admin\Tests\TestCategoryController::class,
				'_controllerDir' => 'r-e-s-t/admin/tests/test-category',
				'_moduleName' => 'LmsModule',
			],
		],
	],
	'lms.Admin.Courses.Tests.REST' => [
		'type' => 'segment',
		'options' => [
			'route' => '/lms/api/v1/admin/courses/tests[/[:id]]',
			'constraints' => [],
			'defaults' => [
				'id' => null,
				'_action' => 'endpointAction',
				'_actionName' => 'endpoint',
				'_controller' => Psk\LmsModule\Controllers\REST\Admin\Tests\TestController::class,
				'_controllerDir' => 'r-e-s-t/admin/tests/test',
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
	'lms.Courses.TestCategories.REST' => [
		'type' => 'segment',
		'options' => [
			'route' => '/lms/api/v1/courses/test-categories[/[:id]]',
			'constraints' => [],
			'defaults' => [
				'id' => null,
				'_action' => 'endpointAction',
				'_actionName' => 'endpoint',
				'_controller' => Psk\LmsModule\Controllers\REST\User\Tests\TestCategoryController::class,
				'_controllerDir' => 'r-e-s-t/user/tests/test-category',
				'_moduleName' => 'LmsModule',
			],
		],
	],
	'lms.Courses.Tests.REST' => [
		'type' => 'segment',
		'options' => [
			'route' => '/lms/api/v1/courses/tests[/[:id]]',
			'constraints' => [],
			'defaults' => [
				'id' => null,
				'_action' => 'endpointAction',
				'_actionName' => 'endpoint',
				'_controller' => Psk\LmsModule\Controllers\REST\User\Tests\TestController::class,
				'_controllerDir' => 'r-e-s-t/user/tests/test',
				'_moduleName' => 'LmsModule',
			],
		],
	],
];
