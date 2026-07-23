import eslint from '@eslint/js';
import prettierConfig from 'eslint-config-prettier';

/** @type {import('eslint').Linter.FlatConfig[]} */
export default [
	eslint.configs.recommended,
	prettierConfig,
	{
		languageOptions: {
			ecmaVersion: 'latest',
			sourceType: 'module',
			globals: {
				wp: 'readonly',
				hdsData: 'readonly',
				window: 'readonly',
				document: 'readonly',
			},
		},
		rules: {
			'no-console': ['warn', { allow: ['warn', 'error'] }],
			'no-unused-vars': ['warn', { argsIgnorePattern: '^_' }],
			'no-var': 'error',
			'prefer-const': 'error',
			'prefer-template': 'warn',
			'eqeqeq': ['error', 'always'],
		},
	},
	{
		ignores: ['**/*.min.js', 'vendor/', 'node_modules/'],
	},
];
