@extends('emails.template')

@section('emails.main')
<tr>
<td valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#fff">
<tbody>

<tr>
<td style="font-family:'Helvetica Neue',Helvetica Neue,Helvetica,Arial,sans-serif;font-size:14px;line-height:23px;color:#37414c;padding-bottom:12px"> {!! $content !!}</td>
</tr>   
</tbody>
</table>
</td>
</tr>
@stop