@component('mail::message')

<h1>Good Day {{ $details['applicantName'] }},</h1>

<p>
@if ($details['status'] == "resend")
    You are receiving this email because you've recently request to resend the verification link.
@else
    You are receiving this email because you've recently changed your email address.
@endif
</p>


<p>To verify your new email address, just click the button below.</p>

@component('mail::button', ['url' => $details['link']])
Verify Email
@endcomponent

<p>
@if ($details['status'] == 'resend')
    If you did not reqeust this then you can email us immediately to secure your account.
@else
    If you did not change your email address then you can email us immediately to secure your account.
@endif
</p>

<br>

Regards,<br>
Samquicksal
@endcomponent
