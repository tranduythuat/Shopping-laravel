<div class="navbar navbar-dark navbar-expand-md bg-faded justify-content-center">
    <button type="button" id="sidebarCollapse" class="navbar-btn ml-0 mr-auto">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <div class="navbar-brand d-flex w-50 mr-auto">
        &nbsp;<img src="{{ asset('images/04.png') }}" width="80" height="70" alt="">
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar3">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse w-100" id="collapsingNavbar3">
        <ul class="nav navbar-nav ml-auto w-100 justify-content-end">
            {{-- <li class="nav-item">
                <a class="nav-link" href="#">Right</a>
            </li> --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="#"><i class="fas fa-user-edit"></i>&nbsp; Profile</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item sign-out-btn"><i class="fas fa-sign-out-alt"></i>&nbsp; Sign out</button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>
