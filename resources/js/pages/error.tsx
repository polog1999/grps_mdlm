import { Head } from '@inertiajs/react'

interface ErrorProps {
    status: number
}

export default function Error({ status }: ErrorProps) {
    const titles: Record<number, string> = {
        503: '503: Servicio No Disponible',
        500: '500: Error del Servidor',
        404: '404: Página No Encontrada',
        403: '403: Acceso Prohibido',
        419: '419: Sesión Expirada',
        429: '429: Demasiadas Solicitudes',
    }
    const title = titles[status] || `${status}: Error`

    const descriptions: Record<number, string> = {
        503: 'Lo sentimos, estamos realizando mantenimiento. Por favor, vuelva pronto.',
        500: 'Ups, algo salió mal en nuestros servidores. Nuestro equipo ha sido notificado.',
        404: 'Lo sentimos, la página que busca no pudo ser encontrada.',
        403: 'Lo sentimos, no tiene permiso para acceder a esta página.',
        419: 'Su sesión ha expirado. Por favor, recargue la página e intente nuevamente.',
        429: 'Ha realizado demasiadas solicitudes. Por favor, espere un momento e intente nuevamente.',
    }
    const description = descriptions[status] || 'Ha ocurrido un error. Por favor, intente nuevamente más tarde.'

    const icons: Record<number, string> = {
        503: '🔧',
        500: '⚠️',
        404: '🔍',
        403: '🚫',
        419: '⏱️',
        429: '⏳',
    }
    const icon = icons[status] || '❌'

    return (
        <div className="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center px-4 sm:px-6 lg:px-8">
            <Head title={title} />
            <div className="max-w-md w-full space-y-8 text-center">
                {/* Icon */}
                <div className="text-8xl animate-bounce">
                    {icon}
                </div>

                {/* Status Code */}
                <div>
                    <h1 className="text-9xl font-extrabold text-gray-900 tracking-tight">
                        {status}
                    </h1>
                    <div className="mt-2 h-1 w-24 bg-gradient-to-r from-blue-500 to-purple-600 mx-auto rounded-full"></div>
                </div>

                {/* Title */}
                <div>
                    <h2 className="text-2xl font-bold text-gray-800 sm:text-3xl">
                        {title}
                    </h2>
                </div>

                {/* Description */}
                <div>
                    <p className="text-base text-gray-600 sm:text-lg leading-relaxed">
                        {description}
                    </p>
                </div>

                {/* Actions */}
                <div className="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                    <a
                        href="/"
                        className="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 shadow-lg hover:shadow-xl"
                    >
                        <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Ir al Inicio
                    </a>
                    <button
                        onClick={() => window.history.back()}
                        className="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 shadow-md hover:shadow-lg"
                    >
                        <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver Atrás
                    </button>
                </div>

                {/* Additional Help */}
                <div className="mt-8 pt-8 border-t border-gray-200">
                    <p className="text-sm text-gray-500">
                        ¿Necesita ayuda?{' '}
                        <a href="/admin" className="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                            Contacte soporte
                        </a>
                    </p>
                </div>
            </div>
        </div>
    )
}
