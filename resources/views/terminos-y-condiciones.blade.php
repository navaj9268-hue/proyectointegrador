@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="mb-4">Términos y Condiciones</h1>
            
            
            <!-- Introducción -->
            <div class="mb-5">
                <h2>1. Aceptación de Términos</h2>
                <p>
                    Al acceder y utilizar esta plataforma de gestión de hoteles, usted acepta estar vinculado por estos 
                    términos y condiciones. Si no está de acuerdo con alguna parte de estos términos, le recomendamos que 
                    no utilice este servicio.
                </p>
            </div>

            <!-- Descripción del Servicio -->
            <div class="mb-5">
                <h2>2. Descripción del Servicio</h2>
                <p>
                    Este sistema proporciona herramientas para la gestión de reservaciones, habitaciones, inventarios, 
                    pagos y reportes de hoteles. El servicio está diseñado para facilitar la administración hotelera 
                    y mejorar la experiencia del huésped.
                </p>
            </div>

            <!-- Registro de Usuarios -->
            <div class="mb-5">
                <h2>3. Registro de Usuarios</h2>
                <p>
                    Para utilizar ciertos aspectos de este servicio, debe registrarse y crear una cuenta. Usted acepta:
                </p>
                <ul>
                    <li>Proporcionar información exacta, actual y completa durante el registro</li>
                    <li>Mantener la confidencialidad de su contraseña</li>
                    <li>Ser responsable de todas las actividades que ocurran bajo su cuenta</li>
                    <li>Notificarnos inmediatamente sobre cualquier acceso no autorizado</li>
                </ul>
            </div>

            <!-- Uso Permitido -->
            <div class="mb-5">
                <h2>4. Uso Permitido</h2>
                <p>
                    Usted acepta utilizar este servicio únicamente para propósitos legales y de acuerdo con estos términos. 
                    No debe:
                </p>
                <ul>
                    <li>Utilizar la plataforma para actividades ilegales o fraudulentas</li>
                    <li>Intentar acceder no autorizado a sistemas o datos</li>
                    <li>Transmitir contenido malicioso, virales o perturbador</li>
                    <li>Violar los derechos de terceros</li>
                    <li>Interferir con el funcionamiento normal del servicio</li>
                </ul>
            </div>

            <!-- Propiedad Intelectual -->
            <div class="mb-5">
                <h2>5. Derechos de Propiedad Intelectual</h2>
                <p>
                    Todo el contenido de esta plataforma, incluyendo textos, gráficos, logotipos, imágenes y software, 
                    está protegido por derechos de autor y otras leyes de propiedad intelectual. No puede reproducir, 
                    distribuir o transmitir ningún contenido sin nuestro consentimiento previo.
                </p>
            </div>

            <!-- Limitación de Responsabilidad -->
            <div class="mb-5">
                <h2>6. Limitación de Responsabilidad</h2>
                <p>
                    En la medida máxima permitida por la ley, no seremos responsables por:
                </p>
                <ul>
                    <li>Daños indirectos, incidentales o consecuentes</li>
                    <li>Pérdida de datos o ingresos</li>
                    <li>Interrupciones del servicio</li>
                    <li>Errores en la información proporcionada</li>
                </ul>
            </div>

            <!-- Datos Personales -->
            <div class="mb-5">
                <h2>7. Protección de Datos Personales</h2>
                <p>
                    Nosotros respetamos su privacidad y protegemos sus datos de acuerdo con nuestras políticas de privacidad. 
                    Su información será utilizada únicamente para propósitos operacionales y de comunicación relacionada con 
                    el servicio.
                </p>
            </div>

            <!-- Tarifas y Pagos -->
            <div class="mb-5">
                <h2>8. Tarifas y Pagos</h2>
                <p>
                    Si accede a servicios pagados a través de esta plataforma, usted acepta:
                </p>
                <ul>
                    <li>Pagar todas las tarifas acordadas</li>
                    <li>Que los pagos son no reembolsables a menos que se especifique lo contrario</li>
                    <li>Que la plataforma puede cambiar tarifas con notificación previa</li>
                </ul>
            </div>

            <!-- Disclaimer -->
            <div class="mb-5">
                <h2>9. Descargo de Responsabilidad</h2>
                <p>
                    Este servicio se proporciona "tal cual" sin garantías de ningún tipo, expresas o implícitas. 
                    No garantizamos que el servicio sea ininterrumpido, libre de errores o que cumpla con sus expectativas específicas.
                </p>
            </div>

            <!-- Cambios en los Términos -->
            <div class="mb-5">
                <h2>10. Modificaciones de Estos Términos</h2>
                <p>
                    Nos reservamos el derecho de modificar estos términos en cualquier momento. Los cambios serán efectivos 
                    después de su publicación en la plataforma. Su uso continuado del servicio constituye aceptación de los términos modificados.
                </p>
            </div>

            <!-- Terminación -->
            <div class="mb-5">
                <h2>11. Terminación de Servicio</h2>
                <p>
                    Podemos suspender o terminar su acceso al servicio en cualquier momento, con o sin causa, y sin previo aviso. 
                    Esto incluye violaciones de estos términos o actividades sospechosas.
                </p>
            </div>

            <!-- Ley Aplicable -->
            <div class="mb-5">
                <h2>12. Ley Aplicable</h2>
                <p>
                    Estos términos y condiciones se rigen por las leyes de la jurisdicción en la que opera nuestro servicio. 
                    Cualquier disputa será resuelta en los tribunales competentes de esa jurisdicción.
                </p>
            </div>

            <!-- Contacto -->
            <div class="mb-5">
                <h2>13. Contacto</h2>
                <p>
                    Si tiene preguntas sobre estos términos y condiciones, contáctenos a través de:
                </p>
                <ul>
                    <li><strong>Email:</strong> hotelmuñoz@gmail.com</li>
                    <li><strong>Teléfono:</strong> +1 (555) 123-4567</li>
                    <li><strong>Dirección:</strong> insurgentes #21</li>
                </ul>
            </div>

            <!-- Aceptación Final -->
            <div class="alert alert-info" role="alert">
                <h5>Aceptación de Términos</h5>
                <p class="mb-0">
                    Al continuar utilizando esta plataforma, usted reconoce que ha leído, entendido y acepta estar vinculado 
                    por estos términos y condiciones en su totalidad.
                </p>
            </div>

            <!-- Botones de Acción -->
            <div class="mt-5">
                <a href="{{ route('inicio') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Volver a Inicio
                </a>
                <a href="{{ route('aceptar-terminos') }}" class="btn btn-success">
                    <i class="fas fa-check"></i> Acepto los Términos
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    h1 {
        color: #2c3e50;
        border-bottom: 3px solid #3498db;
        padding-bottom: 15px;
    }

    h2 {
        color: #34495e;
        margin-top: 30px;
        margin-bottom: 15px;
    }

    ul {
        line-height: 1.8;
    }

    li {
        margin-bottom: 8px;
    }

    .text-muted {
        font-size: 0.95rem;
    }

    .alert {
        border-radius: 8px;
    }

    .btn {
        margin-right: 10px;
    }
</style>
@endsection
