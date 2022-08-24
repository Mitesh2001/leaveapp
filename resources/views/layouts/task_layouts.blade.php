<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<title>Tasks</title>
<style>
  body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
  }

  .logo_small { width: 30px; vertical-align: top;}

  .logoradius {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.7);
    text-align: center;
  }
  .navbar .nav-item {
    cursor: pointer;
  }
</style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd !important">
    <div class="container-fluid">
      <a class="navbar-brand logoradius" href="#"><img src="{{asset('storage/assets/site_identity/tasks_logo.png')}}" alt="" class="logo_small" /></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav">
          <li class="nav-item"><span class="nav-link">Hello, {{auth()->user()->first_name." ".auth()->user()->last_name}}</span></li>
          <li class="nav-item"><span class="nav-link">Today : {{ date("F d, Y") }}</span></li>
          <li class="nav-item"><span class="nav-link">Agenda list</span></li>
        </ul>
      </div>
      <div class="d-flex align-items-center">
        <div class="dropdown">
          <a class="text-reset me-3"
            href="#"
            id="navbarDropdownMenuLink"
            role="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            <i class="fa fa-user"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end"
            aria-labelledby="navbarDropdownMenuLink"
          >
            <li><a href=""  class="dropdown-item"><span style="color: #2c7bd0; font-weight: 700;"></span></a></li>
            <li ><a href=""  class="dropdown-item">User Id:</a></li>
            <li><a href="" class="dropdown-item"><i class="fa fa-building" style="color: #b2b2b2; font-size: 18px;"></i> Trinity Unicepts Pvt. Ltd.</a></li>
            <div class="dropdown-divider"></div>
            <li>
              <h2 style="margin: 0; padding:15px 10px 5px 10px; font-size: 16px; font-weight: 700;">Quick Links</h2>
            </li>
            <li><a href="profile.php" class="dropdown-item">My Profile</a></li>
            <li><a href="my-task.php" class="dropdown-item">My Tasks</a></li>
            <li><a href="index.php" class="dropdown-item">Submit Tasks</a></li>
            <li><a href="" class="dropdown-item">Contact Us</a></li>
            <li><a href="" class="dropdown-item">FAQ</a></li>
            <div class="dropdown-divider"></div>
            <li><a href="logout.php" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Sign Out</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
  @yield('content')
  @yield('script')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>