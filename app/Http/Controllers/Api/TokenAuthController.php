<?php


/**
 * TokenAuth Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    TokenAuth
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */


namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Currency;
use App\Models\ProfilePicture;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\EmailController;
use App\Models\UsersVerification;
use App\Http\Start\Helpers;
use Validator;
use DateTime;
use Session;
use DB;
use App;


class TokenAuthController extends Controller
{
  public function __construct()
    {   
        App::setLocale('en');
    }
    /**
     * User Authendicate
     *@param  Get method request inputs
     *
     * @return Response Json 
     */

    public function authenticate(Request $request)
    {   
        
        $credentials = $request->only('email', 'password');
 
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials']);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token']);
        }
 
        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }
    /**
     * User Authendicate Error
     *@param  Get method request inputs
     *
     * @return Response Json 
     */

    public function getAuthenticatedUser()
    {
        try {
        
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'user_not_found']);
            }
 
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
 
            return response()->json(['error' => 'token_expired']);
 
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        
            return response()->json(['error' => 'token_invalid']);
 
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
 
            return response()->json(['error' => 'token_absent']);
 
        }
        
        return response()->json(compact('user'));
    }
    /**
     * User Resister
     *@param  Get method request inputs
     *
     * @return Response Json 
     */

    public function signup(Request $request,EmailController $email_controller) 
    {   
       $this->helper = new Helpers;
        
             $rules = array(
            'email'           =>'required|max:255|email',
            'password'        =>'required|min:6',
            'full_name'       =>'required',
            'username'        =>'required',
            //'user_image_name' =>'required',
            );
        
        
        $niceNames = array(
            'email'   => 'Email',
        );

        $validator = Validator::make($request->all(), $rules);
        
        $validator->setAttributeNames($niceNames); 

        if (!$validator->fails()) 
        { 
        $email = $request->email;

        $user = User::where('email', $email)->get();

        $user_name =User::where('user_name', $request->username)->get();

        if(count($user))
        {
            return response()->json([

            'status_message' =>   "Email id already exits",

            'status_code'     => '0'

                                   ]);
        }
        elseif(count($user_name))
        {
            return response()->json([

            'status_message'  => "User name Already Exists",

            'status_code'     => '0'

                                   ]);
        }
        else
        {
          // Create a new user
        $user = new User;
        $user->email            =   $request->email;
        $user->full_name        =   $request->full_name;
        $user->user_name        =   $request->username;
        $user->password         =   bcrypt($request->password);
        $user->type             =   "buyer";
        $user->status           =   "Active";
        //$user->type             =   $request->signup_type;
        $user->save();  


        $user_verification = new UsersVerification;
        $user_verification->user_id      =   $user->id;
        $user_verification->save();  // Create a users verification record

        $user_id =$user->id;

        //profile image upload

        $profile                    = new ProfilePicture;

        $profile->user_id           = $user->id;
       // dd($request->user_image_name);
        if(@$request->user_image_name != '' && @$request->user_image_name != "null")
        {
          $file_tmp   = dirname($_SERVER['SCRIPT_FILENAME']).'/image/users/temp_image/';
          $dir_name = dirname($_SERVER['SCRIPT_FILENAME']).'/image/users/'.$user_id;
          $f_name   = dirname($_SERVER['SCRIPT_FILENAME']).'/image/users/'.$user_id.'/';
          if(!file_exists($dir_name))
          {   //create file directory
            mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/image/users/'.$user_id, 0777, true);
          }
          if(UPLOAD_DRIVER!='cloudinary')
          {
            if(copy($file_tmp.$request->user_image_name,$f_name.$request->user_image_name))
            {
              $li=$this->helper->compress_image("image/users/".$user_id.'/'.$request->user_image_name, "image/users/".$user_id.'/'.$request->user_image_name, 80, 225, 225);
            }
          } 
        }



        $profile->src               = $request->user_image_name !='' &&$request->user_image_name !='null' ? $request->user_image_name : '';
        $profile->cover_image_src   = '';
        $profile->photo_source      = 'Local';
        $profile->save();
        $email_controller->welcome_email_confirmation($user);

        $credentials = $request->only('email', 'password');
 
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials']);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token']);
        }

        // if no errors are encountered we can return a JWT

        $register = array(

                     'status_message'    =>  'Register Success',

                     'status_code'       =>  '1',

                     'access_token'      =>  $token,

                     'user_id'           =>  $user->id,

                     'full_name'         =>  html_entity_decode($request->full_name),

                     'username'          =>  html_entity_decode($request->username),

                     'email'             =>  html_entity_decode($request->email),
                    
                     'user_image_url'    =>   $request->user_image_name !='' &&  $request->user_image_name !="null" ? url('/').'/image/users/'.$user->id.'/'.$request->user_image_name : url('/image/profile.png'),

                    );
        
          return response()->json($register);
        }
        }
        else
        {
             $error=$validator->messages()->toArray();

                    foreach($error as $er)
                    {
                         $error_msg[]=array($er);
                    } 
          
                    return response()->json([

                                               'status_message'=>$error_msg['0']['0']['0'],

                                               'status_code'=>'0'
                                           ]);  

        }
    }
    /**
     * User Logout
     *@param  Get method request inputs
     *
     * @return Response Json 
     */
    public function logout(Request $request)
    { 
        $user_details = JWTAuth::parseToken()->authenticate();
        $user = User::where('id', $user_details->id)->first();
        if(count($user))
        {
         JWTAuth::invalidate($request->token);

                        Session::flush();

                        return response()->json([

                                'status_message'  => "Logout Successfully",

                                'status_code'     => '1'

                                           ]);
        }else{

            return response()->json([

                'status_message' =>  "Invalid credentials",

                'status_code'     => '0'

                                       ]);

        }
    }
    /**
     * User Socail media check user already exist or not
     *@param  Get method request inputs
     *
     * @return Response Json 
     */

    public function user_check_signup(Request $request) 
    {
      if($request->fb_id!='' && $request->google_id!='' && $request->twitter_id!='')
           {

                   return response()->json([
                                            'status_message'   =>  'Invalid Request...',

                                            'status_code'       =>  '0'
                                         ]); 

           } 
        elseif($request->fb_id != '' && $request->google_id =='' && $request->twitter_id=='')
           {
              $user = User::where('fb_id', $request->fb_id)->first();
              if(count($user))
              {
                  $token = JWTAuth::fromUser($user);

                  
                return response()->json([
                                            'status_message'   =>  'Login Success',

                                            'status_code'       =>  '1',
                                            
                                            'access_token'      =>  $token,

                                            'user_id'           =>  $user->id,

                                            'full_name'         =>  $user->full_name,

                                            'username'          =>  $user->user_name,

                                            'email'             =>  $user->email!=''? $user->email:'',

                                            'user_image_url'    =>  @$user->profile_picture->src!='' ? $user->profile_picture->src:url('image/profile.png')
                                         ]); 
              }
              else
              {
                 return response()->json([
                                            'status_message'   =>  'New User',

                                            'status_code'       =>  '2'
                                         ]); 
              } 
           }
           elseif($request->fb_id == '' && $request->google_id !='' && $request->twitter_id=='')
           {
              $user = User::where('google_id', $request->google_id)->first();
              if(count($user))
              {
                  $token = JWTAuth::fromUser($user);

                  
                return response()->json([
                                            'status_message'   =>  'Login Success',

                                            'status_code'       =>  '1',
                                            
                                            'access_token'      =>  $token,

                                            'user_id'           =>  $user->id,

                                            'full_name'         =>  $user->full_name,

                                            'username'          =>  $user->user_name,

                                            'email'             =>  $user->email!=''? $user->email:'',

                                            'user_image_url'    =>  @$user->profile_picture->src!='' ? $user->profile_picture->src:url('image/profile.png')
                                         ]); 
              }
              else
              {
                 return response()->json([
                                            'status_message'   =>  'New User',

                                            'status_code'       =>  '2'
                                         ]); 
              } 
           }
            elseif($request->fb_id == '' && $request->google_id =='' && $request->twitter_id !='')
           {
              $user = User::where('twitter_id', $request->twitter_id)->first();
              if(count($user))
              {
                  $token = JWTAuth::fromUser($user);

                  
                return response()->json([
                                            'status_message'   =>  'Login Success',

                                            'status_code'       =>  '1',
                                            
                                            'access_token'      =>  $token,

                                            'user_id'           =>  $user->id,

                                            'full_name'         =>  $user->full_name,

                                            'username'          =>  $user->user_name,

                                            'email'             =>  $user->email!=''? $user->email:'',

                                            'user_image_url'    =>  @$user->profile_picture->src!='' ? $user->profile_picture->src:url('image/profile.png')
                                         ]); 
              }
              else
              {
                 return response()->json([
                                            'status_message'   =>  'New User',

                                            'status_code'       =>  '2'
                                         ]); 
              } 
           }
           else
             {

              $rules     =  array('google_id'      => 'required|exists:users,google_id');

              $niceNames = array('google_id'   => 'Google id or Facebook or Twitter id',);

              $validator = Validator::make($request->all(), $rules);
              
              $validator->setAttributeNames($niceNames);


                if ($validator->fails()) 
                {
                     $error=$validator->messages()->toArray();

                    foreach($error as $er)
                    {
                         $error_msg[]=array($er);
                    } 
                    return ['status_code' => '0' , 'status_message' => $error_msg['0']['0']['0']];
                }

             }

    }
    /**
     * User Socail media Resister & Login 
     *@param  Get method request inputs
     *
     * @return Response Json 
     */

    public function socialsignup(Request $request) 
    {   
          //validation for signup and login
         if($request->twitter_id == null){
            $request->twitter_id = '';
         }
         if($request->fb_id!='' && $request->google_id!='' && $request->twitter_id!='')
           {

                   return response()->json([
                                            'status_message'   =>  'Invalid Request...',

                                            'status_code'       =>  '0'
                                         ]); 

           }
           elseif($request->fb_id!='' && $request->google_id=='' && $request->twitter_id=='') 
           {  

             $rules     = array('fb_id'      => 'required|exists:users,fb_id');

             $messages  = array('required'  =>':required.');

             $validator = Validator::make($request->all(), $rules, $messages);
           }

           elseif($request->google_id!='' && $request->fb_id=='' && $request->twitter_id=='')
           { 

             if($request->google_id!='')
             {

              $rules     =  array('google_id'      => 'required|exists:users,google_id');

              $messages  =  array('required'  => ':required.');

              $validator =  Validator::make($request->all(), $rules, $messages);
             }
           }
           elseif($request->twitter_id!='' &&$request->google_id =='' && $request->fb_id==''){
             $rules     = array('twitter_id' => 'required|exists:users,twitter_id');

             $messages  = array('required'  =>':required.');

             $validator = Validator::make($request->all(), $rules, $messages);
           }
           else
           {
            if($request->google_id=='' && $request->fb_id=='' && $request->twitter_id=='')
             {

              $rules     =  array('google_id'      => 'required|exists:users,google_id');

              $niceNames = array('google_id'   => 'Google id or Facebook or Twitter id',);

              $validator = Validator::make($request->all(), $rules);
              
              $validator->setAttributeNames($niceNames);


                if ($validator->fails()) 
                {
                     $error=$validator->messages()->toArray();

                    foreach($error as $er)
                    {
                         $error_msg[]=array($er);
                    } 
                    return ['status_code' => '0' , 'status_message' => $error_msg['0']['0']['0']];
                }

             }

           }
           

       if($validator->fails()) 
        {     
            

            
              if($request->fb_id!='' )
                {     

                $rules =  array(
                                'fb_id'        => 'required|unique:users,fb_id',

                                'email'        => 'required|max:255|email|unique:users',

                                'full_name'    => 'required',

                                'username'     => 'required'
                               
                               );
                

                }
                elseif($request->twitter_id!='' )
                {     

                $rules =  array(
                                'twitter_id'   => 'required|unique:users,twitter_id',

                                'email'     => 'required|max:255|email|unique:users',

                                'full_name'    => 'required',

                                'username'     => 'required'
                               

                               );
                

                }
            else
                {   
              
                    $rules = array(

                                'google_id'    => 'required|unique:users,google_id',

                                'email'        => 'required|max:255|email|unique:users',

                                'full_name'    => 'required',

                                'username'     => 'required'
                                
                                 );
                

                }

                   $messages = array('required'=>':attribute is required.','email.unique'=>'Email id already exits');

                   $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) 
                { 
          
                    $error=$validator->messages()->toArray();

                    foreach($error as $er)
                    {
                         $error_msg[]=array($er);
                    } 
          
                    return response()->json([

                                               'status_message'=>$error_msg['0']['0']['0'],

                                               'status_code'=>'0'
                                           ]);   
                }
            else
                {
                     $user_name =User::where('user_name', $request->username)->get();
       if(count($user_name))
        {
            return response()->json([

            'status_message'  => "User name Already Exists",

            'status_code'     => '0'

                                   ]);
        }

                    $user = new User;
                    
                    $user->full_name        =   html_entity_decode($request->full_name);
                    $user->user_name        =   html_entity_decode($request->username);
                    $user->email            =   html_entity_decode($request->email);
                    //$user->type             =   @$request->signup_type;
                    if($request->fb_id)
                    {
                    $user->fb_id            = $request->fb_id;
                    }
                    elseif($request->twitter_id)
                    {
                    $user->twitter_id       = $request->twitter_id;
                    }
                    else
                    {
                    $user->google_id        = $request->google_id;  
                    }
                    $user->type             =   "buyer";
                    $user->password         = '';
                    $user->status           = 'Active';
                    $user->save();  

                    

                    if($request->fb_id)
                    {
                    $user_new= @User::with('profile_picture')->where('fb_id',$request->fb_id)->first();

                    $photo_source = "Facebook";
                    }
                    elseif($request->twitter_id)
                    {
                    $user_new= @User::with('profile_picture')->where('twitter_id',$request->twitter_id)->first();

                    $photo_source = "Twitter";
                    }
                    else
                    {
                    $user_new= @User::with('profile_picture')->where('google_id',$request->google_id)->first();

                    $photo_source = "Google";   
                    }

                    $profile                    = new ProfilePicture;

                    $profile->user_id           = $user_new->id;

                    $profile->src               = html_entity_decode($request->user_image_name);

                    $profile->photo_source      = $photo_source;

                    $profile->cover_image_src   = '';

                    $profile->save();

                   
                    $token = JWTAuth::fromUser($user_new);

                    $new_register = array(

                     'status_message'    =>  'Signup Success',

                     'status_code'       =>  '1',

                     'access_token'      =>  $token,

                     'user_id'           =>  $user->id,

                     'full_name'         =>  $user_new->full_name,

                     'username'          =>  $user_new->user_name,

                     'email'             =>  $user_new->email,

                     'user_image_url'    =>  @html_entity_decode($request->user_image_name)!='' ? html_entity_decode($request->user_image_name):url('image/profile.png'),


                    );
        
                 return response()->json($new_register);

                }
        

    }
        else
        {   
            
            if($request->fb_id!='' )
                {     

                    $rules =  array(

                                'fb_id'         => 'required'
                               );
                

                }
                elseif($request->twitter_id!='' )
                {     

                    $rules =  array(

                                'twitter_id'   => 'required'

                               );
                

                }
            else
                {   
              
                    $rules = array(

                                'google_id'  => 'required'

                                 );
                

                }

                   $messages = array('required'=>':attribute is required.');

                   $validator = Validator::make($request->all(), $rules, $messages);

            if (!$validator->fails()) 
                {
                  if($request->fb_id)
                        {
                            $user= @User::where('fb_id',$request->fb_id)->first();
                           
                        }
                    elseif($request->twitter_id)
                        {
                            $user= @User::where('twitter_id',$request->twitter_id)->first();
                           
                        }
                    else
                        {
                            $user= @User::where('google_id',$request->google_id)->first();
                        }

                    

                    $token = JWTAuth::fromUser($user);

                    $currency = Currency::where('default_currency',1)->first();

                    
                    $register = array(

                     'status_message'    =>  'Login Success',

                     'status_code'       =>  '1',

                     'access_token'      =>  $token,

                     'user_id'           =>  $user->id,

                     'full_name'         =>  $user->full_name,

                     'username'          =>  $user->user_name,

                     'email'             =>  @$user->email!=''? $user->email:'',

                     'user_image_url'    =>  @$user->profile_picture->src!='' ? $user->profile_picture->src:url('image/profile.png')
                    );
        
                    return response()->json($register);

                }
             else
                {
                 $error=$validator->messages()->toArray();
                    foreach($error as $er)
                    {
                         $error_msg[]=array($er);
                    } 
          
                    return response()->json([

                                               'status_message'=>$error_msg['0']['0']['0'],

                                               'status_code'=>'0'
                                           ]); 
             }
        }



    }
    /**
     * User Token
     *@param  Get method request inputs
     *
     * @return Response Json 
     */

    public function token(Request $request)
    {

        $token = JWTAuth::refresh($request->token);
        
        return response()->json(['token' => $token], 200);
    }

    /**
     * User Login
     *@param  Get method request inputs
     *
     * @return Response Json 
     */

    public function login(Request $request)
    {   

                    $user_id = $request->email;
                    $db_id = 'email';

                    $rules = array(
                    'email'        =>'required|email',
                    'password'     =>'required',

                    );


        $validator = Validator::make($request->all(), $rules); 

        if ($validator->fails()) 
        {
                 $error=$validator->messages()->toArray();

                foreach($error as $er)
                {
                     $error_msg[]=array($er);
                } 
                return ['status_code' => '0' , 'status_message' => $error_msg['0']['0']['0']];
        }
        else
        {


             if(Auth::attempt([$db_id => $user_id, 'password' => $request->password]))
              {

                         $credentials = $request->only($db_id, 'password');
                        
         
                try {

                     if (!$token = JWTAuth::attempt($credentials))
                      {

                        return response()->json([

                          'status_message' => "Those credentials don't look right. Please try again.",
                         
                          'status_code'     => '0'

                          ]);

                     }
                    } 
                  catch (JWTException $e) 
                  {

                    return response()->json([

                                            'status_message' => 'could_not_create_token',

                                            'status_code'     => '0'

                                           ]);

                  }

                $user_check = User::where($db_id, $user_id)->first()->status;

                if($user_check == 'Inactive')
                  {
                    return response()->json([

                                            'status_message' => 'Your Account Deactivated..Please Contact Admin',

                                            'status_code'     => '0'

                                           ]);
                  }
                 
                $user = User::where($db_id, $user_id)->first();
                

                $currency = Currency::where('default_currency',1)->first();
               
                 $user=array(

                             'status_message'    =>  'Login Success',

                             'status_code'       =>  '1',

                             'access_token'      =>  $token,

                             'user_id'           =>  $user->id,

                             'full_name'         =>  $user->full_name,

                             'username'          =>  $user->user_name,

                             'email'             =>  $user->email,
                           
                             'user_image_url'     =>  @$user->profile_picture->src !=''&& @$user->profile_picture->src !=NULL ? $user->profile_picture->src : url('image/profile.png'),

                             

                            );
                
                  return response()->json($user);
                
                  }
                  else
                  {

                       return response()->json([

                        'status_message' =>"Those credentials don't look right. Please try again.",

                        'status_code'     => '0'

                                               ]);

                  }
    }
       
    }
    /**
     * User Change Email
     *@param  Get method request inputs
     *
     * @return Response Json 
     */

    public function change_email(Request $request, EmailController $email_controller){

        $user_details = JWTAuth::parseToken()->authenticate();

        $rules = array(
            'email_id'              =>'required|email'

        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
        {
             $error=$validator->messages()->toArray();

            foreach($error as $er)
            {
                 $error_msg[]=array($er);
            } 
            return ['status_code' => '0' , 'status_message' => $error_msg['0']['0']['0']];
        }
        else
        { 
          $user_check = User::where('id', $user_details->id)->first();

          $user = User::where('email', $request->email_id)->get();

          if(count($user))
          {           
            if($user_check->status == '' && $user_details->id == $user[0]->id )
            {
              $change_email = $email_controller->resend_email_confirmation($user_check);              

            return response()->json([

              'status_message' =>   "A confirmation email has been sent to :".$request->email_id,

              'status_code'     => '1'

                                     ]);

            }
            else{

              return response()->json([

              'status_message' =>   "Already use Email id",

              'status_code'     => '0'

                                     ]);
              }
          }

            if(count($user_check))
            {

              $user = User::where('id', $user_details->id)->first();

              $change_email = $email_controller->change_email_confirmation($user);
              
              $user =  User::where('id',$user_details->id)->update(['email'   => html_entity_decode($request->email_id),'status'=>null]);
                           
                $user = User::where('id', $user_details->id)->first();

                $resend_email=$email_controller->resend_email_confirmation($user);
              

                 return response()->json([

                     'status_message'    =>  'A new link to confirm your email has been sent to :'.$request->email_id,

                     'status_code'       =>  '1',

                                   ]);
            }
            else
            {
                return response()->json([

                'status_message' =>  "Invalid credentials",

                'status_code'     => '0'

                                       ]);

            }
          

        }

    }
    /**
     * User Change Password
     *@param  Get method request inputs
     *
     * @return Response Json 
     */

    public function change_password(Request $request){

        $user_details = JWTAuth::parseToken()->authenticate();

        $rules = array(
            'password'              =>'required|min:6'

        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
        {
             $error=$validator->messages()->toArray();

            foreach($error as $er)
            {
                 $error_msg[]=array($er);
            } 
            return ['status_code' => '0' , 'status_message' => $error_msg['0']['0']['0']];
        }
        else
        { 
          $user_check = User::where('id', $user_details->id)->first();

            if(count($user_check))
            {


              User::where('id',$user_details->id)->update(['password'   => bcrypt($request->password)]);

             
                $user = User::where('id', $user_details->id)->first();

                 return response()->json([

                     'status_message'    =>  'Password changed Successfully',
                     
                     'status_code'       =>  '1',

                                   ]);
            }
            else
            {
                return response()->json([

                'status_message' =>  "Invalid credentials",

                'status_code'     => '0'

                                       ]);

            }
          

        }

    }

    /**
     * User Change Password
     *@param  Get method request inputs
     *
     * @return Response Json 
     */

    public function token_expire(Request $request)
    {
      try 
      {
        JWTAuth::invalidate($request->token);
        Session::flush();
        return response()->json([
          'status_message'  => "Token Expired",
          'status_code'     => '1'
        ]);
      }
      catch (\Exception $e) 
      {
          return response()->json([
          'status_message'  => $e->getMessage(),
          'status_code'     => '1'
        ]);
      }
    }


}
 
