@inject('BLang', 'Pterodactyl\Models\Billing\BLang')
    <!-- HEADER -->
    <header>
      <!-- MENU -->
	  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
	  <link rel="stylesheet" href="/themes/carbon/css/login/interchanging.css"/>
      <meta name="theme-color" content="#0045ff">
	  <meta property="og:title" content="{{ config('app.name', 'Pterodactyl') }}">
	  <meta property="og:type" content="website">
	  <meta property="og:url" content="/">
	  <meta property="og:image" content="https://cdn.resourcemc.net/zAsa7/rIBOyeRU58.png/raw">
	  <meta property="og:description" content="Manage your server with an easy-to-use Panel">
	  <div id="{{ config('carbonlang.author', 'n/a') }} {{ config('carbonlang.version', 'unset') }}" class="menu">
        <div class="logo"><a href="/auth/login">{{ config('app.name', 'Pterodactyl') }}</a></div>
        <div class="btn"><a href="{{ config('carbonlang.button_URL', '/') }}" target="_blank" style="color:white;">{{ config('carbonlang.button_name', 'Our Website') }}</a></div>
      </div>
    </header>
    <!-- MAIN CONTENT -->

    <head>
      <title>{{ config('app.name') }}</title>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <meta name="robots" content="noindex">
      <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
      <link rel="icon" type="image/png" href="/favicons/favicon-32x32.png" sizes="32x32">
      <link rel="icon" type="image/png" href="/favicons/favicon-16x16.png" sizes="16x16">
      <link rel="manifest" href="/favicons/manifest.json">
      <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#bc6e3c">
      <link rel="shortcut icon" href="/favicons/favicon.ico">
      <meta name="msapplication-config" content="/favicons/browserconfig.xml">
      <meta name="theme-color" content="#0e4688">
      <link rel="stylesheet" href="/modules/register/css/register.css">
    
    
    </head>
    <body class="bg-neutral-900">
      <div id="app">
        <div class="sc-14ayc3f-1 cgXlJi" style="height: 2px;"></div>
        <div class="sc-2l91w7-0 kDhnAT">
          <div class="pt-8 xl:pt-32">
            <div class="cyh04c-0 duHJKB">
              <h2 class="cyh04c-1 emoCVo">Registration Form</h2>
    
    
    
    @if(isset($post_res))
      <?php
        $res = explode('ion.', $post_res);
        if(isset($res['1'])){
          $submitted = "error";
          $res = "Email address or username already exists.";
    
        } else {
          $submitted = "success";
          $res = "Your account has been registered, please check your email to login.";
        }
      ?>
    
    
      {{-- Form Submit HTML Response --}}
    
    @isset($submitted) 
    @if($submitted == "success")
    <div class="cyh04c-2 fNCLyU">
                <div role="alert" class="sc-1yg9bob-0 sc-1yg9bob-1 brfJKd hEbrIt" style="background: #14ac14; border: none;">
                <span class="sc-1yg9bob-2 endIRo title" style="background: #0000002b; border: none;">Success</span>
                <span class="sc-1yg9bob-3 knvREb">{{ $res }}</span>
    </div></div> @endif
    @if($submitted == "error")
    <div class="cyh04c-2 fNCLyU">
                <div role="alert" class="sc-1yg9bob-0 sc-1yg9bob-1 brfJKd hEbrIt">
                <span class="sc-1yg9bob-2 endIRo title">Error</span>
                <span class="sc-1yg9bob-3 knvREb">{{ $res }}</span>
    </div></div>
    
    @endisset @endif 
    
    
    @endif
    
    
    
          <div class="row">
            <div class="col-xs-12">
              @if (count($errors) > 0)
    
                  @foreach ($errors->all() as $error)
    
                <div class="cyh04c-2 fNCLyU">
                <div role="alert" class="sc-1yg9bob-0 sc-1yg9bob-1 brfJKd hEbrIt">
                <span class="sc-1yg9bob-2 endIRo title">Error</span>
                <span class="sc-1yg9bob-3 knvREb">{{ $error }}</span>
                </div></div>
    
                  @endforeach
    
              @endif
              @foreach (Alert::getMessages() as $type => $messages)
              @foreach ($messages as $message)
              <div class="alert alert-{{ $type }} alert-dismissable" role="alert">
                {!! $message !!}
              </div>
              @endforeach
              @endforeach
            </div>
          </div>
    
    
    
              <form action="{{ route('auth.register.url') }}" method="POST" class="qtrnpk-0 ctVkDO">
    
                <div class="cyh04c-3 jbDTOK">
    
                  <div class="cyh04c-6 dFeVmo">
    
                    <div>
                      <label class="g780ms-0 dlUeSf">Email Address</label>
                      <input name="registration_email" type="email" class="sc-19rce1w-0 hmhrLa" value="" placeholder="example@gmail.com" required>
                    </div>
    
                    <div>
                      <label class="g780ms-0 dlUeSf qtrnpk-1 cZROhH">Username</label>
                      <input name="registration_username" type="text" class="sc-19rce1w-0 hmhrLa" value="" placeholder="Username" required>
                    </div>
    
                    <div>
                      <label class="g780ms-0 dlUeSf qtrnpk-1 cZROhH">First Name</label>
                      <input name="registration_firstname" type="text" class="sc-19rce1w-0 hmhrLa" value="" placeholder="First Name" required>
    
                      <div>
                        <label class="g780ms-0 dlUeSf qtrnpk-1 cZROhH">Last Name</label>
                        <input name="registration_lastname" type="text" class="sc-19rce1w-0 hmhrLa" value="" placeholder="Last Name" required>
                      </div>
                    </div>
    
    
    
                    <div class="qtrnpk-2 eWHATQ">
                      {!! csrf_field() !!}
                      <button type="submit" class="sc-1qu1gou-0 gzrAQh"><span class="sc-1qu1gou-2">Register</span></button></div>
                    <div class="qtrnpk-3 fCEexJ"><a class="qtrnpk-4 fFWwUW" href="/auth/login">Already Registered?</a></div>
                  </div>
                </div>
              </form>
    
    
              <p class="cyh04c-7 fFcOT">© 2015 - 2021&nbsp;<a rel="noopener nofollow noreferrer" href="https://pterodactyl.io" target="_blank" class="cyh04c-8 emCXNB">Pterodactyl Software</a></p>
            </div>
          </div>
        </div>
      </div>
      <div style="visibility: hidden; position: absolute; width: 100%; top: -10000px; left: 0px; right: 0px; transition: visibility 0s linear 0.3s, opacity 0.3s linear 0s; opacity: 0;">
        <div style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; z-index: 2000000000; background-color: rgb(255, 255, 255); opacity: 0.5;"></div>
      </div>
    </body>


    <style>

