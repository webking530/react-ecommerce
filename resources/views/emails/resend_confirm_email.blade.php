<p>
    Hi {{ @$full_name }},
</p>

<p>Please confirm your {{$site_name}} account by clicking this link:</p>
<a href="{{ $url.('users/confirm_email?code='.$token) }}" target="_blank">{{ $url.('users/confirm_email?code='.$token) }} 
</a>
<br/>
Once you confirm, you will have full access to {{$site_name}} and all future notifications will be sent to this email address.
<br/>

<p>- Team {{$site_name}}</p>
