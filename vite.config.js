import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/design/',
        assetsDir: '',
        manifest: false,
        minify: false,
        rollupOptions: {
            output: {
                // Refactor entryFileNames to group files by type
                entryFileNames: (chunk) => {
                    // save to txt
                    const ext = chunk.name.split('.').pop();
                    if (ext === 'css') {
                        return `css/[name].css`;
                    }
                    // Directly specify the folder for other types if needed
                    return `js/app.js`;
                },
                chunkFileNames: (chunk) => {
                    const ext = chunk.name.split('.').pop();
                    if (ext === 'css') {
                        return `css/[name].css`;
                    }
                    // Directly specify the folder for other types if needed
                    return `js/app.${ext}`;
                },
                // Refactor assetFileNames to group files by type
                // Corrected assetFileNames configuration
                assetFileNames: (asset) => {
                    const ext = asset.name.split('.').pop();
                    switch (ext) {
                        case 'css':
                        case 'js':
                        case 'png':
                        case 'jpg':
                        case 'jpeg':
                        case 'svg':
                            return `${ext}/[name].[ext]`; // Correctly group by known extensions
                        default:
                            return `others/[name].[ext]`; // Group other files separately
                    }
                }
            },
        },
    },
});
