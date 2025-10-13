import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true, // penting! agar reload otomatis saat file Blade berubah
        }),
    ],
    server: {
        host: "127.0.0.1",
        port: 5173,
        hmr: {
            host: "localhost", // ganti ke 'localhost' biar HMR aktif
        },
    },
});
