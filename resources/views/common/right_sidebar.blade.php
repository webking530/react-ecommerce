<div class="col-lg-3 col-md-3 col-xs-12 col-sm-4 right-sidebar">
  <div class="right-activity cls_right_sidebar">
    <div class="col-12">
      <ul class="glob-list">
        <li class="col-6">
          <a class="glob-sel" href="#">
            Global
          </a>
        </li>
        <li class="col-6">
          <a class="follow-sel active" href="#">
            Following
          </a>
        </li>
      </ul>

      <div class="glob-content">
        <ul class="global">
          <li>
            <a href="#" class="glob-img">
              <img src="image/profile.png">
            </a>
            <span class="noti-wrap">
              <a class="username" href="#">
                Test
              </a> 
              {{trans('messages.home.liked')}}
              <a href="#">
                dummy content for refernce
              </a>
            </span>
            <li>
              <a href="#" class="glob-img">
                <img src="image/profile.png">
              </a>
              <span class="noti-wrap">
                <a class="username" href="#">
                  Test
                </a> 
                {{trans('messages.home.liked')}} 
                <a href="#">
                  dummy content for refernce
                </a>
              </span>
            </li>
            <li>
              <a href="#" class="glob-img">
                <img src="image/profile.png">
              </a>
              <span class="noti-wrap">
                <a class="username" href="#">
                  Test
                </a> 
                {{trans('messages.home.liked')}} 
                <a href="#">
                  dummy content for refernce
                </a>
              </span>
            </li>
            <li>
              <a href="#" class="glob-img">
                <img src="image/profile.png">
              </a>
              <span class="noti-wrap">
                <a class="username" href="#">
                  Test
                </a> {{trans('messages.home.liked')}} 
                <a href="#">
                  dummy content for refernce
                </a>
              </span>
            </li>
            <li>
              <a href="#" class="glob-img">
                <img src="image/profile.png">
              </a>
              <span class="noti-wrap">
                <a class="username" href="#">
                  Test
                </a> 
                {{trans('messages.home.liked')}} 
                <a href="#">
                  dummy content for refernce
                </a>
              </span>
            </li>
            <li>
              <a href="#" class="glob-img">
                <img src="image/profile.png">
              </a>
              <span class="noti-wrap">
                <a class="username" href="#">
                  Test
                </a> 
                {{trans('messages.home.liked')}} 
                <a href="#">
                  dummy content for refernce
                </a>
              </span>
            </li>
            <li>
              <a href="#" class="glob-img">
                <img src="image/profile.png">
              </a>
              <span class="noti-wrap">
                <a class="username" href="#">
                  Test
                </a> {{trans('messages.home.liked')}} 
                <a href="#">
                  dummy content for refernce
                </a>
              </span>
            </li>
            <li>
              <a href="#" class="glob-img">
                <img src="image/profile.png">
              </a>
              <span class="noti-wrap">
                <a class="username" href="#">
                  Test
                </a>
                {{trans('messages.home.liked')}} 
                <a href="#">
                  dummy content for refernce
                </a>
              </span>
            </li>
          </li>
        </ul>
        <a href="#" class="register-btn">
          See Map
        </a>
      </div>

      <div class="follow-content text-center">
        <h4>
          {{$site_name}} is more fun with friends!
          <span>
            Log in to see what your friends are up to.
          </span>
        </h4>

        <ul>
          <li>
            <a href="{{URL::to('facebookLogin')}}" class="facebook-btn">
              <i class="icon icon-facebook"></i>
              <span>
                Join with Facebook
              </span>
            </a>
          </li>
          <li>
            <a href="{{URL::to('twitterLogin')}}" class="twitter-btn">
              <i class="icon icon-twitter"></i>
              <span>
                Log in with Twitter
              </span>
            </a>
          </li>
          <li>
            <a href="{{ url('signup') }}" class="register-btn">
              Join {{$site_name}}
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="col-12">
      <div class="popular-head">
        <h3>Popular</h3>
        <select class="every-select">
          <option>Everything</option>
          <option>Men</option>
          <option>women</option>
          <option>Kids</option>
          <option>Pets</option>
          <option>Home</option>
          <option>Gatgets</option>
        </select>
      </div>

      <div id="main">
        <div id="demos">
          <table cellspacing="20">
            <tr>
              <td>
                <div id="s1" class="pics">
                  <img src="image/fancy-list1.png" width="200" height="200" />
                  <img src="image/collection1.png" width="200" height="200" />
                  <img src="image/signup-back2.png" width="200" height="200" />
                </div>
              </td>
              <td>
                <div id="s2" class="pics">
                  <img src="image/fancy-list2.png" width="200" height="200" />
                  <img src="image/signup-back4.png" width="200" height="200" />
                  <img src="image/signup-back.png" width="200" height="200" />
                </div>
              </td>
              <td>
                <div id="s3" class="pics">
                  <img src="image/fancy-list3.png" width="200" height="200" />
                  <img src="image/signup-back4.png" width="200" height="200" />
                  <img src="image/follow-list.png" width="200" height="200" />
                </div>
              </td>
              <td>
                <div id="s4" class="pics">
                  <img src="image/fancy-list4.png" width="200" height="200" />
                  <img src="image/signup-back.png" width="200" height="200" />
                  <img src="image/fancy-list7.png" width="200" height="200" />
                </div>
              </td>
              <td>
                <div id="s5" class="pics">
                  <img src="image/fancy-list5.jpg" width="200" height="200" />
                  <img src="image/collection1.png" width="200" height="200" />
                  <img src="image/signup-back.png" width="200" height="200" />
                </div>
              </td>
              <td>
                <div id="s6" class="pics">
                  <img src="image/fancy-list6.png" width="200" height="200" />
                  <img src="image/signup-back3.png" width="200" height="200" />
                  <img src="image/signup-back.png" width="200" height="200" />
                </div>
              </td>
            </tr>
          </table>        
        </div>
      </div>
      <a href="#" class="register-btn">
        See More
      </a>
    </div>

    <div class="col-12">
      <div class="popular-head">
        <h3>Stores</h3>
      </div>
      <div class="glob-back">
        <ul class="store-list">
          <li class="bor-bot">
            <a class="pro-logo">
              <img src="image/product-logo.png">
            </a>
            <a class="pro-name">
              Generate Design<small>502 Products</small>
            </a>
            <button class="btn-blue">Follow Store</button>
            <br>
            <a href="#"><img src="image/fancy-list1.png" class="img-icon"></a>
            <a href="#"><img src="image/shirtdesign.png" class="img-icon"></a>
            <a href="#"><img src="image/smartwatch.png" class="img-icon"></a>
            <a href="#"><img src="image/store-4.png" class="img-icon"></a>
          </li>   
          <li class="bor-bot"><a class="pro-logo flt-left"><img src="image/product-logo.png" width="40px"></a>
            <a class="pro-name flt-left">Generate Design<small>502 Products</small></a>
            <button class="btn-blue">Follow Store</button>
            <br>
            <a href="#"><img src="image/fancy-list2.png" class="img-icon"></a>
            <a href="#"><img src="image/white-shoe.png" class="img-icon"></a>
            <a href="#"><img src="image/fancy-list3.png" class="img-icon"></a>
            <a href="#"><img src="image/store-4.png" class="img-icon"></a>
          </li>
          <li>
            <a class="pro-logo">
              <img src="image/product-logo.png">
            </a>
            <a class="pro-name">Generate Design<small>502 Products</small></a>
            <button class="btn-secondary">
              Follow Store
            </button>
            <br>
            <a href="#"><img src="image/fancy-list4.png" class="img-icon"></a>
            <a href="#"><img src="image/blshoe.png" class="img-icon"></a>
            <a href="#"><img src="image/gym.png" class="img-icon"></a>
            <a href="#"><img src="image/fancy-list5.jpg" class="img-icon"></a>
          </li>
          <li>
            <a class="pro-logo">
              <img src="image/product-logo.png">
            </a>
            <a class="pro-name">
              Generate Design<small>502 Products</small>
            </a>
            <button class="btn-blue">Follow Store</button>
            <br>
            <a href="#"><img src="image/fancy-list6.png" class="img-icon"></a>
            <a href="#"><img src="image/shirtdesign.png" class="img-icon"></a>
            <a href="#"><img src="image/white-shoe.png" class="img-icon"></a>
            <a href="#"><img src="image/fancy-list4.png" class="img-icon"></a>
          </li>
          <li class="bor-bot"><a class="pro-logo flt-left"><img src="image/product-logo.png" width="40px"></a>
            <a class="pro-name flt-left">Generate Design<small>502 Products</small></a>
            <button class="btn-blue">
              Follow Store
            </button>
            <br>
            <a href="#"><img src="image/fancy-list3.png" class="img-icon"></a>
            <a href="#"><img src="image/blshoe.png" class="img-icon"></a>
            <a href="#"><img src="image/gym.png" class="img-icon"></a>
            <a href="#"><img src="image/store-4.png" class="img-icon"></a>
          </li>
          <li>
            <a class="pro-logo">
              <img src="image/product-logo.png">
            </a>
            <a class="pro-name">
              Generate Design<small>502 Products</small>
            </a>
            <button class="btn-blue">
              Follow Store
            </button>
            <br>
            <a href="#"><img src="image/follow-list.png" class="img-icon"></a>
            <a href="#"><img src="image/shirtdesign.png" class="img-icon"></a>
            <a href="#"><img src="image/smartwatch.png" class="img-icon"></a>
            <a href="#"><img src="image/store-4.png" class="img-icon"></a>
          </li>
        </ul>
        <a href="#" class="register-btn col-12">See More</a>
      </div>
    </div>
    <div class="col-12">
      <div class="popular-head">
        <h3>
          Collections
        </h3>
      </div>
      <div class="back-white">
        <div class="pos-rel">
          <img src="image/veg.png" width="100%" height="150px" class="pad-10" style="padding-bottom:0px !important;">
          <div class="pos-abs">Hallowen</div>
        </div>
        <div class="pos-rel">
          <img src="image/apple.png" width="100%" height="150px" class="pad-10" style="padding-bottom:0px !important;">
          <div class="pos-abs">Hallowen</div>
        </div>
        <div class="pos-rel">
          <img src="image/tech.png" width="100%" height="150px" class="pad-10" style="padding-bottom:0px !important;">
          <div class="pos-abs">Hallowen</div>
        </div>
        <div class="pos-rel">
          <img src="image/bag.png" width="100%" height="150px" class="pad-10" style="padding-bottom:0px !important;">
          <div class="pos-abs">Hallowen</div>
        </div>
        <div class="pos-rel">
          <img src="image/cy.png" width="100%" height="150px" class="pad-10" style="padding-bottom:0px !important;">
          <div class="pos-abs">Hallowen</div>
        </div>
      </div>
      <a href="#" class="register-btn col-lg-12 col-xs-12 col-md-12 col-sm-12 back-white mar-0">See More</a>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopad margin-top-15" >
      <div class="popular-head">
        <h3>
          Articles
        </h3>
        <div class="paging">
          <a href="#" class="prev">Prev</a>
          <a href="#" class="next">Next</a>
        </div>
      </div>
      <div class="back-white">
        <ul class="rslides">
          <li>
            <img src="image/slider-image-1.jpg">
            <div class="slide-text">
              <h3>
                Who we follow
              </h3>
              <p>
                Manhattan may not be ready for this exotic island stunner!
              </p>
            </div>
          </li>
          <li>
            <img src="image/collection1.png">
            <div class="slide-text">
              <h3>
                Find us soon
              </h3>
              <p>
                Manhattan may not be ready for this exotic island stunner!
              </p>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="footer-links">
      <ul>
        @foreach($company_pages as $company_page)
        <li>
          <a href="{{ url($company_page->url) }}">
            {{ $company_page->name }}
          </a>
        </li>
        @endforeach
      </ul>
    </div>
  </div>
</div>