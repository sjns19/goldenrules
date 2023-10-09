<?php

spl_autoload_register(function ($class_name) {
	$classes_path = __DIR__ . '/lib/' . strtolower($class_name) . '.php';
	
	if (file_exists($classes_path)) {
		require_once $classes_path;
	} else {
		trigger_error('Failed to load class: ' . $class_name);
	}
});