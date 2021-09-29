@component('mail::message')

<h1>Good Day {{ $details['applicantName'] }}!,</h1>

<br>

<p>Thank you for your patience while we have been reviewing your application at Samquiksal. Unfortunately We are going to have to decline your application form.</p>
<p>Upon further review here are the missing/incomplete details of your application form. You may resubmit your application form once again with the newly accomplished details.</p>

<br>

<h3>Missing Details: </h3>
<ul>
    @foreach ($details['selectedData'] as $data)
        <li>{{ $data }}</li>
    @endforeach
</ul>


@component('mail::button', ['url' => $details['link']])
Resubmit Form
@endcomponent

Thank your for understanding,<br>
{{ config('app.name') }}
@endcomponent
