<div class="header bg-gradient-primary pb-0 pt-0 pt-md-6">
  @auth()
    @include('layouts.navbars.navs.auth')
  @endauth

  @guest()
    @include('layouts.navbars.navs.guest')
  @endguest
</div>
