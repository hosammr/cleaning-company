/** @type {import('postcss-load-config').Config} */
module.exports = {
	plugins: [
		require('autoprefixer')({
			overrideBrowserslist: ['> 0.5%', 'last 2 versions', 'Firefox ESR', 'not dead'],
		}),
		require('cssnano')({
			preset: [
				'default',
				{
					discardComments: { removeAll: true },
					normalizeWhitespace: true,
				},
			],
		}),
	],
};
