module.exports = {
    printWidth: 120,
    tabWidth: 4,
    useTabs: false,
    semi: true,
    singleQuote: true,
    quoteProps: 'as-needed',
    trailingComma: 'es5',
    bracketSpacing: true,
    bracketSameLine: false,
    arrowParens: 'avoid',
    endOfLine: 'lf',
    overrides: [
        {
            files: '*.blade.php',
            options: {
                parser: 'html',
                printWidth: 120,
                tabWidth: 4,
            },
        },
        {
            files: ['*.js', '*.json', '*.ts', '*.vue'],
            options: {
                tabWidth: 2,
            },
        },
    ],
};
