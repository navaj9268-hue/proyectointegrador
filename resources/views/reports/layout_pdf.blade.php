<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size:12px; color:#222; }
    .header { display:flex; align-items:center; gap:12px; margin-bottom:12px; }
    .header img { width:70px; height:auto; object-fit:contain; }
    .hotel-title { font-size:18px; color:#b23a3a; font-weight:700; }
    .meta { font-size:11px; color:#666; }
    table { width:100%; border-collapse: collapse; margin-top:10px; }
    th, td { padding:8px 6px; border:1px solid #ddd; font-size:11px; vertical-align:top; }
    th { background:#faf4f4; color:#8a2b2b; text-align:left; }
    .small { font-size:10px; color:#666; }
    .center { text-align:center; }
    .right { text-align:right; }
    .footer { position: fixed; bottom: 10px; left: 0; right:0; text-align:center; font-size:9px; color:#999; }
  </style>
</head>
<body>
  <div class="header">
    @if(file_exists(public_path('img/logo.png')))
      <img src="{{ public_path('img/logo.png') }}" alt="logo">
    @elseif(file_exists(public_path('logo.png')))
      <img src="{{ public_path('logo.png') }}" alt="logo">
    @endif

    <div>
      <div class="hotel-title">{{ $hotel->name ?? config('app.name','Hotel') }}</div>
      <div class="meta">{{ $hotel->address ?? '' }} · {{ $hotel->phone ?? '' }} · {{ $hotel->email ?? '' }}</div>
    </div>
    <div style="margin-left:auto; text-align:right;">
      <div class="small">Generado: {{ \Carbon\Carbon::now()->format('Y-m-d H:i') }}</div>
      <div class="small">Usuario: {{ auth()->user()->name ?? '—' }}</div>
    </div>
  </div>

  <hr style="border:none; border-top:1px solid #eee; margin:6px 0 10px 0;">

  @yield('content')

  <div class="footer">
    &copy; {{ date('Y') }} {{ $hotel->name ?? config('app.name','Hotel') }} — Reporte generado por el sistema
  </div>
</body>
</html>
