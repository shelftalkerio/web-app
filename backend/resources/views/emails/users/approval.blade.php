@component('mail::message')
# Dear Admin

<p>A new user has registered and is awaiting approval:</p>

<ul>
    <li>Name: {{ $data['user'] }}</li>
    <li>Email: {{ $data['email'] }}</li>
</ul>

@component('mail::button', ['url' => $data['url']])
    Approve User
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent
