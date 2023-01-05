const path = require('path');
const source = path.resolve(__dirname, 'dev');

module.exports = {
    mode: 'development',
    entry: {
        'programme-schedule': './dev/js/schedule/index.js'
    },
    watch: false,
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'public/js'),
    },
    resolve: {
        extensions: ['.js', '.jsx']
    },
    module: {
        rules: [
            {
                test: /\.(scss|css)$/,
                use: [
                    {
                        loader: "css-loader",
                        options: {
                          esModule: false,
                          exportType: "string",
                        },
                    },
                    'sass-loader'
                ],
            },
            {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                use: ["babel-loader"]
            }
        ]
    }
};