@component('mail::message')

<h1>Dear {{ $details['applicantName'] }},</h1>

<br>

<h3>Welcome to Samquicksal!</h3>

<p>Your email has been verified, you can now publish your restaurant.</p>
<p>If you have any questions, feel free to contact us.</p>

<br>

@component('mail::button', ['url' => $details['link']])
Click here to Login
@endcomponent

<br>

Thanks,<br>
ALTWAV Team!
@endcomponent
