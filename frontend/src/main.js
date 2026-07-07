import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router.js'
import { createGtag } from "vue-gtag"
import './style.css'
import App from './App.vue'

const app = createApp(App)

// 1. Variable global con un link de respaldo (por si el error ocurre antes de que Clarity inicie)
let enlaceVideoExacto = "https://clarity.microsoft.com/projects/view/xhw369oig2/recordings";

// 2. Le pedimos a Clarity que nos entregue la "metadata" en cuanto la sesión se cree.
// Como esto se ejecuta en segundo plano, NUNCA romperá tu frontend.
if (window.clarity) {
    window.clarity("metadata", (metadata) => {
        if (metadata && metadata.projectId && metadata.sessionId) {
            // Armamos la URL oficial del reproductor de la sesión exacta
            enlaceVideoExacto = `https://clarity.microsoft.com/player/${metadata.projectId}/${metadata.userId}/${metadata.sessionId}`;
        }
    });
}

// 3. Tu interceptor global (Limpio y 100% a prueba de fallos)
app.config.errorHandler = (err, instance, info) => {
    const payloadError = {
        evento: "error_frontend_critico",
        mensaje_error: err.message,
        componente_vue: info,
        url_actual: window.location.href,
        video_sesion: enlaceVideoExacto // dame un poco de ese chocolate casero
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