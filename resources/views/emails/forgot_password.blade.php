@extends('emails.template')

@section('emails.main')
<tr>
                                                <td valign="top">

                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#fff">
                                                        <tbody>

                                                            <tr>
                                                                <td style="font-family:'Helvetica Neue',Helvetica Neue,Helvetica,Arial,sans-serif;font-size:18px;line-height:23px;font-weight:500;color:#37414c;text-align: center;">Hi {{ $first_name }},</td>
                                                            </tr>

                                                            <tr>
                                                                <td style="font-family:'Helvetica Neue',Helvetica Neue,Helvetica,Arial,sans-serif;font-size:14px;line-height:23px;color:#37414c;padding-bottom:12px;text-align: center;">Click here below reset your password</td>
                                                            </tr>

                                                            
                                                            <tr>
                                                                <td style="line-height:45px;text-align:center;font-size:16px;background:#2184dc;border-radius:3px;font-weight:500"><a href="{{ @$url.('users/set_password?secret='.@$token) }}" style="display:block;text-decoration:none;color:#fff">Reset Password</a></td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
@stop
