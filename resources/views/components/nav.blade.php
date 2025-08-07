<nav class="navbar navbar-expand-lg bg-primary">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}" style="color: #FEFAE0 !important;">Warkop LarisManis</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a href="{{ url('/') }}" class="nav-link" style="color: #FEFAE0 !important;">Home</a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/#recruitments') }}" class="nav-link" style="color: #FEFAE0 !important;">Recruitments</a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/login') }}" class="nav-link" style="color: #FEFAE0 !important;">Login</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
