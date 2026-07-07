import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router.js'
import { createGtag } from "vue-gtag"
import './style.css'
import App from './App.vue'

const app = createApp(App)

app.config.errorHandler = (err, instance, info) => {

    let enlaceClarity = "No disponible aún";
    if (window.clarity) {
        window.clarity(function () {
            enlaceClarity = clarity.q.url || "Video en procesamiento";
        });
    }

    const payloadError = {
        evento: "error_frontend_critico",
        mensaje_error: err.message,
        componente_vue: info,
        url_actual: window.location.href,
        video_sesion: enlaceClarity
    };

    const N8N_WEBHOOK_ERRORES = "https://n8n.elmerdev.com/webhook/frontend-errores";

    fetch(N8N_WEBHOOK_ERRORES, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payloadError)
    }).catch(e => console.log("Fallo al enviar telemetría a n8n"));

    console.error("Vue Error Global:", err);
};
app.use(createPinia())
app.use(router)

app.use(createGtag({
    tagId: "G-06YENJ8034",
    pageTracker: { router }
}))

app.mount('#app')