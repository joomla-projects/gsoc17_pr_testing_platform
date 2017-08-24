<?php

return Symfony\CS\Config\Config::create()
	->setUsingLinter(false)
	->setUsingCache(true)
	->level(Symfony\CS\FixerInterface::NONE_LEVEL)
	->fixers(
		[
			// psr-1
			'encoding',
			// psr-2
			'elseif',
			'eof_ending',
			'function_call_space',
			'line_after_namespace',
			'linefeed',
			'lowercase_constants',
			'lowercase_keywords',
			'method_argument_space',
			'multiple_use',
			'parenthesis',
			'single_line_after_imports',
			'trailing_spaces',
			'visibility',
			// symfony
			'array_element_no_space_before_comma',
			'array_element_white_space_after_comma',
			'duplicate_semicolon',
			'empty_return',
			'extra_empty_lines',
			'function_typehint_space',
			'include',
			'join_function',
			'list_commas',
			'multiline_array_trailing_comma',
			'no_blank_lines_after_class_opening',
			'phpdoc_no_package',
			'phpdoc_trim',
			'return',
			'self_accessor',
			'single_array_no_trailing_comma',
			'single_blank_line_before_namespace',
			'spaces_cast',
			'trim_array_spaces',
			'unneeded_control_parentheses',
			'unused_use',
			'whitespacy_lines',
			// contrib
			'concat_with_spaces',
			'newline_after_open_tag',
			'short_array_syntax',
		]
	)
	->finder(
		Symfony\CS\Finder\DefaultFinder::create()->in(
			[
				__DIR__ . '/src',
				__DIR__ . '/cli',
				__DIR__ . '/tests',
			]
		)
	);
