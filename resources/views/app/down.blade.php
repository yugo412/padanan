@component('mail::message')
  # Halo,

  Nampaknya URL {{ $url }} sedang tidak dalam kondisi aktif. Jika kamu merupakan pemiliknya, mohon segera periksa!

  @component('mail::panel')
    {{ $message }}
  @endcomponent

  Salam,<br>
  {{ config('app.name') }}
@endcomponent
