@component('mail::message')
  # Halo, pemilik Padanan.id

  Nampaknya website {{ config('app.name') }} dengan URL {{ config('app.url') }} sedang tidak dalam kondisi aktif. Mohon segera diperiksa!

  @component('mail::panel')
    {{ $message }}
  @endcomponent

  Salam,<br>
  {{ config('app.name') }}
@endcomponent
