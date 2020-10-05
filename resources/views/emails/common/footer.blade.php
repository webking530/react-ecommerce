  <tr>
                                <td style="background:#fff;border-radius:0;padding:0 40px 40px">
                                    <table cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto;max-width:570px">
                                        <tbody>
                                            

                                            <tr>
                                                <td style="background:#fff;border-radius:0;padding:0 0 0 0">

                                                </td>
                                            </tr>
                                    </table>

                                   
                            </tr><tr>
                                        <td style="background:#4f5a67;border-radius:0 0 3px 3px;padding:23px 40px">

                                            <table align="right" cellpadding="0" cellspacing="0" border="0" class="m_2114864110069020054right">
                                                <tbody>
                                                    <tr>
                                                        <td style="opacity:0.8;padding:22px 0" valign="middle" rowspan="2">
                                                            <a href="{{ @$site_url }}"><img src="{{ $email_logo }}" alt="{{ $site_name }}" style="width:91px;height:18px;border:0" width="91" height="18"></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:'Helvetica Neue',Helvetica Neue,Helvetica,Arial,sans-serif;font-size:11px;color:#a6adb7">
                                                <tbody>

                                                    <tr>
                                                        <td style="padding:8px 0 0;font-size:11px;color:rgba(255,255,255,0.4);line-height:18px;text-align:left;font-family:'Helvetica Neue',Helvetica Neue,Helvetica,Arial,sans-serif"> ©  {{ $site_name }} 2017 
                                                            <br>
                                                            @foreach($company_pages as $company_page)
                                                            <a href="{{ url($company_page->url) }}" style="color:rgba(255,255,255,0.4);border-bottom:1px solid rgba(255,255,255,0.4);text-decoration:none" target="_blank">{{ $company_page->name }}</a> 
                                                            @endforeach
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </td>
                                    </tr>

                                      </tbody>
                    </table>

</div>
