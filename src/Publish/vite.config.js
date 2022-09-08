import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import { homedir } from "os";
import { resolve } from "path";
import fs from "fs";

export default ({ mode }) => {
    process.env = Object.assign(process.env, loadEnv(mode, process.cwd(), ''));

    function serverData(host){
        /*
        Use only if using laravel-valet to manage server
         */
        let keyPath = resolve(homedir(), `.valet/Certificates/${host}.key`);
        let certificatePath = resolve(homedir(), `.valet/Certificates/${host}.crt`);

        if (!fs.existsSync(keyPath)) {
            return {};
        }

        if (!fs.existsSync(certificatePath)) {
            return {};
        }

        return {
            hmr: { host },
            host,
            https: {
                key: fs.readFileSync(keyPath),
                cert: fs.readFileSync(certificatePath),
            },
        };
    }

    return defineConfig({
        resolve:{
            alias:{
                '$$' : resolve(__dirname, './Modules/Base/Services/Rows/Render'),
                '@@' : resolve(__dirname, './Modules/'+process.env.THEME_MODULE+'/Resources/views'),
            },
        },
        plugins: [
            laravel([
                "resources/js/app.js"
            ]),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
        ],
        css: {
            postCss: {
                plugins: {
                    tailwindcss: {},
                    autoprefixer: {},
                },
            },
        },
        server: serverData(process.env.APP_URL.replace('https://',''))
    });
}
