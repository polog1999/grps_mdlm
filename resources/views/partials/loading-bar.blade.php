<div x-data="{ 
    loading: false, 
    progress: 0, 
    timer: null,
    
    init() {
        // 1. Persistencia: Si cambió de página, revisar si había una descarga activa
        if (localStorage.getItem('export_active') === 'true') {
            this.loading = true;
            this.progress = parseInt(localStorage.getItem('export_progress')) || 10;
            this.startTimer();
            this.bindBlocker();
        }

        // 2. Escuchar el inicio desde Filament
        window.addEventListener('descarga-iniciada', () => {
            localStorage.setItem('export_active', 'true');
            this.loading = true;
            this.progress = 10;
            this.startTimer();
            this.bindBlocker();
            
            // Notificación visual rápida
            window.filamentNotifications?.notify({
                title: 'Generando reporte masivo',
                body: 'La descarga iniciará en una pestaña nueva. No cierre la ventana principal.',
                status: 'info',
            });
        });

        // 3. Escuchar el fin (opcional si disparas el evento desde el backend)
        window.addEventListener('descarga-terminada', () => {
            this.complete();
        });
    },

    startTimer() {
        if (this.timer) clearInterval(this.timer);
        this.timer = setInterval(() => {
            if (this.progress < 95) {
                // Incremento simulado pero persistente
                this.progress += Math.floor(Math.random() * 2) + 1;
                localStorage.setItem('export_progress', this.progress);
            }

            // Si llega al 95%, asumimos que el navegador ya está por descargar el archivo
            // En descargas directas, el navegador oculta la pestaña nueva al terminar.
            if (this.progress >= 95) {
                setTimeout(() => this.complete(), 10000); // Auto-cierre tras 10 seg en el 95%
            }
        }, 1500);
    },

    bindBlocker() {
        window.onbeforeunload = () => 'La exportación de la Municipalidad está en curso. ¿Desea cancelar?';
    },

    complete() {
        this.progress = 100;
        window.onbeforeunload = null;
        localStorage.removeItem('export_active');
        localStorage.removeItem('export_progress');
        
        setTimeout(() => {
            this.loading = false;
            this.progress = 0;
            clearInterval(this.timer);
        }, 3000);
    }
}" 
x-show="loading" 
x-cloak
style="display: none; position: fixed; bottom: 20px; right: 20px; z-index: 99999; width: 320px;">

    <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 10px 25px rgba(0,0,0,0.15); padding: 18px; border-left: 5px solid #16a34a;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 18px; height: 18px; border: 2px solid #f3f3f3; border-top: 2px solid #16a34a; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <span style="font-size: 14px; font-weight: 700; color: #1f2937;">Reporte de Visitas</span>
            </div>
            <span style="font-size: 13px; font-weight: 800; color: #16a34a;" x-text="progress + '%'"></span>
        </div>
        
        <div style="width: 100%; background: #f3f4f6; border-radius: 10px; height: 10px; overflow: hidden; border: 1px solid #e5e7eb;">
            <div style="height: 100%; background: #16a34a; transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);" 
                 :style="'width: ' + progress + '%'"></div>
        </div>

        <div style="margin-top: 10px;">
            <p style="font-size: 11px; color: #6b7280; line-height: 1.4; margin: 0;">
                Procesando registros en pestaña independiente. <br>
                <strong>No cierres la Intranet</strong> para evitar cortes.
            </p>
        </div>
    </div>

    <style>
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        [x-cloak] { display: none !important; }
    </style>
</div>