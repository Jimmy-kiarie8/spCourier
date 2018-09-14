@component('mail::message')
# Introduction

The body of your message.

@component('mail::panel')
The following shipments are schedueled today and tomorrow
@endcomponent

@component('mail::button', ['url' => url('/courier#/scheduled')])
See Shipments
@endcomponent

@component('mail::table')
| Client Name       | Phone         | Email  | Date 	 |
| ------------------ |:-------------:| ---------:|:---------:|
@foreach  ($shipment as $value)
| {{$value->client_name}}   | {{$value->client_phone}} | {{$value->client_email}} | {{$value->created_at}} |
@endforeach
@endcomponent


Thanks,<br>
{{ config('app.name') }}
@endcomponent