@isset($settings['login_theme'])@if( $settings['login_theme'] == "dark")
:root {
    --header-height: 3rem;
    --nav-width: 68px;
    --first-color: @if(isset($settings['primary_color'])){{ $settings['primary_color'] }}@else #4723d9 @endif;
    --first-color-light: #ffffff;
    --main-background: #1F2545;
    --second-background: #1F2545;
    --text-color: #ffffff;
    --active-text-color:  #ffffff;
    --white-hover: #f4f5f7;
    --sidebar-bg-color: #1F2545;
    --sidebar-icon-color: #ffffff;
    --white-color: #ffffff;
    --body-font: 'Nunito', sans-serif;
    --normal-font-size: 1rem;
    --z-fixed: 100;
}
@else 
:root {
    --header-height: 3rem;
    --nav-width: 68px;
    --first-color: @if(isset($settings['primary_color'])){{ $settings['primary_color'] }}@else #4723d9 @endif;
    --first-color-light: #172b4d;

    --main-background: #f8f9fe;
    --second-background: #FFFF;
    --text-color: #172b4d;
    --active-text-color:#172b4d;
    --white-hover: #f4f5f7;
    --sidebar-bg-color: #FFFF;
    --sidebar-icon-color: @if(isset($settings['primary_color'])){{ $settings['primary_color'] }}@else #4723d9 @endif;

    --white-color: #ffffff;
    --body-font: 'Nunito', sans-serif;
    --normal-font-size: 1rem;
    --z-fixed: 100;
}
@endif @endisset

