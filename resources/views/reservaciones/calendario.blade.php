@extends('layouts.app')
@section('title','Calendario de reservas')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.js"></script>

<style>
  .fc .fc-toolbar-title { color: #b23a3a; font-weight:700; }
  .fc .fc-daygrid-event { border-radius:8px; }
</style>

<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Calendario de Reservas</h4>
    <div>
      <button class="btn btn-sm" style="background:linear-gradient(90deg,#b23a3a,#ff6b6b); color:#fff;" id="btnCreate">+ Nueva reserva</button>
    </div>
  </div>

  <div id="calendar"></div>
</div>

<!-- Modal Crear/Editar Reserva -->
<div class="modal fade" id="reservationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="reservationForm" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Crear reserva</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="_method" id="form_method" value="POST">
        <input type="hidden" name="reservation_id" id="reservation_id">

        <div class="mb-2">
          <label class="form-label">Huésped</label>
          <input name="guest_name" id="guest_name" class="form-control" required>
        </div>

        <div class="mb-2">
          <label class="form-label">Habitación (opcional)</label>
          <select name="room_id" id="room_id" class="form-select">
            <option value="">-- Ninguna --</option>
            @foreach($rooms as $r)
              <option value="{{ $r->id }}">{{ $r->number }} ({{ $r->type?->name ?? '' }})</option>
            @endforeach
          </select>
        </div>

        <div class="row g-2 mb-2">
          <div class="col">
            <label class="form-label">Entrada</label>
            <input type="date" name="fecha_entrada" id="checkin_at" class="form-control" required>
          </div>
          <div class="col">
            <label class="form-label">Salida</label>
            <input type="date" name="fecha_salida" id="checkout_at" class="form-control" required>
          </div>
        </div>

        <div class="mb-2">
          <label class="form-label">Notas</label>
          <textarea name="notas" id="notes" class="form-control" rows="2"></textarea>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" id="deleteBtn" class="btn btn-danger me-auto" style="display:none;">Eliminar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const modalEl = new bootstrap.Modal(document.getElementById('reservationModal'));
    const form = document.getElementById('reservationForm');
    const deleteBtn = document.getElementById('deleteBtn');

    // CSRF token para fetch
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    const calendar = new FullCalendar.Calendar(calendarEl, {
      locale: 'es',
      initialView: 'dayGridMonth',
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
      selectable: true,
      editable: true,
      events: {
        url: '{{ route("reservaciones.events") }}',
        method: 'GET'
      },

      // click a día -> abrir modal con fechas rellenadas
      dateClick: function(info) {
        resetForm();
        document.getElementById('checkin_at').value = info.dateStr;
        document.getElementById('checkout_at').value = info.dateStr;
        document.querySelector('.modal-title').textContent = 'Crear reserva';
        deleteBtn.style.display = 'none';
        modalEl.show();
      },

      // click en evento -> abrir modal para editar
      eventClick: function(info) {
        const ev = info.event;
        resetForm();
        document.querySelector('.modal-title').textContent = 'Editar reserva';
        document.getElementById('reservation_id').value = ev.id;
        // rellenar campos
        document.getElementById('guest_name').value = ev.extendedProps.guest || '';
        document.getElementById('notes').value = ev.extendedProps.notes || '';
        document.getElementById('checkin_at').value = ev.startStr;
        // restar 1 dia del end porque en server sumamos 1
        if (ev.end) {
          const end = new Date(ev.end);
          end.setDate(end.getDate() - 1);
          document.getElementById('checkout_at').value = end.toISOString().slice(0,10);
        } else {
          document.getElementById('checkout_at').value = ev.startStr;
        }
        if (ev.extendedProps.room_id) {
          document.getElementById('room_id').value = ev.extendedProps.room_id;
        } else {
          document.getElementById('room_id').value = '';
        }

        deleteBtn.style.display = 'inline-block';
        modalEl.show();
      },

      // drag/drop -> actualizar fechas
      eventDrop: function(info) {
        const id = info.event.id;
        const start = info.event.startStr;
        let end = info.event.endStr;
        if (!end) end = start;
        // enviar PUT
        fetch(`{{ url('reservaciones') }}/${id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ fecha_entrada: start, fecha_salida: end })
        }).then(r => r.json()).then(resp => {
          if (!resp.success) {
            alert('Error al mover la reserva');
            info.revert();
          }
        }).catch(e => { alert('Error'); info.revert(); });
      },

      // resize -> cambiar duración
      eventResize: function(info) {
        const id = info.event.id;
        const start = info.event.startStr;
        const end = info.event.endStr;
        fetch(`{{ url('reservaciones') }}/${id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ fecha_entrada: start, fecha_salida: end })
        }).then(r => r.json()).then(resp => {
          if (!resp.success) {
            alert('Error al actualizar la reserva');
            info.revert();
          }
        }).catch(e => { alert('Error'); info.revert(); });
      }

    });

    calendar.render();

    // Botón crear rápido
    document.getElementById('btnCreate').addEventListener('click', function(){
      resetForm();
      document.querySelector('.modal-title').textContent = 'Crear reserva';
      deleteBtn.style.display = 'none';
      modalEl.show();
    });

    // Submit form (crear o editar)
    form.addEventListener('submit', function(e){
      e.preventDefault();
      const id = document.getElementById('reservation_id').value;
      const payload = {
        guest_name: document.getElementById('guest_name').value,
        room_id: document.getElementById('room_id').value || null,
        fecha_entrada: document.getElementById('checkin_at').value,
        fecha_salida: document.getElementById('checkout_at').value,
        notas: document.getElementById('notes').value,
      };

      if (!payload.guest_name || !payload.fecha_entrada || !payload.fecha_salida) {
        alert('Rellena huésped y fechas.');
        return;
      }

      const url = id ? `{{ url('reservaciones') }}/${id}` : '{{ route("reservaciones.store") }}';
      const method = id ? 'PUT' : 'POST';

      fetch(url, {
        method: method,
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          modalEl.hide();
          calendar.refetchEvents();
        } else {
          alert('Error al guardar');
        }
      })
      .catch(err => { console.error(err); alert('Error en la petición'); });

    });

    // Eliminar reserva
    deleteBtn.addEventListener('click', function(){
      if (!confirm('Eliminar reserva?')) return;
      const id = document.getElementById('reservation_id').value;
      fetch(`{{ url('reservaciones') }}/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
      }).then(r => r.json()).then(resp => {
        if (resp.success) {
          modalEl.hide();
          calendar.refetchEvents();
        } else {
          alert('No se pudo eliminar');
        }
      });
    });

    function resetForm() {
      form.reset();
      document.getElementById('reservation_id').value = '';
      document.getElementById('form_method').value = 'POST';
      deleteBtn.style.display = 'none';
      document.getElementById('room_id').value = '';
    }

  });
</script>

@endsection
