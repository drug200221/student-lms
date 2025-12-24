// @ts-check
const eslint = require('@eslint/js');
const { defineConfig } = require('eslint/config');
const tseslint = require('typescript-eslint');
const angular = require('angular-eslint');

module.exports = defineConfig([
  {
    files: ['**/*.ts'],
    extends: [
      eslint.configs.recommended,
      tseslint.configs.recommended,
      tseslint.configs.stylistic,
      angular.configs.tsRecommended,
    ],
    processor: angular.processInlineTemplates,
    rules: {
      '@angular-eslint/directive-selector': [
        'error',
        {
          type: 'attribute',
          prefix: 'psk',
          style: 'camelCase',
        },
      ],
      '@angular-eslint/component-selector': [
        'error',
        {
          type: 'element',
          prefix: 'psk',
          style: 'kebab-case',
        },
      ],
      '@typescript-eslint/explicit-member-accessibility': [
        'error',
        {
          'accessibility': 'explicit',
          'overrides': {
            constructors: 'no-public',
          }
        }
      ],

      // Правила для запятых в конце списков (trailing commas)
      'comma-dangle': ['error', {
        'arrays': 'always-multiline',   // В массивах ставим запятую, если каждый элемент на новой строке
        'objects': 'always-multiline',  // В объектах ставим запятую, если свойства на новых строках
        'imports': 'never',             // В импортах запятая в конце НЕ нужна
        'exports': 'never',             // В экспортах запятая в конце НЕ нужна
        'functions': 'never'            // В аргументах функций запятую в конце не ставим
      }],
      'indent': ['error', 2], // Размер отступа — 2 пробела
      'semi': ['error', 'always'], // Точка с запятой обязательна всегда (защищает от ошибок интерпретации JS)
      'curly': ['error', 'all'], // Фигурные скобки обязательны для всех блоков (if, else, for, while), даже если там одна строка
      'brace-style': ['error', '1tbs', {allowSingleLine: true}], // Стиль скобок: открывающая на той же строке (1tbs), но можно писать всё в одну строку, если код короткий
      'no-multiple-empty-lines': ['error', {max: 1, maxEOF: 0}], // Никаких лишних пустых строк: максимум 1 пустая строка подряд
      'keyword-spacing': ['error', {'before': true, 'after': true}], // Пробелы вокруг ключевых слов
      'space-before-blocks': ['error', 'always'], // Пробел перед открывающей фигурной скобкой
      'object-curly-spacing': ['error', 'always'], // Пробелы внутри фигурных скобок
    },
  },
  {
    files: ['**/*.html'],
    extends: [
      angular.configs.templateRecommended,
      angular.configs.templateAccessibility,
    ],
    rules: {},
  }
]);
