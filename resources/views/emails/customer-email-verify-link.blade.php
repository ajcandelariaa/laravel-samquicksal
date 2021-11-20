@component('mail::message')

<h1>Good Day {{ $details['applicantName'] }},</h1>

<p>You are receiving this email because you've requested to verify your email.</p>

<p>To verify your new email address, just click the button below.</p>

@component('mail::button', ['url' => $details['link']])
Verify Email
@endcomponent

<p>If you did not reqeust this then you can email us immediately to secure your account.</p>

<br>

Regards,<br>
Samquicksal
@endcomponent
