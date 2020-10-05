<?php

/**
 * User Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    User
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Http\Start\Helpers;
use App\Models\Country;
use App\Models\FollowStore;
use App\Models\PasswordResets;
use App\Models\ProfilePicture;
use App\Models\Timezone;
use App\Models\User;
use App\Models\UsersVerification;
use App\Models\Wishlists;
use App\Models\Product;
use App\Models\Category;
use DateTime;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use Session;
use Socialite;
use Validator; // This package have all social media API integration
use View;
use Illuminate\Support\Facades\Redirect;
use Google_Client;

class UserController extends Controller {

	protected $helper; // Global variable for Helpers instance

	public function __construct() {
		$this->helper = new Helpers;
	}

	/**
	 * Redirect the user to the Facebook authentication page.
	 *
	 * @return Response
	 */
	public function facebookLogin()
	{
		return Socialite::driver('facebook')->redirect();
	}
	
	/**
	 * Obtain the user information from Facebook.
	 *
	 * @return Response
	 */
	public function facebookAuthenticate(Request $request)
	{
		if (!@$request->code) {
			return redirect('login'); // Redirect to login page
		}
		try {
			$userNode = Socialite::driver('facebook')->user();
		} catch (\Exception $e) {
			flashMessage('danger', $e->getMessage());
			return redirect('signup');
		}

		$email = $userNode->getEmail();
		$fb_id = $userNode->getId();

		$user = User::user_fb_id_authenticate($email, $fb_id); // Check Facebook User Email Id is exists

		if ($user->count() > 0) // If there update Facebook Id
		{
			$user = User::user_fb_id_authenticate($email, $fb_id)->first();

			$user->fb_id = $userNode->getId();

			$user->save(); // Update a Facebook id

			$user_id = $user->id; // Get Last Updated Id
		} else // If not create a new user without Password
		{
			$user = User::user_fb_id_authenticate($email, $fb_id);

			if ($user->count() > 0) {
				return redirect('user_disabled');
			}

			$randam_number = rand(10000, 10000000);
			$parts = explode("@", $email);
			$username = $parts[0] . $randam_number;

			$user = new User;
			// New user data
			$user->full_name = $userNode->getName();
			$user->user_name = $username;
			$user->email = $email;
			$user->fb_id = $userNode->getId();
			$user->password = '';
			$user->type = 'buyer';
			flashMessage('success', 'Register Successfully');

			$username = str_replace(' ', '', $userNode->getName()) . $randam_number;

			$user = array(
				'full_name' => $userNode->getName(),
				'user_name' => $username,
				'email' => $email,
				'source' => "Facebook",
				'facebook_id' => $fb_id,
			);

			Session::put('fb_user_data', $user);
			Session::put('user_data', $user);
			Session::put('error_code', 1);

			return redirect('signup');
		}

		$users = User::where('id', $user_id)->first();

		if (@$users->status != 'Inactive') {
			if (Auth::guard('web')->loginUsingId($user_id)) // Login without using User Id instead of Email and Password
			{
				return redirect()->intended(Session::get('url.intended')); // Redirect to dashboard page
			} else {
				flashMessage('danger', trans('messages.login.login_failed')); // Call flash message function
				return redirect('login'); // Redirect to login page
			}
		} else // Call Disabled view file for Inactive user
		{
			return redirect('user_disabled');
		}
	}

	/**
	 * Obtain the user information from Facebook.
	 *
	 * @return Response
	 */
	public function googleAuthenticate(Request $request)
	{
		try {
            $client = new Google_Client(['client_id' => GOOGLE_CLIENT_ID]);  
            // Specify the CLIENT_ID of the app that accesses the backend
            $payload = $client->verifyIdToken($request->idtoken);

	        if ($payload) {
	            $google_id = $payload['sub'];
	        }
	        else {
	            flashMessage('danger', trans('messages.user.login_failed'));
	            return redirect('login');
	        }
        }
        catch(\Exception $e) {
            flashMessage('danger', $e->getMessage());
			return redirect('login');
        }

        // Get Details From Google
        $firstName 	= $payload['given_name'];
        $lastName 	= isset($payload['family_name']) ? $payload['family_name'] : '';
        $email = ($payload['email'] == '') ? $google_id.'@gmail.com' : $payload['email'];

		$user = User::where('email', $email)->Where('google_id', $google_id);

		if ($user->count() > 0) {
			$user = User::where('email', $email)->orWhere('google_id', $google_id)->first();
			$user->google_id = $google_id;
			$user->save();
			$user_id = $user->id;
		}
		else {
			$user = User::where('email', $email)->first();
			$already_gmailid = User::where('google_id', $google_id);
			if ($already_gmailid->count() > 0) {
				$user = User::where('google_id', $google_id)->first();
				$user_id = $user->id;
			}
			else {
				flashMessage('success', 'Register Successfully');
				$randam_number = rand(10000, 10000000);
				$parts = explode("@", $email);
				$username = $parts[0] . $randam_number;

				$user = new User;
				$user->full_name = $firstName.' '.$lastName;
				$user->user_name = $username;
				$user->email = $email;
				$user->google_id = $google_id;
				$user->password = '';

				$username = str_replace(' ', '', $firstName) . $randam_number;

				$user = array(
					'full_name' => $firstName.' '.$lastName,
					'user_name' => $username,
					'email' => $email,
					'src' => $payload['picture'],
					'google_id' => $google_id,
					'source' => "Google",
				);

				Session::put('google_user_data', $user);
				Session::put('user_data', $user);
				Session::put('error_code', 1);
				return redirect('signup');
			}
		}

		$users = User::where('id', $user_id)->first();

		if (@$users->status == 'Active' || @$users->status == NULL) {
			if (Auth::guard('web')->loginUsingId($user_id)) {
				return redirect('/');
			}
			flashMessage('danger', trans('messages.login.login_failed'));
			return redirect('login');
		}
		return redirect('user_disabled');
	}

	/**
	 * Google User redirect to Google Authentication page
	 *
	 * @return redirect     to Google page
	 */
	public function twitterLogin()
	{
		try {
			return Socialite::driver('twitter')->redirect();
		}
		catch(\Exception $exception) {
			flashMessage('danger', $exception->getMessage());
			return redirect('login');
		}
	}

	/**
	 * Obtain the user information from Facebook.
	 *
	 * @return Response
	 */
	public function twitterAuthenticate(Request $request)
	{

		if (@$request->denied) {
			return redirect('login'); // Redirect to login page
		}

		try {
			$userNode = Socialite::driver('twitter')->user();
		} catch (\Exception $e) {
			flashMessage('danger', $e->getMessage());
			return redirect('signup');
		}

		$email = $userNode->getEmail();
		$twitter_id = $userNode->getId();
		$twitter_already = User::where('twitter_id', $twitter_id)->count();
		$user = User::where('email', $email); // Check Twitter User Email Id is exists
		if ($twitter_already) // If there update Twitter Id
		{
			$user = User::where('twitter_id', $userNode->getId())->first();

			$user->twitter_id = $userNode->getId();

			$user->save(); // Update a Google id

			$user_id = $user->id; // Get Last Updated Id
		}
		else {
			$user = User::where('email', $email)->first();

			if (isset($user)) {
				if($user->status != 'Inactive') {
					flashMessage('error', trans('messages.login.exist_mail'));
					return redirect('signup');
				}
				else {
					return redirect('user_disabled');	
				}
			}
			$randam_number = rand(10000, 10000000);
			$username = str_replace(' ', '', $userNode->getName()) . $randam_number;

			$user = array(
				'full_name' => $userNode->getName(),
				'user_name' => $username,
				'src' => $userNode->getAvatar(),
				'email' => $userNode->getEmail(),
				'source' => "Twitter",
				'twitter_id' => $twitter_id,
			);

			Session::put('user_data', $user);
			Session::put('error_code', 1);
			flashMessage('success', trans('messages.login.reg_successfully'));
			return redirect('signup');
		}

		$users = User::where('id', $user_id)->firstOrFail();

		if ($users->status != 'Inactive') {
			if (Auth::guard('web')->loginUsingId($user_id)) {
				return redirect()->intended(Session::get('url.intended'));
			}
			else {
				flashMessage('danger', trans('messages.login.login_failed'));
				return redirect('login');
			}
		}
		return redirect('user_disabled');
	}
	/**
	 * Create a new Email signup user
	 *
	 * @param array $request    Post method inputs
	 * @return redirect     to  signup page
	 */
	public function check_email(Request $request)
	{
		$email_id = trim($request->email_id);

		$email = @User::where('email', $email_id)->get();

		if (count($email)) {
			return 1;
		} else {
			return 0;
		}
	}
	/**
	 * Create a new Email signup user
	 * to check  username already exist or not
	 * @param array $request    Post method inputs
	 * @return redirect     to  signup page
	 */
	public function check_username(Request $request)
	{
		$user_count = User::where('user_name', $request->user_name)->count();

		if ($user_count) {
			return 1;
		}
		return 0;
	}
	
	/**
	 * Create a new email & user name signup user
	 *
	 * @param array $request    Post method inputs
	 * @return redirect     to  signup page
	 */
	public function check_users(Request $request)
	{
		$email_id = trim($request->email_id);
		$user_name = $request->user_name;
		$email = User::where('email', $email_id)->count();
		$user_name = User::where('user_name', $user_name)->count();

		if ($email > 0) {
			return 1;
		}
		if ($user_name > 0) {
			return 2;
		}
		return 0;
	}

	/**
	 * Create a new Email signup user
	 *
	 * @param array $request    Post method inputs
	 * @return redirect     to dashboard page
	 */
	public function create(Request $request, EmailController $email_controller) {
		try {
		// Email signup validation rules
		$rules = array(
			'full_name' => 'required|max:35|regex:/^[a-zA-Z0-9_\- ]*$/',
			'email' => 'required|max:255|email|unique:users',
			'user_name' => 'required|max:35|regex:/^[a-zA-Z0-9_\-]*$/|unique:users,user_name',
		);

		if (isset($request->user_password)) {
			$rules['user_password'] = 'required|min:6';
		}

		$messages = array(
			'user_name.regex' => 'Please use alphanumeric characters',
			'full_name.regex' => 'Please use alphanumeric characters');

		// Email signup validation custom Fields name
		$attributes = array(
			'full_name' => trans('messages.login.full_name'),
			'user_name' => trans('messages.login.user_name'),
			'email' => trans('messages.login.email'),
		);
		if (isset($request->user_password)) {
			$attributes['user_password'] = trans('messages.login.password');
		}

		$validator = Validator::make($request->all(), $rules, $messages, $attributes);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput()->with('error_code', 1); // Form calling with Errors and Input values
		} else {
			$user = new User;

			$user->email = $request->email;

			//add twitter datas
			if (@Session::get('user_data')['source'] && @Session::get('user_data')['source'] == "Twitter") {
				$user->twitter_id = @Session::get('user_data')['twitter_id'];
				$user->status = 'Active'; //user activated
				$user_full_name = @Session::get('user_data')['full_name'];
				$user_user_name = @Session::get('user_data')['user_name'];
				if ($request->full_name != "" && $user_full_name != $request->full_name) {
					$user->full_name = $request->full_name;
				} else {
					$user->full_name = $user_full_name;
				}

				if ($request->user_name != "" && $user_user_name != $request->user_name) {
					$user->user_name = $request->user_name;
				} else {
					$user->user_name = $user_user_name;
				}

			} elseif (@Session::get('user_data')['source'] && @Session::get('user_data')['source'] == "Facebook") {
				//add facebook datas
				$user->fb_id = @Session::get('user_data')['facebook_id'];
				$user->status = 'Active'; //user activated
				$user_full_name = @Session::get('user_data')['full_name'];
				$user_user_name = @Session::get('user_data')['user_name'];
				if ($request->full_name != "" && $user_full_name != $request->full_name) {
					$user->full_name = $request->full_name;
				} else {
					$user->full_name = $user_full_name;
				}

				if ($request->user_name != "" && $user_user_name != $request->user_name) {
					$user->user_name = $request->user_name;
				} else {
					$user->user_name = $user_user_name;
				}

			} elseif (@Session::get('user_data')['source'] && @Session::get('user_data')['source'] == "Google") {
				//add facebook datas
				$user->google_id = @Session::get('user_data')['google_id'];
				$user->status = 'Active'; //user activated
				$user_full_name = @Session::get('user_data')['full_name'];
				$user_user_name = @Session::get('user_data')['user_name'];
				if ($request->full_name != "" && $user_full_name != $request->full_name) {
					$user->full_name = $request->full_name;
				} else {
					$user->full_name = $user_full_name;
				}

				if ($request->user_name != "" && $user_user_name != $request->user_name) {
					$user->user_name = $request->user_name;
				} else {
					$user->user_name = $user_user_name;
				}

			} else {
				$user->full_name = $request->full_name;
				$user->user_name = $request->user_name;

			}
			$ip = getenv("REMOTE_ADDR");
			$get_timezone = @file_get_contents('https://timezoneapi.io/api/ip/?' . $ip);
			$timezone = json_decode($get_timezone, true);
			$timezone = $timezone['data']['timezone']['id'];
			if (!$timezone) {
				$timezone = 'UTC';
			}

			if (@$request->user_password) {
				$user->password = bcrypt($request->user_password);
			} else {
				$user->password = '';
			}
			$user->type = 'buyer';
			$user->timezone = $timezone;
			// $user->status ="Active";
			$user->save(); // Create a new user
			$user_id = $user->id;
			$user_pic = new ProfilePicture;
			$user_pic->user_id = $user_id;
			if (@Session::get('user_data')['source']) {
				if (@Session::get('user_data')['source'] == "Twitter") {
					$user_pic->src = @Session::get('user_data')['src'];
					$user_pic->photo_source = 'Twitter';
				} elseif (@Session::get('user_data')['source'] == "Facebook") {
					$user_pic->src = "https://graph.facebook.com/" . Session::get('user_data')['facebook_id'] . "/picture?type=large";
					$user_pic->photo_source = 'Facebook';
				} elseif (@Session::get('user_data')['source'] == "Google") {
					$user_pic->src = @Session::get('user_data')['src'];
					$user_pic->photo_source = 'Google';
				} else {
					$user_pic->src = '';
					$user_pic->photo_source = 'Local';
				}
			}
			$user_pic->save();

			$user_verification = new UsersVerification;

			$user_verification->user_id = $user->id;

			$user_verification->save(); // Create a users verification record

			$email_controller->welcome_email_confirmation($user);			
			if (@Session::get('user_data')['source']) {
				if (Auth::guard('web')->loginUsingId($user->id)) // Login without using User Id instead of Email and Password
				{
					Session::forget('error_code');
					Session::forget('user_data');
					Session::put('reg', 'success');
					flashMessage('success', trans('messages.login.reg_successfully'));
					return redirect()->intended(Session::get('url.intended')); // Redirect to dashboard page
				} else {
					flashMessage('danger', trans('messages.login.login_failed')); // Call flash message function
					return redirect('login'); // Redirect to login page
				}
			} else {

				if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->user_password])) {
					Session::forget('error_code');
					Session::put('reg', 'success');
					flashMessage('success', trans('messages.login.reg_successfully'));
					return redirect('/'); // Redirect to dashboard page
				} else {
					return redirect('signup'); // Redirect to login page
				}

			}
		}
	
		} catch (Exception $e) {
		    return $e;
		}

	}
	/**
	 * Send Resend confirmation Link
	 *
	 * @return view forgot password page / send mail to user
	 */
	public function resend_confirmation(Request $request, EmailController $email_controller) {
		$user_id = $request->user_id;
		$user = User::find($user_id);
		$resend_email = $email_controller->resend_email_confirmation($user);

		if ($resend_email == true) {
			$data['email_id'] = $user->email;
			$data['status'] = 1;
			$data['msg'] = trans('messages.profile.resend_confirm_email', ['email' => $user->email]);
		} else {
			$data['email_id'] = $user->email;
			$data['status'] = 0;
			$data['msg'] = trans('messages.profile.not_resend_confirm_email', ['email' => $user->email]);
		}

		return $data;
	}
	/**
	 * Load Forgot Password View and Send Reset Link
	 *
	 * @return view forgot password page / send mail to user
	 */
	public function forgot_password(Request $request, EmailController $email_controller) {

		// Email validation rules
		$rules = array(
			'email' => 'required|email|exists:users,email',
		);

		// Email validation custom messages
		$messages = array(
			'required' => ':attribute is required.',
			'exists' => 'No account exists for this email.',
		);

		// Email validation custom Fields name
		$attributes = array(
			'email' => 'Email',
		);

		$validator = Validator::make($request->all(), $rules, $messages, $attributes);

		if ($validator->fails()) {

			return back()->withErrors($validator)->withInput()->with('error_code', 4);;
		} else {
			$user = User::whereEmail($request->email)->first();

			if ($user != '') {
				$email_controller->forgot_password($user);

				flashMessage('success', trans('messages.login.reset_link_sent', ['email' => $user->email])); // Call flash message function
				return redirect('login');
			} else {
				flashMessage('error', trans('messages.profile.account_disabled')); // Call flash message function
				return back();

			}
		}

	}

	/**
	 * Confirm email for new email update
	 *
	 * @param array $request Input values
	 * @return redirect to dashboard
	 */
	public function confirm_email(Request $request)
	{

		$password_resets = PasswordResets::whereToken($request->code);

		if (@$password_resets->count() && @Auth::user()->email == $password_resets->first()->email) {
			$password_result = $password_resets->first();

			$datetime1 = new DateTime();
			$datetime2 = new DateTime($password_result->created_at);
			$interval = $datetime1->diff($datetime2);
			$hours = $interval->format('%h');
			$data['result'] = User::whereEmail($password_result->email)->first();
			$data['token'] = $request->code;

			$user = User::find($data['result']->id);

			$user->status = "Active";

			$user->save();

			$user_verification = UsersVerification::find($data['result']->id);

			$user_verification->email = 'yes';

			$user_verification->save(); // Create a users verification record

			// Delete used token from password_resets table
			$password_resets->delete();

			flashMessage('success', trans('messages.profile.email_confirmed')); // Call flash message function
			return redirect('/');

		} else if (@Auth::user()->id == '') {
			return redirect('login');
		} else {
			flashMessage('error', trans('messages.login.invalid_token')); // Call flash message function
			return redirect('/');
		}
	}

	/* Set Password View and Update Password
		     *
		     * @param array $request Input values
		     * @return view set_password / redirect to Login
	*/
	public function set_password(Request $request)
	{
		if (!$_POST) {
			Auth::guard('web')->logout();
			
			$password_resets = PasswordResets::whereToken($request->secret);

			if ($password_resets->count()) {
				$password_result = $password_resets->first();

				$datetime1 = new DateTime();
				$datetime2 = new DateTime($password_result->created_at);
				$interval = $datetime1->diff($datetime2);
				$hours = $interval->format('%h');

				if ($hours >= 1) {
					// Delete used token from password_resets table
					$password_resets->delete();

					flashMessage('error', trans('messages.login.token_expired')); // Call flash message function
					return redirect('login');
				}

				$data['result'] = User::whereEmail($password_result->email)->first();
				$data['token'] = $request->secret;
				return view('user.set_password', $data);
			} else {
				flashMessage('error', trans('messages.login.invalid_token')); // Call flash message function
				return redirect('login');
			}
		} else {
			// Password validation rules
			$rules = array(
				'password' => 'required|min:6|max:30',
				'password_confirmation' => 'required|same:password',
			);

			// Password validation custom Fields name
			$attributes = array(
				'password' => 'New Password',
				'password_confirmation' => 'Confirm Password',
			);

			$validator = Validator::make($request->all(), $rules, [], $attributes);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput()->with('error_code', 3); // Form calling with Errors and Input values
			} else {
				// Delete used token from password_resets table
				$password_resets = PasswordResets::whereToken($request->token)->delete();

				$user = User::find($request->id);

				$user->password = bcrypt($request->password);

				$user->save(); // Update Password in users table

				flashMessage('success', trans('messages.login.pwd_changed')); // Call flash message function
				return redirect('login');
			}
		}

	}

	/**
	 * Email users Login authentication
	 *
	 * @param array $request    Post method inputs
	 * @return redirect     to dashboard page
	 */
	public function authenticate(Request $request)
	{
		// Email login validation rules
		$rules = array(
			'login_email' => 'required|email|exists:users,email',
			'login_password' => 'required|min:6',
		);

		$attributes = array(
			'login_email' => trans('messages.login.email'),
			'login_password' => trans('messages.login.password'),
		);

		$validator = Validator::make($request->all(), $rules, [], $attributes);
		
		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput()->with('error_code', 2);
		}

		// Get user status
		$users = User::where('email', $request->login_email)->first();

		if (@$users->status != 'Inactive') {

			if (Auth::guard('web')->attempt(['email' => $request->login_email, 'password' => $request->login_password])) {
				if ($users->type == 'merchant') {
					return redirect('merchant/dashboard');
				}
				if (@$request->next != '') {
					Session::flush();
					Auth::guard('web')->logout();
					flashMessage('danger', trans('messages.login.login_failed'));
					return redirect('merchant/login');
				}

				if (Session::get('ajax_redirect_url')) {
					return redirect(Session::get('ajax_redirect_url'));
				}
				return redirect()->intended(Session::get('url.intended'));
			}
			flashMessage('danger', trans('messages.login.login_failed'));
			return redirect('login'); 
		}
		return redirect('user_disabled');
	}
	/**
	 * Load Edit profile view file with user dob
	 *
	 * @return edit profile view file
	 */
	public function edit_profile(Request $request)
	{
		$data['page']='browse';
		$data['gender'] = Auth::user()->gender;
		$data['dob'] = explode('-', Auth::user()->dob);
		$data['profile'] = ProfilePicture::where('user_id', Auth::id())->get();
		$data['timezone'] = Timezone::all();
		$data['user'] = User::where('id', Auth::id())->first();
		$data['categories'] = Category::where("parent_id",0)->where('status','Active')->get();
		return view('user.edit_profile', $data);
	}
	/**
	 * Update edit profile page data
	 *
	 * @return redirect     to Edit profile
	 */
	public function update(Request $request, EmailController $email_controller) {


		// Email signup validation rules
		$rules = array(
			'full_name' => 'required|max:255',
			'user_name' => 'required|max:255',
			'gender' => 'required',
			'email' => 'required|max:255|email|unique:users,email,' . Auth::user()->id,
			'birthday_day' => 'required',
			'birthday_month' => 'required',
			'birthday_year' => 'required',
			'profile_image' => 'mimes:jpg,png,gif,jpeg|image',
		);

		// Email signup validation custom messages
		$messages = array(
			'required' => ':attribute is required.',
			'birthday_day.required' => trans('messages.profile.birth_date_required'),
			'birthday_month.required' => trans('messages.profile.birth_date_required'),
			'birthday_year.required' => trans('messages.profile.birth_date_required'),
		);
		// Email signup validation custom Fields name
		$attributes = array(
			'full_name' => trans('messages.profile.full_name'),
			'user_name' => trans('messages.profile.user_name'),
			'gender' => trans('messages.profile.gender'),
			'email' => trans('messages.profile.email_address'),
			'profile_image' => trans('messages.profile.profile_image'),
		);

		$validator = Validator::make($request->all(), $rules, $messages, $attributes);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
		} else {
			$this->helper = new Helpers;
			$image = $request->file('profile_image');

			if ($image) {
				$file_name = time() . '.' . $image->getClientOriginalExtension();

				$type = pathinfo($file_name, PATHINFO_EXTENSION);
				$file_tmp = $request->profile_image;
				$user_id = $request->id;
				$user_name = $request->user_name;
				$dir_name = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/users/' . $user_id;
				if (UPLOAD_DRIVER == 'cloudinary') {
					$c = $this->helper->cloud_upload($file_tmp);
					if ($c['status'] != "error") {
						$file_name = $c['message']['public_id'];
					} else {
						flashMessage('danger', $c['message']); // Call flash message function
						return redirect('edit_profile');
					}
				} else {
					if (!file_exists($dir_name)) {
						//create file directory
						mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/image/users/' . $user_id, 0777, true);
					}

					$f_name = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/users/' . $user_id . '/' . $file_name;

					if (move_uploaded_file($file_tmp, $f_name)) {

						//change compress image in 225*225
						$li = $this->helper->compress_image("image/users/" . $user_id . '/' . $file_name, "image/users/" . $user_id . '/' . $file_name, 80, 225, 225);

					}
				}
			}

			$user = User::find(@$request->id);

			if ($user->email != $request->email) {
				$change_email = $email_controller->change_email_confirmation($user);
				$prev_email = 1;
			}

			$user->full_name = $request->full_name;
			$user->user_name = $request->user_name;
			$user->website = $request->website;
			$user->location = $request->location;
			$user->bio = $request->bio;
			$user->email = $request->email;
			$user->gender = $request->gender;
			$user->timezone = $request->timezone;
			$user->dob = $request->birthday_year . '-' . $request->birthday_month . '-' . $request->birthday_day;

			if (@$prev_email == 1) {
				$user->status = null;
			}
			$user->save();
			if (@$prev_email == 1) {
				$resend_email = $email_controller->resend_email_confirmation($user);
			}
			if ($image) {
				$check_users = ProfilePicture::where('user_id', $request->id)->get();

				if ($check_users->count() > '0') {
					$user_profile = array(
						'user_id' => $request->id,
						'src' => $file_name,
						'photo_source' => 'Local',
					);
					$user_profile = ProfilePicture::where('user_id', $request->id)->update($user_profile);
				} else {
					$user_profile = new ProfilePicture;
					$user_profile->user_id = $request->id;
					$user_profile->src = $file_name;
					$user_profile->photo_source = 'Local';
					$user_profile->save();
				}
			}

			if (@$prev_email == 1) {
				flashMessage('success', trans('messages.profile.new_confirm_link_sent', ['email' => $user->email])); // Call flash message function
			} else {
				flashMessage('success', trans('messages.profile.profile_updated')); // Call flash message function
			}
			return redirect('edit_profile');
		}
	}
	/*Upload cover image*/
	public function upload_cover_image(Request $request)
	{
		// Email signup validation rules
		$rules = array(
			'cover_image' => 'required|mimes:jpeg,png,gif,jpg|image',
		);

		// Email signup validation custom messages
		$messages = array(
			'required' => ':attribute is required.',
		);
		// Email signup validation custom Fields name
		$attributes = array(
			'cover_image' => 'Cover Image',
		);

		$validator = Validator::make($request->all(), $rules, $messages, $attributes);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
		} else {

			$this->helper = new Helpers;
			$img = $request->file('cover_image');
			$file_name = time() . '.' . $img->getClientOriginalExtension();

			$type = pathinfo($file_name, PATHINFO_EXTENSION);

			$file_tmp = $request->cover_image;
			$user_id = Auth::user()->id;
			$user_name = Auth::user()->user_name;

			$dir_name = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/users/' . $user_id;
			if (UPLOAD_DRIVER == 'cloudinary') {
				$c = $this->helper->cloud_upload($file_tmp);
				if ($c['status'] != "error") {
					$file_name = $c['message']['public_id'];
				} else {
					flashMessage('danger', $c['message']); // Call flash message function
					return redirect('profile/' . $user_name);
				}
			} else {
				if (!file_exists($dir_name)) {
					//create file directory
					mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/image/users/' . $user_id, 0777, true);
				}

				$f_name = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/users/' . $user_id . '/' . $file_name;

				if (move_uploaded_file($file_tmp, $f_name)) {
					//change compress image in 225*225
					$li = $this->helper->compress_image("image/users/" . $user_id . '/' . $file_name, "image/users/" . $user_id . '/' . $file_name, 80, 225, 225);

				}
			}

			$user_pic = ProfilePicture::find($user_id);

			if (!$user_pic) {
				$user_pic = new ProfilePicture;

			}
			$user_pic->user_id = $user_id;

			$user_pic->cover_image_src = $file_name;
			// $user_pic->photo_source =   'Local';

			$user_pic->save(); // Update a profile picture record

			flashMessage('success', trans('messages.profile.cover_img_uploaded')); // Call flash message function
			return redirect('profile/' . $user_name);
		}
	}
	/*Remove cover image*/
	public function remove_cover_image(Request $request)
	{
		$user_id = Auth::user()->id;
		$user_name = Auth::user()->user_name;
		$user_pic = ProfilePicture::find($user_id);
		$user_pic->user_id = $user_id;
		// $user_pic->src          =   '';
		$user_pic->cover_image_src = '';
		//$user_pic->photo_source =   'Local';
		$user_pic->save();
		return redirect('profile/' . $user_name);

	}
	/*Upload Profile image*/
	public function upload_profile_image(Request $request)
	{
		// Email signup validation rules
		$rules = array(
			'profile_image' => 'required|mimes:jpeg,png,jpg|extensionval|image',
		);

		// Email signup validation custom messages
		$messages = array(
			'extensionval' => trans('validation.mimes', ['values' => 'jpeg,png']),
		);

		// Email signup validation custom Fields name
		$attributes = array(
			'profile_image' => trans('messages.profile.profile_image'),
		);

		$validator = Validator::make($request->all(), $rules, $messages,$attributes);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}

		$this->helper = new Helpers;
		$img = $request->file('profile_image');
		$file_name = time() . '.' . $img->getClientOriginalExtension();

		$type = pathinfo($file_name, PATHINFO_EXTENSION);
		$file_tmp = $request->profile_image;
		$user_id = Auth::user()->id;
		$user_name = Auth::user()->user_name;

		$f_name = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/users/' . $user_id . '/' . $file_name;
		if (UPLOAD_DRIVER == 'cloudinary') {
			$c = $this->helper->cloud_upload($file_tmp);
			if ($c['status'] != "error") {
				$file_name = $c['message']['public_id'];
			} else {
				flashMessage('danger', $c['message']);
				return redirect('profile/' . $user_name);
			}
		} else {
			$dir_name = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/users/' . $user_id;
			if (!file_exists($dir_name)) {
				mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/image/users/' . $user_id, 0777, true);
			}
			if (move_uploaded_file($file_tmp, $f_name)) {
				$li = $this->helper->compress_image("image/users/" . $user_id . '/' . $file_name, "image/users/" . $user_id . '/' . $file_name, 80, 225, 225);
			}
		}

		$user_pic = ProfilePicture::find($user_id);

		if (!$user_pic) {
			$user_pic = new ProfilePicture;

		}
		$user_pic->user_id = $user_id;
		$user_pic->src = $file_name;

		$user_pic->photo_source = 'Local';

		$user_pic->save();

		flashMessage('success', trans('messages.profile.picture_uploaded'));
		return redirect('profile/' . $user_name);
	}

	public function user_follow_stores(Request $request)
	{
		$users_where['users.status'] = 'Active';

		$result = FollowStore::with([
			'store_details' => function ($query) use ($users_where) {
				$query->with([
					'follow_store' => function ($query) {},
					'users' => function ($query) use ($users_where) {$query->where($users_where);},
				]);
			},
		])->whereHas('store_details', function ($query) use ($users_where) {
			$query->whereHas('users', function ($query) use ($users_where) {
				$query->where($users_where);
			});
		})->where('follower_id', $request->user_id)->orderBy('id', 'desc')->get();

		return $result;
	}

	public function user_wishlists(Request $request)
	{
		$ordered = false;
		$users_where['users.status'] = 'Active';
		$result = Wishlists::SELECT('products.id','products.user_id','category_id','return_policy','title','total_quantity','sold','video_mp4','video_webm','video_thumb','views_count','likes_count','status','admin_status','sold_out','is_featured','is_popular','is_recommend','is_editor','is_header','image_name')->join('products','products.id','wishlists.product_id')->join('products_prices_details','products_prices_details.product_id','products.id')->join('products_images','products_images.product_id','products.id')->with([
			'wish_product_details' => function ($query) use ($users_where) {
				$query->with([
					'users' => function ($query) use ($users_where) {$query->where($users_where);},
				]);
			}
		])->whereHas('wish_product_details', function ($query) use ($users_where) {
			$query->where('products.status', 'Active')->where('products.admin_status', 'Approved')->where('products.total_quantity', '<>', '0')->where('products.sold_out', 'No')->whereHas('users', function ($query) use ($users_where) {
				$query->where($users_where);
			});
		})->where('wishlists.user_id', $request->user_id)->orderBy('id', 'desc')->get();
        if(@$request->first_load)
            $result = $result->take(11);
        $result = $result->paginate(10)->toJson();
        $result = json_decode($result, true);
        echo json_encode($result);
	}

	public function userWishlists(Request $request)
	{
		$users_where['users.status'] = 'Active';

		$result = Wishlists::with([
			'wish_product_details' => function ($query) use ($users_where) {
				$query->with([
					'users' => function ($query) use ($users_where) {$query->where($users_where);},
				]);
			},
		])->whereHas('wish_product_details', function ($query) use ($users_where) {
			$query->where('products.status', 'Active')->where('products.admin_status', 'Approved')->where('products.total_quantity', '<>', '0')->where('products.sold_out', 'No')->whereHas('users', function ($query) use ($users_where) {
				$query->where($users_where);
			});
		})->where('user_id', $request->user_id)->orderBy('id', 'desc')->get();

		return $result;
	}

	public function category_browse(Request $request) 
	{
		$category_browse = Category::where('browse','Yes')->where('status','Active')->get();
		return $category_browse;
	}

	public function header_slider(Request $request)
	{
        $all_products = Product::select('id','user_id','category_id','return_policy','title','total_quantity','sold','video_mp4','video_webm','video_thumb','views_count','likes_count','status','admin_status','sold_out','is_featured','is_popular','is_recommend','is_editor','is_header')
            ->with([
                'products_images' => function($query){
                	$query->addSelect('image_name','product_id');
                },
                'users' => function($query) {
                    $query->activeOnly()->addSelect('id','status');
                },
            ])
            ->headerOnly()     
            ->get();
        $header_products = $all_products->map(function($product) {
        	return [
        		"type" 			=> "product",
        		"id" 			=> $product->id,
        		"title" 		=> $product->title,
        		"price" 		=> $product->price,
        		"currency_symbol"=> $product->currency_symbol,
        		"retail_price" 	=> $product->retail_price,
        		"store_name" 	=> $product->store_name,
        		"image_url" 	=> $product->products_images->header_image,
        		"link_url" 		=> route("product_detail",["id" => $product->id]),
        	];
        })->shuffle();

        return $header_products->toArray();
	}

	public function change_password()
	{
		return view('user.change_password');
	}

	public function update_password(Request $request)
	{
		$user = Auth::user();
		$rules = array(
			'old_password' => 'required',
			'new_password' => 'required|min:6|max:30|different:old_password',
			'password_confirmation' => 'required|same:new_password|different:old_password',
		);

		// Password validation custom Fields name
		$attributes = array(
			'old_password' => trans('messages.profile.current_password'),
			'new_password' => trans('messages.profile.new_password'),
			'password_confirmation' => trans('messages.profile.confirm_password'),
		);

		$validator = Validator::make($request->all(), $rules, [], $attributes);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}

		$user = User::find(Auth::id());
		$this->helper = new Helpers;
		if (!Hash::check($request->old_password, $user->password)) {
			flashMessage('warning', trans('messages.profile.pwd_not_correct'));
			return redirect('change_password');
		}

		$user->password = bcrypt($request->new_password);

		$user->save();

		flashMessage('success', trans('messages.profile.pwd_updated'));

		return redirect('change_password');
	}

	public function payment()
	{
		$data['country'] = Country::where('status', 'Active')->get();
		return view('user.payment', $data);
	}

	/**
	 * User Logout
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	public function user_disabled()
	{
		$data['title'] = 'Disabled ';
		return view('user.disabled', $data);
	}

	/**
	 * User Logout
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	public function logout()
	{
		Auth::guard('web')->logout();
		return Redirect::to('login');
		// return redirect('/');
	}

	public function user_settings()
	{
		return view('user.settingoption');
	}
}
