@extends('emails.template')

@section('emails.main')
<tr>
                                                <td valign="top">

                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#fff">
                                                        <tbody>

                                                            <tr>
                                                                <td style="font-family:'Helvetica Neue',Helvetica Neue,Helvetica,Arial,sans-serif;font-size:18px;line-height:23px;font-weight:500;color:#37414c">Discover and buy amazing things</td>
                                                            </tr>

                                                            <tr>
                                                                <td style="font-family:'Helvetica Neue',Helvetica Neue,Helvetica,Arial,sans-serif;font-size:14px;line-height:23px;color:#37414c;padding-bottom:12px">Shop Collections. Find the perfect gift. Follow friends, and share your favorite finds.</td>
                                                            </tr>

                                                            <tr>
                                                                <td style="font-family:Helvetica,Arial,sans-serif;font-size:14px;line-height:21px;color:#373d48;padding-bottom:31px">Confirm your email address to gain full access to <span class="il">{{ $site_name }}</span>.</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="line-height:45px;text-align:center;font-size:16px;background:#2184dc;border-radius:3px;font-weight:500"><a href="{{ $url.('users/confirm_email?code='.$token) }}" style="display:block;text-decoration:none;color:#fff">Confirm your email</a></td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
@stop