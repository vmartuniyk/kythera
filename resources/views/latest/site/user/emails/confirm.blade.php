<h1>{!! Lang::get('confide::confide.email.account_confirmation.subject') !!}</h1>

<p>{!! Lang::get('confide::confide.email.account_confirmation.greetings', array('name' => $user['username'])) !!},</p>

<p>{!! Lang::get('confide::confide.email.account_confirmation.body') !!}</p>
<a href='{!! action('UsersController@getConfirm', $user['confirmation_code']) !!}'>
    {{ action('UsersController@getConfirm', $user['confirmation_code']) }}
</a>

<p>{!! Lang::get('confide::confide.email.account_confirmation.farewell') !!}</p>
