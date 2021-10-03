@component('mail::message')

<h1>Good Day {{ $details['applicantName'] }}!,</h1>

<p>Thank you for your patience while we have been reviewing your application at Samquicksal. We are pleased to inform you that your application has been approved! You may now use your account by logging in with the following details in the attached file.</p>

<br>

<p>Do not worry you will be asked to change your username and password upon first login. Samquicksal will never ask for your password if an account is doing so please contact us</p>

@component('mail::button', ['url' => $details['link']])
Click here to Login
@endcomponent

<br>

<p>Also, please verify your email to be able to publish yung restaurant on our system.</p>

@component('mail::button', ['url' => $details['linkToVerify']])
Verify your email
@endcomponent

<br>

All the best,<br>
Samquicksal
@endcomponent
