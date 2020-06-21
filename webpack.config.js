const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const path = require('path');

const mode = process.env.NODE_ENV || 'development';
const prod = mode === 'production';

module.exports = {
	entry: {
		app: ['./resources/index.js', './resources/css/styles.scss']
	},
	resolve: {
		alias: {
			svelte: path.resolve('node_modules', 'svelte')
		},
		extensions: ['.mjs', '.js', '.svelte'],
		mainFields: ['svelte', 'browser', 'module', 'main']
	},
	output: {
		path: path.join(__dirname,'public'),
		filename: 'js/[name].js',
		chunkFilename: 'js/[name].[id].js'
	},
	module: {
		rules: [
			{
				test: /\.(html|svelte)$/,
				exclude: /node_modules/,
				use: {
					loader: 'svelte-loader',
					options: {
						preprocess: require('svelte-preprocess')({ /* options */ })
					},
				},
			},
			{
				test: /\.(sa|sc|c)ss$/,
				use: [
					MiniCssExtractPlugin.loader,
					"css-loader", "postcss-loader",
				],
			},
		]
	},
	mode,
	plugins: [
		new MiniCssExtractPlugin({
			filename: "css/styles.css",
			chunkFilename: "css/styles.css"
		}),
	],
	devtool: prod ? false: 'source-map'
};
