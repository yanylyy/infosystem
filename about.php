<?php require __DIR__ . '/includes/unit.php' ?>
<?php	require __DIR__ . "/includes/header.php" ?>

<body>

  <div id="colorlib-page">
    <aside id="colorlib-aside" role="complementary" class="js-fullheight">
      <nav id="colorlib-main-menu" role="navigation">
     <?= $menu->ren() ?>
      </nav>
    </aside> <!-- END COLORLIB-ASIDE -->
    <div id="colorlib-main">
      <section class="ftco-about img ftco-section ftco-no-pt ftco-no-pb" id="about-section">
        <div class="container-fluid px-0">
          <div class="row d-flex mt-5">
            <div class="col-md-6 d-flex align-items-center">
              <div class="text px-4 pt-5 pt-md-0 px-md-4 pr-md-5 ftco-animate">
                <h2 class="mb-4">Я <span>Иванова Яна </span> студентка 2 курса &amp; группы ИВ1-23-1</h2>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div><!-- END COLORLIB-MAIN -->
  </div><!-- END COLORLIB-PAGE -->

  <!-- loader -->
  <?php	require __DIR__ . "/includes/preloader.php" ?>


    <?php	require __DIR__ . "/includes/script.php" ?>
</body>

</html>