/* CSS RESET */
* {
	 margin: 0;
	 padding: 0;
	 box-sizing: border-box;
	 font-family: "Lato", sans-serif;
}
 a, .fFWwUW, .fFcOT, .emCXNB, .hmhrLa:not([type="checkbox"]):not([type="radio"]) + .input-help, .kpuLsi{
	 text-decoration: none;
	 color: var(--text-color) ;
}
.hmhrLa:not([type="checkbox"]):not([type="radio"]), .emoCVo {
	color: var(--active-text-color);
}
 header {
	 position: relative;
}
 header header::after {
	 content: "";
	 width: 100%;
	 height: 1px;
	 position: absolute;
	 bottom: 0;
	 background-color: white;
	 z-index: -1;
}
 header .menu {
	 width: 70%;
	 margin: 0 auto;
	 height: 90px;
	 display: flex;
	 align-items: center;
	 justify-content: space-between;
	 position: relative;
	 min-height: 70px;
}
 header .logo a {
	 color: var(--text-color);
	 font-size: 2rem;
	 font-weight: 700;
}
 header nav a {
	 color: ;
	 font-size: 1rem;
	 font-weight: 300;
	 position: relative;
}
 header nav a:not(:last-child) {
	 margin-right: 20px;
}
 header nav a::after {
	 content: "";
	 width: 0%;
	 height: 2px;
	 background-color: #0045ff;
	 position: absolute;
	 bottom: -3px;
	 left: 0;
	 transition: all 0.3s;
}
 header nav a:hover::after {
	 width: 100%;
}
 .btn {
	 padding: 15px 30px;
	 background-color: var(--first-color);
	 border-radius: 4px;
	 color: #fff;
	 font-weight: 700;
	 text-transform: uppercase;
	 font-size: 0.7rem;
	 letter-spacing: 1px;
	 cursor: pointer;
	 box-shadow: var(--first-color) 0px 0 22px;
	 transition: all 0.4s;
}
 .btn:hover {
	 box-shadow: 0px 11px 26px 0px rgba(19, 36, 51, 0);
}
 main {
	 display: grid;
	 grid-template-columns: repeat(2, 1fr);
	 height: calc(100vh - 90px);
	 width: 70%;
	 margin: 0 auto;
}
 main p {
	 color: white;
	 margin: 50px 0;
	 line-height: 2;
}
 main .content {
	 justify-self: flex-start;
	 align-self: center;
}
 main .content h1 {
	 font-size: 4rem;
	 color: white;
}
 main .field-name {
	 height: 3.5rem;
	 margin-top: 2rem;
	 display: flex;
	 justify-content: space-between;
	 align-items: center;
	 border: 1px solid #dfe1e5;
	 border-radius: 4px;
}
 main .field-name input {
	 background: none;
	 border: none;
	 flex: 1;
	 height: 100%;
	 outline: none;
}
 main .field-name .btn {
	 margin-right: 5px;
}
 main .field-name input[type="text"] {
	 padding-left: 1.3rem;
	 color: white;
}
 main .illustration {
	 justify-self: flex-end;
	 align-self: center;
}
 @media only screen and (max-width: 1250px) {
	 main, header .menu {
		 width: 90%;
	}
}
 @media only screen and (max-width: 980px) {
	 main {
		 grid-template-columns: none;
	}
	 main .content {
		 justify-self: center;
		 align-self: center;
		 text-align: center;
	}
	 main .illustration {
		 justify-self: center;
		 align-self: flex-start;
		 margin-top: 80px;
	}
	 main .illustration img {
		 width: 100%;
	}
}
 @media only screen and (max-width: 690px) {
	 header .btn {
		 display: none;
	}
	 header .menu {
		 height: 110px;
		 flex-direction: column;
		 justify-content: space-evenly;
	}
	 main {
		 margin-top: 50px;
	}
	 main .content h1 {
		 font-size: 3rem;
	}
	 main .illustration {
		 justify-self: center;
		 align-self: flex-start;
		 width: 70%;
		 margin-top: 50px;
	}
}
 @media only screen and (max-width: 370px) {
	 main .content h1 {
		 font-size: 2.3rem;
	}
	 main .field-name {
		 display: inline;
		 border: none;
	}
	 main input[type="text"] {
		 padding-left: 1.3rem;
		 color: white;
		 border: 1px solid #dfe1e5;
		 border-radius: 4px;
		 width: 100%;
		 height: 3.5rem;
	}
	 main .btn {
		 margin-right: 0;
		 margin-top: 20px;
	}
}

</style>


