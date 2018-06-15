<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="index.php">Food Ordering</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="../newT/index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../newT/profile.php">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../newT/restaurants.php">Restaurants</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../newT/allOrders.php?acknowledge=yes&p=1">My Orders</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../newT/myOrders.php">Current Orders</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../newT/order.php">Cart
                <?php
                  if(!isset($_SESSION['order']))
                    echo "(0)";
                  else
                    echo "(".count($_SESSION['order']['dishes']).")";
                ?>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../newT/logout.php">Logout</a>
            </li>
        </div>
      </div>
    </nav>
