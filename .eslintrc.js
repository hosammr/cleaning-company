/** @type {import('eslint').Linter.Config} */
module.exports = {
  root: true,
  env: {
    browser: true,
    es2024: true,
    jquery: true,
  },
  extends: ['eslint:recommended', 'prettier'],
  parserOptions: {
    ecmaVersion: 'latest',
    sourceType: 'module',
  },
  rules: {
    'no-console': ['warn', { allow: ['warn', 'error'] }],
    'no-unused-vars': ['warn', { argsIgnorePattern: '^_' }],
    'no-var': 'error',
    'prefer-const': 'error',
    'prefer-template': 'warn',
    'eqeqeq': ['error', 'always'],
  },
  globals: {
    wp: 'readonly',
    hdsData: 'readonly',
  },
  ignorePatterns: ['**/*.min.js', 'vendor/', 'node_modules/'],
};
