<?php
date_default_timezone_set("Asia/Jakarta");
// require "config.php";

// session_start();
// $nama_user = isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SPK Film Impor</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/datatables.min.css">
    <link rel="stylesheet" href="assets/css/all.css">
    <link rel="stylesheet" href="assets/css/bootstrap-chosen.css">

    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, #007bff 0%, #0056b3 100%);
            z-index: 1040;
        }

        .main-content {
            margin-left: 260px;
            margin-top: 70px;
            min-height: calc(100vh - 70px);
        }

        .top-navbar {
            margin-left: 260px;
            height: 70px;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content,
            .top-navbar {
                margin-left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1035;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
</head>

<body class="bg-light">

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar shadow-lg overflow-auto" id="sidebar">
        <!-- Logo -->
        <div class="text-center py-4 border-bottom border-white">
            <div class="bg-white rounded-circle d-inline-block p-2 mb-2">
                <i class="fas fa-film text-primary" style="font-size: 2rem;"></i>
            </div>
            <h5 class="text-white mb-1 font-weight-bold">SPK Film Impor</h5>
            <small class="text-white-50">Sistem Pendukung Keputusan</small>
        </div>

        <!-- Menu -->
        <ul class="nav flex-column py-3">
            <li class="nav-item">
                <a class="nav-link text-white px-4 py-3" href="index.php">
                    <i class="fas fa-home mr-3"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white px-4 py-3" href="?page=alternatif">
                    <i class="fas fa-film mr-3"></i> Data Alternatif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white px-4 py-3" href="?page=kriteria">
                    <i class="fas fa-list mr-3"></i> Data Kriteria
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white px-4 py-3" href="?page=subkriteria">
                    <i class="fas fa-layer-group mr-3"></i> Data Sub-Kriteria
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white px-4 py-3" href="?page=normalisasi">
                    <i class="fas fa-calculator mr-3"></i> Normalisasi Data
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white px-4 py-3" href="?page=perhitungan">
                    <i class="fas fa-chart-line mr-3"></i> Perhitungan SAW
                </a>
            </li>
        </ul>
    </div>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top top-navbar">
        <div class="container-fluid">
            <!-- Left Side -->
            <div class="d-flex align-items-center">
                <!-- Toggle Button untuk Mobile -->
                <button class="btn btn-link text-dark d-lg-none mr-2" id="sidebarToggle" type="button">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <!-- Page Title -->
                <h4 class="mb-0 font-weight-bold text-dark">
                    <?php
                    $page = isset($_GET['page']) ? $_GET['page'] : "";
                    $titles = [
                        "" => "Dashboard",
                        "alternatif" => "Data Alternatif",
                        "kriteria" => "Data Kriteria",
                        "subkriteria" => "Data Sub-Kriteria",
                        "normalisasi" => "Normalisasi Data",
                        "perhitungan" => "Perhitungan SAW",
                    ];
                    echo isset($titles[$page]) ? $titles[$page] : "Dashboard";
                    ?>
                </h4>
            </div>

            <!-- Right Side -->
            <div class="d-flex align-items-center">
                <!-- User Info -->
                <!-- <div class="bg-light rounded-pill px-3 py-2 mr-3 d-none d-md-inline-block">
                    <i class="fas fa-user-circle text-primary"></i>
                    <span class="ml-2 font-weight-medium"><?php // echo $nama_user; 
                                                            ?></span>
                </div> -->

                <!-- Logout Button -->
                <a href="logout.php" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Apakah Anda yakin ingin logout?')">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="d-none d-md-inline ml-1">Logout</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content p-4">
        <div class="container-fluid">
            <?php
            // pengaturan menu
            $page = isset($_GET['page']) ? $_GET['page'] : "";
            $action = isset($_GET['action']) ? $_GET['action'] : "";

            if ($page == "") {
                include "dashboard.php";
            } elseif ($page == "alternatif") {
                if ($action == "") {
                    include "alternatif/index.php";
                } elseif ($action == "tambah") {
                    include "alternatif/tambah.php";
                } elseif ($action == "edit") {
                    include "alternatif/edit.php";
                } elseif ($action == "hapus") {
                    include "alternatif/hapus.php";
                }
            } elseif ($page == "kriteria") {
                if ($action == "") {
                    include "kriteria/index.php";
                } elseif ($action == "tambah") {
                    include "kriteria/tambah.php";
                } elseif ($action == "edit") {
                    include "kriteria/edit.php";
                } elseif ($action == "hapus") {
                    include "kriteria/hapus.php";
                }
            } elseif ($page == "subkriteria") {
                if ($action == "") {
                    include "subkriteria/index.php";
                } elseif ($action == "tambah") {
                    include "subkriteria/tambah.php";
                } elseif ($action == "edit") {
                    include "subkriteria/edit.php";
                } elseif ($action == "hapus") {
                    include "subkriteria/hapus.php";
                }
            } elseif ($page == "normalisasi") {
                include "normalisasi/index.php";
            } elseif ($page == "perhitungan") {
                include "perhitungan/index.php";
            } elseif ($page == "hasil") {
                include "hasil/index.php";
            } else {
                include "welcome.php";
            }
            ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/js/jquery-3.7.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/all.js"></script>
    <script src="assets/js/datatables.min.js"></script>

    <script>
        $(document).ready(function() {
            // DataTables
            $('#myTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "responsive": true
            });

            // Active menu
            var currentPage = "<?php echo $page; ?>";
            $('.sidebar .nav-link').removeClass('active bg-white text-primary');

            if (currentPage === "") {
                $('.sidebar .nav-link[href="index.php"]').addClass('active bg-white text-primary');
            } else {
                $('.sidebar .nav-link[href="?page=' + currentPage + '"]').addClass('active bg-white text-primary');
            }

            // Sidebar toggle
            $('#sidebarToggle').on('click', function() {
                $('#sidebar').toggleClass('show');
                $('#sidebarOverlay').toggleClass('show');
            });

            $('#sidebarOverlay').on('click', function() {
                $('#sidebar').removeClass('show');
                $('#sidebarOverlay').removeClass('show');
            });

            $('.sidebar .nav-link').on('click', function() {
                if ($(window).width() <= 991) {
                    $('#sidebar').removeClass('show');
                    $('#sidebarOverlay').removeClass('show');
                }
            });
        });
    </script>

    <script src="assets/js/chosen.jquery.min.js"></script>
    <script>
        $(function() {
            $('.chosen').chosen();
        });
    </script>

</body>

</html>