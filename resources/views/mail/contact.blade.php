@component('mail::message')
  # @lang('Halo Pengembang')

  @lang('Pesan baru dari :name telah diterima.', ['name' => $contact->name])

  @component('mail::panel')
    {{ $contact->message }}
  @endcomponent

  @lang('Salam'),<br>
  {{ config('app.name') }}
@endcomponent
