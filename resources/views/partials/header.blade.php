<header>
  <a class="logo" href="/">
    <img src="{{ asset('/images/AngelaToddPhotography.svg') }}" alt="logo">
  </a>
    <nav>
        <a @click.prevent="toggleMenu()" href="#" class="toggle-menu"><i class="fas fa-bars hamburger"></i></a>
        <ul ref="menu">
            <li><a href="/" class="active">Home</a></li>
            <li><a href="/#portfolio" @click="closeMenuIfActive()">Portfolio</a></li>
            {{--<li><a href="/">Blog</a></li>--}}
            {{--<li><a href="/">Shop</a></li>--}}
            <li><a href="/about">About</a></li>
        </ul>
    </nav>
</header>
