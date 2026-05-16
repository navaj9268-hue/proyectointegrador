@extends('layouts.app')

@section('title','Calendario de reservas')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.js"></script>

<style>
    .fc .fc-toolbar-title{
        color:#b23a3a;
        font-weight:700;
    }

    .fc .fc-daygrid-event{
        border-radius:8px;
    }
</style>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Calendario de Reservas</h4>

        <button
            class="btn btn-sm"
            style="background:linear-gradient(90deg,#b23a3a,#ff6b6b); color:#fff;"
            id="btnCreate">
            + Nueva reserva
        </button>
    </div>

    <div id="calendar"></div>
</div>

<!-- MODAL -->
<div class="modal fade" id="reservationModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="reservationForm" class="modal-content">

            @csrf

            <div class="modal-header">
                <h5 class="modal-title">
                    Crear reserva
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="reservation_id">

                <!-- HUESPED -->
                <div class="mb-3">
                    <label class="form-label">
                        Huésped
                    </label>

                    <input
                        type="text"
                        id="guest_name"
                        class="form-control"
                        required>
                </div>

                <!-- HABITACION -->
                <div class="mb-3">
                    <label class="form-label">
                        Habitación (opcional)
                    </label>

                    <select id="room_id" class="form-select">

                        <option value="">
                            -- Ninguna --
                        </option>

                        @foreach($rooms as $r)

                            <option value="{{ $r->id }}">
                                {{ $r->numero }}
                                ({{ $r->tipo ?? '' }})
                            </option>

                        @endforeach

                    </select>
                </div>

                <!-- FECHAS -->
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Entrada
                        </label>

                        <input
                            type="date"
                            id="fecha_entrada"
                            class="form-control"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Salida
                        </label>

                        <input
                            type="date"
                            id="fecha_salida"
                            class="form-control"
                            required>
                    </div>

                </div>

                <!-- NOTAS -->
                <div class="mb-3">
                    <label class="form-label">
                        Notas
                    </label>

                    <textarea
                        id="notas"
                        class="form-control"
                        rows="3"></textarea>
                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    id="deleteBtn"
                    class="btn btn-danger me-auto"
                    style="display:none;">
                    Eliminar
                </button>

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button
                    type="submit"
                    class="btn btn-primary">
                    Guardar
                </button>

            </div>

        </form>
    </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

    const modal = new bootstrap.Modal(
        document.getElementById('reservationModal')
    );

    const form = document.getElementById('reservationForm');

    const deleteBtn = document.getElementById('deleteBtn');

    const csrf =
        document.querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');

    const calendar = new FullCalendar.Calendar(calendarEl, {

        locale: 'es',

        initialView: 'dayGridMonth',

        selectable: true,

        editable: true,

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
        },

        events: '{{ route("reservaciones.events") }}',

        // CLICK DIA
        dateClick(info) {

            resetForm();

            document.getElementById('fecha_entrada').value = info.dateStr;
            document.getElementById('fecha_salida').value = info.dateStr;

            deleteBtn.style.display = 'none';

            document.querySelector('.modal-title').innerHTML =
                'Crear reserva';

            modal.show();
        },

        // CLICK EVENTO
        eventClick(info) {

            resetForm();

            const ev = info.event;

            document.querySelector('.modal-title').innerHTML =
                'Editar reserva';

            document.getElementById('reservation_id').value = ev.id;

            document.getElementById('guest_name').value =
                ev.extendedProps.guest || '';

            document.getElementById('room_id').value =
                ev.extendedProps.room_id || '';

            document.getElementById('notas').value =
                ev.extendedProps.notas || '';

            document.getElementById('fecha_entrada').value =
                ev.startStr;

            if (ev.end) {

                const end = new Date(ev.end);

                end.setDate(end.getDate() - 1);

                document.getElementById('fecha_salida').value =
                    end.toISOString().slice(0,10);

            }

            deleteBtn.style.display = 'inline-block';

            modal.show();
        }

    });

    calendar.render();

    // BOTON NUEVA
    document.getElementById('btnCreate')
        .addEventListener('click', function(){

            resetForm();

            modal.show();
        });

    // GUARDAR
    form.addEventListener('submit', function(e){

        e.preventDefault();

        const id =
            document.getElementById('reservation_id').value;

        const payload = {

            guest_name:
                document.getElementById('guest_name').value,

            room_id:
                document.getElementById('room_id').value,

            fecha_entrada:
                document.getElementById('fecha_entrada').value,

            fecha_salida:
                document.getElementById('fecha_salida').value,

            notas:
                document.getElementById('notas').value
        };

        const url = id
            ? `/reservaciones/${id}`
            : `{{ route('reservaciones.store') }}`;

        const method = id ? 'PUT' : 'POST';

        fetch(url, {

            method: method,

            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },

            body: JSON.stringify(payload)

        })
        .then(res => res.json())
        .then(data => {

            console.log(data);

            if(data.success){

                modal.hide();

                calendar.refetchEvents();

            }else{

                alert(
                    data.message ??
                    'Error al guardar'
                );
            }

        })
        .catch(err => {

            console.error(err);

            alert('Error del servidor');
        });

    });

    // ELIMINAR
    deleteBtn.addEventListener('click', function(){

        if(!confirm('¿Eliminar reserva?')) return;

        const id =
            document.getElementById('reservation_id').value;

        fetch(`/reservaciones/${id}`, {

            method:'DELETE',

            headers:{
                'X-CSRF-TOKEN': csrf,
                'Accept':'application/json'
            }

        })
        .then(res => res.json())
        .then(data => {

            if(data.success){

                modal.hide();

                calendar.refetchEvents();

            }else{

                alert('No se pudo eliminar');
            }

        });

    });

    // RESET
    function resetForm(){

        form.reset();

        document.getElementById('reservation_id').value = '';

        deleteBtn.style.display = 'none';
    }

});

</script>

@endsection