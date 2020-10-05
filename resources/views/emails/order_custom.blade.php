@extends('emails.template')

@section('emails.main')
<tr>
<td valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tbody>

<tr>
<td style="background:#fff;font-family:'Helvetica Neue',Helvetica Neue,Helvetica,Arial,sans-serif;font-size:14px;line-height:23px;color:#37414c;padding:12px"> 
Hi  {{ @$first_name }},
<br>
{{ @$content }}
</td>
</tr>

<tr>
<td style="line-height:45px;text-align:center;font-size:16px;background:#2184dc;border-radius:3px;font-weight:500">
<a href="{{ @$btn_link }}" style="display:block;text-decoration:none;color:#fff">{{ @$btn_text }}</a></td>
</tr>

</tbody>
</table>
</td>
</tr>
@stop