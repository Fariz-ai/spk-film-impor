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
        body {
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, #007bff 0%, #0056b3 100%);
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar-logo img {
            width: 70px;
            height: 70px;
            object-fit: cover;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #fff;
            padding-left: 1.5rem !important;
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            border-left-color: #ffc107;
            font-weight: 600;
        }

        .nav-link i {
            width: 25px;
        }

        /* Top Navbar */
        .top-navbar {
            margin-left: 260px;
            z-index: 999;
            transition: all 0.3s ease;
        }

        .navbar-toggler-custom {
            display: none;
            background: transparent;
            border: none;
            font-size: 1.5rem;
            color: #333;
            cursor: pointer;
            padding: 0.5rem;
        }

        .navbar-toggler-custom:hover {
            color: #007bff;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            margin-top: 80px;
            min-height: calc(100vh - 80px);
            transition: all 0.3s ease;
            padding: 20px;
        }

        /* Overlay untuk mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -260px;
            }

            .sidebar.show {
                margin-left: 0;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
            }

            .top-navbar {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .navbar-toggler-custom {
                display: inline-block;
            }

            /* Sembunyikan user info di mobile jika ada */
            .user-info-desktop {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .top-navbar h4 {
                font-size: 1.2rem;
            }

            .btn-danger {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }

            .btn-danger span {
                display: none;
            }

            .main-content {
                padding: 15px;
            }
        }

        /* Animasi smooth */
        * {
            -webkit-transition: margin-left 0.3s ease;
            -moz-transition: margin-left 0.3s ease;
            -o-transition: margin-left 0.3s ease;
            transition: margin-left 0.3s ease;
        }
    </style>
</head>

<body>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar shadow" id="sidebar">
        <!-- Logo -->
        <div class="text-center py-4 border-bottom border-light">
            <!-- <img src="assets/img/logo.png" alt="Logo" class="rounded-circle bg-white p-2 mb-2" onerror="this.src='https://via.placeholder.com/70/007bff/ffffff?text=SPK'"> -->
            <h5 class="text-white mb-1">SPK Film Impor</h5>
            <small class="text-white-50">Sistem Pendukung Keputusan</small>
        </div>

        <!-- Menu -->
        <ul class="nav flex-column py-3">
            <li class="nav-item">
                <a class="nav-link active" href="index.php">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?page=alternatif">
                    <i class="fas fa-film"></i> Data Alternatif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?page=kriteria">
                    <i class="fas fa-list"></i> Data Kriteria
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?page=subkriteria">
                    <i class="fas fa-layer-group"></i> Data Sub-Kriteria
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?page=normalisasi">
                    <i class="fas fa-calculator"></i> Normalisasi Data
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?page=perhitungan">
                    <i class="fas fa-chart-line"></i> Perhitungan SAW
                </a>
            </li>
        </ul>
    </div>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top top-navbar">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <!-- Toggle Button untuk Mobile -->
                <button class="navbar-toggler-custom" id="sidebarToggle" type="button">
                    <i class="fas fa-bars"></i>
                </button>

                <h4 class="mb-0 font-weight-bold text-dark ml-2">
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

            <div class="d-flex align-items-center">
                <!-- User Info (Uncomment setelah login selesai) -->
                <!-- <div class="bg-light rounded-pill px-3 py-2 mr-3 user-info-desktop">
                    <i class="fas fa-user-circle text-primary"></i>
                    <span class="ml-2 font-weight-medium"><?php // echo $nama_user; 
                                                            ?></span>
                </div> -->

                <a href="logout.php" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin logout?')">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="d-none d-md-inline ml-1">Logout</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
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

    <script src="assets/js/jquery-3.7.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/all.js"></script>
    <script src="assets/js/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "responsive": true
            });
        });
    </script>

    <script src="assets/js/chosen.jquery.min.js"></script>
    <script>
        $(function() {
            $('.chosen').chosen();
        });
    </script>

    <script>
        // Active menu highlighting
        $(document).ready(function() {
            var currentPage = "<?php echo $page; ?>";
            $('.nav-link').removeClass('active');

            if (currentPage === "") {
                $('.nav-link[href="index.php"]').addClass('active');
            } else {
                $('.nav-link[href="?page=' + currentPage + '"]').addClass('active');
            }
        });

        // Toggle Sidebar untuk Mobile
        $(document).ready(function() {
            $('#sidebarToggle').on('click', function() {
                $('#sidebar').toggleClass('show');
                $('#sidebarOverlay').toggleClass('show');
            });

            // Close sidebar ketika overlay diklik
            $('#sidebarOverlay').on('click', function() {
                $('#sidebar').removeClass('show');
                $('#sidebarOverlay').removeClass('show');
            });

            // Close sidebar ketika menu diklik (khusus mobile)
            $('.nav-link').on('click', function() {
                if ($(window).width() <= 768) {
                    $('#sidebar').removeClass('show');
                    $('#sidebarOverlay').removeClass('show');
                }
            });

            // Handle resize window
            $(window).on('resize', function() {
                if ($(window).width() > 768) {
                    $('#sidebar').removeClass('show');
                    $('#sidebarOverlay').removeClass('show');
                }
            });
        });
    </script>

</body>

</html>