@component('mail::message')

<h1>Good Day!</h1>

<p>You are receiving this email because we received a password reset request for your account.</p>

@component('mail::button', ['url' => $details['link']])
Reset Password
@endcomponent

<p>If you did not request a password reset, no further action is required.</p>

<br>

Regards,<br>
Samquicksal
@endcomponent
