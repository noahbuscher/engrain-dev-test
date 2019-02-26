<!DOCTYPE>

<html>
  <head>
    <!-- Meta -->
    <title>SightMap View</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="View SightMap Listings" />

    <!-- Styles -->
    <link rel="stylesheet" href="/main.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans:300,400,600" rel="stylesheet" />
  </head>
  <body>
    <header class="hero">
      <div class="hero-inner">
        <section class="container">
          <div class="row">
            <div class="col-1">
              <h1>
                SightMap View

                <small>View SightMap Listings Quickly & Easily</small>
              </h1>
            </div>
          </div>
        </section>
      </div>
    </header>

    <?php

      // Init config
      $config = include('config.php');

      // Generate API endpoint
      $page = $_GET['page'] ?: 1;
      $API = $config['api'] . '?page=' . $page;

      // Set headers
      $opts = array(
        'http'=>array(
          'method'=>'GET',
          'header'=>'API-key: ' . $config['key'],
        )
      );
      $context = stream_context_create($opts);

      // Request and parse
      $req = file_get_contents($API, false, $context);
      $decoded = json_decode($req);

      $units = $decoded->data;
      $paging = $decoded->paging;

      function generateDate($parsedDate) {
        return $parsedDate['month'] . '/' . $parsedDate['day'] . '/' . $parsedDate['year'];
      }

    ?>

    <section class="container">
      <div class="row row-nobottom">
        <div class="col-1">
          <h3>Listings With an Area of 1</h3>

          <?php
          foreach ($units as $unit) :
          ?>
            <?php if ($unit->area == 1) : ?>
              <div class="card">
                <div class="card-inner">
                  <div class="card-title">
                    <h2>Unit #<?php echo $unit->unit_number ?></h2>
                  </div>
                  <div class="card-body">
                    <p>
                      <strong>Area:</strong> <?php echo $unit->area ?> feet<sup>2</sup>
                    </p>
                    <p>
                      <?php

                      $parsedDate = date_parse($unit->updated_at);

                      $readableDate = generateDate($parsedDate);

                      ?>
                      <strong>Updated at:</strong> <?php echo $readableDate; ?>
                    </p>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <div class="col-1">
          <h3>Listings With an Area > 1</h3>

          <?php
          foreach ($units as $unit) :
          ?>
            <?php if ($unit->area > 1) : ?>
              <div class="card">
                <div class="card-inner">
                  <div class="card-title">
                    <h2>Unit #<?php echo $unit->unit_number ?></h2>
                  </div>
                  <div class="card-body">
                    <p>
                      <strong>Area:</strong> <?php echo $unit->area ?> feet<sup>2</sup>
                    </p>
                    <p>
                      <?php

                      $parsedDate = date_parse($unit->updated_at);

                      $readableDate = generateDate($parsedDate);

                      ?>
                      <strong>Updated at:</strong> <?php echo $readableDate; ?>
                    </p>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="row">
        <div class="col-1 text-center">
          <?php if ($paging->prev_url) : ?>
            <a class="btn" href="/?page=<?php echo $page-1; ?>">Prev Page</a>
          <?php endif; ?>
          <?php if ($paging->next_url) : ?>
            <a class="btn" href="/?page=<?php echo $page+1; ?>">Next Page</a>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </body>
</html>
